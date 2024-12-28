<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data username
$user_query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch total expenses by category
$category_query = "SELECT category, SUM(amount) AS total_amount FROM expenses WHERE user_id = ? GROUP BY category";
$stmt = $conn->prepare($category_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$totals = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
    $totals[] = $row['total_amount'];
}

// Fetch the 5 most recent expenses
$recent_expenses_query = "SELECT * FROM expenses WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($recent_expenses_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_expenses = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      
        .sidebar {
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: #2C3E50;
            padding-top: 30px;
            width: 250px;
            color: white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 15px 20px;
            display: block;
            border-bottom: 1px solid #34495E;
        }

        .sidebar a:hover {
            background-color: #1ABC9C;
            color: white;
        }

        .sidebar .user-info {
            padding: 20px;
            background-color: #1A252F;
            color: white;
            margin-bottom: 30px;
            text-align: center;
            border-radius: 5px;
        }

        .content {
            margin-left: 270px;
            padding: 30px;
        }

        .content h2 {
            /* font-size: 32px; */
            font-weight: 700;
            margin-bottom: 30px;
            color: #34495E;
        }

        .content table {
            border-collapse: collapse;
        }

        .content table th, .content table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .content table th {
            background-color: #1ABC9C;
            color: white;
        }

        .content .chart-container {
            margin-bottom: 40px;
            width: 30%; /* Adjust the width */
            margin: 0 auto; /* Center the chart */
        }

        /* .content .table-container {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        } */

        /* Footer styles */
        /* footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #2C3E50;
            color: white;
            text-align: center;
            padding: 10px 0;
        } */
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="user-info">
        <h4>Welcome, <?= htmlspecialchars($user['username']) ?></h4>
    </div>
    <a href="dashboard.php">Dashboard</a>
    <a href="add_expense.php">Add Expenses</a>
    <a href="view_expenses.php">View Expenses</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main content -->
<div class="content">
    <h2>Expense Tracker Dashboard</h2>


    <div class="row">
        <div class="col-md-6 chart-container">
            <canvas id="expenseChart"></canvas>
        </div>
        <div class="col-md-6 table-container">
            <h4>Recent Expenses</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Created At</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($expense = $recent_expenses->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($expense['category']) ?></td>
                            <td><?= htmlspecialchars($expense['amount']) ?></td>
                            <td><?= htmlspecialchars($expense['created_at']) ?></td>
                            <td><?= htmlspecialchars($expense['comments']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<!-- <footer>
    <p>&copy; 2024 Expense Tracker. All Rights Reserved.</p>
</footer> -->

<script>
// Data for the Pie Chart
const categories = <?= json_encode($categories) ?>;
const totals = <?= json_encode($totals) ?>;

// Create the Chart
const ctx = document.getElementById('expenseChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: categories,
        datasets: [{
            data: totals,
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
            ],
            hoverOffset: 4
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

</body>
</html>
