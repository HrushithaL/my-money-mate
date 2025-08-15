session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

$summary = $conn->query("
  SELECT type, SUM(amount) as total 
  FROM transactions 
  WHERE user_id=$user_id AND MONTH(date) = MONTH(CURRENT_DATE()) 
  GROUP BY type
");

$income = $expense = 0;
while($row = $summary->fetch_assoc()) {
    if ($row['type'] == 'income') $income = $row['total'];
    if ($row['type'] == 'expense') $expense = $row['total'];
}
?>

<h2>Monthly Summary (<?= date('F') ?>)</h2>
<p>Income: ₹<?= number_format($income, 2) ?></p>
<p>Expense: ₹<?= number_format($expense, 2) ?></p>
<p>Balance: ₹<?= number_format($income - $expense, 2) ?></p>
