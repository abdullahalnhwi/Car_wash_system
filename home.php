<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Car Wash</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .hero-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            gap: 50px;
            align-items: center;
            padding: 40px;
            padding-right: 0;
        }

        .content-section {
            flex: 1;
            color: white;
            animation: fadeInLeft 1s ease;
        }

        .image-section {
            flex: 1;
            animation: fadeInRight 1s ease;
            margin-right: -100px;
            transform: translateX(50px);
        }

        h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .tagline {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .buttons {
            display: flex;
            gap: 20px;
        }

        .btn {
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
            border: none;
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::after {
            width: 300px;
            height: 300px;
        }

        .feature-icons {
            display: flex;
            gap: 30px;
            margin-top: 50px;
        }

        .feature {
            text-align: center;
            animation: fadeInUp 1s ease;
        }

        .feature i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .feature p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .car-animation {
            width: 100%;
            max-width: 500px;
            height: auto;
            filter: drop-shadow(5px 5px 15px rgba(0,0,0,0.3));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 968px) {
            .hero-container {
                flex-direction: column;
                text-align: center;
            }

            .buttons {
                justify-content: center;
            }

            .feature-icons {
                justify-content: center;
            }

            h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="hero-container">
        <div class="content-section">
            <h1>Premium Car Wash Services</h1>
            <p class="tagline">Experience the finest car cleaning service with cutting-edge technology and professional care.</p>
            
            <div class="buttons">
                <a href="login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="register.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>

            <div class="feature-icons">
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <p>Quick Service</p>
                </div>
                <div class="feature">
                    <i class="fas fa-star"></i>
                    <p>Premium Quality</p>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <p>100% Secure</p>
                </div>
            </div>
        </div>
        
        <div class="image-section">
            <img src="img/logo.png" alt="Car Wash Service" class="car-animation">
        </div>
    </div>
</body>
</html> 