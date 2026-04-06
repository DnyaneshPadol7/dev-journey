<!-- Session file Added(Session Start) -->
<?php include('../auth.php');?>
<?php include('../dbconnect.php');?>



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
     <a href="../Employee/Admin.html"><h1 id="maintitle">Admin Panel</h1></a>

    <div class="contaier">
        

<div class="box1">
    <h2>All Employee</h2>
</div>
<br>
    <div class="contaier">

    <div class="table-responsive">
<table class="table table-hover table-bordered table-striped ">
    <thead>
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Mobile No</th>
            <th>Password</th>
            <th>Role</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $query ="select * from `userinfo`";
            $result = mysqli_query($conn,$query);
            
            if (!$result) {
                die("query failed".mysqli_error());
            }
            
            else {
               
               
                while ($row = mysqli_fetch_assoc($result)) {
       ?>
        <tr>
            <td><?php echo$row['id']; ?></td>
            <td><?php echo$row['userName']; ?></td>
            <td><?php echo$row['email']; ?></td>
            <td><?php echo$row['mobileNo']; ?></td>
            <td><?php echo$row['password']; ?></td>
            <td><?php echo$row['role']; ?></td>
            <td><a href="../Employee/EmployeeUpdate.php?id=<?php echo$row['id']; ?> "class="btn btn-success">Update</a></td>
        </tr>

        <?php
                }
            }
        ?>
    </tbody>
</table>
</div>


<?php

if (isset($_GET['message'])) {
    echo "<h6>".$_GET['message']."</h6>";
}

?>

<?php

if (isset($_GET['insert_msg'])) {
    echo "<h5>".$_GET['insert_msg']."</h5>";
}

?>


<?php

if (isset($_GET['update_msg'])) {
    echo "<h5>".$_GET['update_msg']."</h5>";
}

?>


