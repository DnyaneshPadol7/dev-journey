<?php
include('../dbconnect.php');

if (isset($_POST['submit'])) {

    // 1️⃣ User input sanitize kar rahe hai
    $name    = mysqli_real_escape_string($conn, $_POST['seller_Name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['mobileNo']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // 2️⃣ Basic validation check
    if (empty($name) || empty($phone) || empty($email) || empty($address)) {
        echo "<p style='color:red; text-align:center;'>❌ All fields are required!</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red; text-align:center;'>❌ Invalid email format!</p>";
    } else {

        // 3️⃣ Insert query
        $query = "INSERT INTO sellers(seller_Name, mobileNo, email, address) 
                  VALUES('$name', '$phone', '$email', '$address')";

        $result = mysqli_query($conn, $query);

        if ($result) {
            // 4️⃣ Flash message show karna
            ?>
            <div id="flashUpdate" 
                 style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                        background: #fff; color: green; padding: 20px 30px; border-radius: 10px;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 9999; text-align: center; font-weight: bold;">
                Seller added successfully!
            </div>

            <script>
                const flash = document.getElementById('flashUpdate');
                flash.style.opacity = 1;

                setTimeout(() => {
                    let fadeEffect = setInterval(() => {
                        if (!flash.style.opacity) flash.style.opacity = 1;
                        if (flash.style.opacity > 0) {
                            flash.style.opacity -= 0.05;
                        } else {
                            clearInterval(fadeEffect);
                            flash.style.display = 'none';
                            // 5️⃣ Redirect karna fade out ke baad
                            window.location.href = 'viewSeller.php';
                        }
                    }, 30);
                }, 3000); // 3 seconds tak flash message dikhega
            </script>
            <?php
        } else {
            // 6️⃣ Agar query fail ho jaye
            error_log("Failed to add Seller: " . mysqli_error($conn));
            echo "<p style='color:red; text-align:center;'>❌ Failed to add Seller. Please try again.</p>";
        }
    }
}
?>
