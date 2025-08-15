<?php
include '../config/db.php';

// Validate and sanitize ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid User ID.");
}

// Fetch existing user data
$result = $conn->prepare("SELECT * FROM users WHERE id=?");
$result->bind_param("i", $id);
$result->execute();
$res = $result->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Avoid duplicate email issue
    $check = $conn->prepare("SELECT id FROM users WHERE email=? AND id != ?");
    $check->bind_param("si", $email, $id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Email already in use by another user.');</script>";
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
        $stmt->execute();

        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User - MyMoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fb;
    }
    .container {
      max-width: 600px;
      margin-top: 80px;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card p-4">
      <h3 class="text-center mb-4">Edit User</h3>
      <form method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role">
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Update User</button>
          <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
