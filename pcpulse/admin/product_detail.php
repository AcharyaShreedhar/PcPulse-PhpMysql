<!DOCTYPE html>
<?php
include '../header.php';

// Include the database initialization file
include '../dbinit.php';
include 'Product.php'; // Include the Product class file

$db = new Database("localhost", "root", "", "pcpulse");
$product = new Product($db);

// Retrieve product ID from the URL
$productId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch product details
if (!is_null($productId)) {
    // Assuming there is a method like getProductById in the Product class
    $productDetails = $product->readProduct($productId);

    if (!$productDetails) {
        // Product not found, you may handle this accordingly
        // For simplicity, we'll redirect to the index.php
        header("Location: index.php");
        exit();
    }
} else {
    // Product ID not provided, redirect to index.php
    header("Location: index.php");
    exit();
}

?>

<div class="main-container">
    <h1 class="mb-4">Product Details
        <a href="products.php" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back to Products</a>
    </h1>
    <hr />

    <div class="row">
        <div class="col-md-6">
        <dl class="row">
                <dt class="col-sm-4">Product ID:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['ProductID'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Product Name:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['Name'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Description:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['Description'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Brand:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['Brand'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Model:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['Model'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">In Stock:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['InStock'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Price:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['Price'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Updated By:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['UpdatedBy'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Category:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($productDetails['CategoryID'], ENT_QUOTES, 'UTF-8'); ?></dd>
            </dl>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>
