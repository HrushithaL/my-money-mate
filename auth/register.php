<?php
session_start();
require_once '../config/db.php';

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $username;
                $_SESSION['role'] = 'user';
                $success = "Registration successful! Redirecting...";
                header("Refresh: 2; URL=../user/dashboard.php");
                exit();
            } else {
                $error = "Registration failed. Try again.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="/my-money-mate/favicon.ico">
  <title>Register - MyMoneyMate</title>
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
      top: 20px;
      right: 25px;
      font-size: 1.5rem;
      color: #aaa;
      text-decoration: none;
      font-weight: bold;
    }

    .close-btn:hover {
      color: #000;
    }
  </style>
</head>
<body>

<!-- ✅ Register Form -->
<div class="container">
  <a href="../index.php" class="close-btn">&times;</a>
  <h3 class="text-center mb-4">Create Your Account</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="username" class="form-control" placeholder="Full Name" required>
    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>

    <button type="submit" class="btn btn-accent w-100">Register</button>
  </form>

  <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
