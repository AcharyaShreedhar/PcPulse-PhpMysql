<?php

class CartItem
{
    private $productId;
    private $productName;
    private $imageURL;
    private $price;
    private $quantity;

    public function __construct($productId, $productName, $imageURL, $price, $quantity)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->imageURL = $imageURL;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getImageURL()
    {
        return $this->imageURL;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function increaseQuantity()
    {
        $this->quantity++;
    }

    public function toArray()
    {
        return [
            'productId' => $this->productId,
            'productName' => $this->productName,
            'imageURL' => $this->imageURL,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
?>
