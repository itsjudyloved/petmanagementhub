<?php
require 'vendor/autoload.php';
require_once 'config.php';

// Initialize Flight
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=pet_management', 'root', ''));

// Enable CORS for API endpoints
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// API Routes
// Get all pets
Flight::route('GET /api/pets', function() {
    try {
        $db = Flight::db();
        $stmt = $db->query("SELECT * FROM pets");
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Flight::json($pets);
    } catch (PDOException $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

// Get single pet
Flight::route('GET /api/pets/@id', function($id) {
    try {
        $db = Flight::db();
        $stmt = $db->prepare("SELECT * FROM pets WHERE id = ?");
        $stmt->execute([$id]);
        $pet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pet) {
            Flight::json($pet);
        } else {
            Flight::json(['error' => 'Pet not found'], 404);
        }
    } catch (PDOException $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

// Add new pet
Flight::route('POST /api/pets', function() {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        
        if (!$data) {
            Flight::json(['error' => 'Invalid data'], 400);
            return;
        }

        $db = Flight::db();
        $stmt = $db->prepare("INSERT INTO pets (name, breed, age, description, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['breed'],
            $data['age'],
            $data['description'],
            $data['image_url'] ?? null
        ]);

        Flight::json(['id' => $db->lastInsertId(), 'message' => 'Pet added successfully'], 201);
    } catch (PDOException $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

// Update pet
Flight::route('PUT /api/pets/@id', function($id) {
    try {
        $data = json_decode(Flight::request()->getBody(), true);
        
        if (!$data) {
            Flight::json(['error' => 'Invalid data'], 400);
            return;
        }

        $db = Flight::db();
        $stmt = $db->prepare("UPDATE pets SET name = ?, breed = ?, age = ?, description = ?, image_url = ? WHERE id = ?");
        $stmt->execute([
            $data['name'],
            $data['breed'],
            $data['age'],
            $data['description'],
            $data['image_url'] ?? null,
            $id
        ]);

        Flight::json(['message' => 'Pet updated successfully']);
    } catch (PDOException $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

// Delete pet
Flight::route('DELETE /api/pets/@id', function($id) {
    try {
        $db = Flight::db();
        $stmt = $db->prepare("DELETE FROM pets WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            Flight::json(['message' => 'Pet deleted successfully']);
        } else {
            Flight::json(['error' => 'Pet not found'], 404);
        }
    } catch (PDOException $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

// Serve the React app for all other routes
Flight::route('/*', function() {
    include 'public/index.php';
});

Flight::start(); 