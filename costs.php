<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

// إضافة راتب الموظف
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_salary'])) {
    $employee_name = $_POST['employee_name'];
    $salary = $_POST['salary'];
    $sql = "UPDATE users SET salary = ? WHERE employee_name = ? AND role = 'employee'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $salary, $employee_name);
    $stmt->execute();
}

// إضافة مصروفات أخرى
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $expense_name = $_POST['expense_name'];
    $expense_amount = $_POST['expense_amount'];
    $expense_date = $_POST['expense_date'];
    $sql = "INSERT INTO expenses (expense_name, expense_amount, expense_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $expense_name, $expense_amount, $expense_date);
    $stmt->execute();
}

// حساب مجموع الطلبات الشهرية
$current_month = date('m');
$current_year = date('Y');
$sql = "SELECT SUM(total_amount) as total_orders FROM orders WHERE MONTH(arrival_time) = ? AND YEAR(arrival_time) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_month, $current_year);
$stmt->execute();
$result = $stmt->get_result();
$total_orders = $result->fetch_assoc()['total_orders'];

// حساب مجموع رواتب الموظفين
$sql = "SELECT employee_name, salary FROM users WHERE role = 'employee'";
$result = $conn->query($sql);
$total_salaries = 0;
$salaries = [];
while ($row = $result->fetch_assoc()) {
    $total_salaries += $row['salary'];
    $salaries[] = $row;
}

// حساب مجموع المصروفات الأخرى
$sql = "SELECT expense_name, expense_amount, expense_date FROM expenses WHERE MONTH(expense_date) = ? AND YEAR(expense_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_month, $current_year);
$stmt->execute();
$result = $stmt->get_result();
$total_expenses = 0;
$expenses = [];
while ($row = $result->fetch_assoc()) {
    $total_expenses += $row['expense_amount'];
    $expenses[] = $row;
}

// حساب الربح الإجمالي
$total_revenue = $total_orders - $total_salaries - $total_expenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Costs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h3, h4 {
            margin-bottom: 20px;
        }
        p {
            margin: 0 0 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Project Costs</h3>
        <p><strong>Total Orders:</strong> <?php echo number_format($total_orders, 2); ?> OMR</p>
        <p><strong>Total Salaries:</strong> <?php echo number_format($total_salaries, 2); ?> OMR</p>
        <p><strong>Total Expenses:</strong> <?php echo number_format($total_expenses, 2); ?> OMR</p>
        <p><strong>Total Revenue:</strong> <?php echo number_format($total_revenue, 2); ?> OMR</p>

        <h4>Add Employee Salary</h4>
        <form method="post" action="">
            <div class="form-group">
                <label for="employee_name">Employee Name</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" step="0.01" class="form-control" id="salary" name="salary" required>
            </div>
            <button type="submit" name="add_salary" class="btn btn-primary">Add Salary</button>
        </form>

        <h4>Employee Salaries</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salaries as $salary): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($salary['employee_name']); ?></td>
                        <td><?php echo number_format($salary['salary'], 2); ?> OMR</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Add Other Expense</h4>
        <form method="post" action="">
            <div class="form-group">
                <label for="expense_name">Expense Name</label>
                <input type="text" class="form-control" id="expense_name" name="expense_name" required>
            </div>
            <div class="form-group">
                <label for="expense_amount">Expense Amount</label>
                <input type="number" step="0.01" class="form-control" id="expense_amount" name="expense_amount" required>
            </div>
            <div class="form-group">
                <label for="expense_date">Expense Date</label>
                <input type="date" class="form-control" id="expense_date" name="expense_date" required>
            </div>
            <button type="submit" name="add_expense" class="btn btn-primary">Add Expense</button>
        </form>

        <h4>Other Expenses</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Expense Name</th>
                    <th>Expense Amount</th>
                    <th>Expense Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($expense['expense_name']); ?></td>
                        <td><?php echo number_format($expense['expense_amount'], 2); ?> OMR</td>
                        <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
