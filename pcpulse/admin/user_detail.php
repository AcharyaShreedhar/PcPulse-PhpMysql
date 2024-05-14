<!DOCTYPE html>
<?php
include '../header.php';

// Include the database initialization file
include '../dbinit.php';
include 'User.php'; // Include the User class file

$db = new Database("localhost", "root", "", "pcpulse");
$user = new User($db);

// Retrieve user ID from the URL
$userId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch user details
if (!is_null($userId)) {
    // Assuming there is a method like getUserById in the User class
    $userDetails = $user->readUser($userId);

    if (!$userDetails) {
        // User not found, you may handle this accordingly
        // For simplicity, we'll redirect to the index.php
        header("Location: index.php");
        exit();
    }
} else {
    // User ID not provided, redirect to index.php
    header("Location: index.php");
    exit();
}

?>

<div class="main-container">
    <h1 class="mb-4">User Details
        <a href="users.php" class="btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back to Users</a>
    </h1>
    <hr />

    <div class="row">
        <div class="col-md-6">
        <dl class="row">
                <dt class="col-sm-4">User ID:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['UserID'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">First Name:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['FirstName'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Last Name:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['LastName'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Email:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['Email'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">Address:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['Address'], ENT_QUOTES, 'UTF-8'); ?></dd>

                <dt class="col-sm-4">User Type:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($userDetails['UserType'], ENT_QUOTES, 'UTF-8'); ?></dd>
            </dl>
        </div>
    </div>
</div>

<?php
include '../footer.php';
?>
