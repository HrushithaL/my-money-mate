<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch total users
$totalUsersQuery = $conn->query("SELECT COUNT(id) AS total_users FROM users WHERE role='user'");
$totalUsersResult = $totalUsersQuery->fetch_assoc();
$totalUsers = $totalUsersResult['total_users'] ?? 0;

// Fetch total transactions
$totalTransactionsQuery = $conn->query("SELECT COUNT(id) AS total_transactions FROM transactions");
$totalTransactionsResult = $totalTransactionsQuery->fetch_assoc();
$totalTransactions = $totalTransactionsResult['total_transactions'] ?? 0;

// Fetch total income (assuming 'income' type transactions are being stored)
$totalIncomeQuery = $conn->query("SELECT SUM(amount) AS total_income FROM transactions WHERE type='income'");
$totalIncomeResult = $totalIncomeQuery->fetch_assoc();
$totalIncome = $totalIncomeResult['total_income'] ?? 0;

// Fetch total spending by category
$categoryDataQuery = $conn->query("SELECT category, SUM(amount) AS total_spent FROM transactions WHERE type='expense' GROUP BY category");
$categoryData = [];
while ($row = $categoryDataQuery->fetch_assoc()) {
    $categoryData[$row['category']] = $row['total_spent'];
}

// Fetch monthly spending data for the line chart
$monthlyDataQuery = $conn->query("SELECT DATE_FORMAT(date, '%b') AS month, SUM(amount) AS total_spent FROM transactions WHERE type='expense' GROUP BY month ORDER BY MONTH(date)");
$monthlyData = [];
$months = [];
while ($row = $monthlyDataQuery->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyData[] = $row['total_spent'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">

  <title>Admin Dashboard - MyMoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #00796b;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar .nav-link {
      color: white;
      margin: 15px 0;
      font-weight: 600;
    }

    .sidebar .nav-link:hover {
      color: #80cbc4;
    }

    .sidebar .navbar-brand {
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
    }

    /* Logo Styling */
    .logo-icon {
      width: 30px;
      height: 30px;
      margin-right: 10px; /* Space between logo and text */
    }

    /* Navbar */
    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #ddd;
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
    .btn-custom {
      background-color: #20c997;
      color: white;
      font-weight: 500;
    }
    .btn-custom:hover {
      background-color: #17b28c;
    }
    .navbar-nav .nav-item {
      margin-right: 10px;
    }

    /* Dashboard Card Styling */
    .overview-card {
      border-radius: 1rem;
      padding: 20px;
      text-align: center;
      color: white;
    }

    .overview-card h5 {
      font-size: 1.2rem;
    }

    /* Charts */
    .chart-card {
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Reduced size for the pie chart */
    .chart-container {
      height: 300px; /* Control the height of both charts */
    }

    /* Optional: You can fine-tune the size of both charts further */
    canvas {
      max-height: 250px; /* Limiting height */
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <a class="navbar-brand" href="#">
    <!-- Logo Icon -->
    <img src="logo.png" alt="Logo" class="logo-icon">


    
  </a>
  <nav class="nav flex-column">
    <a class="nav-link active" href="dashboard.php">Dashboard</a>
    <a class="nav-link" href="users.php">Users</a>
    <a class="nav-link" href="add_user.php">Add User</a>
    <a class="nav-link" href="transactions.php">Transactions</a>
    <a class="nav-link" href="view_feedback.php">Feedback</a>
    <a class="nav-link" href="../auth/logout.php">Logout</a>
  </nav>
</div>
<!-- Main Content -->
<div class="content" style="margin-left: 250px; padding: 20px;">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="#">MyMoneyMate</a>
      <div class="d-flex align-items-center">
        <span class="me-3">👋 Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="../auth/logout.php" class="btn btn-outline-dark btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Dashboard Overview -->
  <div class="container mt-4">
    <h2 class="text-center mb-4">Welcome Admin, <?= $_SESSION['user_name'] ?? '' ?> 👋</h2>

    <!-- Dashboard Cards Row -->
    <div class="row text-center mb-4">
      <!-- Total Users -->
      <div class="col-md-4">
        <div class="card overview-card" style="background-color: #00796b;">
          <div class="card-body">
            <h5>Total Users</h5>
            <h4><?= number_format($totalUsers) ?></h4>
          </div>
        </div>
      </div>
      <!-- Total Transactions -->
      <div class="col-md-4">
        <div class="card overview-card" style="background-color: #6f42c1;">
          <div class="card-body">
            <h5>Total Transactions</h5>
            <h4><?= number_format($totalTransactions) ?></h4>
          </div>
        </div>
      </div>
      <!-- Total Income -->
      <div class="col-md-4">
        <div class="card overview-card" style="background-color: #dc3545;">
          <div class="card-body">
            <h5>Total Income</h5>
            <h4>₹<?= number_format($totalIncome, 2) ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Spending by Category Chart -->
    <div class="row">
      <div class="col-md-6">
        <div class="card chart-card">
          <h5 class="text-center">Spending by Category</h5>
          <div class="chart-container">
            <canvas id="categoryChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Monthly Spending Chart -->
      <div class="col-md-6">
        <div class="card chart-card">
          <h5 class="text-center">Monthly Spending</h5>
          <div class="chart-container">
            <canvas id="spendingChart"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Chart.js for Spending by Category
  const ctxCategory = document.getElementById('categoryChart').getContext('2d');
  new Chart(ctxCategory, {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_keys($categoryData)) ?>,
      datasets: [{
        data: <?= json_encode(array_values($categoryData)) ?>,
        backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#6f42c1', '#17a2b8']
      }]
    }
  });

  // Chart.js for Monthly Spending
  const ctxSpending = document.getElementById('spendingChart').getContext('2d');
  new Chart(ctxSpending, {
    type: 'line',
    data: {
      labels: <?= json_encode($months) ?>,
      datasets: [{
        label: 'Money Spent',
        data: <?= json_encode($monthlyData) ?>,
        borderColor: '#20c997',
        backgroundColor: 'rgba(32, 200, 151, 0.2)',
        fill: true
      }]
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
