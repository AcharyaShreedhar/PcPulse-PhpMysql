<!--
    ------------------------------------------------------
    Project Name: PcPulse- an online computer and accesories selling ecommerce store
    Group: 1
    Members:
            Shree Dhar Acharya
            Prashant Sahu
            Abhijit Singhs
            Karamjot Singh
    -------------------------------------------------------
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PcPulse-Computers and accesories Ecommerce Application</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="/pcpulse/css/styles.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
            <img src="/pcpulse/images/logo.png" alt="Logo of Pcpulse" class="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <?php
                // Check if the session is not already started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Check user type and include specific content
                if (isset($_SESSION['UserType']) && $_SESSION['UserType'] === 'Admin') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/admin/products.php">Computers and Accessories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/admin/users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inventory</a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/user/shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/user/cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pcpulse/user/checkout.php">Checkout</a>
                    </li>
                <?php
                }

                if (isset($_SESSION['user_id'])) {
                    // User is logged in, display username and logout button
                    echo '<li class="nav-item"><span class="nav-link">Welcome, ' . $_SESSION['username'] . '</span></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="/pcpulse/logout.php">Logout</a></li>';
                } else {
                    // User is not logged in, display login link
                    echo '<li class="nav-item"><a class="nav-link" href="/pcpulse/login.php">Login</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</body>

</html>