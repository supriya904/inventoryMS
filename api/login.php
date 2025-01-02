<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';

$database = new Database();
$db = $database->getConnection();

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    $username = sanitize_input($data->username);
    $password = sanitize_input($data->password);

    try {
        // Check user credentials
        $query = "SELECT User_ID, Username, Password_Hash, Role FROM Users WHERE Username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (verify_password($password, $row['Password_Hash'])) {
                // Create response
                $response = array(
                    "status" => true,
                    "message" => "Login successful",
                    "data" => array(
                        "user_id" => $row['User_ID'],
                        "username" => $row['Username'],
                        "role" => $row['Role']
                    )
                );
                http_response_code(200);
            } else {
                // Password is incorrect
                $response = array(
                    "status" => false,
                    "message" => "Invalid password"
                );
                http_response_code(401);
            }
        } else {
            // User not found
            $response = array(
                "status" => false,
                "message" => "User not found"
            );
            http_response_code(404);
        }
    } catch (PDOException $e) {
        // Database error
        $response = array(
            "status" => false,
            "message" => "Database error: " . $e->getMessage()
        );
        http_response_code(500);
    }
} else {
    // Missing data
    $response = array(
        "status" => false,
        "message" => "Missing required fields"
    );
    http_response_code(400);
}

// Return response
echo json_encode($response);
?>
