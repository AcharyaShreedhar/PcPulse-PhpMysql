<?php include '../header.php'; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../dbinit.php';
include 'Product.php';

$errors = array(
    'name' => '',
    'description' => '',
    'brand' => '',
    'model' => '',
    'inStock' => '',
    'price' => '',
    'category' => '',
    'image' => ''
);

$productName = $productDescription = $productBrand = $productModel = $quantityInStock = $productPrice = '';

$db = new Database("localhost", "root", "", "pcpulse");
$product = new Product($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = validateInput($_POST['productName']);
    $productDescription = validateInput($_POST['productDescription']);
    $productBrand = validateInput($_POST['productBrand']);
    $productModel = validateInput($_POST['productModel']);
    $quantityInStock = validateNumber($_POST['quantityInStock']);
    $productPrice = validateNumber($_POST['productPrice']);
    $productCategory = validateNumber($_POST['productCategory']);
    $productImage = $_FILES['productImage']['name'];
    $updatedBy=$_POST['productUpdatedBy']||1;

    if (empty($productName) || empty($productDescription) || empty($productBrand) || empty($productModel) || is_null($quantityInStock) || is_null($productPrice) || is_null($productCategory) || empty($productImage)) {
        foreach ($errors as $key => $value) {
            if (empty($$key)) {
                $errors[$key] = ucfirst($key) . ' is required.';
            }
        }
    } else {
        // Validate image file
        if (!fileIsValid($_FILES['productImage'])) {
            $errors['image'] = 'Invalid file format. Allowed formats: jpg, jpeg, png, gif';
        }

        if (empty($errors['image'])) {
            if ($product->createProduct($productName, $productDescription, $productBrand, $productModel, $productPrice, $productCategory, $productImage, $updatedBy, $quantityInStock)) {
                echo "<div class='message-container text-center mt-5 pt-5'><div class='alert alert-success' role='alert'>Product has been added successfully.</div></div>";
            } else {
                echo "<div class='message-container text-center mt-5 pt-5'><div class='alert alert-danger' role='alert'>Error adding product.</div></div>";
            }
        }
    }
}

function validateInput($input)
{
    if (!is_null($input)) {
        $input = trim($input);

        if (!empty($input)) {
            $input = filter_var($input, FILTER_SANITIZE_STRING);

            // Use htmlspecialchars for output encoding
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            return $input;
        }
    }
    return null;
}

function validateNumber($input)
{
    if (is_numeric($input)) {
        return (float)$input;
    }
    return null;
}

function fileIsValid($file)
{
    // Implement your file validation logic here
    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    return in_array(strtolower($fileExtension), $allowedFormats);
}

$db->closeConnection();
?>

<div class="main-container">
    <h1 class="mb-4">Add Product
        <a href="products.php" class="btn btn-primary float-right"><i class="fas fa-eye"></i> View Products</a>
    </h1>
    <hr />

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName">
            <span class="text-danger">
                <?php echo $errors['name']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productDescription">Product Description</label>
            <textarea class="form-control" id="productDescription" name="productDescription" rows="3"></textarea>
            <span class="text-danger">
                <?php echo $errors['description']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productBrand">Product Brand</label>
            <input type="text" class="form-control" id="productBrand" name="productBrand">
            <span class="text-danger">
                <?php echo $errors['brand']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productModel">Product Model</label>
            <input type="text" class="form-control" id="productModel" name="productModel">
            <span class="text-danger">
                <?php echo $errors['model']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="quantityInStock">Quantity In Stock</label>
            <input type="number" class="form-control" id="quantityInStock" name="quantityInStock">
            <span class="text-danger">
                <?php echo $errors['inStock']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productPrice">Product Price</label>
            <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice">
            <span class="text-danger">
                <?php echo $errors['price']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productCategory">Product Category</label>
            <select class="form-control" id="productCategory" name="productCategory">
                <option value="1">Laptops</option>
                <option value="2">Tablets</option>
               
            </select>
            <span class="text-danger">
                <?php echo $errors['category']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="productImage">Product Image</label>
            <input type="file" class="form-control-file" id="productImage" name="productImage">
            <span class="text-danger">
                <?php echo $errors['image']; ?>
            </span>
        </div>
        <div class="form-group">
            <input type="hidden" name="productUpdatedBy" value=1>
        </div>

        <button type="submit" class="btn btn-md btn-primary">Add Product</button>
    </form>
</div>

<?php include '../footer.php'; ?>
