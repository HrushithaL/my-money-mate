<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: users.php");
  exit();
}

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
  echo "Invalid user ID.";
  exit();
}

$user = $conn->query("SELECT username, email FROM users WHERE id = $id")->fetch_assoc();
if (!$user) {
  echo "User not found.";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Delete - MyMoneyMate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 100px;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card p-4">
    <h4 class="mb-3">Delete User</h4>
    <p>Are you sure you want to delete <strong><?= htmlspecialchars($user['username']) ?></strong> (<?= htmlspecialchars($user['email']) ?>)?</p>
    
    <form method="POST">
      <input type="hidden" name="id" value="<?= $id ?>">
      <button type="submit" class="btn btn-danger">Yes, Delete</button>
      <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
</body>
</html>
