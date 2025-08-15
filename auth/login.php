<?php
session_start();
require_once '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
  <title>Login - MyMoneyMate</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="/assets/logo.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #e0f7fa);
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      max-width: 500px;
      margin-top: 70px;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      position: relative;
    }

    .form-control {
      margin-bottom: 20px;
    }

    .btn-accent {
      background-color: #20c997;
      color: white;
      font-weight: bold;
    }

    .btn-accent:hover {
      background-color: #17b28c;
    }

    .close-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 20px;
      color: #888;
      text-decoration: none;
    }

    .close-btn:hover {
      color: #000;
    }
  </style>
</head>
<body>

<div class="container">
  <a href="../index.php" class="close-btn" title="Back to Home">&times;</a>
  <h3 class="text-center mb-4">Login to Your Account</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    <input type="password" name="password" class="form-control" placeholder="Password" required>

    <button type="submit" class="btn btn-accent w-100">Login</button>
  </form>

  <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
