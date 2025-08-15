<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['user_name'] ?? 'Guest';
    $email = ''; // default in case not found

    // Fetch email from DB if not stored in session
    if ($user_id) {
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
    }

    $msg = trim($_POST['msg']);

    if ($user_id && !empty($msg)) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, email, username, msg, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $email, $username, $msg);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: dashboard.php?feedback=success");
    exit;
}
?>
