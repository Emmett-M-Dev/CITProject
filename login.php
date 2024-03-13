<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Connect to the database (you should have a database connection)
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "emurray46";
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    } else {
        $enteredUsername = $_POST['username'];
        $enteredPassword = $_POST['password'];

        // Retrieve user data from the database based on the entered username
        $sql = "SELECT id, username, password FROM users WHERE username = '$enteredUsername'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $userId = $row['id'];
            $storedPassword = $row['password'];

            // Verify the entered password with the stored password 
            if ($enteredPassword === $storedPassword) {
                // User authentication successful, start a session
                session_start();
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $enteredUsername;

                // redirect to index when logged in
                header("Location: dashboard.php");
                exit();
            } else {
                $loginError = "Invalid credentials. Please try again.";
            }
        } else {
            $loginError = "User not found.";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Include Tailwind CSS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        /* Custom CSS for fade-in animation */
        .container {
            opacity: 0;
            transform: translateX(-4rem);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }

        .container.loaded {
            opacity: 1;
            transform: translateX(0);
        }
        body {
            margin: 0;
            padding: 0;
            background-image: url('includes/images/MoonSkyBackground.webp');
            background-attachment: fixed; /* This makes the background image fixed */
            background-position: center; /* Centers the image in the viewport */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-size: cover; /* Covers the entire viewport */
            
        }
    </style>
</head>
<body>
    <div class="container mx-auto mt-8 p-4">
        <div class="bg-navy-900 text-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <h2 class="text-2xl mb-4">Login</h2>
            <form method="post" action="" class="space-y-4">
                <?php if (isset($loginError)): ?>
                    <p class="text-red-500"><?php echo $loginError; ?></p>
                <?php endif; ?>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" class="rounded p-2 w-full text-black">
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" class="rounded p-2 w-full text-black">
                </div>
                <div class="input-group">
                    <button type="submit" name="login" id="loginButton" class="bg-blue-500 text-white rounded p-2">Login</button>
                </div>
                <p class="text-center">or</p>
                <div class="input-group">
                    <a href="registration.php" class="bg-white text-blue-500 rounded p-2">Register</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Add JavaScript to apply the "loaded" class for the fade-in effect -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector('.container');
            container.classList.add('loaded');
        });
    </script>
</body>
</html>
