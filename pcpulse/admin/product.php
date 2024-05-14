<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Product
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db->getConnection(); 
    }

    public function createProduct($name, $description, $brand, $model, $price, $categoryID, $imageURL, $updatedBy, $inStock)
    {
        $stmt = $this->conn->prepare("INSERT INTO Products (Name, Description, Brand, Model, Price, CategoryID, ImageURL, UpdatedBy, InStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdissi", $name, $description, $brand, $model, $price, $categoryID, $imageURL, $updatedBy, $inStock);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function readProducts()
    {
        $result = $this->conn->query("SELECT ProductID, Name, Description, Brand, InStock, Price,ImageURL, UpdatedBy FROM Products");
    
        if (!$result) {
            die("Error reading products: " . $this->conn->error);
        }
    
        return $result;
    }

    public function readProduct($productID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Products WHERE ProductID = ?");
        $stmt->bind_param("i", $productID);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            return $product;
        } else {
            die("Error reading product: " . $this->conn->error);
        }
    }
    

    public function updateProduct($productID, $name, $description, $brand, $model, $price, $categoryID, $imageURL, $updatedBy, $inStock)
    {
        $stmt = $this->conn->prepare("UPDATE Products SET Name=?, Description=?, Brand=?, Model=?, Price=?, CategoryID=?, ImageURL=?, UpdatedBy=?, InStock=? WHERE ProductID=?");
        $stmt->bind_param("ssssdissii", $name, $description, $brand, $model, $price, $categoryID, $imageURL, $updatedBy, $inStock, $productID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($productID)
    {
        $stmt = $this->conn->prepare("DELETE FROM Products WHERE ProductID=?");
        $stmt->bind_param("i", $productID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getCategories()
{
    $sql = "SELECT CategoryID, CategoryName FROM categories";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $categories = array();

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}
public function getProductsByCategoryAndSearch($categoryID, $searchText)
{
    // Base SQL query
    $sql = "SELECT ProductID, Name, Description, Brand, InStock, Price, ImageURL, UpdatedBy FROM Products WHERE 1 ";

    // Add conditions for category and search text if provided
    $params = array();

    if ($categoryID && is_numeric($categoryID)) {
        $sql .= " AND CategoryID = ?";
        $params[] = $categoryID;
    }

    if ($searchText) {
        // Input validation to prevent XSS
        $searchText = strip_tags($searchText);

        $sql .= " AND Name LIKE ?";
        $params[] = '%' . $searchText . '%';
    }

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind parameters dynamically
    if ($params) {
        $paramTypes = str_repeat('s', count($params));
        $stmt->bind_param($paramTypes, ...$params);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = array();

        while ($row = $result->fetch_assoc()) {
            // Output escaping to prevent XSS
            $row = array_map('htmlspecialchars', $row);
            $products[] = $row;
        }

        return $products;
    } else {
        die("Error fetching products by category and search: " . $this->conn->error);
    }
}



}


?>
