<?php
class Task {
    public static function getAllTasks($conn, $userId) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $tasks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }

        return $tasks;
    }

    public static function addTask($conn, $title, $description, $priority, $due_date, $assignee, $userId) {
        $stmt = mysqli_prepare($conn, "INSERT INTO tasks (title, description, priority, due_date, assignee, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $priority, $due_date, $assignee, $userId);
            return mysqli_stmt_execute($stmt);
        }
        return false;
    }

    public static function deleteTask($conn, $id) {
        $stmt = mysqli_prepare($conn, "DELETE FROM tasks WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }

    public static function getTaskById($conn, $id) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM tasks WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public static function updateTask($conn, $id, $title, $description, $priority, $due_date, $assignee) {
        $stmt = mysqli_prepare($conn, "UPDATE tasks SET title = ?, description = ?, priority = ?, due_date = ?, assignee = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $priority, $due_date, $assignee, $id);
        return mysqli_stmt_execute($stmt);
    }

    public static function updateStatus($conn, $id, $status) {
        $stmt = mysqli_prepare($conn, "UPDATE tasks SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        return mysqli_stmt_execute($stmt);
    }

    //Admin function to get all tasks
    public static function getAllTasksForAdmin($conn) {
        $result = mysqli_query($conn, "SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $tasks[] = $row;
            }
        }
        return $tasks;
    }
}
