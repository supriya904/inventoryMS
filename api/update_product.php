<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';

$database = new Database();
$db = $database->getConnection();

// Handle file upload if new image is provided
function handleImageUpload($file, $category_name, $product_name, $old_image = null) {
    $target_dir = "../assets/";
    $category_dir = $category_name . "/";
    
    // Create category directory if it doesn't exist
    if (!file_exists($target_dir . $category_dir)) {
        mkdir($target_dir . $category_dir, 0777, true);
    }
    
    // Generate unique filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $base_filename = strtolower(str_replace(' ', '-', $product_name));
    $target_file = $target_dir . $category_dir . $base_filename . "." . $file_extension;
    $relative_path = "assets/" . $category_dir . $base_filename . "." . $file_extension;
    
    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($file_extension, $allowed_types)) {
        return array("status" => false, "message" => "Only JPG, JPEG, PNG & GIF files are allowed.");
    }
    
    // Delete old image if it exists and is different from new path
    if ($old_image && file_exists("../" . $old_image) && $old_image != $relative_path) {
        unlink("../" . $old_image);
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return array("status" => true, "path" => $relative_path);
    } else {
        return array("status" => false, "message" => "Error uploading file.");
    }
}

try {
    // Get the current product data
    $query = "SELECT p.*, c.Category_Name 
              FROM Products p 
              JOIN Categories c ON p.Category_ID = c.Category_ID 
              WHERE p.Product_ID = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $_POST['product_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        throw new Exception("Product not found");
    }
    
    $current_product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Handle image upload if new image is provided
    $image_path = $current_product['imageAddress'];
    if (isset($_FILES['product_image']) && $_FILES['product_image']['size'] > 0) {
        $image_result = handleImageUpload(
            $_FILES['product_image'], 
            $_POST['category_name'],
            $_POST['product_name'],
            $current_product['imageAddress']
        );
        if (!$image_result['status']) {
            throw new Exception($image_result['message']);
        }
        $image_path = $image_result['path'];
    }
    
    // Get category ID
    $category_query = "SELECT Category_ID FROM Categories WHERE Category_Name = :category_name";
    $stmt = $db->prepare($category_query);
    $stmt->bindParam(":category_name", $_POST['category_name']);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        throw new Exception("Invalid category");
    }
    
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Start transaction
    $db->beginTransaction();
    
    // Update product
    $query = "UPDATE Products 
              SET Product_Name = :name,
                  Category_ID = :category_id,
                  Quantity_In_Stock = :quantity,
                  Price_Per_Unit = :price,
                  imageAddress = :image
              WHERE Product_ID = :id";
    
    $stmt = $db->prepare($query);
    
    // Bind values
    $stmt->bindParam(":name", $_POST['product_name']);
    $stmt->bindParam(":category_id", $category['Category_ID']);
    $stmt->bindParam(":quantity", $_POST['quantity']);
    $stmt->bindParam(":price", $_POST['price']);
    $stmt->bindParam(":image", $image_path);
    $stmt->bindParam(":id", $_POST['product_id']);
    
    if ($stmt->execute()) {
        // Add transaction record if quantity changed
        if ($current_product['Quantity_In_Stock'] != $_POST['quantity']) {
            $quantity_diff = $_POST['quantity'] - $current_product['Quantity_In_Stock'];
            $transaction_type = $quantity_diff > 0 ? 'Purchase' : 'Sale';
            $transaction_quantity = abs($quantity_diff);
            
            $transaction_query = "INSERT INTO Inventory_Transactions 
                                (Product_ID, Transaction_Type, Quantity, Transaction_Date, Remarks) 
                                VALUES 
                                (:product_id, :type, :quantity, CURDATE(), :remarks)";
            
            $trans_stmt = $db->prepare($transaction_query);
            $trans_stmt->bindParam(":product_id", $_POST['product_id']);
            $trans_stmt->bindParam(":type", $transaction_type);
            $trans_stmt->bindParam(":quantity", $transaction_quantity);
            $remarks = "Stock updated for " . $_POST['product_name'];
            $trans_stmt->bindParam(":remarks", $remarks);
            
            if (!$trans_stmt->execute()) {
                throw new Exception("Error creating transaction record");
            }
        }
        
        $db->commit();
        echo json_encode(array(
            "status" => true,
            "message" => "Product updated successfully."
        ));
    } else {
        throw new Exception("Error updating product");
    }
    
} catch(Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(array(
        "status" => false,
        "message" => $e->getMessage()
    ));
}
?>
