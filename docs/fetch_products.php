<?php
/**
 * FETCH PRODUCTS WITH INGREDIENT AVAILABILITY + RATINGS
 * This version checks the product_ingredients table to determine stock status.
 */

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Database config
require_once(__DIR__ . '/api/config/db_config.php');
header('Content-Type: application/json; charset=utf-8');

try {
    $conn->set_charset("utf8mb4");

    // ----------------------------------------------------------------
    // Step 1: Fetch all Available products
    // ----------------------------------------------------------------
    $sql = "SELECT 
                p.ProductID, 
                p.ProductName, 
                p.Category, 
                p.Description, 
                p.Price, 
                p.Availability, 
                p.ServingSize, 
                p.Image, 
                p.PopularityTag,
                p.OrderCount,
                p.SpicyLevel,
                CASE 
                    WHEN p.OrderCount >= 100 THEN 5
                    WHEN p.OrderCount >= 75 THEN 4
                    WHEN p.OrderCount >= 50 THEN 3
                    WHEN p.OrderCount >= 25 THEN 2
                    WHEN p.OrderCount > 0 THEN 1
                    ELSE 0
                END AS StarRating
            FROM products p
            WHERE p.Availability = 'Available'
            ORDER BY p.OrderCount DESC, p.Category ASC, p.ProductID ASC";

    $result = $conn->query($sql);
    if (!$result) {
        throw new Exception($conn->error);
    }

    // ----------------------------------------------------------------
    // Step 2: Prepare ingredient check query
    // ----------------------------------------------------------------
    $ingredientCheckSql = "
        SELECT
            pi.ProductID,
            i.IngredientName,
            COALESCE(SUM(b.StockQuantity), 0) AS StockQuantity,
            pi.QuantityUsed,
            CASE
                WHEN pi.QuantityUsed <= 0 THEN 9999
                ELSE FLOOR(COALESCE(SUM(b.StockQuantity), 0) / pi.QuantityUsed)
            END AS ServingsLeft
        FROM product_ingredients pi
        JOIN ingredients i ON pi.IngredientID = i.IngredientID
        LEFT JOIN inventory_batches b ON i.IngredientID = b.IngredientID AND b.BatchStatus = 'Active'
        WHERE pi.ProductID = ?
        GROUP BY pi.ProductID, i.IngredientID
    ";
    $ingredientStmt = $conn->prepare($ingredientCheckSql);

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['ProductID'] = intval($row['ProductID']);
        $row['Price']      = floatval($row['Price']);
        $row['OrderCount'] = intval($row['OrderCount']);
        $row['StarRating'] = intval($row['StarRating']);

        // Check ingredients for this product
        $productID = $row['ProductID'];
        $ingredientStmt->bind_param('i', $productID);
        $ingredientStmt->execute();
        $ingResult = $ingredientStmt->get_result();

        $minServings    = PHP_INT_MAX;
        $hasIngredients = false;

        while ($ing = $ingResult->fetch_assoc()) {
            $hasIngredients = true;
            $servingsLeft   = intval($ing['ServingsLeft']);
            if ($servingsLeft < $minServings) {
                $minServings = $servingsLeft;
            }
        }
        $ingResult->free();

        if (!$hasIngredients) {
            // No ingredients mapped -> treat as available
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'available';
            $row['AvailabilityReason']  = 'In stock';
        } elseif ($minServings <= 0) {
            $row['IngredientAvailable'] = false;
            $row['StockStatus']         = 'out_of_stock';
            $row['AvailabilityReason']  = 'Out of stock';
        } elseif ($minServings <= 10) {
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'low_stock';
            $row['AvailabilityReason']  = 'Low stock - Order soon';
        } else {
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'available';
            $row['AvailabilityReason']  = 'In stock';
        }

        $products[] = $row;
    }
    $ingredientStmt->close();
    $conn->close();

    echo json_encode([
        "success" => true,
        "message" => "Products fetched successfully",
        "count" => count($products),
        "products" => $products
    ]);

    ob_end_flush();
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
    ob_end_flush();
    exit;
}
?>
