<?php
class Config {
    public static function pdo() {
        try {
            // Connect to the pet_shop database
            // Update these values with your database credentials
            $pdo = new PDO('mysql:host=localhost;port=3306;dbname=pet_shop', 'root', 'your_password_here');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Handle the exception if the connection fails
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
?>

