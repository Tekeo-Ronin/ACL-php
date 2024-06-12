<?php
class ACL implements iVerifier {
    private $userId;
    private $userName;
    private $groupId;
    private $groupName;
    private $serviceName;
    private $action;

    public function __construct($userId, $userName, $groupId, $groupName, $serviceName, $action) {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->groupId = $groupId;
        $this->groupName = $groupName;
        $this->serviceName = $serviceName;
        $this->action = $action;
    }

    public function whoAmI() {
        return $this->userName;
    }

    public function addToGroup($userId, $groupId) {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare('INSERT INTO user_groups (user_id, group_id) VALUES (?, ?)');
        $stmt->execute([$userId, $groupId]);
    }

    public function remFromGroup($userId, $groupId) {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare('DELETE FROM user_groups WHERE user_id = ? AND group_id = ?');
        $stmt->execute([$userId, $groupId]);
    }

    public function check($userId, $serviceName) {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM permissions WHERE user_id = ? AND service_name = ?');
        $stmt->execute([$userId, $serviceName]);
        $permission = $stmt->fetch(PDO::FETCH_ASSOC);

        return $permission ? true : false;
    }

    public function report($userId, $serviceName) {
        $log = sprintf("User: %s tried to access: %s\n", $userId, $serviceName);
        file_put_contents('../logs/access.log', $log, FILE_APPEND);
    }

    private function getConnection() {
        $config = require '../config/config.php';
        $pdo = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['user'], $config['db']['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
