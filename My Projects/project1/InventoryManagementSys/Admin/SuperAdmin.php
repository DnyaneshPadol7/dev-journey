<!-- Session file Added(Session Start) -->
<?php include('../auth.php');?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    

    <!-- Bootstrap only for navbar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark adbar">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="../Admin/SuperAdmin.php">
                <span class="material-symbols-outlined maintitle">admin_panel_settings</span>
                Super Admin Panel
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Offcanvas Menu -->
            <div class="offcanvas offcanvas-end text-bg-light" tabindex="-1" id="offcanvasDarkNavbar"
                aria-labelledby="offcanvasDarkNavbarLabel">

                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Tools</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="../Admin/SuperAdmin.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../Login/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Cards -->
    <main class="cards-container">

        <div class="dashboard-card" onclick="window.location.href='../Sales/sales1.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">trending_up</span>
            <p>Sales</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Purchase/purchase1.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">shopping_bag</span>
            <p>Purchase</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Product/viewProduct.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">add_shopping_cart</span>
            <p>Products</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Customer/viewCustomer.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">contacts_product</span>
            <p>Customers</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Seller/viewSeller.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">contacts_product</span>
            <p>Sellers</p>
        </div>

         <div class="dashboard-card" onclick="window.location.href='../Employee/Employee.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">group</span>
            <p>User</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Dashboard/showOutseller.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">receipt_long</span>
            <p>Seller Outstandings</p>
        </div>

        
        <div class="dashboard-card" onclick="window.location.href='../Dashboard/showOutstanding.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">account_balance_wallet</span>
            <p>Customer Outstandings</p>
        </div>
        

        <div class="dashboard-card" onclick="window.location.href='../Bill/showSales.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">sell</span>
            <p>Show Sales</p>
        </div>

        <div class="dashboard-card" onclick="window.location.href='../Bill/showPurchase.php'" style="cursor: pointer;">
            <span class="material-symbols-rounded">inventory</span>
            <p>Show Purchase</p>
        </div>

        

    </main>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>
