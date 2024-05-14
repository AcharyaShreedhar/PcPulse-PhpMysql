<?php include 'header.php'; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'dbinit.php';
include '../pcpulse/admin/User.php';

$errors = array(
    'firstName' => '',
    'lastName' => '',
    'email' => '',
    'password' => '',
    'address' => '',
    'userType' => ''
);

$firstName = $lastName = $email = $password = $address = $userType = '';

$db = new Database("localhost", "root", "", "pcpulse");
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = validateInput($_POST['firstName']);
    $lastName = validateInput($_POST['lastName']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $address = validateInput($_POST['address']);
    $userType = validateInput($_POST['userType']);

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($address) || empty($userType)) {
        foreach ($errors as $key => $value) {
            if (empty($$key)) {
                $errors[$key] = ucfirst($key) . ' is required.';
            }
        }
    } else {
        if ($user->createUser($firstName, $lastName, $email, $password, $address, $userType)) {
            echo "<div class='message-container text-center mt-5 pt-5'><div class='alert alert-success' role='alert'>Registration Successfull.</div></div>";
        } else {
            echo "<div class='message-container text-center mt-5 pt-5'><div class='alert alert-danger' role='alert'>Sorry, Registration Failed. Please try again!!.</div></div>";
        }
    }
}

function validateInput($input)
{
    if (!is_null($input)) {
        $input = trim($input);

        if (!empty($input)) {
            $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
            return $input;
        }
    }
    return null;
}

$db->closeConnection();
?>

<div class="main-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName">
            <span class="text-danger">
                <?php echo $errors['firstName']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName">
            <span class="text-danger">
                <?php echo $errors['lastName']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
            <span class="text-danger">
                <?php echo $errors['email']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <span class="text-danger">
                <?php echo $errors['password']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
            <span class="text-danger">
                <?php echo $errors['address']; ?>
            </span>
        </div>
        <div class="form-group">
            <label for="userType">User Type</label>
            <select class="form-control" id="userType" name="userType">
                <option value="Customer">Customer</option>
                <option value="Admin">Admin</option>
            </select>
            <span class="text-danger">
                <?php echo $errors['userType']; ?>
            </span>
        </div>

        <button type="submit" class="btn btn-md btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have a account? <a href="login.php">Login here</a></p>
</div>

<?php include 'footer.php'; ?>
