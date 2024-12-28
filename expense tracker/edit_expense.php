<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_expenses.php');
    exit;
}

$expense_id = $_GET['id'];

// Fetch the existing expense data
$query = "SELECT * FROM expenses WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $expense_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: view_expenses.php');
    exit;
}

$expense = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $comments = $_POST['comments'];

    if (empty($category) || empty($amount)) {
        $error = "Please fill all fields.";
    } else {
       
        $update_query = "UPDATE expenses SET category = ?, amount = ?, comments = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sdsi", $category, $amount, $comments, $expense_id);

        if ($update_stmt->execute()) {
            header('Location: view_expenses.php');
            exit;
        } else {
            $error = "Error updating expense.";
        }
    }
}


// Fetch user data username
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
    <title>Edit Expense</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: #2C3E50;
            width: 250px;
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
            margin-left: 270px;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
        }

        h2 {
            /* font-size: 2.5rem; */
            color: #343a40;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .btn {
            border-radius: 5px;
        }

        .btn-save {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-save:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-back {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-back:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .error {
            color: red;
            margin-bottom: 15px;
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

<div class="container mt-5">
    <h2>Edit Expense</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="edit_expense.php?id=<?= $expense_id ?>" method="POST">
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($expense['category']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars($expense['amount']) ?>" required step="0.01">
        </div>

        <div class="mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea class="form-control" id="comments" name="comments"><?= htmlspecialchars($expense['comments']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="view_expenses.php" class="btn btn-secondary">Back to Expenses</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
