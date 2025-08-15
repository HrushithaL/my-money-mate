<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, category, amount, description, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $user_id, $type, $category, $amount, $description, $date);
    $stmt->execute();

    header("Location: view_transactions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico"> 
    <title>Add Transaction - MyMoneyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="dashboard.php"> MyMoneyMate</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="view_transactions.php">Transactions</a></li>
      <li class="nav-item"><a class="nav-link active" href="add_transaction.php">Add Transaction</a></li>
      <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="mb-4">Add New Transaction</h2>
    <form method="POST" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input name="category" id="category" class="form-control" placeholder="e.g., Food, Salary" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount (₹)</label>
            <input name="amount" type="number" step="0.01" id="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input name="description" id="description" class="form-control" placeholder="Optional">
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input name="date" type="date" id="date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add Transaction</button>
    </form>
</div>

</body>
</html>
