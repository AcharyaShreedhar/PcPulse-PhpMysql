<?php
session_start();

if (isset($_POST['action']) && $_POST['action'] == 'add' && isset($_POST['item'])) {
    $item = json_decode($_POST['item'], true);

    // Check if the cartlist is already in the session
    if (!isset($_SESSION['cartlist'])) {
        $_SESSION['cartlist'] = array();
    }

    $found = false;

    // Check if the item is already in the cartlist
    foreach ($_SESSION['cartlist'] as &$cartItem) {
        if ($cartItem['productId'] == $item['productId']) {
            // Item already exists, increase quantity
            $cartItem['quantity'] += 1;
            $found = true;
            break;
        }
    }

    // If the item is not found, add it to the cartlist
    if (!$found) {
        $_SESSION['cartlist'][] = $item;
    }

    echo json_encode(['status' => 'success', 'message' => 'Item added to cart.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
