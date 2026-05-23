<?php
// Database connection details
require_once 'db_connection.php';

// Fetch all testimonials from the database
$sql = "SELECT cf.*, 
               CASE WHEN IsAnonymous = 1 THEN 'Anonymous' ELSE (SELECT CONCAT(FirstName, ' ', LastName) FROM customer WHERE CustomerID = cf.CustomerID) END as user_name 
        FROM customer_feedback cf ORDER BY CreatedDate DESC";
$testimonials_result = $conn->query($sql);

// Process the review status update
if (isset($_GET["update_id"]) && isset($_GET["status"])) {
    $testimonial_id = intval($_GET["update_id"]);
    $new_status = $conn->real_escape_string($_GET["status"]);

    // Update the testimonial status
    $sql = "UPDATE customer_feedback SET Status = ? WHERE FeedbackID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $testimonial_id);

    if ($stmt->execute()) {
        echo "<script>alert('Review status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating review status: " . $stmt->error . "');</script>";
    }

    $stmt->close();

    // Refresh the page to show updated results
    echo "<script>window.location.href = 'testimonials.php';</script>";
}

// Process the review deletion
if (isset($_GET["delete_id"])) {
    $testimonial_id = intval($_GET["delete_id"]);

    // Delete the testimonial
    $sql = "DELETE FROM customer_feedback WHERE FeedbackID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $testimonial_id);
    // Execute the statement
    if ($stmt->execute()) {
        // Check if this was the last review
        $check_count_sql = "SELECT COUNT(*) as count FROM customer_feedback";
        $count_result = $conn->query($check_count_sql);
        $count_row = $count_result->fetch_assoc();
        
        if ($count_row['count'] == 0) {
            // If no reviews left, reset auto-increment to 1
            $reset_auto_increment_sql = "ALTER TABLE customer_feedback AUTO_INCREMENT = 1";
            $conn->query($reset_auto_increment_sql);
        }

        echo "<script>alert('Review deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting review: " . $stmt->error . "');</script>";
    }

    $stmt->close();

    // Refresh the page to show updated results
    echo "<script>window.location.href = 'testimonials.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <link rel="stylesheet" href="../CSS/testimonialsDesign.css">
</head>
<body>
<h1>Manage Reviews</h1>

<!-- Reviews List -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Review</th>
            <th>Rating</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($review = $testimonials_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $review['FeedbackID']; ?></td>
                <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                <td><?php echo htmlspecialchars($review['ReviewMessage']); ?></td>
                <td><?php echo $review['OverallRating']; ?>/5</td>
                <td><?php echo htmlspecialchars($review['CreatedDate']); ?></td>
                <td class="status-<?php echo strtolower($review['Status'] ?? 'Pending'); ?>">
                    <?php echo $review['Status'] ?? 'Pending'; ?>
                </td>
                <td class="action-links">
                    <?php if ($review['Status'] !== 'Approved'): ?>
                        <a href="testimonials.php?update_id=<?php echo $review['FeedbackID']; ?>&status=Approved" class="approve">Approve</a>
                    <?php endif; ?>
                    <?php if ($review['Status'] !== 'Rejected'): ?>
                        <a href="testimonials.php?update_id=<?php echo $review['FeedbackID']; ?>&status=Rejected" class="reject">Reject</a>
                    <?php endif; ?>
                    <a href="testimonials.php?delete_id=<?php echo $review['FeedbackID']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>