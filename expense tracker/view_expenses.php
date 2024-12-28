<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM expenses WHERE user_id = $user_id ORDER BY created_at DESC");

// Fetch user data (username)
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
    <title>View Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin-left: 250px;
            transition: all 0.3s;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            /* font-size: 2.5rem; */
            color: #343a40;
            font-weight: 700;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: center;
        }

        .btn {
            border-radius: 5px;
        }

        .btn-edit {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

 
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
            transition: all 0.3s;
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

        @media (max-width: 768px) {
            body {
                margin-left: 0;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .sidebar a {
                font-size: 16px;
                padding: 12px;
            }
        }

    </style>
</head>
<body>


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


<div class="content-wrapper">
    <div class="container mt-5">
        <h2>Your Expenses</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Comments</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format($row['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['comments']) ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="edit_expense.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_expense.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
       
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
