<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit();
}

include '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fullname = $_POST['fullname'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $role = $_POST['role'] ?? '';

  if (!empty($fullname) && !empty($email) && !empty($password) && !empty($role)) {

    // Check for duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "A user with this email already exists.";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $fullname, $email, $hashedPassword, $role);

      if ($stmt->execute()) {
        $success = "User added successfully!";
        header("Location: users.php");
        exit();
      } else {
        $error = "Failed to add user. Please try again.";
      }
    }

  } else {
    $error = "All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
  <title>Add New User - MyMoneyMate</title>
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
    .form-control, .form-select {
      border-radius: 8px;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">Add New User</h3>
      <a href="dashboard.php" class="close-btn" title="Back to Dashboard">&times;</a>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input name="fullname" class="form-control" required placeholder="Enter full name">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required placeholder="Enter email">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required placeholder="Enter password">
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="">Select role</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Add User</button>
    </form>
  </div>
</div>

</body>
</html>
