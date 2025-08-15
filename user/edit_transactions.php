<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Transaction ID not provided.";
    exit();
}
$id = intval($_GET['id']);

// Fetch the transaction securely
$stmt = $conn->prepare("SELECT * FROM transactions WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$txn = $result->fetch_assoc();

if (!$txn) {
    echo "Transaction not found or unauthorized.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE transactions SET type=?, category=?, amount=?, description=?, date=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssdssii", $type, $category, $amount, $description, $date, $id, $user_id);
    $stmt->execute();

    header("Location: view_transactions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="favicon.ico">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="dashboard.php">💰 MyMoneyMate</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="view_transactions.php">Transactions</a></li>
      <li class="nav-item"><a class="nav-link" href="add_transaction.php">Add</a></li>
      <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- Form -->
<div class="container mt-5">
    <h2>Edit Transaction</h2>
    <form method="POST" class="p-4 border rounded shadow-sm bg-light">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="income" <?= $txn['type'] == 'income' ? 'selected' : '' ?>>Income</option>
                <option value="expense" <?= $txn['type'] == 'expense' ? 'selected' : '' ?>>Expense</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input name="category" class="form-control" value="<?= htmlspecialchars($txn['category']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Amount (₹)</label>
            <input name="amount" type="number" step="0.01" class="form-control" value="<?= $txn['amount'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <input name="description" class="form-control" value="<?= htmlspecialchars($txn['description']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input name="date" type="date" class="form-control" value="<?= $txn['date'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Transaction</button>
        <a href="view_transactions.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
