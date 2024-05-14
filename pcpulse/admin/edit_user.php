<!DOCTYPE html>
<?php
include '../header.php';

// Include the database initialization file
include '../dbinit.php';
include 'User.php'; // Include the User class file

$errors = array(
    'firstName' => '',
    'lastName' => '',
    'email' => '',
    'password' => '',
    'address' => '',
    'userType' => ''
);

// Initialize variables to hold the input
$firstName = $lastName = $email = $password = $address = $userType = '';

$db = new Database("localhost", "root", "", "pcpulse");
// Retrieve user ID from the URL
$userId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch existing user details
if (!is_null($userId)) {
    // Assuming there is a method like getUserById in the User class
    $user = new User($db);
    $existingUser = $user->readUser($userId);

    if ($existingUser) {
        $firstName = $existingUser['FirstName'];
        $lastName = $existingUser['LastName'];
        $email = $existingUser['Email'];
        $password = $existingUser['Password'];
        $address = $existingUser['Address'];
        $userType = $existingUser['UserType'];
    } else {
        // User not found, you may handle this accordingly
        // For simplicity, we'll redirect to the index.php
        header("Location: index.php");
        exit();
    }
}

// Handle form submission to update user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;

    // Extract the form inputs
    $firstName = validateInput($_POST['firstName']);
    $lastName = validateInput($_POST['lastName']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $address = validateInput($_POST['address']);
    $userType = validateInput($_POST['userType']);

    // Assuming there is a method like updateUser in the User class
    $user = new User($db);

    // Ensure all required fields are filled
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($address) || empty($userType)) {
        foreach ($errors as $key => $value) {
            if (empty($$key)) {
                $errors[$key] = ucfirst($key) . ' is required.';
            }
        }
    } else {
        // Call the updateUser method in the User class
        if ($user->updateUser($userId, $firstName, $lastName, $email, $password, $address, $userType)) {
            echo "<div class='message-container mt-5 pt-5 text-center'><div class='alert alert-success' role='alert'>User updated successfully.</div></div>";
        } else {
            echo "<div class='message-container mt-5 pt-5 text-center'><div class='alert alert-danger' role='alert'>Error updating user.</div></div>";
        }
    }
}

function validateInput($input)
{
    if (!is_null($input)) {
        // Trim any leading or trailing spaces
        $input = trim($input);

        if (!empty($input)) {
            // Sanitize the input
            $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            return $input;
        }
    }
    return null;
}

?>

<div class="main-container">
    <h1 class="mb-4">Update User
        <a href="users.php" class="btn btn-primary float-right"><i class="fas fa-eye"></i> View Users</a>
    </h1>
    <hr />

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName; ?>">
            <span class="text-danger">
                <?php echo $errors['firstName']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>">
            <span class="text-danger">
                <?php echo $errors['lastName']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
            <span class="text-danger">
                <?php echo $errors['email']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>">
            <span class="text-danger">
                <?php echo $errors['password']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?php echo $address; ?></textarea>
            <span class="text-danger">
                <?php echo $errors['address']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="userType">User Type</label>
            <select class="form-control" id="userType" name="userType">
                <option value="Customer" <?php echo ($userType == 'Customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="Admin" <?php echo ($userType == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <span class="text-danger">
                <?php echo $errors['userType']; ?>
            </span>
        </div>

        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

<?php
include '../footer.php';
?>
