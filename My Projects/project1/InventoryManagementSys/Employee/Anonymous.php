<!-- Session file Added(Session Start) -->
<?php include('../auth.php');?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Access Restricted</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom, #b198f2, #8fa7f6);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: white;
      padding: 40px 30px 60px 30px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      width: 550px;
      max-width: 90%;
      text-align: center;
      position: relative;
    }

    .container h1 {
      color: #5c39d1;
      margin-bottom: 16px;
      padding-bottom: 16px;
    }

    .container h3 {
      color: #5c39d1;
      margin-top: 20px;
      padding-top: 20px;
    }

    .info {
      font-size: 0.95rem;
      color: #333;
      margin-top: 20px;
      line-height: 1.5;
    }

    .info span {
      display: block;
      margin-bottom: 10px;
    }

    .logout {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: #5c39d1;
      font-weight: bold;
      font-size: 0.9rem;
      transition: transform 0.9s;
    }


    .logout:hover {
      color: rgb(255, 0, 0);
      font-size: 16px;
    }

    .logout-icon {
      transition: transform 0.4s;
      font-size: 1.2rem;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.2rem;
    }
  </style>
  <!-- Material Icons CDN -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
</head>

<body>
  <div class="container">
    <h1>Welcome,
      <?php echo htmlspecialchars($_SESSION['userName']); ?> 👋
    </h1>
    <div class="info">
      <span>📍 Location: Pune, India</span>
      <span>📞 Contact: +91-8623958038</span>
      <span>📞 Contact: +91-9028871152</span>
      <span>🌐 Website: www.infotech.com</span>
      <span>🕒 Founded: 2010</span>
      <span>💼 Domain: Web Services | AI Solutions | SaaS</span>
    </div>
    <h3>Contact to Admin for access</h3>

    <!-- 👇 Logout link -->
    <a href="../Login/logout.php" class="logout">
      <span class="material-symbols-rounded logout-icon">logout</span>Logout
    </a>
  </div>
</body>

</html>