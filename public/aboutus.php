<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - The Lilac Constellation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add new styles for the top header */
        .top-header {
            background: #9333EA;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .top-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-name {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .top-nav {
            display: flex;
            gap: 1.5rem;
        }
        .top-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .top-nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        /* Adjust main content to account for fixed header */
        .main-content {
            padding-top: 4rem;
        }
        /* Existing styles */
        .main-header {
            background: #9333EA;
            box-shadow: 0 4px 6px rgba(147, 51, 234, 0.1);
        }
        .nav-link {
            padding: 0.5rem 1rem;
            color: white;
            opacity: 0.9;
            transition: opacity 0.2s;
        }
        .nav-link:hover {
            opacity: 1;
        }
        body {
            background-image: url('https://img.freepik.com/free-vector/cute-pets-illustration_53876-112522.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 1;
        }
        .container {
            position: relative;
            z-index: 2;
        }
        .content-wrapper {
            position: relative;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(147, 51, 234, 0.1);
            transition: transform 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(147, 51, 234, 0.15);
        }
        .nav-group {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
    </style>
</head>
<body class="bg-purple-50">
    <!-- Top Header -->
    <header class="top-header">
        <div class="container mx-auto">
            <div class="nav-group">
                <div class="brand-name">The Lilac Constellation</div>
                <nav class="top-nav">
                    <a href="index.php">Dashboard</a>
                    <a href="aboutus.php">About Us</a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-white text-sm">
                    <span class="opacity-75">User ID:</span>
                    <span class="font-medium ml-1"><?php echo $user_id; ?></span>
                </div>
                <form action="logout.php" method="POST">
                    <button type="submit" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-50 transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-purple-800 mb-4">Welcome to The Lilac Constellation</h1>
                <p class="text-xl text-purple-600 mb-8">A Universe of Care for Your Furry Friends</p>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    We're dedicated to making pet management simple, organized, and delightful. Our system helps you keep track of your beloved pets with style and efficiency.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 gap-8 mb-16">
                <div class="feature-card">
                    <div class="text-purple-600 text-4xl mb-4">🐾</div>
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">Comprehensive Pet Profiles</h3>
                    <p class="text-gray-600">Keep detailed records of your pets including their breed, age, medical history, and special care requirements.</p>
                </div>

                <div class="feature-card">
                    <div class="text-purple-600 text-4xl mb-4">💫</div>
                    <h3 class="text-xl font-semibold text-purple-800 mb-2">Beautiful Experience</h3>
                    <p class="text-gray-600">A visually appealing and user-friendly interface that makes pet management a joy.</p>
                </div>
            </div>

            <!-- Mission Statement -->
            <div class="bg-white rounded-2xl p-8 shadow-lg max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-purple-800 mb-4">Our Mission</h2>
                <p class="text-gray-600 mb-4">
                    At The Lilac Constellation, we believe that every pet deserves the best care possible. Our mission is to provide pet owners with a beautiful and efficient way to manage their pets' information, ensuring that every furry friend receives the attention and care they deserve.
                </p>
                <p class="text-gray-600">
                    We've created this platform with love and attention to detail, making sure that managing your pets' information is not just a task, but a delightful experience that helps you be the best pet parent you can be.
                </p>
            </div>
        </div>
    </div>
</body>
</html> 