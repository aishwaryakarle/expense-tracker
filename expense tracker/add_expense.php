<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $comments = $_POST['comments'];

    
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, category, amount, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $category, $amount, $comments);
    $stmt->execute();

   
    header('Location: view_expenses.php');
    exit;
}

// Fetch user data 
$user_query = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2C3E50;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 30px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 15px 20px;
            display: block;
            border-bottom: 1px solid #34495E;
            transition: background-color 0.3s ease;
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

        .container {
            margin-left: 400px;
            margin-bottom: 200px;
            background-color: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
            max-width: 800px;
            margin-top: 50px;
        }

        h2 {
            /* font-size: 2.5rem; */
            color: #343a40;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control, .btn {
            border-radius: 5px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-container a {
            text-decoration: none;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

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


<div class="container">
    <h2>Add Expense</h2>
    

    <form method="POST" action="add_expense.php">
        <div class="form-group mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" placeholder="Enter category" required>
        </div>

        <div class="form-group mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
        </div>

        <div class="form-group mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea class="form-control" id="comments" name="comments" placeholder="Enter any comments"></textarea>
        </div>

    
        <div class="btn-container">
            <button type="submit" class="btn btn-primary">Add Expense</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
