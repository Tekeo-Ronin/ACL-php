<?php
require 'vendor/autoload.php';

use WhichBrowser\Parser;

class Sensor {
    public function logAccessAttempt($userId, $serviceName) {
        $result = new Parser(getallheaders());
        $log = sprintf("User: %s, Service: %s, Browser: %s, Platform: %s\n",
            $userId, $serviceName, $result->browser->toString(), $result->os->toString());
        file_put_contents('../logs/access.log', $log, FILE_APPEND);
    }
}
