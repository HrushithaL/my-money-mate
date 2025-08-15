<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM transactions WHERE user_id=$user_id ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
    <title>Your Transactions - MyMoneyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="dashboard.php">MyMoneyMate</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link active" href="view_transactions.php">Transactions</a></li>
      <li class="nav-item"><a class="nav-link" href="add_transaction.php">Add</a></li>
      <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="mb-4">Your Transactions</h2>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount (₹)</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= ucfirst($row['type']) ?></td>
                    <td><?= $row['category'] ?></td>
                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                    <td><?= $row['description'] ?></td>
                    <td>
                        <a href="edit_transactions.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_transaction.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
