
<?php

$loginForm = '
<div class="card">
    <h2 class="card-header">Login</h2>
    <div class="card-body">
        <form method="POST" action="">
            <table class="table">
                <tr>
                    <td><label for="username">Username:</label></td>
                    <td><input type="text" id="username" name="username" required class="form-control"></td>
                </tr>
                <tr>
                    <td><label for="password">Password:</label></td>
                    <td><input type="password" id="password" name="password" required class="form-control"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Login" class="btn btn-success"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo 'Please fill out all required fields';
        exit;
    }

    foreach (USERS as $user) {

        # If the username doesn't match, skip to the next user
        if ($user['username'] !== $username) {
            continue;
        }

        $salt = $user['salt'];
        if ($user['password'] !== hash("sha512", $salt.$password)) {
            // Unsuccessful login
            echo alert("Invalid username or password", "danger");
            echo $loginForm;
            exit;
        }

        // Successful login
        echo alert("Welcome back, $username!", "success");
        $_SESSION['id'] = $user['id'];
    }
}

echo $loginForm;
?>


