<?php
include_once __DIR__ . '/../config/db_connect.php';

class VisitorLogs {
    private $conn;
    private $tableName;

    public function __construct() {
        global $conn;
        $this->tableName = 'visitorlogs';
        $this->conn = $conn;
    }

    public function addVisitorLog($ip) {
        $sql = "INSERT INTO `$this->tableName` (ip_address) VALUES (?)";
        $stmt = $this->conn->prepare( $sql );
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        if ( $stmt->affected_rows > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function getVisitorLogs() {
        $sql = "SELECT * FROM `$this->tableName`";
        $result = $this->conn->query( $sql );
        if ( $result->num_rows > 0 ) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function checkExistingVisitorLogs($ip, $date) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM $this->tableName WHERE ip_address = ? AND DATE(timestamp) = ?");
        $stmt->bind_param("ss", $ip, $date);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ($count > 0) {
            return true; 
        } else {
            return false;
        }
    }
    
}