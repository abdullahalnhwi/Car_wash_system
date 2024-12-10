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
$prev_services_sql = "SELECT o.id, o.order_code, o.services, o.arrival_time, 
                             o.total_amount, o.status, o.car_number 
                      FROM orders o 
                      WHERE o.car_number = ? 
                      AND o.status = 'completed'
                      ORDER BY o.arrival_time DESC";
$prev_services_stmt = $conn->prepare($prev_services_sql);
$prev_services_stmt->bind_param("s", $car_details['car_number']);
$prev_services_stmt->execute();
$prev_services_result = $prev_services_stmt->get_result();

// Fetch all booked slots from the database
$booked_slots_sql = "SELECT slot_datetime 
                     FROM booked_slots 
                     WHERE status != 'cancelled'";
$booked_slots_result = $conn->query($booked_slots_sql);
$booked_slots = [];

while ($slot = $booked_slots_result->fetch_assoc()) {
    $booked_slots[] = $slot['slot_datetime'];
}
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

        .previous-services-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #00416A;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        .prev-service-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .prev-service-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .prev-service-header {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .order-number {
            background: #e8f5e9;
            color: #4CAF50;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .service-date {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .service-amount {
            color: #4CAF50;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .services-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .service-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .service-detail i {
            color: #4CAF50;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .reuse-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .reuse-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }

        .view-details-btn {
            background: #f8f9fa;
            color: #00416A;
            border: 1px solid #00416A;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .view-details-btn:hover {
            background: #00416A;
            color: white;
        }

        .booking-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .date-time-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .date-picker {
            padding: 15px;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            font-size: 1rem;
            width: 100%;
            margin-bottom: 20px;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        .time-slot {
            padding: 12px;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-align: center;
            background: #e8f5e9;
            border: 2px solid #4CAF50;
            color: #4CAF50;
            white-space: pre-line;
        }

        .time-slot.booked {
            background: #ffebee;
            border-color: #ff5252;
            color: #ff5252;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .time-slot.selected {
            background: #4CAF50;
            color: white;
        }

        .time-slot:hover:not(.booked) {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }

        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .available-color {
            background: #e8f5e9;
            border: 2px solid #4CAF50;
        }

        .booked-color {
            background: #ffebee;
            border: 2px solid #ff5252;
        }

        .selected-color {
            background: #4CAF50;
            border: 2px solid #4CAF50;
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
        
        <div class="previous-services-section">
            <h3 class="section-title">
                <i class="fas fa-history"></i>
                Previous Services History
            </h3>
            
            <div class="prev-service-list">
                <?php if ($prev_services_result && $prev_services_result->num_rows > 0): 
                    while ($order = $prev_services_result->fetch_assoc()):
                        $services = json_decode($order['services'], true);
                ?>
                    <div class="prev-service-item">
                        <div class="prev-service-header">
                            <span class="order-number">
                                #<?php echo htmlspecialchars($order['order_code']); ?>
                            </span>
                            <span class="service-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo date('l, d M Y - h:i A', strtotime($order['arrival_time'])); ?>
                            </span>
                            <span class="service-amount">
                                <?php echo number_format($order['total_amount'], 3); ?> OMR
                            </span>
                        </div>

                        <div class="services-details">
                            <?php foreach ($services as $service): ?>
                                <div class="service-detail">
                                    <i class="fas fa-check-circle"></i>
                                    <?php echo htmlspecialchars($service); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="view-details-btn" 
                                    onclick="window.location.href='view_order.php?id=<?php echo $order['id']; ?>'">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button type="button" class="reuse-btn" 
                                    onclick="reselectServices(<?php echo htmlspecialchars(json_encode($services)); ?>)">
                                <i class="fas fa-redo-alt"></i> Use These Services
                            </button>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else: ?>
                    <p class="no-services">No previous services found for this car.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="booking-section">
            <h3 class="section-title">
                <i class="far fa-calendar-alt"></i>
                Select Date & Time
            </h3>

            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color available-color"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color booked-color"></div>
                    <span>Booked</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color selected-color"></div>
                    <span>Selected</span>
                </div>
            </div>

            <div class="date-time-grid">
                <input type="date" 
                       id="appointment-date" 
                       class="date-picker"
                       min="<?php echo date('Y-m-d'); ?>"
                       value="<?php echo date('Y-m-d'); ?>"
                       onchange="generateTimeSlots(this.value)">

                <div class="time-slots" id="time-slots-container">
                    <!-- Time slots will be generated here -->
                </div>
            </div>
        </div>

        <form id="servicesForm" action="process_services.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
            <input type="hidden" name="selected_datetime" id="selected-datetime">
            
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

        function reselectServices(previousServices) {
            // Uncheck all services first
            document.querySelectorAll('.service-item input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest('.service-item').classList.remove('selected');
            });

            // Select the previous services
            document.querySelectorAll('.service-item').forEach(item => {
                const serviceName = item.querySelector('.service-details div').textContent.trim();
                if (previousServices.includes(serviceName)) {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    checkbox.checked = true;
                    item.classList.add('selected');
                }
            });

            // Update total and scroll to services section
            updateTotal();
            document.querySelector('.services-container').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // Show confirmation message
            const message = document.createElement('div');
            message.className = 'alert alert-success';
            message.style.position = 'fixed';
            message.style.top = '20px';
            message.style.right = '20px';
            message.style.padding = '15px';
            message.style.borderRadius = '10px';
            message.style.backgroundColor = '#4CAF50';
            message.style.color = 'white';
            message.style.zIndex = '1000';
            message.innerHTML = '<i class="fas fa-check-circle"></i> Previous services selected!';
            document.body.appendChild(message);

            setTimeout(() => {
                message.remove();
            }, 3000);
        }

        const bookedSlots = <?php echo json_encode($booked_slots); ?>;
        let selectedSlot = null;

        function generateTimeSlots(selectedDate) {
            const container = document.getElementById('time-slots-container');
            container.innerHTML = '';
            
            // Generate slots from 8 AM to 10 PM
            for (let hour = 8; hour < 22; hour++) {
                for (let minute of ['00', '30']) {
                    const timeString = `${hour.toString().padStart(2, '0')}:${minute}`;
                    const dateTimeString = `${selectedDate} ${timeString}:00`;
                    
                    // Check if this slot is booked
                    const isBooked = bookedSlots.includes(dateTimeString);
                    
                    const slot = document.createElement('div');
                    slot.className = 'time-slot';
                    
                    if (isBooked) {
                        slot.className += ' booked';
                    }
                    
                    slot.textContent = formatTime(timeString);
                    slot.dataset.datetime = dateTimeString;
                    
                    if (!isBooked) {
                        slot.onclick = () => selectTimeSlot(slot);
                    }
                    
                    container.appendChild(slot);
                }
            }
        }

        function selectTimeSlot(element) {
            if (selectedSlot) {
                selectedSlot.classList.remove('selected');
            }
            
            element.classList.add('selected');
            selectedSlot = element;
            
            document.getElementById('selected-datetime').value = element.dataset.datetime;
        }

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes}\n${ampm}`;
        }

        // Generate initial time slots for today
        document.addEventListener('DOMContentLoaded', function() {
            generateTimeSlots(document.getElementById('appointment-date').value);
        });

        // Add this function to update booked slots after successful booking
        function updateBookedSlots(newBookedDateTime) {
            bookedSlots.push(newBookedDateTime);
            generateTimeSlots(document.getElementById('appointment-date').value);
        }
    </script>
</body>
</html> 