<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

$employee_name = $_SESSION['employee_name'];
$employee_image = isset($_SESSION['image']) && !empty($_SESSION['image']) ? $_SESSION['image'] : 'img/user_icong.png';
$role = $_SESSION['role'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    .navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        height: 70px;
    }

    .navbar-brand img {
        width: 60px;
        height: auto;
        transition: transform 0.3s ease;
    }

    .navbar-nav {
        align-items: center;
        width: 100%;
    }

    .nav-item {
        margin: 0 10px;
    }

    .nav-link {
        color: #00416A !important;
        font-weight: 500;
        padding: 8px 15px !important;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background: rgba(0, 65, 106, 0.1);
        transform: translateY(-2px);
    }

    .employee-info {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        background: rgba(0, 65, 106, 0.1);
        border-radius: 25px;
        margin-left: 15px;
    }

    .employee-info img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
        border: 2px solid #00416A;
        object-fit: cover;
    }

    .employee-info span {
        color: #00416A;
        font-weight: 500;
    }

    .navbar-toggler {
        border: none;
        padding: 10px;
    }

    .navbar-toggler:focus {
        outline: none;
        box-shadow: none;
    }

    .logout-link {
        color: #dc3545 !important;
    }

    .logout-link:hover {
        background: rgba(220, 53, 69, 0.1);
    }

    @media (min-width: 992px) {
        .navbar-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .employee-info {
            margin-left: 15px;
            order: 2;
        }
    }

    @media (max-width: 991px) {
        .navbar-collapse {
            background: rgba(255, 255, 255, 0.98);
            padding: 20px;
            border-radius: 15px;
            margin-top: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .employee-info {
            margin: 15px 0;
            justify-content: center;
        }

        .nav-item {
            margin: 5px 0;
            text-align: center;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.png" alt="Company Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                <?php if ($role == 'manager'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="employees.php">
                        <i class="fas fa-users"></i> Employees
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link logout-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
                <li class="nav-item">
                    <div class="employee-info">
                        <img src="<?php echo htmlspecialchars($employee_image); ?>" alt="Employee Image" onerror="this.src='img/user_icong.png'">
                        <span><?php echo htmlspecialchars($employee_name); ?></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>