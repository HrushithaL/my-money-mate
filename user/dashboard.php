<?php session_start(); 
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../auth/login.php"); 
    exit(); 
} 
include '../config/db.php'; 

$user_id = $_SESSION['user_id']; 

// Fetch income
$incomeQuery = $conn->prepare("SELECT SUM(amount) as total_income FROM transactions WHERE user_id=? AND type='income'");
$incomeQuery->bind_param("i", $user_id);
$incomeQuery->execute();
$incomeResult = $incomeQuery->get_result()->fetch_assoc();
$totalIncome = $incomeResult['total_income'] ?? 0;

// Fetch expense
$expenseQuery = $conn->prepare("SELECT SUM(amount) as total_expense FROM transactions WHERE user_id=? AND type='expense'");
$expenseQuery->bind_param("i", $user_id);
$expenseQuery->execute();
$expenseResult = $expenseQuery->get_result()->fetch_assoc();
$totalExpense = $expenseResult['total_expense'] ?? 0;

$balance = $totalIncome - $totalExpense;

// Categories
$categories = ['Food', 'Electricity', 'Transport', 'Shopping', 'Earnings', 'Others'];
$categoryData = [];

foreach ($categories as $cat) {
    $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM transactions WHERE user_id=? AND category=?");
    $stmt->bind_param("is", $user_id, $cat);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $categoryData[$cat] = $result['total'] ?? 0;
    $stmt->close();
}

// Monthly income and expense for line chart
$monthlyData = [];
$months = [];

$query = $conn->prepare("SELECT
        DATE_FORMAT(date, '%b') as month,
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
    FROM transactions
    WHERE user_id = ?
    GROUP BY MONTH(date)
    ORDER BY MONTH(date)");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyData['income'][] = $row['income'];
    $monthlyData['expense'][] = $row['expense'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    Play me a song for me. <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
    <title>Dashboard | MyMoneyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --accent: #20c997;
            --primary-bg: #f9fdfd;
            --card-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
            --text-muted: #6c757d;
        }

        body {
            background: var(--primary-bg);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #00796b;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
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

        .content {
            margin-left: 250px;
            width: 100%;
            padding: 20px;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #00796b;
        }

        .card {
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
        }

        .card h5, .card h4 {
            margin-bottom: 0;
        }

        .stat-card {
            margin-bottom: 20px;
        }

        .stat-card .card-body {
            padding: 20px;
        }

        .card-header {
            background: #f5f5f5;
            font-weight: bold;
        }

        .highlight-text {
            color: var(--accent);
        }

        .btn-accent {
            background-color: var(--accent);
            color: white;
        }

        .btn-accent:hover {
            background-color: #17b28c;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <a class="navbar-brand" href="#">MyMoneyMate</a>
    <nav class="nav flex-column">
        <a class="nav-link active" href="dashboard.php">Dashboard</a>
        <a class="nav-link" href="add_transaction.php">Add Transaction</a>
        <a class="nav-link" href="view_transactions.php">View Transactions</a>
        <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
    </nav>
</div>

<!-- Content Section -->
<div class="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">User Overview</a>
            
            <button class="btn btn-sm btn-outline-success ms-auto" data-bs-toggle="modal" data-bs-target="#feedbackModal">
    💬 Give Feedback
</button>

        </div>
    </nav>

    <!-- Dashboard Stats -->
    <div class="row text-center mb-4">
        <div class="col-md-3 stat-card">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Income</h5>
                    <h4>₹<?= number_format($totalIncome, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Expense</h5>
                    <h4>₹<?= number_format($totalExpense, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Balance</h5>
                    <h4>₹<?= number_format($balance, 2) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 stat-card">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Monthly Savings Avg</h5>
                    <h4>₹<?= number_format($totalIncome - $totalExpense, 2) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Cards -->
    <div class="row">
        <?php foreach ($categoryData as $cat => $amount): ?>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($cat) ?></h5>
                        <h4>₹<?= number_format($amount, 2) ?></h4>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Export Button -->
    <div class="text-end mt-4">
        <a href="export_report.php" class="btn btn-outline-custom">Export Monthly Report (CSV)</a>
    </div>

    <!-- Charts Row -->
    <div class="row mt-5">
        <div class="col-md-5 mb-3">
            <div class="card p-3">
                <h5 class="text-center">Category-wise Spending</h5>
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5 class="text-center">Income vs Expense (Last 6 Months)</h5>
                <canvas id="lineChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Charts Script -->
<script>
    const ctxPie = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($categoryData)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($categoryData)) ?>,
                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#6f42c1', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [
                {
                    label: 'Income',
                    data: <?= json_encode($monthlyData['income'] ?? []) ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Expense',
                    data: <?= json_encode($monthlyData['expense'] ?? []) ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Feedback Modal -->
 <!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="submit_feedback.php">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">We value your feedback!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="feedbackText" class="form-label">Your Feedback</label>
          <textarea name="msg" id="feedbackText" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-accent">Submit</button>
      </div>
    </form>
  </div>
</div>



</body>
</html>
