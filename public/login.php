<?php
session_start(); // Start session for login tracking

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    try {
        require_once '../config.php';
        $pdo = Config::pdo();

        // Query to fetch the user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit();
        } else {
            $error_message = 'Invalid email or password!';
        }
    } catch (PDOException $e) {
        $error_message = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Lilac Constellation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .pet-bg {
            background-image: url('https://img.freepik.com/free-vector/cute-pets-illustration_53876-112522.jpg');
            background-size: cover;
            background-position: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            z-index: -1;
        }
        .login-container {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.95);
        }
        .subtitle {
            color: #9333EA;
            font-style: italic;
            letter-spacing: 0.025em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="pet-bg"></div>

    <div class="max-w-md mx-auto">
        <!-- Logo/Title Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-purple-800 mb-3">The Lilac Constellation</h1>
            <p class="subtitle text-lg mb-6">A Universe of Care for Your Furry Friends</p>
            <p class="text-gray-600">Welcome back! Please login to continue.</p>
        </div>

        <div class="login-container rounded-xl shadow-xl p-8 relative">
            <!-- Display error message if login fails -->
            <?php if (isset($error_message)) : ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative">
                        <input type="email" id="email" name="email" required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 
                                      focus:border-transparent transition duration-150">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input type="password" id="password" name="password" required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 
                                      focus:border-transparent transition duration-150">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                                   shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 
                                   transition duration-150">
                        Sign in to your account
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <p class="text-center text-sm text-gray-600">
                    Don't have an account? 
                    <a href="register.php" class="font-medium text-purple-600 hover:text-purple-500 transition duration-150">
                        Register here
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
