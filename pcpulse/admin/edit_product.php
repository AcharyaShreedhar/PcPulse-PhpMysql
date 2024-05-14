<!DOCTYPE html>
<?php
include '../header.php';

// Include the database initialization file
include '../dbinit.php';
include 'Product.php'; // Include the Product class file

$errors = array(
    'productName' => '',
    'productDescription' => '',
    'productBrand' => '',
    'productModel' => '',
    'quantityInStock' => '',
    'productPrice' => '',
    'categoryID' => '' // Add category error
);

// Initialize variables to hold the input
$productName = $productDescription = $productBrand = $productModel = $quantityInStock = $productPrice = $categoryID = '';

$db = new Database("localhost", "root", "", "pcpulse");
// Retrieve product ID from the URL
$productId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch existing product details
if (!is_null($productId)) {
    // Assuming there is a method like getProductById in the Product class
    $product = new Product($db);
    $existingProduct = $product->readProduct($productId);

    if ($existingProduct) {
        $productName = $existingProduct['Name'];
        $productDescription = $existingProduct['Description'];
        $productBrand = $existingProduct['Brand'];
        $productModel = $existingProduct['Model'];
        $quantityInStock = $existingProduct['InStock'];
        $productPrice = $existingProduct['Price'];
        $categoryID = $existingProduct['CategoryID']; // Assuming CategoryID is a column in your database
        $productUpdatedBy = $existingProduct['UpdatedBy'];
    } else {
        // Product not found, you may handle this accordingly
        // For simplicity, we'll redirect to the index.php
        header("Location: index.php");
        exit();
    }
}

// Fetch category list for dropdown
$categoryList = []; // Assuming you have a method like getCategories in your Product class
if (isset($product)) {
    $categoryList = $product->getCategories();
}

// Handle form submission to update product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['productId']) ? $_POST['productId'] : null;

    // Extract the form inputs
    $productName = validateInput($_POST['productName']);
    $productDescription = validateInput($_POST['productDescription']);
    $productBrand = validateInput($_POST['productBrand']);
    $productModel = validateInput($_POST['productModel']);
    $quantityInStock = validateNumber($_POST['quantityInStock']);
    $productPrice = validateNumber($_POST['productPrice']);
    $categoryID = validateNumber($_POST['categoryID']); // Validate category ID
    $productUpdatedBy = 1;

    // Assuming there is a method like updateProduct in the Product class
    $product = new Product($db);

    // Ensure all required fields are filled
    if (empty($productName) || empty($productDescription) || empty($productBrand) || empty($productModel) || is_null($quantityInStock) || is_null($productPrice) || empty($productUpdatedBy) || is_null($categoryID)) {
        foreach ($errors as $key => $value) {
            if (empty($$key)) {
                $errors[$key] = ucfirst($key) . ' is required.';
            }
        }
    } else {
        // Call the updateProduct method in the Product class
        if ($product->updateProduct($productId, $productName, $productDescription, $productBrand, $productModel, $productPrice, $categoryID, null, $productUpdatedBy, $quantityInStock)) {
            echo "<div class='message-container mt-5 pt-5 text-center'><div class='alert alert-success' role='alert'>Product updated successfully.</div></div>";
        } else {
            echo "<div class='message-container mt-5 pt-5 text-center'><div class='alert alert-danger' role='alert'>Error updating product.</div></div>";
        }
    }
}

function validateInput($input)
{
    if (!is_null($input)) {
        // Trim any leading or trailing spaces
        $input = trim($input);

        if (!empty($input)) {
            // Sanitize the input
            $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            return $input;
        }
    }
    return null;
}

function validateNumber($input)
{
    // Check if input is numeric
    if (is_numeric($input)) {
        // Convert to float
        return (float) $input;
    }
    return null;
}

?>

<div class="main-container">
    <h1 class="mb-4">Update Product
        <a href="products.php" class="btn btn-primary float-right"><i class="fas fa-eye"></i> View Products</a>
    </h1>
    <hr />

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" value="<?php echo $productName; ?>">
            <span class="text-danger">
                <?php echo $errors['productName']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productDescription">Product Description</label>
            <textarea class="form-control" id="productDescription" name="productDescription" rows="3"><?php echo $productDescription; ?></textarea>
            <span class="text-danger">
                <?php echo $errors['productDescription']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productBrand">Product Brand</label>
            <input type="text" class="form-control" id="productBrand" name="productBrand" value="<?php echo $productBrand; ?>">
            <span class="text-danger">
                <?php echo $errors['productBrand']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productModel">Product Model</label>
            <input type="text" class="form-control" id="productModel" name="productModel" value="<?php echo $productModel; ?>">
            <span class="text-danger">
                <?php echo $errors['productModel']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="quantityInStock">Quantity In Stock</label>
            <input type="number" class="form-control" id="quantityInStock" name="quantityInStock" value="<?php echo $quantityInStock; ?>">
            <span class="text-danger">
                <?php echo $errors['quantityInStock']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productPrice">Product Price</label>
            <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice" value="<?php echo $productPrice; ?>">
            <span class="text-danger">
                <?php echo $errors['productPrice']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="categoryID">Category</label>
            <select class="form-control" id="categoryID" name="categoryID">
                <option value="" disabled selected>Select a category</option>
                <?php
                // Populate the dropdown with categories
                foreach ($categoryList as $category) {
                    $selected = ($category['CategoryID'] == $categoryID) ? 'selected' : '';
                    echo "<option value='{$category['CategoryID']}' $selected>{$category['CategoryName']}</option>";
                }
                ?>
            </select>
            <span class="text-danger">
                <?php echo $errors['categoryID']; ?>
            </span>
        </div>
        <div class="form-group">
            <input type="hidden" name="productUpdatedBy" value="Shree Dhar Acharya">
        </div>
        <input type="hidden" name="productId" value="<?php echo $productId; ?>">
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<?php
include '../footer.php';
?>
