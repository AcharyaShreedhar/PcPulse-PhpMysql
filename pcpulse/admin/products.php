<!--
    ------------------------------------------------------
    Project Name: PcPulse- an online computer and accessories selling e-commerce store
    Group: 1
    Members:
            Shree Dhar Acharya
            Prashant Sahu
            Abhijit Singh
            Karamjot Singh
    -------------------------------------------------------
-->

<?php
include_once '../dbinit.php';
include_once 'Product.php';

// Instantiate the Database class
$db = new Database('localhost', 'root', '', 'pcpulse');
$conn = $db->getConnection();

// Check if the request is a POST request and if the 'ProductID' is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ProductID'])) {
    // Get the ProductID from the POST request
    $ProductID = $_POST['ProductID'];

    // Instantiate the Product class
    $product = new Product($db);

    // Call the deleteProduct method to delete the Product
    if ($product->deleteProduct($ProductID)) {
        $successMessage = "Product with ID $ProductID deleted successfully.";
    } else {
        $errorMessage = "Error deleting product.";
    }
}

include '../header.php';
?>

<div class="main-container">
    <?php
    // Display success or error message if set
    if (isset($successMessage)) {
        echo "<div class='message-container'><div class='alert alert-success' role='alert'>$successMessage</div></div>";
    } elseif (isset($errorMessage)) {
        echo "<div class='message-container'><div class='alert alert-danger' role='alert'>$errorMessage</div></div>";
    }

    // Include the product list
    include 'product_list.php';
    ?>
</div>

<?php
include '../footer.php';
?>
