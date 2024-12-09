<?php
ob_start();
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

$employee_name = $_SESSION['employee_name'];
$employee_image = $_SESSION['image'];
$role = $_SESSION['role'];
$car_type = $_GET['type'];

include('db.php');

// First, check if the client_cars table exists
$check_table = $conn->query("SHOW TABLES LIKE 'client_cars'");
if ($check_table->num_rows == 0) {
    // Create the table if it doesn't exist
    $create_table = "CREATE TABLE IF NOT EXISTS `client_cars` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `car_number` varchar(20) NOT NULL,
        `car_name` varchar(100) NOT NULL,
        `car_type` enum('saloon','4wheel') NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `car_number` (`car_number`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->query($create_table);
}

// Fetch existing cars for quick selection
$cars_sql = "SELECT c.*, u.employee_name 
             FROM client_cars c 
             JOIN users u ON c.employee_id = u.id 
             WHERE c.car_type = ? 
             AND c.employee_id = ? 
             ORDER BY c.created_at DESC";
$cars_stmt = $conn->prepare($cars_sql);
if ($cars_stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$user_id = $_SESSION['user_id'];
$cars_stmt->bind_param("si", $car_type, $user_id);
$cars_stmt->execute();
$existing_cars = $cars_stmt->get_result();

// Fetch categories and services as before
if ($role === 'manager') {
    $sql = "SELECT * FROM categories";
} else {
    $sql = "SELECT * FROM categories WHERE access = 'employee'";
}
$result = $conn->query($sql);

$services_sql = "SELECT * FROM services WHERE car_type = ?";
$services_stmt = $conn->prepare($services_sql);
$services_stmt->bind_param("s", $car_type);
$services_stmt->execute();
$services_result = $services_stmt->get_result();

$services = [];
while ($row = $services_result->fetch_assoc()) {
    $services[$row['category_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Wash Services</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease;
        }

        .cars-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .car-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .car-card h3 {
            color: #00416A;
            margin-bottom: 10px;
        }

        .car-info {
            margin: 5px 0;
            color: #666;
        }

        .car-info i {
            width: 20px;
            color: #00416A;
            margin-right: 10px;
        }

        .add-new-car {
            background: #4CAF50;
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-new-car:hover {
            background: #45a049;
            transform: translateY(-5px);
        }

        .add-new-car i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            animation: slideIn 0.3s ease;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #00416A;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .cars-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="page-title">
            <h1>Car Wash Services</h1>
            <p>Select an existing car or add a new one</p>
        </div>

        <div class="cars-container">
            <?php if ($existing_cars->num_rows > 0): ?>
                <?php while($car = $existing_cars->fetch_assoc()): ?>
                    <div class="car-card" onclick="selectCar('<?php echo htmlspecialchars(json_encode($car)); ?>')">
                        <h3><i class="fas fa-car"></i> <?php echo htmlspecialchars($car['car_name']); ?></h3>
                        <div class="car-info">
                            <i class="fas fa-hashtag"></i> <?php echo htmlspecialchars($car['car_number']); ?>
                        </div>
                        <div class="car-info">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($car['client_name']); ?>
                        </div>
                        <div class="car-info">
                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($car['phone']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <div class="car-card add-new-car" onclick="showNewCarForm()">
                <i class="fas fa-plus-circle"></i>
                <h3>Add New Car</h3>
                <p>Register a new vehicle for service</p>
            </div>
        </div>
    </div>

    <!-- New Car Modal -->
    <div id="newCarModal" class="modal">
        <div class="modal-content">
            <h2>Add New Car</h2>
            <form id="newCarForm" onsubmit="saveNewCar(event)">
                <div class="form-group">
                    <label for="car_number">Car Number</label>
                    <input type="text" id="car_number" name="car_number" required>
                </div>
                <div class="form-group">
                    <label for="car_name">Car Name</label>
                    <input type="text" id="car_name" name="car_name" required>
                </div>
                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" required>
                </div>
                <input type="hidden" name="car_type" value="<?php echo htmlspecialchars($car_type); ?>">
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Car</button>
                    <button type="button" class="btn btn-secondary" onclick="hideNewCarForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showNewCarForm() {
            document.getElementById('newCarModal').style.display = 'flex';
        }

        function hideNewCarForm() {
            document.getElementById('newCarModal').style.display = 'none';
        }

        function selectCar(carData) {
            const car = JSON.parse(carData);
            window.location.href = `select_services.php?car_id=${car.id}&type=${car.car_type}`;
        }

        function saveNewCar(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            fetch('save_car.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the car.');
            });
        }
    </script>
</body>
</html>
