<?php
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../config/database.php'; // defines $conn

class TaskController {
    public function index() {
        session_start();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header("Location: /Principles_Project/views/auth/login.php");
            exit;
        }

        global $conn;
        $userId = $_SESSION['user_id']; //  Fetch only this user's tasks
        $tasks = Task::getAllTasks($conn, $userId);

        include __DIR__ . '/../views/tasks/home.php';
    }

    public function create() {
        session_start();
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
            $userId = $_SESSION['user_id']; // ✅ Store who created the task

            if (Task::addTask($conn, $title, $description, $priority, $due, $assignee, $userId)) {
                header("Location: /Principles_Project/public/index.php?route=tasks");
                exit;
            } else {
                echo "Error adding task.";
            }
        }
    }

    public function delete() {
        require_once __DIR__ . '/../config/database.php';
        global $conn;

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            Task::deleteTask($conn, $id);
        }

        header("Location: /Principles_Project/public/index.php?route=tasks");
        exit;
    }

    public function edit() {
        session_start();
        require_once __DIR__ . '/../config/database.php';
        global $conn;

        if (!isset($_GET['id'])) {
            header("Location: /Principles_Project/public/index.php?route=tasks");
            exit;
        }

        $task = Task::getTaskById($conn, $_GET['id']);
        include __DIR__ . '/../views/tasks/edit.php';
    }

    public function update() {
        require_once __DIR__ . '/../config/database.php';
        global $conn;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $priority = $_POST['priority'];
            $due = $_POST['due'];
            $assignee = $_POST['assignee'];

            Task::updateTask($conn, $id, $title, $description, $priority, $due, $assignee);
        }

        header("Location: /Principles_Project/public/index.php?route=tasks");
        exit;
    }

    public function toggleStatus() {
        require_once __DIR__ . '/../config/database.php';
        global $conn;

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $task = Task::getTaskById($conn, $id);

            $nextStatus = 'To Do';
            if ($task['status'] === 'To Do') $nextStatus = 'In Progress';
            elseif ($task['status'] === 'In Progress') $nextStatus = 'Done';

            Task::updateStatus($conn, $id, $nextStatus);
        }

        header("Location: /Principles_Project/public/index.php?route=tasks");
        exit;
    }
}
