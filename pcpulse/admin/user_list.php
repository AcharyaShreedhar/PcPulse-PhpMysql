<?php
include_once '../dbinit.php';
include_once 'User.php';

// Instantiate the Database class
$db = new Database('localhost', 'root', '', 'pcpulse');
$user = new User($db);
?>

<div>
    <h1 class="mb-4">Users List
        <a href="add_user.php" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Add User</a>
    </h1>
    <hr />
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <th scope="col">User Type</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serialNumber = 1;
                $users = $user->readUsers();

                while ($row = $users->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'>$serialNumber</th>";
                    echo "<td><a href='user_detail.php?id=" . htmlspecialchars($row['UserID']) . "'>" . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['UserType']) . "</td>";
                    echo "<td><a href='edit_user.php?id=" . htmlspecialchars($row['UserID']) . "' class='btn btn-sm btn-info'><i class='fas fa-pen'></i> Edit</a></td>";
                    echo "<td>
                        <form action='" . $_SERVER['PHP_SELF'] . "' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                            <input type='hidden' name='UserID' value='" . htmlspecialchars($row['UserID']) . "'>
                            <button type='submit' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Delete</button>
                        </form>
                    </td>";
                    echo "</tr>";
                    $serialNumber++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
