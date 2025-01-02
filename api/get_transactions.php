<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

try {
    // Simple query to get all transactions with product names
    $query = "SELECT t.Transaction_ID, p.Product_Name, t.Transaction_Type, 
              t.Quantity, t.Transaction_Date, t.Remarks 
              FROM Inventory_Transactions t
              JOIN Products p ON t.Product_ID = p.Product_ID
              ORDER BY t.Transaction_Date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($transactions);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
