<?php
session_start();
require_once '../utils/database.php';

if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ./login.php');
}

// Fetch restaurants from database
$where_conditions = [];

// Handle search by name
if (isset($_POST['search']) && !empty($_POST['name'])) {
    $search_term = $_POST['name'];
    $where_conditions[] = "name LIKE '%$search_term%'";
}

// Handle category filter
if (isset($_POST['category_filter']) && !empty($_POST['category'])) {
    $category = $_POST['category'];
    $where_conditions[] = "category = '$category'";
}

// Build the WHERE clause
$search_query = '';
if (!empty($where_conditions)) {
    $search_query = "WHERE " . implode(' AND ', $where_conditions);
}

$restaurants_query = "SELECT * FROM restaurants $search_query ORDER BY name ASC";
$restaurants = query($restaurants_query);

$available_categories = query("SELECT DISTINCT category FROM `restaurants`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <div class="navbar container py-3">
        <div class="navbar-profile">
            <img class="profile-picture" 
                src="https://images.vexels.com/media/users/3/129733/isolated/preview/a558682b158debb6d6f49d07d854f99f-casual-male-avatar-silhouette.png" alt="profile-picture">
            <h1 class="navbar-profile-name mb-0">Hi, <?= $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] ?></h1>
        </div>

        <form class="action-form" action="" method="post">
            <button class="logout-button" type="submit" name="logout">
                <img src="../icons/logout.png" alt="logout">
            </button>
        </form>
    </div>

    <?php include '../parts/promotions.php'; ?>

    <div class="search-container container">
        <form class="search-form" action="./home.php" method="post">
            <input class="search-input" type="text" name="name" placeholder="Search restaurants, cuisines, or dishes...">
            <button class="search-button" type="submit" name="search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>

        <!-- Category Filter Buttons -->
        <div class="category-filters">
            <form class="category-form" action="./home.php" method="post">
                <button class="category-btn <?php echo !isset($_POST['category_filter']) ? 'active' : ''; ?>" type="submit" name="clear_filter">
                    All
                </button>
            </form>
            
            <?php if (!empty($available_categories)): ?>
                <?php foreach ($available_categories as $cat): ?>
                    <form class="category-form" action="./home.php" method="post">
                        <input type="hidden" name="category" value="<?= htmlspecialchars($cat['category']) ?>">
                        <button class="category-btn <?php echo (isset($_POST['category']) && $_POST['category'] == $cat['category']) ? 'active' : ''; ?>" type="submit" name="category_filter">
                            <?= htmlspecialchars($cat['category']) ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div class="restaurants-grid">
            <?php if (!empty($restaurants)): ?>
                <?php foreach ($restaurants as $restaurant): ?>
                    <div class="card">
                        <img src="../images/venues/<?= $restaurant['venue'] ?>" alt="<?= htmlspecialchars($restaurant['name']) ?>">
                        <div class="category-tag"><?= htmlspecialchars($restaurant['category']) ?></div>
                        <div class="card-content">
                            <h1><?= htmlspecialchars($restaurant['name']) ?></h1>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <h2>No restaurants found</h2>
                    <p>Try adjusting your search terms or browse all restaurants.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../parts/bootstrap.php'; ?>
</body>
</html>