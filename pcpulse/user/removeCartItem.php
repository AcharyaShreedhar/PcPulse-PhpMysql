<?php
session_start();

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Check if the cartlist is in the session
    if (isset($_SESSION['cartlist']) && !empty($_SESSION['cartlist'])) {
        $cartItems = &$_SESSION['cartlist'];

        // Remove the item with the specified product ID
        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['productId'] == $productId) {
                // Save the removed item details for updating totals
                $removedItem = $cartItems[$key];

                unset($cartItems[$key]);

                // Calculate new subtotal, tax, and total
                $subtotal = 0;
                foreach ($cartItems as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $taxRate = 0.13; // 13% tax rate
                $tax = $subtotal * $taxRate;
                $total = $subtotal + $tax;

                // Return the updated values in the response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Item removed from cart.',
                    'subtotal' => number_format($subtotal, 2),
                    'tax' => number_format($tax, 2),
                    'total' => number_format($total, 2),
                ]);
                exit();
            }
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Item not found in cart.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
