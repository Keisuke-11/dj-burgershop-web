<?php
/**
 * Unified Products API
 * Fetches products with inventory availability and star ratings.
 */

require_once(__DIR__ . '/../config/db_config.php');

header('Content-Type: application/json; charset=utf-8');

try {
    // ----------------------------------------------------------------
    // Step 1: Fetch all available products
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
    // Step 2: For each product, check ingredient availability.
    //
    // Logic:
    //   - A product is OUT OF STOCK  if ANY required ingredient has
    //     StockQuantity < QuantityUsed (can't make even 1 serving).
    //   - A product is LOW STOCK     if all ingredients are available
    //     but the most-constrained one can only cover <= 10 servings.
    //   - A product is AVAILABLE     otherwise.
    //   - If the product has no ingredient mappings in product_ingredients,
    //     it is treated as AVAILABLE (admin hasn't configured ingredients yet).
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
    if (!$ingredientStmt) {
        throw new Exception('Ingredient check prepare failed: ' . $conn->error);
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['ProductID'] = intval($row['ProductID']);
        $row['Price']      = floatval($row['Price']);
        $row['OrderCount'] = intval($row['OrderCount']);
        $row['StarRating'] = intval($row['StarRating']);

        // Fix image path
        if (!empty($row['Image'])) {
            $img = str_replace('\\', '/', $row['Image']);
            if (stripos($img, 'docs/') === 0) {
                $img = substr($img, strlen('docs/'));
            }
            $row['Image'] = $img;
        }

        // -- Ingredient availability check --
        $productID = $row['ProductID'];
        $ingredientStmt->bind_param('i', $productID);
        $ingredientStmt->execute();
        $ingResult = $ingredientStmt->get_result();

        $minServings    = PHP_INT_MAX;  // minimum servings across all ingredients
        $hasIngredients = false;        // whether any ingredient rows exist

        while ($ing = $ingResult->fetch_assoc()) {
            $hasIngredients = true;
            $servingsLeft   = intval($ing['ServingsLeft']);
            if ($servingsLeft < $minServings) {
                $minServings = $servingsLeft;
            }
        }
        $ingResult->free();

        if (!$hasIngredients) {
            // No ingredient mapping configured by admin → mark available
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'available';
            $row['AvailabilityReason']  = 'No ingredients configured';
            $row['StockQuantity']       = 9999; // uncapped
        } elseif ($minServings <= 0) {
            // At least one ingredient is depleted → cannot make this product
            $row['IngredientAvailable'] = false;
            $row['StockStatus']         = 'out_of_stock';
            $row['AvailabilityReason']  = 'Out of stock';
            $row['StockQuantity']       = 0;
        } elseif ($minServings <= 10) {
            // Can still make it, but running low
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'low_stock';
            $row['AvailabilityReason']  = 'Low stock - Order soon';
            $row['StockQuantity']       = $minServings; // actual cap
        } else {
            $row['IngredientAvailable'] = true;
            $row['StockStatus']         = 'available';
            $row['AvailabilityReason']  = 'In stock';
            $row['StockQuantity']       = $minServings; // actual cap
        }

        $products[] = $row;
    }
    $ingredientStmt->close();

    echo json_encode([
        'success' => true,
        'products' => $products,
        'count' => count($products)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
