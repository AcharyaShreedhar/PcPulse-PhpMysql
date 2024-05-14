<?php
/*
    ------------------------------------------------------
    Project Name: PcPulse- an online computer and accessories selling ecommerce store
    Group: 1
    Members:
        Shree Dhar Acharya
        Prashant Sahu
        Abhijit Singhs
        Karamjot Singh
    -------------------------------------------------------
*/

class Database
{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->createDatabase();
        $this->selectDatabase();
        $this->createTables();
    }

    private function createDatabase()
    {
        $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $this->dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

        if ($this->conn->query($sqlCreateDB) === false) {
            die("Error creating database: " . $this->conn->error . "\n");
        }
    }

    private function selectDatabase()
    {
        $this->conn->select_db($this->dbname);
    }

    private function createTables()
    {
        $this->createCategoriesTable();
        $this->createUsersTable();
        $this->createProductsTable();
        $this->createOrdersTable();
        $this->createOrderDetailsTable();
    }

    private function createCategoriesTable()
    {
        $sqlCategoriesTable = "CREATE TABLE IF NOT EXISTS Categories (
            CategoryID INT PRIMARY KEY AUTO_INCREMENT,
            CategoryName VARCHAR(255) NOT NULL
        )";

        $this->executeQuery($sqlCategoriesTable);
    }

    private function createUsersTable()
    {
        $sqlUsersTable = "CREATE TABLE IF NOT EXISTS Users (
            UserID INT PRIMARY KEY AUTO_INCREMENT,
            FirstName VARCHAR(50) NOT NULL,
            LastName VARCHAR(50) NOT NULL,
            Email VARCHAR(100) NOT NULL,
            Password VARCHAR(255) NOT NULL,
            Address TEXT,
            UserType ENUM('Admin', 'Customer') DEFAULT 'Customer'
        )";

        $this->executeQuery($sqlUsersTable);
    }

    private function createProductsTable()
    {
        $sqlProductsTable = "CREATE TABLE IF NOT EXISTS Products (
            ProductID INT PRIMARY KEY AUTO_INCREMENT,
            Name VARCHAR(255) NOT NULL,
            Description TEXT,
            Price DECIMAL(10, 2) NOT NULL,
            InStock INt,
            CategoryID INT,
            ImageURL VARCHAR(255),
            Brand VARCHAR(50),
            Model VARCHAR(50),
            UpdatedBy INT,
            FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID),
            FOREIGN KEY (UpdatedBy) REFERENCES Users(UserID)
        )";

        $this->executeQuery($sqlProductsTable);
    }

    private function createOrdersTable()
    {
        $sqlOrdersTable = "CREATE TABLE IF NOT EXISTS Orders (
            OrderID INT PRIMARY KEY AUTO_INCREMENT,
            UserID INT,
            OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            TotalAmount DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (UserID) REFERENCES Users(UserID)
        )";

        $this->executeQuery($sqlOrdersTable);
    }

    private function createOrderDetailsTable()
    {
        $sqlOrderDetailsTable = "CREATE TABLE IF NOT EXISTS OrderDetails (
            OrderDetailID INT PRIMARY KEY AUTO_INCREMENT,
            OrderID INT,
            ProductID INT,
            Quantity INT NOT NULL,
            Subtotal DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
            FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
        )";

        $this->executeQuery($sqlOrderDetailsTable);
    }

    private function executeQuery($query)
    {
        if ($this->conn->query($query) === false) {
            die("Error creating table: " . $this->conn->error . "\n");
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

// Instantiate the Database class
$db = new Database("localhost", "root", "", "pcpulse");
?>
