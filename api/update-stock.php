<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['productId'];
    $quantity = $data['quantity'];
    $transactionType = $data['transactionType'];
    $remarks = $data['remarks'];

    // Start transaction
    $db->beginTransaction();

    try {
        // Update Products table
        $updateProductSql = "UPDATE Products SET Quantity_In_Stock = Quantity_In_Stock " . 
            ($transactionType === 'Purchase' ? '+' : '-') . " :quantity WHERE Product_ID = :productId";
        
        $updateProductStmt = $db->prepare($updateProductSql);
        $updateProductStmt->execute([
            ':quantity' => $quantity,
            ':productId' => $productId
        ]);

        // Insert into Inventory_Transactions table
        $insertTransactionSql = "INSERT INTO Inventory_Transactions 
            (Product_ID, Transaction_Type, Quantity, Transaction_Date, Remarks) 
            VALUES (:productId, :transactionType, :quantity, CURDATE(), :remarks)";
        
        $insertTransactionStmt = $db->prepare($insertTransactionSql);
        $insertTransactionStmt->execute([
            ':productId' => $productId,
            ':transactionType' => $transactionType,
            ':quantity' => $quantity,
            ':remarks' => $remarks
        ]);

        // Check if stock would go negative for sales
        if ($transactionType === 'Sale') {
            $checkStockSql = "SELECT Quantity_In_Stock FROM Products WHERE Product_ID = :productId";
            $checkStockStmt = $db->prepare($checkStockSql);
            $checkStockStmt->execute([':productId' => $productId]);
            $currentStock = $checkStockStmt->fetchColumn();

            if ($currentStock < 0) {
                throw new Exception("Insufficient stock available");
            }
        }

        // Commit transaction
        $db->commit();
        
        echo json_encode(['success' => true, 'message' => 'Stock updated successfully']);
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
