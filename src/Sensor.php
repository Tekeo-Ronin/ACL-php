<?php

require_once __DIR__ . '/../vendor/autoload.php';
use WhichBrowser\Parser;

class Sensor {
    public function logUnauthorizedAccess($userId, $service) {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $result = new Parser($ua);
        $details = $result->toString();

        $logMessage = sprintf("Unauthorized access attempt by User ID %d for service %s. User Agent: %s", $userId, $service, $details);
        error_log($logMessage, 3, __DIR__ . '/../logs/access.log');
    }
}
