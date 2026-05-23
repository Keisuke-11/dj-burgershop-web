<?php
// Database connection
require_once 'db_connection.php';

// Calculate Total Users
$users_query = "SELECT COUNT(*) as total_users FROM customer";
$users_result = $conn->query($users_query);
$total_users = $users_result ? $users_result->fetch_assoc()['total_users'] : 0;

// Calculate Total Reservations
$reservations_query = "SELECT COUNT(*) as total_reservations FROM reservation";
$reservations_result = $conn->query($reservations_query);
$total_reservations = $reservations_result ? $reservations_result->fetch_assoc()['total_reservations'] : 0;

// Calculate Total Orders
$orders_query = "SELECT COUNT(*) as total_orders FROM orders";
$orders_result = $conn->query($orders_query);
$total_orders = $orders_result ? $orders_result->fetch_assoc()['total_orders'] : 0;

// Calculate Overall Rating
$rating_query = "SELECT 
    COUNT(*) as total_reviews, 
    AVG(OverallRating) as average_rating
    FROM customer_feedback 
    WHERE Status = 'Approved'";
$rating_result = $conn->query($rating_query);
$rating_data = $rating_result ? $rating_result->fetch_assoc() : ['total_reviews' => 0, 'average_rating' => 0];
$total_reviews = $rating_data['total_reviews'];
$overall_rating = number_format($rating_data['average_rating'], 1) ?: '0.0';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboarddesign.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Key Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <h2><?php echo $total_users; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $total_reservations; ?></h2>
                <p>Reservations</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $total_orders; ?></h2>
                <p>Orders</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $overall_rating; ?>/5</h2>
                <p>Average Ratings (<?php echo $total_reviews; ?> Reviews)</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="user.php" target="content-frame">Manage Users</a></li>
                <li><a href="products.php" target="content-frame">Manage Products</a></li>
                <li><a href="reservation.php" target="content-frame">View Reservations</a></li>
                <li><a href="orders.php" target="content-frame">View Orders</a></li>
                <li><a href="testimonials.php" target="content-frame">Review Testimonials</a></li>
                <li><a href="reports.php" target="content-frame">View Reports</a></li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>