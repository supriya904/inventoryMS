<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';
require_once 'error_logger.php';

$logger = new ErrorLogger();

try {
    $logger->log("Starting dashboard data fetch", "INFO");
    
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        $logger->log("Database connection failed", "ERROR");
        throw new Exception('Database connection failed');
    }
    
    $logger->log("Database connection successful", "INFO");
    
    $response = [
        'summary' => [],
        'recentTransactions' => [],
        'categoryDistribution' => []
    ];
    
    // Get summary statistics
    $logger->log("Executing summary query", "INFO");
    $summaryQuery = "SELECT 
        COUNT(*) as totalProducts,
        COALESCE(SUM(Quantity_In_Stock * Price_Per_Unit), 0) as totalInventoryValue
        FROM Products";
    
    $stmt = $conn->query($summaryQuery);
    if (!$stmt) {
        $logger->log("Summary query failed", "ERROR", ['query' => $summaryQuery]);
        throw new Exception('Failed to execute summary query');
    }
    
    $response['summary'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $logger->log("Summary data fetched", "INFO", ['data' => $response['summary']]);
    
    if (!$response['summary']) {
        $response['summary'] = ['totalProducts' => 0, 'totalInventoryValue' => 0];
        $logger->log("No summary data found, using defaults", "WARNING");
    }
    
    // Get recent transactions
    $logger->log("Executing recent transactions query", "INFO");
    $recentTransactionsQuery = "SELECT 
        t.Transaction_ID,
        p.Product_Name,
        t.Transaction_Type,
        t.Quantity,
        t.Transaction_Date,
        t.Remarks
        FROM Inventory_Transactions t
        JOIN Products p ON t.Product_ID = p.Product_ID
        ORDER BY t.Transaction_Date DESC
        LIMIT 5";
    
    $stmt = $conn->query($recentTransactionsQuery);
    if (!$stmt) {
        $logger->log("Recent transactions query failed", "ERROR", ['query' => $recentTransactionsQuery]);
        throw new Exception('Failed to execute recent transactions query');
    }
    
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $logger->log("Recent transactions data fetched", "INFO", ['data' => $transactions]);
    
    // Calculate time ago for each transaction
    foreach ($transactions as &$transaction) {
        $transactionDate = strtotime($transaction['Transaction_Date']);
        $now = time();
        $diff = $now - $transactionDate;
        
        $transaction['hours_ago'] = floor($diff / 3600);
        $transaction['days_ago'] = floor($diff / (3600 * 24));
    }
    
    $response['recentTransactions'] = $transactions;
    
    // Get category distribution
    $logger->log("Executing category distribution query", "INFO");
    $categoryDistQuery = "SELECT 
        c.Category_Name,
        COUNT(p.Product_ID) as product_count,
        SUM(p.Quantity_In_Stock) as total_stock,
        SUM(p.Quantity_In_Stock * p.Price_Per_Unit) as category_value
        FROM Categories c
        LEFT JOIN Products p ON c.Category_ID = p.Category_ID
        GROUP BY c.Category_ID, c.Category_Name
        ORDER BY category_value DESC";
    
    $stmt = $conn->query($categoryDistQuery);
    if (!$stmt) {
        $logger->log("Category distribution query failed", "ERROR", ['query' => $categoryDistQuery]);
        throw new Exception('Failed to execute category distribution query');
    }
    
    $response['categoryDistribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $logger->log("Category distribution data fetched", "INFO", ['data' => $response['categoryDistribution']]);
    
    echo json_encode($response);
    $logger->log("Response sent successfully", "INFO");
    
} catch (PDOException $e) {
    $logger->logException($e);
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    $logger->logException($e);
    http_response_code(500);
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
