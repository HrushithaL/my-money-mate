<?php
include '../config/db.php';
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
  <title>All Users - MyMoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 60px;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .close-btn {
      font-size: 1.5rem;
      text-decoration: none;
      color: #000;
      opacity: 0.6;
    }
    .close-btn:hover {
      color: #dc3545;
      opacity: 1;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">All Users</h3>
      <a href="dashboard.php" class="close-btn" title="Back to Dashboard">&times;</a>
    </div>

    <div class="d-flex justify-content-end mb-3">
      <a href="add_user.php" class="btn btn-success">+ Add User</a>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= htmlspecialchars($row['fullname'] ?? $row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
              <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
