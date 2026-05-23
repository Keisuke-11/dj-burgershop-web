<?php
function deleteReservation($conn, $delete_id) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete related entries in ALL connected tables
        $tables_to_delete = [
            'reservation_items' => 'ReservationID',
            'reservation' => 'ReservationID'
        ];
        
        foreach ($tables_to_delete as $table => $id_col) {
            $delete_query = "DELETE FROM $table WHERE $id_col = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $delete_id);
            $stmt->execute();
            $stmt->close();
        }
        
        // Commit transaction
        $conn->commit();
        
        // Reset auto-increment across all tables
        resetAllAutoIncrements($conn);
        
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Delete failed: " . $e->getMessage();
        return false;
    }
}

function resetAllAutoIncrements($conn) {
    $tables = ['reservation' => 'ReservationID', 'reservation_items' => 'ReservationItemID'];
    
    foreach ($tables as $table => $id_col) {
        // Find the maximum existing ID
        $max_id_result = $conn->query("SELECT MAX($id_col) as max_id FROM $table");
        $max_id_row = $max_id_result->fetch_assoc();
        $max_id = $max_id_row['max_id'] ? $max_id_row['max_id'] : 0;

        // Reset the auto-increment to the maximum ID + 1
        $conn->query("ALTER TABLE $table AUTO_INCREMENT = " . ($max_id + 1));
    }
}
?>