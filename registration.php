<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "emurray46";
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Registration Page</title>
   <!--- <link rel="stylesheet" href="styles.css" type="text/css"> -->
    <!---<script src="Scripts.js"></script>-->


    <!-- Include Tailwind CSS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        /* fade-in animation */
        .container {
            opacity: 0;
            transform: translateX(-4rem);
            transition: opacity 1s ease-in-out, transform 0.5s ease-in-out;
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
<body class="bg-purple-900">
<div class="container mx-auto mt-8 p-4">
        <div class="bg-navy-900 text-white p-8 rounded-lg shadow-xl max-w-md mx-auto">
            <h2 class="text-2xl mb-4">Registration</h2>
            <form method="post" action="" class="space-y-4">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" class="rounded p-2 w-full text-black">
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" class="rounded p-2 w-full text-black">
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" class="rounded p-2 w-full text-black">
                </div>
                <div class="input-group">
                    <button type="submit" name="register" id="registerButton" class="bg-blue-500 text-white rounded p-2">Register</button>
                </div>
                <p class="text-center">or</p>
                <div class="input-group">
                    <a href="login.php" class="text-blue-500">Log In</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for fade-in animation -->
    <script>
         document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector('.container');
            container.classList.add('loaded');
        });
    </script>
</body>
</html>