<?php include('../auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Eployee.css">
</head>

<body>
    <?php if ($_SESSION['role'] === 'SuperAdmin') { ?>
        <a href="../Admin/SuperAdmin.php">
            <h1 id="maintitle">Super Admin Panel</h1>
        </a>
    <?php } elseif ($_SESSION['role'] === 'Admin') { ?>
        <a href="../Employee/Admin.html">
            <h1 id="maintitle">Admin Panel</h1>
        </a>
    <?php } elseif ($_SESSION['role'] === 'Employee') { ?>
        <a href="../Employee/Employee.html">
            <h1 id="maintitle">Employee Panel</h1>
        </a>
    <?php } ?>

    
</body>
</html>
