<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Car Type</title>
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

        .page-title h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .page-title p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .car-selection {
            display: flex;
            gap: 40px;
            justify-content: center;
            align-items: stretch;
            padding: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .car-option {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .car-option:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .car-option i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .car-option.saloon i {
            color: #4CAF50;
        }

        .car-option.wheel4 i {
            color: #00416A;
        }

        .car-option h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .car-option p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .car-option .price-range {
            background: rgba(0, 65, 106, 0.1);
            padding: 8px 15px;
            border-radius: 15px;
            color: #00416A;
            font-weight: 500;
            font-size: 0.9rem;
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

        @media (max-width: 768px) {
            .car-selection {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }

            .car-option {
                width: 100%;
                max-width: 320px;
            }

            .page-title h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="page-title">
            <h1>Select Vehicle Type</h1>
            <p>Choose the appropriate vehicle category for your service</p>
        </div>
        
        <div class="car-selection">
            <div class="car-option saloon" onclick="location.href='car_wash_services.php?type=saloon'">
                <i class="fas fa-car"></i>
                <h2>Saloon</h2>
                <p>Perfect for sedans, hatchbacks, and compact cars. Professional cleaning services tailored for standard vehicles.</p>
                <span class="price-range">Starting from 2 OMR</span>
            </div>
            
            <div class="car-option wheel4" onclick="location.href='car_wash_services.php?type=4wheel'">
                <i class="fas fa-truck-monster"></i>
                <h2>4 Wheel</h2>
                <p>Specialized service for SUVs, trucks, and larger vehicles. Enhanced cleaning for bigger vehicles.</p>
                <span class="price-range">Starting from 3 OMR</span>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>