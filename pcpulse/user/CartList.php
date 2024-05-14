<?php

class CartList
{
    private $cartItems;

    public function __construct()
    {
        // Initialize the cart items as an empty array
        $this->cartItems = array();
    }

    public function getCartItems()
    {
        return $this->cartItems;
    }

    public function addToCart($item)
    {
        // Check if the item is already in the cart
        foreach ($this->cartItems as &$cartItem) {
            if ($cartItem['productId'] == $item['productId']) {
                // Item already exists, increase quantity
                $cartItem['quantity'] += 1;
                return;
            }
        }

        // If the item is not found, add it to the cart
        $this->cartItems[] = $item;
    }

    public function removeCartItem($productId)
    {
        // Remove the item from the cart based on product ID
        foreach ($this->cartItems as $key => $cartItem) {
            if ($cartItem['productId'] == $productId) {
                // Debugging: Log the cart item to be removed
                error_log('Debugging: Removing cart item - ' . print_r($cartItem, true));

                unset($this->cartItems[$key]);
                return;
            }
        }

        // Debugging: Log if the item to remove was not found
        error_log('Debugging: Item to remove not found - Product ID: ' . $productId);
    }

    public function clearCart()
    {
        // Clear all items from the cart
        $this->cartItems = array();
    }
}
?>
