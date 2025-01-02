<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';

$database = new Database();
$db = $database->getConnection();

// Handle file upload
function handleImageUpload($file) {
    $target_dir = "../assets/";
    $category_dir = $_POST['category_name'] . "/";
    
    // Create category directory if it doesn't exist
    if (!file_exists($target_dir . $category_dir)) {
        mkdir($target_dir . $category_dir, 0777, true);
    }
    
    // Generate unique filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $base_filename = strtolower(str_replace(' ', '-', $_POST['product_name']));
    $target_file = $target_dir . $category_dir . $base_filename . "." . $file_extension;
    $relative_path = "assets/" . $category_dir . $base_filename . "." . $file_extension;
    
    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($file_extension, $allowed_types)) {
        return array("status" => false, "message" => "Only JPG, JPEG, PNG & GIF files are allowed.");
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return array("status" => true, "path" => $relative_path);
    } else {
        return array("status" => false, "message" => "Error uploading file.");
    }
}

try {
    // Handle image upload first
    if (!isset($_FILES['product_image'])) {
        throw new Exception("No image file uploaded");
    }
    
    $image_result = handleImageUpload($_FILES['product_image']);
    if (!$image_result['status']) {
        throw new Exception($image_result['message']);
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
    
    // Insert product
    $query = "INSERT INTO Products 
              (Product_Name, Category_ID, Quantity_In_Stock, Price_Per_Unit, imageAddress) 
              VALUES 
              (:name, :category_id, :quantity, :price, :image)";
    
    $stmt = $db->prepare($query);
    
    // Bind values
    $stmt->bindParam(":name", $_POST['product_name']);
    $stmt->bindParam(":category_id", $category['Category_ID']);
    $stmt->bindParam(":quantity", $_POST['quantity']);
    $stmt->bindParam(":price", $_POST['price']);
    $stmt->bindParam(":image", $image_result['path']);
    
    if ($stmt->execute()) {
        echo json_encode(array(
            "status" => true,
            "message" => "Product added successfully.",
            "id" => $db->lastInsertId()
        ));
    } else {
        throw new Exception("Error adding product");
    }
    
} catch(Exception $e) {
    echo json_encode(array(
        "status" => false,
        "message" => $e->getMessage()
    ));
}
?>
