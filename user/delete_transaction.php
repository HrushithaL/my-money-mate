session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="favicon.ico">

<?php
session_start();
include '../config/db.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$conn->query("DELETE FROM transactions WHERE id=$id AND user_id=$user_id");

header("Location: view_transactions.php");
