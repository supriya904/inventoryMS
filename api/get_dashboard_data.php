<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $response = [
        'summary' => [],
        'recentTransactions' => [],
        'lowStockProducts' => [],
        'categoryDistribution' => []
    ];
    
    // Get summary statistics
    $summaryQuery = "SELECT 
        (SELECT COUNT(*) FROM Products) as totalProducts,
        (SELECT COUNT(*) FROM Products WHERE Quantity_In_Stock < 10) as lowStockCount,
        (SELECT COUNT(*) FROM Inventory_Transactions WHERE Transaction_Date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as weeklyTransactions,
        (SELECT SUM(Quantity * Price_Per_Unit) FROM Products) as totalInventoryValue";
    
    $stmt = $conn->query($summaryQuery);
    $response['summary'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get recent transactions
    $recentTransactionsQuery = "SELECT 
        t.Transaction_ID,
        p.Product_Name,
        t.Transaction_Type,
        t.Quantity,
        t.Transaction_Date,
        TIMESTAMPDIFF(HOUR, t.Transaction_Date, NOW()) as hours_ago,
        TIMESTAMPDIFF(DAY, t.Transaction_Date, NOW()) as days_ago
        FROM Inventory_Transactions t
        JOIN Products p ON t.Product_ID = p.Product_ID
        ORDER BY t.Transaction_Date DESC
        LIMIT 5";
    
    $stmt = $conn->query($recentTransactionsQuery);
    $response['recentTransactions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get low stock products
    $lowStockQuery = "SELECT 
        Product_Name,
        Quantity_In_Stock,
        Price_Per_Unit
        FROM Products 
        WHERE Quantity_In_Stock < 10
        ORDER BY Quantity_In_Stock ASC
        LIMIT 5";
    
    $stmt = $conn->query($lowStockQuery);
    $response['lowStockProducts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get category distribution
    $categoryDistQuery = "SELECT 
        c.Category_Name,
        COUNT(p.Product_ID) as product_count,
        SUM(p.Quantity_In_Stock) as total_stock,
        SUM(p.Quantity_In_Stock * p.Price_Per_Unit) as category_value
        FROM Categories c
        LEFT JOIN Products p ON c.Category_ID = p.Category_ID
        GROUP BY c.Category_ID
        ORDER BY category_value DESC";
    
    $stmt = $conn->query($categoryDistQuery);
    $response['categoryDistribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
