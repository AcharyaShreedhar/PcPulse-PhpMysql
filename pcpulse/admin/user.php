<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db->getConnection(); 
    }

    public function createUser($firstName, $lastName, $email, $password, $address, $userType)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO Users (FirstName, LastName, Email, Password, Address, UserType) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $hashedPassword, $address, $userType);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function readUsers()
    {
        $result = $this->conn->query("SELECT UserID, FirstName, LastName, Email, Address, UserType FROM Users");

        if (!$result) {
            die("Error reading users: " . $this->conn->error);
        }

        return $result;
    }

    public function readUser($userID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            return $user;
        } else {
            die("Error reading user: " . $this->conn->error);
        }
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $address, $userType)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("UPDATE Users SET FirstName=?, LastName=?, Email=?, Password=?, Address=?, UserType=? WHERE UserID=?");
        $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $hashedPassword, $address, $userType, $userID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($userID)
    {
        $stmt = $this->conn->prepare("DELETE FROM Users WHERE UserID=?");
        $stmt->bind_param("i", $userID);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function loginWithEmail($email, $password)
{
    $stmt = $this->conn->prepare("SELECT UserID, Email,FirstName, LastName,Address,UserType, Password FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['Password'];

            if (password_verify($password, $hashedPassword)) {
                return $user; // Return user details including UserID
            }
        }
    }

    return null;
}

    
}

?>
