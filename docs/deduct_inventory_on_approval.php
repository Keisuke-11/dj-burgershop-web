<?php
/**
 * DEDUCT INVENTORY ON RESERVATION APPROVAL
 * Call this when admin approves a reservation
 * This deducts ingredients from inventory based on reservation items
 */

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/inventory_deduction.log');

header('Content-Type: application/json; charset=utf-8');

 require_once(__DIR__ . '/api/config/db_config.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die(json_encode(["success" => false, "message" => "POST required"]));
    }

    // $conn is already established in db_config.php
    $conn->set_charset("utf8mb4");

    // Get reservation ID from request
    $input = json_decode(file_get_contents('php://input'), true);
    $reservationId = isset($input['reservation_id']) ? intval($input['reservation_id']) : 0;
    
    if (!$reservationId && isset($_POST['reservation_id'])) {
        $reservationId = intval($_POST['reservation_id']);
    }

    if ($reservationId <= 0) {
        http_response_code(400);
        die(json_encode(["success" => false, "message" => "Invalid reservation ID"]));
    }

    // Check if reservation exists and is being approved
    $checkSql = "SELECT ReservationID, ReservationStatus FROM reservation WHERE ReservationID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $reservationId);
    $checkStmt->execute();
    $reservation = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    if (!$reservation) {
        http_response_code(404);
        die(json_encode(["success" => false, "message" => "Reservation not found"]));
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get all items in this reservation
        $itemsSql = "SELECT ri.ProductID, ri.Quantity, ri.ProductName 
                     FROM reservation_items ri 
                     WHERE ri.ReservationID = ?";
        $itemsStmt = $conn->prepare($itemsSql);
        $itemsStmt->bind_param("i", $reservationId);
        $itemsStmt->execute();
        $items = $itemsStmt->get_result();

        $deductedItems = [];
        $errors = [];

        while ($item = $items->fetch_assoc()) {
            $productId = $item['ProductID'];
            $productName = $item['ProductName'];
            $orderQty = intval($item['Quantity']);

            // Deduct directly from inventory table for this product
            $deductSql = "UPDATE inventory SET StockQuantity = StockQuantity - ? WHERE ProductID = ?";
            $deductStmt = $conn->prepare($deductSql);
            $deductStmt->bind_param("ii", $orderQty, $productId);
            
            if ($deductStmt->execute() && $deductStmt->affected_rows > 0) {
                $deductedItems[] = [
                    'product' => $productName,
                    'deducted' => $orderQty
                ];
            } else {
                $errors[] = "Failed to deduct inventory for product: $productName (maybe not tracked in inventory)";
            }
            $deductStmt->close();
        }
        $itemsStmt->close();

        // Update reservation status to Confirmed
        $updateSql = "UPDATE reservation SET ReservationStatus = 'Confirmed' WHERE ReservationID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $reservationId);
        $updateStmt->execute();
        $updateStmt->close();

        $conn->commit();

        error_log("SUCCESS: Inventory deducted for reservation #$reservationId");

        ob_clean();
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Reservation approved and inventory deducted",
            "reservation_id" => $reservationId,
            "deductions" => $deductedItems,
            "errors" => $errors
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log("TRANSACTION ERROR: " . $e->getMessage());
        
        ob_clean();
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Failed to process: " . $e->getMessage()
        ]);
    }

    $conn->close();

} catch (Exception $e) {
    error_log("FATAL ERROR: " . $e->getMessage());
    
    ob_clean();
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}

ob_end_flush();
?>