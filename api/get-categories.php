<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT Category_ID, Category_Name FROM Categories ORDER BY Category_Name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($categories) > 0) {
        echo json_encode($categories);
    } else {
        echo json_encode(['error' => 'No categories found']);
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode(['error' => 'An error occurred']);
}
?>
