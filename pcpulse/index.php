<?php
/*
    ------------------------------------------------------
    Project Name: PcPulse- an online computer and accesories selling ecommerce store
    Group: 1
    Members:
            Shree Dhar Acharya
            Prashant Sahu
            Abhijit Singhs
            Karamjot Singh
    -------------------------------------------------------
*/

include 'header.php';
?>
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to the PcPulse</h1>
            <p>Your one-stop destination for computer products and accesories.</p>
            <a href="computers.php" class="btn btn-hero animate__animated">Get Started</a>
        </div>
    </div>
</section>

<?php
// Check if the session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check user type and include specific content
if (isset($_SESSION['UserType']) && $_SESSION['UserType'] === 'Admin') {
    // Include the admin dashboard
    include 'admin/dashboard.php';
} else {
    // Include the user dashboard
    include 'user/dashboard.php';
}
?>



<?php
// Include the footer
include 'footer.php';
?>