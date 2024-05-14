<?php include '../header.php'; ?>
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: ../login.php");
    exit(); // Make sure to stop executing the script after the redirect
}

// Get user details
$userID = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$address = $_SESSION['address'];

// You can fetch more details from the user if needed

// Retrieve cartlist from the session
$cartItems = isset($_SESSION['cartlist']) ? $_SESSION['cartlist'] : array();

// Calculate subtotal, tax, and total
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$taxRate = 0.13; // 13% tax rate
$tax = $subtotal * $taxRate;
$total = $subtotal + $tax;

?>

<div class="container mt-5">

    <div class="mt-5">
        <hr />
        <h2>Checkout Page</h2>
        <hr />
    </div>

    <!-- Display user details in the contact information form -->
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
                <h2>Contact Information</h2>
                <form>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $username; ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>"
                            disabled>
                    </div>
                </form>
            </div>

            <!-- Shipping Information Form (Add more fields as needed) -->
            <div class="col-md-12">
                <h2>Shipping Information</h2>
                <form>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adress:</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?php echo $address; ?>" disabled>
                    </div>
                    <!-- Checkbox for Terms and Policies -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">I agree to the <a
                                href="terms_and_conditions.php" target="_blank">Terms and Conditions</a> and <a
                                href="privacy_policy.php" target="_blank">Privacy Policy</a>.</label>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-6">
            <h2>Order Summary</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cartItems as $item) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $item['productName']; ?>
                            </td>
                            <td>
                                <?php echo $item['quantity']; ?>
                            </td>
                            <td>$
                                <?php echo number_format($item['price'], 2); ?>
                            </td>
                            <td>$
                                <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- Display Subtotal, Tax, and Total -->
            <div class="mt-3">
                <p><strong>Subtotal:</strong> $
                    <?php echo number_format($subtotal, 2); ?>
                </p>
                <p><strong>Tax (13%):</strong> $
                    <?php echo number_format($tax, 2); ?>
                </p>
                <p><strong>Total:</strong> $
                    <?php echo number_format($total, 2); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Agreement and Place Order Button -->
    <div class="mt-4">
        <button type="button" class="btn btn-primary" onclick="placeOrder()">Place Order</button>
    </div>
</div>

<script>
    function placeOrder() {
    // Your existing code for placing the order

    // Prepare data to send to generate_invoice.php
    const userData = JSON.stringify({
        username: '<?php echo $username; ?>',
        email: '<?php echo $email; ?>',
    });

    const shippingInfoData = JSON.stringify({
        address: '<?php echo $address; ?>',
    });

    const cartItemsData = JSON.stringify(<?php echo json_encode($cartItems); ?>);
    const subtotalData = <?php echo $subtotal; ?>;
    const taxData = <?php echo $tax; ?>;
    const totalData = <?php echo $total; ?>;

    // Add the following Fetch API request to generate the invoice
    fetch('generate_invoice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user=${encodeURIComponent(userData)}&shippingInfo=${encodeURIComponent(shippingInfoData)}&cartItems=${encodeURIComponent(cartItemsData)}&subtotal=${encodeURIComponent(subtotalData)}&tax=${encodeURIComponent(taxData)}&total=${encodeURIComponent(totalData)}`,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error generating the invoice.');
            }
            return response.blob(); // Assuming the server returns a blob (PDF content)
        })
        .then(blob => {
            const pdfUrl = URL.createObjectURL(blob);
            window.open(pdfUrl, '_blank'); // Open the PDF in a new tab
        })
        .catch(error => {
            alert(error.message);
        });
}


</script>


<?php include '../footer.php'; ?>