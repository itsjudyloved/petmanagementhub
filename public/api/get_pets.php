<?php
// api/get_pets.php

require_once '../db_connect.php';

header('Content-Type: application/json');

try {
    // Fetch all pets from the database
    $stmt = $pdo->query("SELECT * FROM pets");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the pets as JSON
    echo json_encode($pets);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
