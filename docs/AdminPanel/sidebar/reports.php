<?php
// Database connection
require_once 'db_connection.php';

// Sales Report Query (Combining orders and reservations)
$sales_query = "
    SELECT 
        ProductName as product_name, 
        SUM(Quantity) as total_quantity, 
        SUM(TotalPrice) as total_sales,
        'Order' as source
    FROM 
        orderdetails od
    JOIN products p ON od.ProductID = p.ProductID
    GROUP BY 
        ProductName
    UNION ALL
    SELECT 
        ProductName as product_name, 
        SUM(Quantity) as total_quantity, 
        SUM(TotalPrice) as total_sales,
        'Reservation' as source
    FROM 
        reservation_items
    GROUP BY 
        ProductName
    ORDER BY 
        total_sales DESC
";
$sales_result = $conn->query($sales_query);

// Grouped query for the table (summing by product)
$grouped_sales_query = "
    SELECT 
        product_name,
        SUM(total_quantity) as total_quantity,
        SUM(total_sales) as total_sales
    FROM (
        SELECT ProductName as product_name, Quantity as total_quantity, TotalPrice as total_sales FROM orderdetails od JOIN products p ON od.ProductID = p.ProductID
        UNION ALL
        SELECT ProductName as product_name, Quantity as total_quantity, TotalPrice as total_sales FROM reservation_items
    ) as combined
    GROUP BY product_name
    ORDER BY total_sales DESC
";
$grouped_sales_result = $conn->query($grouped_sales_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="../CSS/reportsDesign.css">
    <style>
        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    
    <!-- Sales Report -->
    <div class="report-section">
        <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Quantity</th>
                <th>Total Sales (₱)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_overall_sales = 0;
            if ($grouped_sales_result && $grouped_sales_result->num_rows > 0) {
                while ($row = $grouped_sales_result->fetch_assoc()) { 
                    $total_overall_sales += $row['total_sales'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo number_format($row['total_quantity'], 0); ?></td>
                        <td><?php echo number_format($row['total_sales'], 2); ?></td>
                    </tr>
            <?php } ?>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td>Total</td>
                    <td></td>
                    <td><?php echo number_format($total_overall_sales, 2); ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="3">No sales history available</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>