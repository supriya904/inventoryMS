<?php
class Database {
    private $host = "localhost";
    private $db_name = "inventory";
    private $username = "root";
    private $password = "";
    private $conn = null;

    // Get the database connection
    public function getConnection() {
        try {
            if ($this->conn === null) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            return $this->conn;
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            return null;
        }
    }
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to verify password hash
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Function to create password hash
function create_password_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
