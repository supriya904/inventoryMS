<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Get date range parameters
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
    
    // Prepare response array
    $response = [
        'summary' => [],
        'priceAnalysis' => [],
        'transactions' => []
    ];
    
    // Get summary data
    $summaryQuery = "SELECT 
        COUNT(*) as totalTransactions,
        SUM(CASE WHEN t.Transaction_Type = 'Purchase' THEN t.Quantity * p.Price_Per_Unit ELSE 0 END) as totalPurchase,
        SUM(CASE WHEN t.Transaction_Type = 'Sale' THEN t.Quantity * p.Price_Per_Unit ELSE 0 END) as totalSale,
        (SELECT COUNT(*) FROM Products WHERE Quantity_In_Stock < 10) as lowStockCount
        FROM Inventory_Transactions t
        JOIN Products p ON t.Product_ID = p.Product_ID
        WHERE t.Transaction_Date BETWEEN :startDate AND :endDate";
        
    $stmt = $conn->prepare($summaryQuery);
    $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
    $response['summary'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get price analysis data (daily totals of purchase and sale values)
    $priceAnalysisQuery = "SELECT 
        t.Transaction_Date as date,
        SUM(CASE WHEN t.Transaction_Type = 'Purchase' THEN t.Quantity * p.Price_Per_Unit ELSE 0 END) as purchase_value,
        SUM(CASE WHEN t.Transaction_Type = 'Sale' THEN t.Quantity * p.Price_Per_Unit ELSE 0 END) as sale_value
        FROM Inventory_Transactions t
        JOIN Products p ON t.Product_ID = p.Product_ID
        WHERE t.Transaction_Date BETWEEN :startDate AND :endDate
        GROUP BY t.Transaction_Date
        ORDER BY t.Transaction_Date";
        
    $stmt = $conn->prepare($priceAnalysisQuery);
    $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
    $priceData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format price analysis data for chart
    $response['priceAnalysis'] = [
        'dates' => array_column($priceData, 'date'),
        'purchaseValues' => array_map('floatval', array_column($priceData, 'purchase_value')),
        'saleValues' => array_map('floatval', array_column($priceData, 'sale_value'))
    ];
    
    // Get detailed transactions
    $transactionsQuery = "SELECT 
        t.Transaction_Date as date,
        p.Product_Name as product,
        t.Transaction_Type as type,
        t.Quantity as quantity,
        (t.Quantity * p.Price_Per_Unit) as value
        FROM Inventory_Transactions t
        JOIN Products p ON t.Product_ID = p.Product_ID
        WHERE t.Transaction_Date BETWEEN :startDate AND :endDate
        ORDER BY t.Transaction_Date DESC
        LIMIT 100";
        
    $stmt = $conn->prepare($transactionsQuery);
    $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
    $response['transactions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
