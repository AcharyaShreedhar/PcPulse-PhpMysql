<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'dbinit.php';
include_once 'admin/user.php';

// Check if the user is already logged in, redirect to home page
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Initialize variables
$email = $password = '';
$errors = array('login' => '');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user input
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);

    // If inputs are valid, attempt to log in
    if (!empty($email) && !empty($password)) {
        $db = new Database("localhost", "root", "", "pcpulse");
        $user = new User($db);

        if ($userInfo = $user->loginWithEmail($email, $password)) {
            // Login successful, redirect to home page
            $_SESSION['user_id'] = $userInfo['UserID'];
            $_SESSION['username'] = $userInfo['FirstName'] . ' ' . $userInfo['LastName'];
            $_SESSION['email'] = $userInfo['Email'];
            $_SESSION['address'] = $userInfo['Address'];
            $_SESSION['UserType'] = $userInfo['UserType'];
            header('Location: index.php?success=1');
            exit();
        } else {
            $errors['login'] = 'Invalid email or password';
        }
    } else {
        $errors['login'] = 'Email and password are required';
    }
}

function validateInput($input)
{
    if (!is_null($input)) {
        $input = trim($input);

        if (!empty($input)) {
            $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            return $input;
        }
    }
    return null;
}
?>

<?php include 'header.php'; ?>

<div class="main-container">
    <h2 class="mb-4">Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email !== null ? htmlspecialchars($email) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
        <p class="text-danger"><?php echo $errors['login']; ?></p>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <p class="text-success">Login successful. Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <?php endif; ?>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
</div>
