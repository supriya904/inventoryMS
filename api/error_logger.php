<?php
class ErrorLogger {
    private $logFile;
    
    public function __construct($logFile = null) {
        $this->logFile = $logFile ?: __DIR__ . '/error_log.txt';
    }
    
    public function log($message, $type = 'ERROR', $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logMessage = "[$timestamp] [$type] $message $contextStr\n";
        
        error_log($logMessage, 3, $this->logFile);
        return true;
    }
    
    public function logException($exception) {
        $context = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        $this->log($exception->getMessage(), 'EXCEPTION', $context);
    }
}

// For API error responses
function sendErrorResponse($message, $statusCode = 500) {
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit;
}
?>
