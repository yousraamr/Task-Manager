<?php
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function index() {
        $tasks = Task::getAllTasksForAdmin($this->conn);
        require_once __DIR__ . '/../views/tasks/viewtask.php';
    }    
    
    public function create() {
        include __DIR__ . '/../views/tasks/addtask.php'; 
    }
    
    public function store() {
        session_start();
        require_once __DIR__ . '/../config/database.php';
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $priority = $_POST['priority'];
            $due = $_POST['due'];
            $assignee = $_POST['assignee'];
            $userId = $_SESSION['user_id']; 

            if (Task::addTask($conn, $title, $description, $priority, $due, $assignee, $userId)) {
                header("Location: /Principles_Project/public/index.php?route=tasks");
                exit;
            } else {
                echo "Error adding task.";
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $task = Task::getTaskById($this->conn, $id);
        if ($task) {
            include __DIR__ . '/../views/tasks/edittask.php'; 
        } else {
            header('Location: /index.php?route=tasks'); // task not found
        }
    }

    public function update() {
        $id = $_GET['id'] ?? ($_POST['id'] ?? 0);
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $priority = $_POST['priority'] ?? '';
        $due_date = $_POST['due_date'] ?? null;
        $assignee = $_POST['assignee'] ?? '';
        $status = $_POST['status'] ?? null; 
    
        if ($id && Task::updateTask($this->conn, $id, $title, $description, $priority, $due_date, $assignee, $status)) {
            header('Location: /index.php?route=tasks');
            exit;
        } else {
            session_start();
            $_SESSION['error'] = "Failed to update the task.";
            $_SESSION['old_data'] = $_POST;
            header("Location: /index.php?route=tasks/edit&id=$id");
            exit;
        }
    }    

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskId = $_POST['task_id'];
            $status = $_POST['status'];
    
            if (Task::updateStatus($this->conn, $taskId, $status)) {
                header("Location: /Principles_Project/public/index.php?route=tasks"); 
                exit;
            } else {
                echo json_encode(["error" => "Failed to update status."]);
            }
        } else {
            echo json_encode(["error" => "Invalid request method."]);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        Task::deleteTask($this->conn, $id);
        header('Location: /index.php?route=tasks');
    }

    public function toggleStatus() {
        $taskId = $_POST['task_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
    
        if (!$taskId || !$newStatus) {
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }
    
        $updated = Task::updateStatus($this->conn, $taskId, $newStatus);
    
        if ($updated) {
            echo json_encode(['success' => 'Status updated']);
        } else {
            echo json_encode(['error' => 'Failed to update status']);
        }
    }
} 