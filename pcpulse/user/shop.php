<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../header.php';
include_once '../dbinit.php';
include_once '../admin/Product.php';



// Include the CartItem and Cart classes
include 'CartItem.php'; 
include 'CartList.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../autoloader.php';
// Create a new Cart instance
$cart = new CartList();



// Create a new Product instance
$db = new Database("localhost", "root", "", "pcpulse");
$product = new Product($db);

// Get the list of products
$products = $product->readProducts();

?>

<div class="main-container mt-5">
    <hr />
    <div id="success-message-container"></div>

    <div class="col-md-12 d-flex">
        <div class="col-md-2">
            <h2>Products </h2>
        </div>
        <div class="col-md-10 d-flex align-items-end">
            <form id="filterForm" method="GET" action="shop.php" class="row w-100">
                <div class="col-md-2 mt-2">
                    <label for="categoryFilter" class="form-label">Filter by Category:</label>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter" name="categoryFilter">
                        <option value="">All Categories</option>
                        <?php
                        $categories = $product->getCategories();
                        foreach ($categories as $category) {
                            echo '<option value="' . $category['CategoryID'] . '">' . $category['CategoryName'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" id="searchInput"
                            name="searchInput">

                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="applyFilterAndSearch()">Filter</button>
                </div>
            </form>

        </div>
    </div>

    <hr />
    <div class="row" id="productListContainer">
        <?php
        // Check if there are products to display
        if ($products->num_rows > 0) {
            while ($row = $products->fetch_assoc()) {
                ?>
                <div class="col-md-4 mb-4 py-5" style="background-color: white; border-radius: 10px;">

                    <div class="card" style="box-shadow: 0 4px 8px rgba(89, 87, 87, 0.75);">
                        <img src="<?php echo "../images/" . $row['ImageURL']; ?>" class="card-img-top mx-auto d-block pt-5"
                            style="width:200px" alt="<?php echo $row['Name']; ?>">

                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">
                                    <?php echo $row['Name']; ?>
                                </h5>
                                <p class="card-text text-danger">$
                                    <?php echo $row['Price']; ?>
                                </p>
                            </div>
                            <hr />
                            <p class="card-text">
                                <?php echo $row['Description']; ?>
                            </p>
                            <div class="d-flex align-items-center mt-3">
                                <?php
                                $productId = isset($row['ProductID']) ? $row['ProductID'] : '';
                                $productName = isset($row['Name']) ? addslashes($row['Name']) : '';
                                $productImageURL = isset($row['ImageURL']) ? $row['ImageURL'] : '';
                                $productPrice = isset($row['Price']) ? $row['Price'] : 0.00;
                                ?>

                                <a href="#" class="btn btn-md btn-primary"
                                    onclick="addToCart('<?php echo $productId; ?>', '<?php echo $productName; ?>', '<?php echo $productImageURL; ?>', <?php echo $productPrice; ?>)">
                                    Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p>No products available.</p>';
        }
        ?>
    </div>

</div>
<script>
    function addToCart(productId, productName, imageURL, price) {
        // Check if all required keys are defined
        if (productId !== undefined && productName !== undefined && imageURL !== undefined && price !== undefined) {
            var item = {
                productId: productId,
                productName: productName,
                imageURL: imageURL,
                price: price,
                quantity: 1
            };

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    console.log('Status:', xhr.status);
                    console.log('Response:', xhr.responseText);

                    // Handle the response
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        console.log(response.message);
                        displaySuccessMessage('Item added to cart successfully!');
                        // Update the user interface or perform any additional actions
                    } else {
                        console.error(response.message);
                    }
                }
            };

            xhr.open('POST', 'addToCart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('action=add&item=' + JSON.stringify(item));
        } else {
            console.error('Error: Undefined array key in addToCart function');
        }
    }

    // Function to display Bootstrap success message
    function displaySuccessMessage(message) {
        var successAlert = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';

        // Append the success message to a container (adjust the container ID accordingly)
        document.getElementById('success-message-container').innerHTML = successAlert;
    }


    document.getElementById('filterForm').addEventListener('submit', function (event) {
        // Prevent the default form submission to avoid a full page reload
        event.preventDefault();

        // Manually trigger the update based on form values
        applyFilterAndSearch();
    });

  
    function applyFilterAndSearch() {
        var selectedCategory = document.getElementById('categoryFilter').value;
        var searchInput = document.getElementById('searchInput').value;

        // Make an AJAX request to fetch products based on category and search
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Handle the response
                    var products = JSON.parse(xhr.responseText);
                    // Update the UI with the filtered products
                    updateProductList(products);
                    addAddToCartEventListener();
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };

        // Construct the URL with parameters
        var url = 'getProducts.php?category=' + selectedCategory + '&search=' + searchInput;

        xhr.open('GET', url, true);
        xhr.send();
    }


    // Function to update the product list on the UI
    function updateProductList(products) {
        // Select the container where you want to display the products
        var productListContainer = document.getElementById('productListContainer');

        // Clear existing content in the container
        productListContainer.innerHTML = '';

        // Check if there are products to display
        if (products.length > 0) {
            // Loop through each product and create HTML elements
            products.forEach(function (product) {
                var productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4 py-5';

                productCard.innerHTML = `
                <div class="card" style="box-shadow: 0 4px 8px rgba(89, 87, 87, 0.75);">
                    <img src="../images/${product.ImageURL}" class="card-img-top mx-auto d-block pt-5" style="width:200px" alt="${product.Name}">
                    <div class="card-body" style="background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">${product.Name}</h5>
                            <p class="card-text text-danger">$${product.Price}</p>
                        </div>
                        <hr />
                        <p class="card-text">${product.Description}</p>
                        <div class="d-flex align-items-center mt-3">
                <a href="#" class="btn btn-md btn-primary addToCartBtn"
                    data-product-id="${product.ProductID}"
                    data-product-name="${product.Name}"
                    data-product-image="../images/${product.ImageURL}"
                    data-product-price="${product.Price}">
                    Add to Cart
                </a>
            </div>
                    </div>
                </div>
            `;

                // Append the product card to the container
                productListContainer.appendChild(productCard);
            });
        } else {
            // Display a message if no products are available
            productListContainer.innerHTML = '<p>No products available.</p>';
        }
    }

    function addAddToCartEventListener() {
        var productListContainer = document.getElementById('productListContainer');

        // Add event listener to the parent container for event delegation
        productListContainer.addEventListener('click', function (event) {
            // Check if the clicked element has the class "addToCartBtn"
            if (event.target && event.target.matches('.addToCartBtn')) {
                // Get the product details from the clicked button's data attributes
                var productId = event.target.getAttribute('data-product-id');
                var productName = event.target.getAttribute('data-product-name');
                var productImageURL = event.target.getAttribute('data-product-image');
                var productPrice = parseFloat(event.target.getAttribute('data-product-price'));

                // Call the addToCart function with the product details
                addToCart(productId, productName, productImageURL, productPrice);
            }
        });
    }




</script>

<?php include '../footer.php'; ?>