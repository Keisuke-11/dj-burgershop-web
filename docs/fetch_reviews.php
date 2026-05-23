<?php
// Database connection details
 require_once(__DIR__ . '/api/config/db_config.php');

// Fetch only approved reviews
$sql = "SELECT FeedbackID as review_id, 
               CASE WHEN IsAnonymous = 1 THEN 'Anonymous' ELSE (SELECT CONCAT(FirstName, ' ', LastName) FROM customer WHERE CustomerID = cf.CustomerID) END as user_name, 
               ReviewMessage as user_review, 
               OverallRating as user_rating 
        FROM customer_feedback cf WHERE Status = 'Approved' ORDER BY CreatedDate DESC";
$result = $conn->query($sql);

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Send reviews as JSON
header('Content-Type: application/json');
echo json_encode($reviews);

$conn->close();
?>