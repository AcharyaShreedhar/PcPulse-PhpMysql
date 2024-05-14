<?php

session_start();

include '../header.php';
?>

<div class="main-container w-100">
    <hr />
    <h2 class="mb-5 mt-5">My Cart</h2>
    <hr />
    <div class="row">
        <div class="col-md-8">
            <?php
            // Check if the cartlist is in the session
            if (isset($_SESSION['cartlist']) && !empty($_SESSION['cartlist'])) {
                $cartItems = $_SESSION['cartlist'];
                ?>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cartItems as $item) {
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo "../images/" . $item['imageURL']; ?>"
                                        alt="<?php echo $item['productName']; ?>" style="max-width: 50px; max-height: 50px;">
                                </td>
                                <td>
                                    <?php echo $item['productName']; ?>
                                </td>
                                <td>$
                                    <?php echo number_format($item['price'], 2); ?>
                                </td>
                                <td>
                                    <?php echo $item['quantity']; ?>
                                </td>
                                <td>$
                                    <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-item-btn"
                                        data-product-id="<?php echo $item['productId']; ?>">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<p class="text-center">Your shopping cart is empty.</p>';
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            // Calculate subtotal, tax, and total
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $taxRate = 0.13; // 13% tax rate
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;
            ?>

            <div class="cart-totals">
                <label class="font-weight-bold">Cart Totals:</label>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Subtotal:</label>
                    </div>
                    <div class="col-sm-6" id="subtotal">
                        <?php echo '$' . number_format($subtotal, 2); ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Tax (13%):</label>
                    </div>
                    <div class="col-sm-6" id="tax">
                        <?php echo '$' . number_format($tax, 2); ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <label class="font-weight-bold">Total:</label>
                    </div>
                    <div class="col-sm-6" id="total">
                        <?php echo '$' . number_format($total, 2); ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <a href="shop.php" class="btn btn-sm btn-info">Continue Shopping</a>
                    </div>
                    <div class="col-sm-4">
                        <a href="checkout.php" class="btn  btn-sm btn-primary">Proceed To Checkout</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add this script at the bottom of your HTML file -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach click event to all "Remove" buttons
        var removeButtons = document.querySelectorAll('.remove-item-btn');
        removeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var productId = this.getAttribute('data-product-id');
                removeFromCart(productId);
            });
        });

        function removeFromCart(productId) {
    // Make an AJAX request to removeCartItem.php
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('Status:', xhr.status);
            console.log('Response:', xhr.responseText);

            // Handle the response
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                console.log(response.message);
                // Update the user interface or perform any additional actions

                // Update the subtotal, tax, and total
                document.getElementById('subtotal').innerText = '$' + response.subtotal;
                document.getElementById('tax').innerText = '$' + response.tax;
                document.getElementById('total').innerText = '$' + response.total;

                // For example, remove the item from the HTML
                var rowToRemove = document.querySelector('[data-product-id="' + productId + '"]').closest('tr');
                rowToRemove.remove();
            } else {
                console.error(response.message);
            }
        }
    };

    xhr.open('POST', 'removeCartItem.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('productId=' + productId);
}
    });
</script>


<?php include '../footer.php'; ?>