<?php
include_once '../dbinit.php';
include_once '../admin/Product.php';

// Instantiate the Database class
$db = new Database('localhost', 'root', '', 'pcpulse');
$conn = $db->getConnection();


// Instantiate the Product class
$product = new Product($db);
if (isset($_GET['category']) && isset($_GET['search'])) {
    $categoryID = $_GET['category'];
    $searchText = $_GET['search'];

    // Fetch products based on category and search
    $filteredProducts = $product->getProductsByCategoryAndSearch($categoryID, $searchText);

    // Output the products as JSON
    echo json_encode($filteredProducts);

    // Terminate to prevent further processing of the page
    exit();
}
