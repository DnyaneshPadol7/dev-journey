<!-- Session file Added(Session Start) -->
<?php include('../auth.php');?>

<?php include('../Employee/EmployeeHeader.php');?>
<?php include('../dbconnect.php');?>


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
                    <th>Delete</th>
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
                    
                    <td>
                        <?php echo$row['userName']; ?>
                    </td>
                    <td>
                        <?php echo$row['email']; ?>
                    </td>
                    <td>
                        <?php echo$row['mobileNo']; ?>
                    </td>
                    <td>
                        <?php echo$row['password']; ?>
                    </td>
                    <td>
                        <?php echo$row['role']; ?>
                    </td>

                    <td><a href="../Employee/EmployeeUpdate.php?id=<?php echo$row['id']; ?> "
                            class="btn btn-success">Update</a></td>

                    <td>
                        <?php if ($_SESSION['role']==='SuperAdmin') { ?>
                        <a href="../Employee/EmployeeDelete.php?id=<?php echo$row['id']; ?>"
                            class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        <?php } else { ?>
                        <span class="text-muted">No Acess</span>
                        <?php } ?>


                    </td>
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
        if (isset($_GET['update_msg'])): ?>
            <div id="flashUpdate" class="modal fade show" style="display:block; background: rgba(0,0,0,0.5);" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center text-success fw-bold">
                            <?= htmlspecialchars($_GET['update_msg']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    let modal = document.getElementById('flashUpdate');
                    if (modal){modal.style.display = 'none';}
                    
                }, 3000);
            </script>
            <?php endif;
                ?>

        <?php if (isset($_GET['delete_msg'])): ?>
    <div id="flashModal" class="modal fade show" style="display:block; background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center text-danger fw-bold">
                    <?= htmlspecialchars($_GET['delete_msg']); ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            let modal = document.getElementById('flashModal');
            if (modal) { modal.style.display = 'none'; }
        }, 3000); // 3 sec ke baad modal hat jayega
    </script>
    <?php endif;

        ?>


    <form action="../Employee/EmployeeInsert.php" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="userName">User Name</label>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="mobileNo">Mobile NO.</label>
                            <input type="text" name="mobileNo" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" name="password" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" name="role" class="form-control">
                        </div>
                        <br>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="add_userinfo" value="Employee">
                    </div>
                </div>
            </div>
        </div>
    </form>