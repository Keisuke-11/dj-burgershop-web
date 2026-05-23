<?php
// Database connection
require_once 'db_connection.php';

// Include the database functions
require_once 'db_functions.php';

// Function to reset auto-increment to the maximum existing ID
function resetAutoIncrement($conn) {
    // Find the maximum existing ID
    $max_id_result = $conn->query("SELECT MAX(ReservationID) as max_id FROM reservation");
    $max_id_row = $max_id_result->fetch_assoc();
    $max_id = $max_id_row['max_id'] ? $max_id_row['max_id'] : 0;

    // Reset the auto-increment to the maximum ID + 1
    $conn->query("ALTER TABLE reservation AUTO_INCREMENT = " . ($max_id + 1));
}

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);
    $update_query = "UPDATE reservation SET ReservationStatus = '$status' WHERE ReservationID = $id";
    $conn->query($update_query);
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    
    if (deleteReservation($conn, $delete_id)) {
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Optional: Add error handling or message display
        echo "Failed to delete the reservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Reservation Info</title>
    <link rel="stylesheet" href="../CSS/reservationDesign.css">
</head>
<body>
    <h1>Reservations</h1>

    <!-- Display User Info -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Guests</th>
                <th>Time</th>
                <th>Date</th>
                <th>Phone</th>
                <th>Seat Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch data from the reservation table joined with customer
            $sql = "SELECT r.*, CONCAT(c.FirstName, ' ', c.LastName) as full_name, c.Email as email, c.ContactNumber as phone 
                    FROM reservation r 
                    LEFT JOIN customer c ON r.CustomerID = c.CustomerID 
                    ORDER BY r.ReservationID DESC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status = $row['ReservationStatus'];
                    $status_lower = strtolower($status);
                    echo "<tr>
                            <td>{$row['ReservationID']}</td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['NumberOfCustomers']}</td>
                            <td>{$row['ReservationTime']}</td>
                            <td>{$row['ReservationDate']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['SeatType']}</td>
                            <td>
                                <form method='POST' class='status-form'>
                                    <input type='hidden' name='id' value='{$row['ReservationID']}'>
                                    <select name='status' onchange='this.form.submit()'>
                                        <option value='Pending' " . ($status == 'Pending' ? 'selected' : '') . ">Pending</option>
                                        <option value='Confirmed' " . ($status == 'Confirmed' ? 'selected' : '') . ">Confirmed</option>
                                        <option value='Cancelled' " . ($status == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                        <option value='Completed' " . ($status == 'Completed' ? 'selected' : '') . ">Completed</option>
                                    </select>
                                    <input type='hidden' name='update_status' value='1'>
                                </form>
                            </td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='delete_id' value='{$row['ReservationID']}'>
                                    <button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this reservation?\")'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No reservations found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>