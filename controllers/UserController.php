<?php
// controllers/UserController.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $userModel;
    private $adminModel;

    public function __construct() {
        $this->db        = $GLOBALS['conn'];

        
        $regularFactory = new RegularUserFactory($this->db);
        $adminFactory = new AdminUserFactory($this->db);
    
        $this->userModel = $regularFactory->getUser();
        $this->adminModel = $adminFactory->getUser();
    }


    public function getMembers() {
        $members = $this->adminModel->getMembers();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'members' => $members]);
    }

    public function addUser() {
        $fullname = $_POST['fullName'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $password = bin2hex(random_bytes(8));

        if ($this->userModel->findByEmail($email)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        if ($this->userModel->create($fullname, $email, $password, $role)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'User created successfully',
                'password' => $password // Send back the generated password
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to create user']);
        }
    }
    
    public function deleteUser() {
        $userId = $_POST['userId'] ?? null;
        
        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }

        if ($this->adminModel->delete($userId)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    }

    public function updateUser() {
        $userId = $_POST['userId'] ?? null;
        $fullname = $_POST['fullName'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }

        if ($this->adminModel->update($userId, $fullname, $email, $role)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update user']);
        }
    }

    public function findUserById() {
        $userId = $_GET['userId'] ?? null;
        
        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }

        $user = $this->userModel->findById($userId);
        
        if ($user) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }

    public function changePassword() {
        $userId = $_POST['userId'] ?? null;
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';

        if (!$userId || !$currentPassword || !$newPassword) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            return;
        }

        $user = $this->userModel->findById($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            return;
        }

        if ($this->userModel->updatePassword($userId, $newPassword)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update password']);
        }
    }
}
