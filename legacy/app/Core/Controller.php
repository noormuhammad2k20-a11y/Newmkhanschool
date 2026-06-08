<?php
namespace App\Core;

abstract class Controller {
    /**
     * Send a structured JSON response.
     */
    protected function json(array $data, int $statusCode = 200): void {
        // Clear any previous buffer outputs
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
