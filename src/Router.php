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
                    $this->callService($service, $category);
                } else {
                    $acl->report($this->userId, $service);
                    $this->logUnauthorizedAccess($this->userId, $service);
                    echo "Ви не маєте доступу до цього сервісу.";
                }
            } else {
                echo "Сервіс недоступний.";
            }
        } else {
            echo "Неправильний запит.";
        }
    }

    private function isServiceActive($service) {
        return $this->checkServiceInDatabase($service);
    }

    private function checkServiceInDatabase($service) {
        $config = include(__DIR__ . '/../config/config.php');
        $db = new PDO($config['database']['dsn'], $config['database']['username'], $config['database']['password']);
        $stmt = $db->prepare("SELECT COUNT(*) FROM services WHERE name = :service AND active = 1");
        $stmt->execute(['service' => $service]);
        return $stmt->fetchColumn() > 0;
    }

    private function logUnauthorizedAccess($userId, $service) {
        $sensor = new Sensor();
        $sensor->logUnauthorizedAccess($userId, $service);
    }

}
