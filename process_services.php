<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $services = isset($_POST['services']) ? $_POST['services'] : [];
    $employee_id = $_SESSION['user_id'];

    // First, get car details
    $car_sql = "SELECT c.*, u.employee_name, u.phone 
                FROM client_cars c 
                JOIN users u ON c.employee_id = u.id 
                WHERE c.id = ?";
    $car_stmt = $conn->prepare($car_sql);
    $car_stmt->bind_param("i", $car_id);
    $car_stmt->execute();
    $car_result = $car_stmt->get_result();
    $car_details = $car_result->fetch_assoc();

    if (!$car_details) {
        echo json_encode(['success' => false, 'message' => 'Car not found']);
        exit;
    }

    // Get selected services details and calculate total
    $services_list = [];
    $total_amount = 0;

    if (!empty($services)) {
        $services_sql = "SELECT name, price FROM services WHERE id IN (" . str_repeat('?,', count($services) - 1) . '?)';
        $services_stmt = $conn->prepare($services_sql);
        
        // Create array of types for bind_param
        $types = str_repeat('i', count($services));
        $services_stmt->bind_param($types, ...$services);
        
        $services_stmt->execute();
        $services_result = $services_stmt->get_result();
        
        while ($service = $services_result->fetch_assoc()) {
            $services_list[] = $service['name'];
            $total_amount += $service['price'];
        }
    }

    // Create new order
    $order_sql = "INSERT INTO orders (
        client_car_id,
        car_type,
        employee_name,
        client_name,
        services,
        car_number,
        car_name,
        phone_number,
        arrival_time,
        total_amount,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, 'pending')";

    $services_json = json_encode($services_list);
    
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param(
        "isssssssd",
        $car_id,
        $car_details['car_type'],
        $_SESSION['employee_name'],
        $car_details['client_name'],
        $services_json,
        $car_details['car_number'],
        $car_details['car_name'],
        $car_details['phone'],
        $total_amount
    );

    if ($order_stmt->execute()) {
        $order_id = $conn->insert_id;
        
        // Generate a unique order code (e.g., ORD-2024-001)
        $order_code = 'ORD-' . date('Y') . '-' . str_pad($order_id, 3, '0', STR_PAD_LEFT);
        
        // Update the order with the generated code
        $update_code_sql = "UPDATE orders SET order_code = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_code_sql);
        $update_stmt->bind_param("si", $order_code, $order_id);
        $update_stmt->execute();

        // Redirect to a success page or order details page
        header("Location: order_details.php?id=" . $order_id);
        exit;
    } else {
        // Handle error
        header("Location: select_services.php?car_id=" . $car_id . "&type=" . $car_details['car_type'] . "&error=1");
        exit;
    }
} else {
    // Show error page with consistent styling
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Process Services - Car Wash</title>
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
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }

            .error-card {
                background: rgba(255, 255, 255, 0.95);
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
                text-align: center;
                animation: fadeInUp 0.8s ease;
            }

            .error-icon {
                font-size: 4rem;
                color: #dc3545;
                margin-bottom: 1rem;
            }

            h1 {
                color: #00416A;
                margin-bottom: 1rem;
                font-size: 2rem;
            }

            p {
                color: #666;
                margin-bottom: 2rem;
            }

            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            }

            .btn i {
                margin-right: 8px;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Processing animation */
            .processing {
                display: none;
                text-align: center;
                padding: 2rem;
            }

            .spinner {
                width: 50px;
                height: 50px;
                border: 5px solid #f3f3f3;
                border-top: 5px solid #4CAF50;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 1rem;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <?php include 'nav.php'; ?>

        <div class="container">
            <div class="error-card">
                <i class="fas fa-exclamation-circle error-icon"></i>
                <h1>Invalid Request</h1>
                <p>This page can only be accessed through the service selection form.</p>
                <a href="index.php" class="btn">
                    <i class="fas fa-home"></i> Return to Home
                </a>
            </div>
        </div>

        <!-- Processing overlay -->
        <div class="processing" id="processingOverlay">
            <div class="spinner"></div>
            <h2>Processing your order...</h2>
            <p>Please wait while we save your services.</p>
        </div>

        <script>
            // Show processing overlay when form is submitted
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const overlay = document.getElementById('processingOverlay');
                
                if (form) {
                    form.addEventListener('submit', function() {
                        overlay.style.display = 'block';
                    });
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}
?> 