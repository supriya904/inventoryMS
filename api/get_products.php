<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config.php';

$database = new Database();
$db = $database->getConnection();

try {
    $query = "SELECT p.*, c.Category_Name 
              FROM Products p 
              JOIN Categories c ON p.Category_ID = c.Category_ID 
              ORDER BY c.Category_Name, p.Product_Name";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $products = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = array(
            "id" => $row['Product_ID'],
            "name" => $row['Product_Name'],
            "category" => $row['Category_Name'],
            "quantity" => $row['Quantity_In_Stock'],
            "price" => $row['Price_Per_Unit'],
            "image" => $row['imageAddress']
        );
    }
    
    echo json_encode(array(
        "status" => true,
        "data" => $products
    ));
} catch(PDOException $e) {
    echo json_encode(array(
        "status" => false,
        "message" => "Error: " . $e->getMessage()
    ));
}
?>