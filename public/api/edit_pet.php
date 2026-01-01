<?php
session_start(); // Start session for login tracking

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch user information (optional, for display)
$user_id = $_SESSION['user_id'];

// Correct path to config.php (going two levels up)
require_once '../../config.php';  // Adjusted path for config.php

try {
    // Get the database connection
    $pdo = Config::pdo();

    // Get pet ID from URL
    $pet_id = isset($_GET['pet_id']) ? (int)$_GET['pet_id'] : 0;
    
    if ($pet_id === 0) {
        header('Location: ../index.php');
        exit();
    }

    // Fetch current pet data
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        header('Location: ../index.php');
        exit();
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $name = htmlspecialchars($_POST['name']);
        $breed = htmlspecialchars($_POST['breed']);
        $age = (int)$_POST['age'];
        $description = htmlspecialchars($_POST['description']);
        $image_path = $pet['image_url']; // Keep existing image by default

        // Handle new image upload if provided
        if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            
            // Get file info
            $file_info = pathinfo($_FILES['pet_image']['name']);
            $file_extension = strtolower($file_info['extension']);
            
            // Validate file extension
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception('Invalid file type. Only JPG, PNG, and GIF files are allowed.');
            }
            
            // Generate unique filename
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            // Check if it's an actual image
            $check = getimagesize($_FILES['pet_image']['tmp_name']);
            if ($check === false) {
                throw new Exception('File is not an image.');
            }
            
            // Create uploads directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['pet_image']['tmp_name'], $file_path)) {
                $image_path = 'uploads/' . $file_name;
            } else {
                throw new Exception('Failed to move uploaded file.');
            }
        }

        // Update pet with new data
        $stmt = $pdo->prepare("UPDATE pets SET name = ?, breed = ?, age = ?, description = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$name, $breed, $age, $description, $image_path, $pet_id]);

        // Redirect to dashboard after successful update
        header('Location: ../index.php');
        exit();
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pet - The Lilac Constellation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
        }
        .content-wrapper {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        .form-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-purple-50">
    <!-- Main Header -->
    <header class="main-header text-white py-4 px-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <h1 class="text-2xl font-bold">The Lilac Constellation</h1>
                    <nav class="hidden md:flex space-x-6">
                        <a href="../index.php" class="nav-link">Dashboard</a>
                        <a href="../aboutus.php" class="nav-link">About Us</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="../logout.php" method="POST">
                        <button type="submit" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-purple-50 transition duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="container mx-auto px-6 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Page Title -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-purple-800 mb-2">Edit Pet</h1>
                    <p class="text-gray-600">Update the details of <?php echo htmlspecialchars($pet['name']); ?></p>
                </div>

                <!-- Error Message Display -->
                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Current Pet Image Preview -->
                <div class="mb-8 text-center">
                    <img src="../<?php echo htmlspecialchars($pet['image_url']); ?>" 
                         alt="Current Pet Image" 
                         class="w-48 h-48 object-cover rounded-lg mx-auto shadow-lg">
                    <p class="text-sm text-gray-600 mt-2">Current Image</p>
                </div>

                <!-- Edit Pet Form -->
                <div class="form-card p-8">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Pet Name</label>
                                <input type="text" id="name" name="name" required
                                       value="<?php echo htmlspecialchars($pet['name']); ?>"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">Breed</label>
                                <input type="text" id="breed" name="breed" required
                                       value="<?php echo htmlspecialchars($pet['breed']); ?>"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age (years)</label>
                                <input type="number" id="age" name="age" min="0" step="1" required
                                       value="<?php echo (int)$pet['age']; ?>"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="pet_image" class="block text-sm font-medium text-gray-700 mb-2">Update Pet Image (Optional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="pet_image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload a new image</span>
                                            <input id="pet_image" name="pet_image" type="file" accept="image/*" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4" required
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent"><?php echo htmlspecialchars($pet['description']); ?></textarea>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 
                                           transition duration-200 transform hover:-translate-y-1">
                                Save Changes
                            </button>
                            <a href="../index.php" 
                               class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 
                                      transition duration-200 text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
