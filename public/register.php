<?php
session_start(); // Start the session for login tracking

// Include the Config.php file for DB connection
require_once '../config.php'; // Adjust the path to config.php (one level up)

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Connect to the database
            $pdo = Config::pdo();

            // Insert the new user into the 'users' table with email and hashed password
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->execute(['email' => $email, 'password' => $hashed_password]);

            // Redirect to login page after successful registration
            header('Location: login.php');
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            $error_message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet"> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-sm mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-center text-indigo-600 mb-6">Create an Account</h2>

    <!-- Display error message if there is one -->
    <?php if (isset($error_message)) : ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="POST">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 mt-2">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 mt-2">
        </div>

        <div class="mb-6">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 mt-2">
        </div>

        <div class="text-center">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Register
            </button>
        </div>
    </form>

    <p class="mt-4 text-center text-sm">
        Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-800">Login here</a>
    </p>
</div>

</body>
</html>
