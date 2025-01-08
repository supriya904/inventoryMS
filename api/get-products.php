<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $categoryId = isset($_GET['category']) ? $_GET['category'] : null;

    if (!$categoryId) {
        throw new Exception('Category ID is required');
    }

    $query = "SELECT Product_ID, Product_Name, Quantity_In_Stock 
              FROM Products 
              WHERE Category_ID = :categoryId 
              ORDER BY Product_Name";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($products) > 0) {
        echo json_encode($products);
    } else {
        echo json_encode(['error' => 'No products found for this category']);
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>
