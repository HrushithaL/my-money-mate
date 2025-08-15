<?php
include '../config/db.php';
$result = $conn->query("
  SELECT t.*, u.username 
  FROM transactions t 
  JOIN users u ON t.user_id = u.id 
  ORDER BY t.date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
  <title>All Transactions - MyMoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      margin-top: 60px; /* Add space for fixed navbar */
    }

    /* Fixed navbar styling */
    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000; /* Ensure the navbar is above other content */
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #20c997; /* Green accent color */
    }

    .nav-link {
      font-weight: 500;
      color: #333;
    }

    .nav-link:hover {
      color: #20c997;
    }

    .nav-link.active {
      color: white;
      background-color: #20c997;
      border-radius: 5px;
      padding: 6px 12px;
    }

    .navbar-nav .nav-item {
      margin-right: 10px;
    }

    .d-flex span {
      font-size: 1.1rem;
      color: #333;
    }

    /* Table styling */
    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
    }
    th {
      background-color: #20c997;
      color: white;
      font-weight: bold;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #dff0d8;
    }
    a {
      color: #dc3545;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
    h2 {
      text-align: center;
      margin-top: 30px;
      color: #333;
    }
    .logo-icon {
      width: 30px;
      height: 30px;
      margin-right: 10px; /* Space between logo and text */
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    
    <a class="navbar-brand" href="#">
    <!-- Logo Icon -->
    <img src="logo.png" alt="Logo" class="logo-icon">
    MyMoneyMate

    
  </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav">
        <!-- Tabs in Navbar -->
        <li class="nav-item">
          <a class="nav-link active" href="users.php">Users</a> <!-- Navigate to Users Page -->
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_user.php">Add User</a> <!-- Navigate to Add User Page -->
        </li>
        <li class="nav-item">
          <a class="nav-link" href="transactions.php">Transactions</a> <!-- Navigate to Transactions Page -->
        </li>
      </ul>
      <div class="d-flex align-items-center">
        
        <a href="../auth/logout.php" class="btn btn-outline-dark btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
  <h2>All Transactions</h2>
  <table>
    <tr>
      <th>User</th><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th><th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['username'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= ucfirst($row['type']) ?></td>
        <td><?= $row['category'] ?></td>
        <td>₹<?= number_format($row['amount'], 2) ?></td>
        <td><?= $row['description'] ?></td>
        <td>
          <a href="delete_transaction.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete transaction?')">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
