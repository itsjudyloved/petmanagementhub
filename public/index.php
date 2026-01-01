<?php
session_start(); // Start session for login tracking

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user information (optional, for display)
$user_id = $_SESSION['user_id'];

require_once '../config.php'; // Make sure to adjust the path to config.php if needed

try {
    // Get the database connection
    $pdo = Config::pdo();

    // Query to fetch all pets with specific columns
    $stmt = $pdo->query("SELECT id, name, breed, age, description, image_url FROM pets");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
    $pets = []; // Initialize empty array if query fails
}

// Initialize $pets as empty array if not set
if (!isset($pets)) {
    $pets = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Lilac Constellation</title>
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
        .nav-group {
            display: flex;
            align-items: center;
            gap: 2rem;
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
        .pet-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(147, 51, 234, 0.1);
        }
        .pet-content {
            flex-grow: 1;
        }
        .button-container {
            margin-top: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            padding-top: 1rem;
        }
        .main-header {
            background: #9333EA;
            box-shadow: 0 4px 6px rgba(147, 51, 234, 0.1);
        }
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
            color: #7E22CE;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #9333EA;
            border-radius: 2px;
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
        .hidden {
            display: none !important;
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
        <div class="content-wrapper">
            <div class="container mx-auto px-6">
                <!-- Welcome & Stats Section -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-12">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h2 class="text-3xl font-bold text-purple-800 mb-4">Welcome to Your Pet Management Hub</h2>
                            <p class="text-gray-600 mb-4">
                                Track, manage, and care for your pets with our comprehensive logging system. 
                                Keep all your pet's important information in one beautiful, organized space.
                            </p>
                            <div class="flex space-x-4">
                                <a href="api/add_pet.php" 
                                   class="bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 
                                          transition duration-200 transform hover:-translate-y-1 shadow-lg
                                          hover:shadow-xl flex items-center space-x-2">
                                    <span class="text-xl">+</span>
                                    <span>Add New Pet</span>
                                </a>
                                <a href="#pet-list" 
                                   class="bg-purple-100 text-purple-700 py-3 px-6 rounded-lg hover:bg-purple-200 
                                          transition duration-200 flex items-center">
                                    <span>View All Pets</span>
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-purple-50 rounded-lg p-4 text-center">
                                <div class="text-3xl font-bold text-purple-800 mb-2">
                                    <?php echo count($pets); ?>
                                </div>
                                <div class="text-purple-600">Total Pets</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Features Section -->
                <div class="grid md:grid-cols-2 gap-6 mb-12">
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <div class="text-purple-600 text-2xl mb-3">📋</div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-2">Pet Profiles</h3>
                        <p class="text-gray-600">Create detailed profiles including breed, age, and special care requirements.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <div class="text-purple-600 text-2xl mb-3">🔄</div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-2">Easy Updates</h3>
                        <p class="text-gray-600">Quick and simple tools to update pet information as needed.</p>
                    </div>
                </div>

                <!-- Pet List Section -->
                <div id="pet-list">
                    <h2 class="text-2xl font-bold text-center section-title mb-12">Available Pets</h2>

                    <!-- Search and Filter Section -->
                    <div class="bg-white rounded-xl p-6 mb-8 shadow-md">
                        <div class="flex flex-wrap gap-4 items-center justify-between">
                            <div class="flex-1 min-w-[200px]">
                                <input type="text" id="searchInput" placeholder="Search pets..." 
                                       class="w-full px-4 py-2 rounded-lg border border-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div class="flex gap-4">
                                <select id="sortBy" class="px-4 py-2 rounded-lg border border-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">Sort By</option>
                                    <option value="name">Name</option>
                                    <option value="age">Age</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Display Pet List -->
                    <div id="petList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php if (isset($pets) && count($pets) > 0): ?>
                            <?php foreach ($pets as $pet): ?>
                                <div class="bg-white p-6 rounded-xl shadow-sm pet-card" 
                                     data-name="<?php echo strtolower(htmlspecialchars($pet['name'])); ?>"
                                     data-age="<?php echo isset($pet['age']) ? (int)$pet['age'] : 0; ?>">
                                    <div class="pet-content">
                                        <img src="<?php echo htmlspecialchars($pet['image_url']); ?>" alt="Pet Image" 
                                             class="w-full h-48 object-cover rounded-lg mb-4">
                                        <h3 class="text-xl font-semibold text-purple-800"><?php echo htmlspecialchars($pet['name']); ?></h3>
                                        <p class="text-purple-600"><?php echo htmlspecialchars($pet['breed']); ?></p>
                                        <p class="text-purple-600">Age: <?php echo isset($pet['age']) ? htmlspecialchars($pet['age']) : 0; ?> years</p>
                                        <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($pet['description']); ?></p>
                                    </div>

                                    <div class="button-container">
                                        <a href="api/edit_pet.php?pet_id=<?php echo $pet['id']; ?>" 
                                           class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 
                                                  transition duration-200 text-center">
                                            Edit Pet
                                        </a>
                                        <form action="api/delete_pet.php" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this pet?');">
                                            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                            <button type="submit" 
                                                    class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 
                                                           transition duration-200 w-full text-center">
                                                Delete Pet
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-3 text-center py-8">
                                <div class="bg-white rounded-xl p-8 shadow-sm">
                                    <h3 class="text-xl font-semibold text-purple-800 mb-2">No Pets Found</h3>
                                    <p class="text-gray-600 mb-4">You haven't added any pets yet.</p>
                                    <a href="api/add_pet.php" 
                                       class="inline-block bg-purple-600 text-white py-2 px-6 rounded-lg hover:bg-purple-700 
                                              transition duration-200 transform hover:-translate-y-1">
                                        Add Your First Pet
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const sortBy = document.getElementById('sortBy');
            const petList = document.getElementById('petList');
            const petCards = document.querySelectorAll('.pet-card');

            function filterAndSortPets() {
                const searchTerm = searchInput.value.toLowerCase();
                const sortValue = sortBy.value;

                // Convert NodeList to Array for sorting
                const petsArray = Array.from(petCards);

                // First filter
                petsArray.forEach(card => {
                    const name = card.getAttribute('data-name');
                    let isVisible = name.includes(searchTerm);
                    card.classList.toggle('hidden', !isVisible);
                });

                // Then sort if a sort option is selected
                if (sortValue) {
                    petsArray.sort((a, b) => {
                        const valueA = a.getAttribute(`data-${sortValue}`);
                        const valueB = b.getAttribute(`data-${sortValue}`);
                        
                        if (sortValue === 'name') {
                            return valueA.localeCompare(valueB);
                        } else if (sortValue === 'age') {
                            return parseInt(valueA) - parseInt(valueB);
                        }
                        return 0;
                    });

                    // Reorder the cards in the DOM
                    petsArray.forEach(card => {
                        petList.appendChild(card);
                    });
                }
            }

            // Add event listeners
            searchInput.addEventListener('input', filterAndSortPets);
            sortBy.addEventListener('change', filterAndSortPets);
        });
    </script>
</body>
</html>
