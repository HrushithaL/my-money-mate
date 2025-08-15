<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="favicon.ico">

  <title>MyMoneyMate - Track. Save. Grow.</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f7fdfd;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      padding: 80px 0;
    }

    .btn-accent {
      background-color: #20c997;
      color: white;
    }

    .btn-accent:hover {
      background-color: #17b28c;
    }

    .btn-outline-custom {
      border-color: #20c997;
      color: #20c997;
      font-weight: 600;
      transition: background-color 0.3s, color 0.3s;
    }

    .btn-outline-custom:hover {
      background-color: #20c997;
      color: white;
    }

    .highlight-text {
      color: #20c997;
    }

    .logo-icon {
      height: 32px;
      width: 32px;
      margin-right: 8px;
    }

    /* Feature Section */
    .feature-section {
      padding: 60px 0;
      background-color: #fff;
    }

    /* Ensure cards are equal in size */
    .feature-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      height: 100%; /* Make sure all cards have the same height */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* Hover effect on cards */
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
      background-color: #20c997;
      color: white;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 24px;
      margin-bottom: 15px;
    }

    .feature-section h2 {
      font-weight: bold;
      margin-bottom: 40px;
    }

    .highlight-text {
      color: #20c997;
    }

    /* Footer */
    .footer {
      background-color: #f3fdfc;
      padding: 60px 0 20px;
    }

    .footer .logo-icon {
      height: 32px;
      width: 32px;
      margin-right: 8px;
    }

    /* Navbar Styling */
    .navbar-light .navbar-nav .nav-link {
      font-weight: 600;
      margin-right: 15px; /* Added space between navbar buttons */
    }

    .navbar-nav .btn {
      margin-left: 10px; /* Added space between buttons */
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
      <img src="assets/logo.png" alt="Logo" class="logo-icon">
      MyMoneyMate
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="btn text-dark fw-semibold me-2 btn-outline" href="index.php">Home</a> <!-- Added Home Link with button styling -->
        </li>
        <li class="nav-item">
          <a class="btn btn-accent fw-semibold" href="auth/login.php">Sign In</a> <!-- Added Sign In Link with button styling -->
        </li>
        <li class="nav-item">
          <a class="btn btn-accent fw-semibold" href="auth/register.php">Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section text-center text-md-start">
  <div class="container">
    <div class="row align-items-center">
      <!-- Text Content -->
      <div class="col-md-6 mb-4 mb-md-0">
        <h1 class="fw-bold display-5">Take Control of Your <span class="highlight-text">Personal Finances</span></h1>
        <p class="lead text-muted mt-3">Track. Save. Grow. <br> MyMoneyMate helps users manage their income, expenses, and savings effortlessly.</p>
        <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
          <a href="auth/register.php" class="btn btn-accent btn-lg">Get Started Free →</a>
          <a href="auth/login.php" class="btn btn-outline-secondary btn-lg">Sign In</a>
        </div>
        <p class="text-muted mt-3 small"><i class="bi bi-graph-up-arrow text-success"></i> Join thousands already managing their finances</p>
      </div>

      <!-- Image & Stats -->
      <div class="col-md-6 text-center">
        <img src="assets/dashboard.png" alt="Dashboard Preview" class="img-fluid rounded shadow-sm">
      </div>
    </div>
  </div>
</section>

<!-- Feature Section -->
<section class="feature-section text-center">
  <div class="container">
    <h2>Everything You Need to <span class="highlight-text">Manage Your Money</span></h2>
    <p class="lead text-muted">Powerful features designed to make financial management simple, effective, and insightful.</p>
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <!-- Card 1 -->
      <div class="col">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
          <h5>Easy Expense Tracking</h5>
          <p>Effortlessly categorize and track your daily expenses with our intuitive interface.</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-pie-chart"></i></div>
          <h5>Monthly Financial Summaries</h5>
          <p>Get comprehensive monthly reports that show exactly where your money is going.</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
          <h5>Visual Insights with Charts</h5>
          <p>Understand your financial patterns with beautiful charts and graphs.</p>
        </div>
      </div>
      <!-- Card 4 -->
      <div class="col">
        <div class="feature-card">
          <div class="feature-icon"><i class="bi bi-people"></i></div>
          <h5>Admin Dashboard</h5>
          <p>Comprehensive admin panel for managing users and viewing analytics data.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer mt-5">
  <div class="container">
    <div class="row text-center text-md-start">
      <div class="col-md-4 mb-4">
        <a class="navbar-brand d-flex align-items-center fw-bold mb-3" href="#">
          <img src="assets/logo.png" alt="Logo" class="logo-icon">
          MyMoneyMate
        </a>
        <p class="text-muted">Take control of your personal finances with our easy-to-use budget tracking platform. Track, save, and grow your money with confidence.</p>
      </div>
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled text-muted">
          <li><a href="#" class="text-decoration-none text-muted">Home</a></li>
          <li><a href="auth/login.php" class="text-decoration-none text-muted">Sign In</a></li>
          <li><a href="auth/register.php" class="text-decoration-none text-muted">Sign Up</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold mb-3">Support</h6>
        <ul class="list-unstyled text-muted">
          <li><a href="#" class="text-decoration-none text-muted">Privacy Policy</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Terms of Service</a></li>
          <li><a href="#" class="text-decoration-none text-muted">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p class="text-center text-muted small mb-0">© 2024 MyMoneyMate. All rights reserved.</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>
</html>
