<?php
require_once __DIR__ . '/interfaces/UserInterface.php';

abstract class UserFactory {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract protected function createUser(): UserInterface;
    
    public function getUser(): UserInterface {
        return $this->createUser();
    }
}

class RegularUserFactory extends UserFactory {
    protected function createUser(): UserInterface {
        return new User($this->db);
    }
}

class AdminUserFactory extends UserFactory {
    protected function createUser(): UserInterface {
        return new Admin($this->db);
    }
}

class User implements UserInterface {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($result);
        }
        return false;
    }
public function findById($id) {
    $sql = "SELECT id, fullname, email, role, password FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($this->conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    return false;
}
    public function create($fullname, $email, $password, $role = 'user') {
        $sql = "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $hashed, $role);
            return mysqli_stmt_execute($stmt);
        }
        return false;
    }
   


public function updatePassword($userId, $newPassword) {
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($this->conn, $sql)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "si", $hashed, $userId);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}
    
}

class Admin implements UserInterface {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

     
     public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_assoc($result);
        }
        return false;
    }
    public function findById($id) {
    $sql = "SELECT id, fullname, email, role, password FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($this->conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    return false;
}

     public function create($fullname, $email, $password, $role = 'user') {
        $sql = "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $hashed, $role);
            return mysqli_stmt_execute($stmt);
        }
        return false;
    }
    public function updatePassword($userId, $newPassword) {
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($this->conn, $sql)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "si", $hashed, $userId);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}
  public function delete($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            return mysqli_stmt_execute($stmt);
        }
        return false;
    }

    public function update($userId, $fullname, $email, $role) {
        $sql = "UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($this->conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $role, $userId);
            return mysqli_stmt_execute($stmt);
        }
        return false;
    }
    public function getMemberCount() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'];
        }
        return 0;
    }
       
      public function getMembers() {
        $sql = "SELECT id, fullname, email, role FROM users";
        $result = mysqli_query($this->conn, $sql);
        
        if (!$result) {
            return [];
        }
        
        $members = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = [
                'id' => $row['id'],
                'fullname' => $row['fullname'],
                'email' => $row['email'],
                'role' => $row['role']
            ];
        }
        return $members;
    }

    public function getTaskStats() {
        $sql = "SELECT 
                COUNT(*) as total_tasks,
                COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 END) as new_tasks,
                COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 2 MONTH) 
                          AND created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 END) as last_month_tasks
                FROM tasks";
        
        $result = mysqli_query($this->conn, $sql);
        $stats = mysqli_fetch_assoc($result);
        
        // Calculate percentage change
        $last_month = $stats['last_month_tasks'] ?: 1; // Prevent division by zero
        $percentage_change = (($stats['new_tasks'] - $last_month) / $last_month) * 100;
        
        return [
            'total_tasks' => $stats['total_tasks'],
            'percentage_change' => round($percentage_change),
            'trend' => $percentage_change >= 0 ? 'positive' : 'negative'
        ];
    }

    public static function getRecentTasks($conn, $limit = 5) {
        $sql = "SELECT t.*, u.fullname as assignee_name 
                FROM tasks t 
                LEFT JOIN users u ON t.assignee = u.id 
                ORDER BY t.created_at DESC 
                LIMIT ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $tasks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
        
        return $tasks;
    }
}