<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    
  
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $expense_id, $_SESSION['user_id']);
    $stmt->execute();

    
    header('Location: view_expenses.php');
    exit;
} else {
    echo "Expense ID not specified.";
}
?>
