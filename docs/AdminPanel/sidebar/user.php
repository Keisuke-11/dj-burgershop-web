<?php
// Database connection
require_once 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Info</title>
    <link rel="stylesheet" href="../CSS/userDesign.css">
</head>
<body>
    <h1>User Info Table</h1>

    <!-- Display User Info -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch data from the customer table
            $result = $conn->query("SELECT * FROM customer ORDER BY CustomerID DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fullName = $row['FirstName'] . ' ' . $row['LastName'];
                    echo "<tr>
                            <td>{$row['CustomerID']}</td>
                            <td>{$fullName}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['ContactNumber']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No customers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
