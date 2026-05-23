<?php
/**
 * Create Reservation API
 * Handles saving reservation, items, and initial payment.
 */

require_once(__DIR__ . '/../config/db_config.php');
require_once(__DIR__ . '/../functions/activity_logger.php');

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('POST method required');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        // Fallback to $_POST for FormData submissions (like receipts)
        $data = $_POST;
    }

    $customerId = isset($data['customer_id']) ? intval($data['customer_id']) : 0;
    $eventDate = $data['event_date'] ?? '';
    $eventTime = $data['event_time'] ?? '';
    $guests = isset($data['guests']) ? intval($data['guests']) : 0;
    $specialRequests = $data['special_requests'] ?? '';
    $totalPrice = isset($data['total_price']) ? floatval($data['total_price']) : 0;
    $items = isset($data['selected_products']) ? json_decode($data['selected_products'], true) : [];
    
    // Additional fields for Notes
    $eventType = $data['event_type'] ?? '';
    $phone = $data['customer_phone'] ?? '';
    $deliveryOption = $data['delivery_option'] ?? 'Pickup';
    $deliveryAddress = $data['delivery_address'] ?? '';
    
    $notes = "Event: $eventType | Phone: $phone | Delivery: $deliveryOption | Address: $deliveryAddress";

    $conn->begin_transaction();

    // 1. Insert Reservation
    $sql = "INSERT INTO reservation 
            (CustomerID, ReservationDate, ReservationTime, NumberOfCustomers, SpecialRequests, ReservationStatus, SeatType, Notes)
            VALUES (?, ?, ?, ?, ?, 'Pending', 'Indoor', ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);
    
    $stmt->bind_param("ississ", $customerId, $eventDate, $eventTime, $guests, $specialRequests, $notes);
    if (!$stmt->execute()) throw new Exception($stmt->error);
    
    $reservationId = $conn->insert_id;
    $stmt->close();

    // 2. Insert Items
    $itemSql = "INSERT INTO reservation_items (ReservationID, ProductID, ProductName, Quantity, UnitPrice, TotalPrice) 
                VALUES (?, ?, ?, ?, ?, ?)";
    $itemStmt = $conn->prepare($itemSql);
    
    foreach ($items as $item) {
        $pId = intval($item['id']);
        $pName = $item['name'];
        $pQty = intval($item['quantity']);
        $pPrice = floatval($item['price']);
        $pTotal = $pQty * $pPrice;
        
        $itemStmt->bind_param("iisidd", $reservationId, $pId, $pName, $pQty, $pPrice, $pTotal);
        if (!$itemStmt->execute()) throw new Exception($itemStmt->error);
    }
    $itemStmt->close();

    // 3. Insert Payment (Initial record)
    $paySql = "INSERT INTO reservationpayment (ReservationID, PaymentMethod, PaymentStatus, AmountPaid)
               VALUES (?, 'Cash', 'Pending', ?)";
    $payStmt = $conn->prepare($paySql);
    $payStmt->bind_param("id", $reservationId, $totalPrice);
    if (!$payStmt->execute()) throw new Exception($payStmt->error);
    $payStmt->close();

    // 4. Update Customer Count
    $updateSql = "UPDATE customer SET ReservationCount = ReservationCount + 1, LastTransactionDate = NOW() WHERE CustomerID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $customerId);
    $updateStmt->execute();
    $updateStmt->close();

    // 5. Log Activity
    logCustomerReservation($conn, $customerId, $data['customer_name'] ?? 'Customer', $reservationId, $eventType, $eventDate, $guests);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'status' => 'success', // For compatibility with existing UI
        'message' => 'Reservation created successfully',
        'reservationId' => $reservationId
    ]);

} catch (Exception $e) {
    if (isset($conn)) $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>