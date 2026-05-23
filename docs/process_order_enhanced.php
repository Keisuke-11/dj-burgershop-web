<?php
/**
 * ENHANCED ORDER PROCESSING - FIXED VERSION
 * Properly handles delivery options and order status
 * Uses OrderStatus only (WebsiteStatus column removed)
 */

require_once(__DIR__ . '/api/config/db_config.php');
require_once(__DIR__ . '/api/functions/activity_logger.php');

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/order_error.log');

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        die(json_encode(["success" => false, "message" => "POST required"]));
    }

    // Get form data
    $customer_id = $_POST['customer_id'] ?? null;
    $total_amount = $_POST['total_amount'] ?? 0.00;
    $order_type_raw = $_POST['order_type'] ?? 'PICKUP';
    $payment_method_raw = $_POST['payment_method'] ?? 'COD';
    $cart_data_json = $_POST['cart_data'] ?? '[]';
    $special_requests = $_POST['special_requests'] ?? null;
    $delivery_address = $_POST['address'] ?? null;
    $transaction_id = $_POST['transaction_id'] ?? null;

    // Validate cart data
    $cart_items = json_decode($cart_data_json, true);
    if (empty($customer_id) || !is_numeric($customer_id) || $customer_id <= 0 || !is_array($cart_items) || count($cart_items) === 0) {
        http_response_code(400);
        die(json_encode(["success" => false, "message" => "Missing customer ID or empty cart"]));
    }

    // Map order type to database enum
    // OrderType enum: 'Dine-in','Takeout','Online'
    $order_type_db = 'Online';

    // OrderSource enum: 'POS','Website'
    $order_source_db = 'Website';

    // Set DeliveryOption enum: 'Delivery','Pickup'
    $delivery_option_db = null;
    if (strtoupper($order_type_raw) === 'DELIVERY') {
        $delivery_option_db = 'Delivery';

        // Validate delivery address is provided
        if (empty($delivery_address)) {
            http_response_code(400);
            die(json_encode(["success" => false, "message" => "Delivery address required for delivery orders"]));
        }
    } else {
        $delivery_option_db = 'Pickup';
        $delivery_address = null; // Clear address for pickup orders
    }

    // Map payment method
    $payment_method_db = ($payment_method_raw === 'GCASH') ? 'GCash' : 'COD';

    // Handle GCash receipt upload
    $receipt_path = null;
    $receipt_filename = null;

    if ($payment_method_raw === 'GCASH' && isset($_FILES['gcash_receipt']) && $_FILES['gcash_receipt']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['gcash_receipt'];

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_types)) {
            http_response_code(400);
            die(json_encode(["success" => false, "message" => "Invalid image type"]));
        }

        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            die(json_encode(["success" => false, "message" => "File too large (max 5MB)"]));
        }

        // Create upload directory
        $upload_dir = __DIR__ . '/uploads/order_receipts/' . date('Y') . '/' . date('m') . '/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $receipt_filename = 'order_' . $customer_id . '_' . time() . '_' . rand(1000, 9999) . '.jpg';
        $full_path = $upload_dir . $receipt_filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $full_path)) {
            $receipt_path = 'uploads/order_receipts/' . date('Y') . '/' . date('m') . '/' . $receipt_filename;
        } else {
            error_log("Failed to move uploaded file");
        }
    }

    // ----------------------------------------------------------------
    // SERVER-SIDE STOCK VALIDATION
    // Check each cart item against actual ingredient inventory BEFORE
    // inserting anything into the database.
    // ----------------------------------------------------------------
    $stockCheckSql = "
        SELECT 
            p.ProductName,
            COALESCE(MIN(floor_calc.ServingsLeft), 9999) AS MinServings
        FROM products p
        LEFT JOIN (
            SELECT 
                pi.ProductID,
                FLOOR(COALESCE(SUM(b.StockQuantity), 0) / pi.QuantityUsed) AS ServingsLeft
            FROM product_ingredients pi
            JOIN ingredients i ON pi.IngredientID = i.IngredientID
            LEFT JOIN inventory_batches b ON i.IngredientID = b.IngredientID AND b.BatchStatus = 'Active'
            WHERE pi.QuantityUsed > 0
            GROUP BY pi.ProductID, pi.IngredientID
        ) floor_calc ON p.ProductID = floor_calc.ProductID
        WHERE p.ProductID = ?
        GROUP BY p.ProductID, p.ProductName
    ";

    $stmt_stock = $conn->prepare($stockCheckSql);
    if (!$stmt_stock) {
        http_response_code(500);
        die(json_encode(["success" => false, "message" => "Stock check prepare failed: " . $conn->error]));
    }

    foreach ($cart_items as $item) {
        $product_id  = intval($item['id']      ?? 0);
        $ordered_qty = intval($item['quantity'] ?? 1);
        $item_name   = htmlspecialchars($item['name'] ?? "Product #$product_id");

        $stmt_stock->bind_param('i', $product_id);
        $stmt_stock->execute();
        $stock_result = $stmt_stock->get_result();
        $stock_row    = $stock_result->fetch_assoc();
        $stock_result->free();

        if ($stock_row === null) {
            // Product not found in DB at all
            http_response_code(400);
            $stmt_stock->close();
            die(json_encode(["success" => false, "message" => "Product not found: \"$item_name\""]));
        }

        $min_servings = intval($stock_row['MinServings']);
        $product_name = $stock_row['ProductName'];

        if ($min_servings <= 0) {
            http_response_code(400);
            $stmt_stock->close();
            die(json_encode([
                "success" => false,
                "message" => "\"$product_name\" is currently out of stock and cannot be ordered."
            ]));
        }

        if ($ordered_qty > $min_servings) {
            http_response_code(400);
            $stmt_stock->close();
            die(json_encode([
                "success" => false,
                "message" => "Insufficient stock for \"$product_name\". You ordered $ordered_qty but only $min_servings are available."
            ]));
        }
    }
    $stmt_stock->close();

    // Start transaction
    $conn->begin_transaction();

    try {
        // Set OrderStatus to 'Preparing' for all new orders
        $order_status = 'Preparing';

        // Insert into orders table (Strictly matching SQL schema)
        $items_count = count($cart_items);
        $order_type_db = 'Take out'; // Enum only allows 'Dine-in','Take out'
        
        // Combine extra info into Remarks
        $delivery_info = ($delivery_option_db === 'Delivery') ? "DELIVERY to: $delivery_address" : "PICKUP";
        $combined_remarks = "Source: Website | $delivery_info | Requests: $special_requests";

        $sql_order = "INSERT INTO orders 
                      (CustomerID, OrderType, OrderDate, OrderTime, ItemsOrderedCount, TotalAmount, OrderStatus, Remarks) 
                      VALUES (?, ?, CURDATE(), CURTIME(), ?, ?, ?, ?)";

        $stmt_order = $conn->prepare($sql_order);
        if ($stmt_order === false) {
            throw new Exception("SQL Prepare Failed for orders: " . $conn->error);
        }

        $stmt_order->bind_param(
            "isidss",
            $customer_id,
            $order_type_db,
            $items_count,
            $total_amount,
            $order_status,         // 'Preparing'
            $combined_remarks
        );

        if (!$stmt_order->execute()) {
            throw new Exception("Order insert failed: " . $stmt_order->error);
        }

        $order_id = $conn->insert_id;
        $stmt_order->close();

        // Insert order items
        // Insert order details
        $sql_item = "INSERT INTO orderdetails (OrderID, ProductID, Quantity, UnitPrice, TotalPrice) 
                     VALUES (?, ?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);

        if ($stmt_item === false) {
            throw new Exception("SQL Prepare Failed for orderdetails: " . $conn->error);
        }

        foreach ($cart_items as $item) {
            $product_id = $item['id'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $price = $item['price'] ?? 0.00;
            $item_total = $quantity * $price;

            $stmt_item->bind_param("iiidd", $order_id, $product_id, $quantity, $price, $item_total);

            if (!$stmt_item->execute()) {
                throw new Exception("Order detail insert failed: " . $stmt_item->error);
            }
        }
        $stmt_item->close();

        // Insert payment record
        $sql_payment = "INSERT INTO payment 
                        (OrderID, PaymentMethod, AmountPaid, PaymentStatus, PaymentSource, 
                         ProofOfPayment, ReceiptFileName, Notes, TransactionID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_payment = $conn->prepare($sql_payment);
        if ($stmt_payment === false) {
            throw new Exception("SQL Prepare Failed for payment: " . $conn->error);
        }

        $payment_status = 'Preparing'; // Always Preparing for new orders
        $payment_notes = ($payment_method_raw === 'GCASH') ? 'GCash receipt uploaded - awaiting verification' : 'Cash on Delivery';

        $stmt_payment->bind_param(
            "isdssssss",
            $order_id,
            $payment_method_db,
            $total_amount,
            $payment_status,
            $order_source_db,
            $receipt_path,
            $receipt_filename,
            $payment_notes,
            $transaction_id
        );

        if (!$stmt_payment->execute()) {
            throw new Exception("Payment insert failed: " . $stmt_payment->error);
        }
        $stmt_payment->close();

        // Update customer order count
        try {
            $stmt_proc = $conn->prepare("CALL IncrementCustomerOrderCount(?)");
            if ($stmt_proc) {
                $stmt_proc->bind_param("i", $customer_id);
                $stmt_proc->execute();
                $stmt_proc->close();

                while ($conn->more_results()) {
                    $conn->next_result();
                }
            } else {
                // Fallback: Direct update
                $update_sql = "UPDATE customer 
                              SET TotalOrdersCount = TotalOrdersCount + 1,
                                  LastTransactionDate = NOW()
                              WHERE CustomerID = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $customer_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        } catch (Exception $e) {
            error_log("Customer count update failed: " . $e->getMessage());

            $update_sql = "UPDATE customer 
                          SET TotalOrdersCount = TotalOrdersCount + 1,
                              LastTransactionDate = NOW()
                          WHERE CustomerID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $customer_id);
            $update_stmt->execute();
            $update_stmt->close();
        }

        // Commit transaction
        $conn->commit();

        // ----------------------------------------------------------------
        // POST-COMMIT: Deduct ingredients from inventory (FIFO)
        // and log each deduction to inventory_movement so it appears
        // in the Admin "View Usage History" screen.
        // This runs OUTSIDE the main order transaction so a stock error
        // doesn't roll back a successfully paid order.
        // ----------------------------------------------------------------
        try {
            foreach ($cart_items as $item) {
                $product_id  = intval($item['id']      ?? 0);
                $ordered_qty = intval($item['quantity'] ?? 1);
                $item_name   = $item['name'] ?? "Product #$product_id";
                if ($product_id <= 0 || $ordered_qty <= 0) continue;

                // Fetch product ingredients
                $sql_ing = "
                    SELECT pi.IngredientID, pi.QuantityUsed, pi.UnitType
                    FROM product_ingredients pi
                    WHERE pi.ProductID = ?
                ";
                $stmt_ing = $conn->prepare($sql_ing);
                if (!$stmt_ing) continue;
                $stmt_ing->bind_param('i', $product_id);
                $stmt_ing->execute();
                $res_ing = $stmt_ing->get_result();

                while ($ing_row = $res_ing->fetch_assoc()) {
                    $ingredient_id  = intval($ing_row['IngredientID']);
                    $qty_per_item   = floatval($ing_row['QuantityUsed']);
                    $unit_type      = $ing_row['UnitType'];
                    $remaining      = $qty_per_item * $ordered_qty;

                    if ($remaining <= 0) continue;

                    // FIFO: fetch oldest active batches
                    $sql_batches = "
                        SELECT BatchID, StockQuantity
                        FROM inventory_batches
                        WHERE IngredientID = ? AND BatchStatus = 'Active' AND StockQuantity > 0
                        ORDER BY PurchaseDate ASC, BatchID ASC
                    ";
                    $stmt_b = $conn->prepare($sql_batches);
                    if (!$stmt_b) continue;
                    $stmt_b->bind_param('i', $ingredient_id);
                    $stmt_b->execute();
                    $res_b = $stmt_b->get_result();
                    $batches = $res_b->fetch_all(MYSQLI_ASSOC);
                    $stmt_b->close();

                    foreach ($batches as $batch) {
                        if ($remaining <= 0) break;

                        $batch_id   = intval($batch['BatchID']);
                        $stock_now  = floatval($batch['StockQuantity']);
                        $deduct     = min($stock_now, $remaining);
                        $stock_after = $stock_now - $deduct;

                        // Update batch stock
                        $new_status = ($stock_after <= 0) ? 'Depleted' : 'Active';
                        $sql_upd = "UPDATE inventory_batches
                                    SET StockQuantity = StockQuantity - ?,
                                        BatchStatus   = ?
                                    WHERE BatchID = ?";
                        $stmt_upd = $conn->prepare($sql_upd);
                        if ($stmt_upd) {
                            $stmt_upd->bind_param('dsi', $deduct, $new_status, $batch_id);
                            $stmt_upd->execute();
                            $stmt_upd->close();
                        }

                        // Log to inventory_movement
                        $reason  = "Website Order #$order_id - $item_name";
                        $neg_qty = -$deduct;
                        $sql_log = "INSERT INTO inventory_movement
                                    (IngredientID, BatchID, ChangeType, QuantityChanged,
                                     StockBefore, StockAfter, UnitType, Reason,
                                     Source, OrderID, MovementDate)
                                    VALUES (?, ?, 'DEDUCT', ?, ?, ?, ?, ?, 'WEBSITE', ?, NOW())";
                        $stmt_log = $conn->prepare($sql_log);
                        if ($stmt_log) {
                            $stmt_log->bind_param(
                                'iidddssi',
                                $ingredient_id, $batch_id, $neg_qty,
                                $stock_now, $stock_after, $unit_type, $reason,
                                $order_id
                            );
                            $stmt_log->execute();
                            $stmt_log->close();
                        }

                        $remaining -= $deduct;
                    }
                }

                $stmt_ing->close();
            }
        } catch (Exception $inv_ex) {
            // Non-fatal: order is already committed, just log the stock error
            error_log("Inventory deduction warning for Order #$order_id: " . $inv_ex->getMessage());
        }

        // Log order activity
        $customer_name = $_POST['name'] ?? 'Customer';
        logCustomerOrderPlaced(
            $conn,
            $customer_id,
            $customer_name,
            $order_id,
            $total_amount,
            $delivery_option_db
        );

        error_log("SUCCESS: Order #$order_id created - Status: $order_status, Delivery: $delivery_option_db");

        ob_clean();
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Order placed successfully!",
            "order_id" => $order_id,
            "total_amount" => $total_amount,
            "payment_method" => $payment_method_db,
            "order_status" => $order_status,
            "delivery_option" => $delivery_option_db
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log("TRANSACTION ERROR: " . $e->getMessage());

        ob_clean();
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
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