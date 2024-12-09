<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

include('db.php');

$car_id = $_GET['car_id'];
$car_type = $_GET['type'];
$employee_id = $_SESSION['user_id'];

// Fetch car details
$car_sql = "SELECT c.*, u.employee_name, u.phone 
            FROM client_cars c 
            JOIN users u ON c.employee_id = u.id 
            WHERE c.id = ? AND c.employee_id = ?";
$car_stmt = $conn->prepare($car_sql);
$car_stmt->bind_param("ii", $car_id, $employee_id);
$car_stmt->execute();
$car_result = $car_stmt->get_result();
$car_details = $car_result->fetch_assoc();

if (!$car_details) {
    header('Location: car_wash_services.php?type=' . $car_type);
    exit();
}

// Fetch available services for this car type
$services_sql = "SELECT s.*, c.name as category_name, c.service_level 
                 FROM services s 
                 JOIN categories c ON s.category_id = c.id 
                 WHERE c.car_type = ? 
                 ORDER BY c.service_level, c.name, s.name";
$services_stmt = $conn->prepare($services_sql);
$services_stmt->bind_param("s", $car_type);
$services_stmt->execute();
$services_result = $services_stmt->get_result();

// Group services by category
$categories = [];
while ($service = $services_result->fetch_assoc()) {
    if (!isset($categories[$service['category_name']])) {
        $categories[$service['category_name']] = [
            'level' => $service['service_level'],
            'services' => []
        ];
    }
    $categories[$service['category_name']]['services'][] = $service;
}

// After fetching car details, modify the query to get previous services
$prev_services_sql = "SELECT o.services, o.arrival_time, o.total_amount 
                      FROM orders o
                      WHERE o.car_number = ? 
                      AND o.status = 'completed'
                      ORDER BY o.arrival_time DESC 
                      LIMIT 5";
$prev_services_stmt = $conn->prepare($prev_services_sql);
$prev_services_stmt->bind_param("s", $car_details['car_number']);
$prev_services_stmt->execute();
$prev_services_result = $prev_services_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00416A, #E4E5E6);
            min-height: 100vh;
            padding-top: 90px;
        }

        .container-serves {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    padding-bottom: 100px; /* Add this to create space for the fixed total section */
}

        .car-details {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .services-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .services-container {
                grid-template-columns: 1fr;
            }
        }

        .category {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-title {
            color: #00416A;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4CAF50;
        }

        .service-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.7);
        }

        .service-item.selected {
            background: rgba(76, 175, 80, 0.1);
            border-color: #4CAF50;
        }

        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .service-item input[type="checkbox"] {
            margin-right: 10px;
        }

        .service-details {
            flex-grow: 1;
        }

        .service-price {
            color: #4CAF50;
            font-weight: 600;
        }

        .total-section {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 20px;
    box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000; /* Add this to ensure it stays on top */
}

        .submit-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container-serves">
        <div class="car-details">
            <h2><i class="fas fa-car"></i> <?php echo htmlspecialchars($car_details['car_name']); ?></h2>
            <p><strong>Car Number:</strong> <?php echo htmlspecialchars($car_details['car_number']); ?></p>
            <p><strong>Client:</strong> <?php echo htmlspecialchars($car_details['employee_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($car_details['phone']); ?></p>
        </div>
        
        <div class="previous-services">
            <h3><i class="fas fa-history"></i> Previous Services</h3>
            <?php if ($prev_services_result && $prev_services_result->num_rows > 0): ?>
                <div class="prev-services-grid">
                    <?php while ($order = $prev_services_result->fetch_assoc()): ?>
                        <div class="prev-service-item">
                            <div class="prev-service-name">
                                <?php 
                                    // The services field might be stored as JSON or comma-separated string
                                    $services = json_decode($order['services'], true) ?: explode(',', $order['services']);
                                    if (is_array($services)) {
                                        echo htmlspecialchars(implode(', ', $services));
                                    } else {
                                        echo htmlspecialchars($order['services']);
                                    }
                                ?>
                            </div>
                            <div class="prev-service-details">
                                <span class="prev-service-price"><?php echo number_format($order['total_amount'], 3); ?> OMR</span>
                                <span class="prev-service-date"><?php echo date('d M Y', strtotime($order['arrival_time'])); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-services">No previous services found for this car.</p>
            <?php endif; ?>
        </div>

        <form id="servicesForm" action="process_services.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
            
            <div class="services-container">
                <?php foreach ($categories as $category_name => $category_data): ?>
                    <div class="category">
                        <h3 class="category-title">
                            <?php echo htmlspecialchars($category_name); ?>
                            <?php if ($category_data['level'] === 'special'): ?>
                                <i class="fas fa-star" style="color: gold;"></i>
                            <?php endif; ?>
                        </h3>
                        
                        <?php foreach ($category_data['services'] as $service): ?>
                            <div class="service-item" onclick="toggleService(this, <?php echo $service['id']; ?>, <?php echo $service['price']; ?>)">
                                <input type="checkbox" 
                                       name="services[]" 
                                       value="<?php echo $service['id']; ?>"
                                       data-price="<?php echo $service['price']; ?>"
                                       style="display: none;">
                                <div class="service-details">
                                    <div><?php echo htmlspecialchars($service['name']); ?></div>
                                </div>
                                <div class="service-price">
                                    <?php echo number_format($service['price'], 3); ?> OMR
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-section">
                <div class="total">
                    <h3>Total: <span id="totalAmount">0.000</span> OMR</h3>
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-check"></i> Confirm Services
                </button>
            </div>
        </form>

        
    </div>

    <script>
        function toggleService(element, serviceId, price) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            element.classList.toggle('selected');
            checkbox.checked = !checkbox.checked;
            updateTotal();
        }

        function updateTotal() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            let total = 0;
            
            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.dataset.price);
            });
            
            document.getElementById('totalAmount').textContent = total.toFixed(3);
        }
    </script>
</body>
</html> 