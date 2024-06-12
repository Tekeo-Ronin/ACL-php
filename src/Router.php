<?php
class Router {
    private $userId;

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public function handleRequest() {
        $service = isset($_GET['service']) ? $_GET['service'] : null;
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        if ($service && $category) {
            if ($this->isServiceActive($service)) {
                $acl = new ACL($this->userId);
                if ($acl->check($this->userId, $service)) {
                    // Call the service
                } else {
                    $acl->report($this->userId, $service);
                    echo "You do not have access to this service.";
                }
            } else {
                echo "The service is unavailable.";
            }
        } else {
            echo "Invalid request.";
        }
    }

    private function isServiceActive($service) {
        // The logic of checking whether the service is active
    }
}

