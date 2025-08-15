<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="monthly_report.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, ['Month', 'Total Income', 'Total Expense', 'Balance']);

// Fetch monthly data
$query = $conn->prepare("
  SELECT 
    DATE_FORMAT(date, '%M %Y') as month_name,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
  FROM transactions
  WHERE user_id = ?
  GROUP BY YEAR(date), MONTH(date)
  ORDER BY YEAR(date), MONTH(date)
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

// Output rows to CSV
while ($row = $result->fetch_assoc()) {
  $balance = $row['income'] - $row['expense'];
  fputcsv($output, [$row['month_name'], $row['income'], $row['expense'], $balance]);
}

fclose($output);
exit();
?>
