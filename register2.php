<?php
// Include the database configuration
include_once 'config/dbconfig.php';

// Initialize variables to store user input
$username = $email = $password = '';
$username_err = $email_err = $password_err = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email address.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } else {
        $password = trim($_POST['password']);
    }

    // If there are no validation errors, proceed with registration
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        // Hash the password for security (you may use a more secure hashing method)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database (replace 'users' with your table name)
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = $hashed_password;

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to the login page after successful registration
                header("location: login.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        }
    }

    // Close the database connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Include your Tailwind CSS styles here -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-semibold mb-4">Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="border rounded p-2 w-full" value="<?php echo $username; ?>">
                <span class="text-red-500"><?php echo $username_err; ?></span>
            </div>
            <div class="mb-4">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="border rounded p-2 w-full" value="<?php echo $email; ?>">
                <span class="text-red-500"><?php echo $email_err; ?></span>
            </div>
            <div class="mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="border rounded p-2 w-full">
                <span class="text-red-500"><?php echo $password_err; ?></span>
            </div>
            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white rounded p-2">Register</button>
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
