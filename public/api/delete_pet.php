<?php
session_start(); // Start session for login tracking

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the pet ID from the form submission
    $pet_id = $_POST['pet_id'];

    try {
        // Include the database configuration
        require_once '../../config.php'; // Adjusted path for config.php
        $pdo = Config::pdo();

        // Delete the pet from the database using the pet ID
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = :pet_id");
        $stmt->execute(['pet_id' => $pet_id]);

        // Redirect to the dashboard (index.php)
        header('Location: ../index.php');
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // Redirect if accessed without POST
    header('Location: ../index.php');
    exit();
}
