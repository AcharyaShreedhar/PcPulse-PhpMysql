<!--
    ------------------------------------------------------
    Project Name: PcPulse - an online computer and accessories selling e-commerce store
    Group: 1
    Members:
            Shree Dhar Acharya
            Prashant Sahu
            Abhijit Singh
            Karamjot Singh
    -------------------------------------------------------
-->

<?php
include_once '../dbinit.php';
include_once 'User.php';

// Instantiate the Database class
$db = new Database('localhost', 'root', '', 'pcpulse');
$conn = $db->getConnection();

// Check if the request is a POST request and if the 'UserID' is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['UserID'])) {
    // Get the UserID from the POST request
    $UserID = $_POST['UserID'];

    // Instantiate the User class
    $user = new User($db);

    // Call the deleteUser method to delete the User
    if ($user->deleteUser($UserID)) {
        $successMessage = "User with ID $UserID deleted successfully.";
    } else {
        $errorMessage = "Error deleting user.";
    }
}

include '../header.php';
?>

<div class="main-container">
    <?php
    // Display success or error message if set
    if (isset($successMessage)) {
        echo "<div class='message-container'><div class='alert alert-success' role='alert'>$successMessage</div></div>";
    } elseif (isset($errorMessage)) {
        echo "<div class='message-container'><div class='alert alert-danger' role='alert'>$errorMessage</div></div>";
    }

    // Include the user list
    include 'user_list.php';
    ?>
</div>

<?php
include '../footer.php';
?>
