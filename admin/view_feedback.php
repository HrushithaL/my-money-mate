<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback - MyMoneyMate Admin</title>
  <link rel="icon" href="/assets/logo.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f4f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      margin-top: 50px;
    }

    .table thead {
      background-color: #20c997;
      color: #fff;
    }

    .table tbody tr:hover {
      background-color: #e8f5e9;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .card-header {
      background-color: #20c997;
      color: #fff;
      font-weight: bold;
      font-size: 1.2rem;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .back-btn {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <a href="dashboard.php" class="btn btn-secondary back-btn">⬅ Back to Dashboard</a>

  <div class="card">
    <div class="card-header text-center">
      🗣️ User Feedback
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Email</th>
              <th>Message</th>
              <th>Submitted At</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= htmlspecialchars($row['username']); ?></td>
                  <td><?= htmlspecialchars($row['email']); ?></td>
                  <td><?= nl2br(htmlspecialchars($row['msg'])); ?></td>
                  <td><?= date('d M Y, h:i A', strtotime($row['submitted_at'])); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No feedback submitted yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
