<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /Principles_Project/views/auth/login.php");
    exit;
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: /Principles_Project/views/auth/login.php");
    exit;
}


if (!isset($tasks)) {
    // Prevents direct access
    header("Location: /Principles_Project/public/index.php?route=tasks");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Manager</title>
  <link rel="stylesheet" href="/Principles_Project/assets/css/styles.css" >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo"><span>TaskManager</span></div>
    <div class="header-buttons">
      <div id="userInfo">
        <span id="userName" style="margin-right: 15px; color: #333;">
          <?= htmlspecialchars($_SESSION['fullname']) ?>
        </span>
        <button class="logout-btn" onclick="window.location.href='/Principles_Project/public/index.php?route=logout'">Logout</button>

      </div>
      <button class="crud-task-btn" onclick="window.location.href='/Principles_Project/public/index.php?route=admin_tasks'">Task</button>
      <button class="settings-btn" onclick="window.location.href='/Principles_Project/views/user/user_settings.php'">Settings</button>
    </div>
  </header>

  <main>
    <section class="main-content">
      <div class="top-row">
        <h1>My Tasks</h1>
        <button class="new-task-btn" onclick="window.location.href='/Principles_Project/public/index.php?route=tasks/create'">+ New Task</button>
      </div>

      <input type="text" class="search-bar" placeholder="Search tasks..." />

      <div class="tabs">
        <button class="tab active" onclick="filterTasks('all')">All</button>
        <button class="tab" onclick="filterTasks('todo')">To Do</button>
        <button class="tab" onclick="filterTasks('inprogress')">In Progress</button>
        <button class="tab" onclick="filterTasks('done')">Done</button>
      </div>

      <div class="task-container">
        <?php foreach ($tasks as $task): ?>
        <div class="task-card" 
             data-id="<?= $task['id'] ?>" 
             data-status="<?= strtolower(str_replace(' ', '', $task['status'] ?? 'todo')) ?>">
          <span class="badge"><?= htmlspecialchars($task['status'] ?? 'To Do') ?></span>
          <h2><?= htmlspecialchars($task['title']) ?></h2>
          <p><?= htmlspecialchars($task['description']) ?></p>
          <div class="task-info">
            <p><i class="fa-regular fa-calendar"></i> Due: <?= htmlspecialchars($task['due_date']) ?></p>
            <p><i class="fa-regular fa-user"></i> <?= htmlspecialchars($task['assignee']) ?></p>
            <p><i class="fa-regular fa-clock"></i> Created <?= htmlspecialchars($task['created_at']) ?></p>
          </div>
          <span class="priority medium"><?= htmlspecialchars($task['priority']) ?> Priority</span>
          <div class="card-actions">
            <i class="fa-solid fa-pen-to-square edit-task" title="Edit Task"></i>
            <i class="fa-solid fa-rotate-right toggle-status" title="Change Status"></i>
            <i class="fa-solid fa-trash delete-icon" title="Delete Task"></i>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <script>
    function filterTasks(status) {
      console.log("Filtering for:", status); // Debug
      const tabs = document.querySelectorAll(".tab");
      tabs.forEach(tab => tab.classList.remove("active"));
      const clickedTab = [...tabs].find(tab => tab.textContent.toLowerCase().includes(status));
      if (clickedTab) clickedTab.classList.add("active");

      const cards = document.querySelectorAll(".task-card");
      cards.forEach(card => {
        const cardStatus = card.dataset.status.toLowerCase();
        if (status === 'all' || cardStatus === status) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }

    // 🗑Delete
    document.querySelectorAll('.delete-icon').forEach(icon => {
      icon.addEventListener('click', function () {
        const card = this.closest('.task-card');
        const id = card.dataset.id;
        if (confirm('Are you sure you want to delete this task?')) {
          window.location.href = `/Principles_Project/public/index.php?route=tasks/delete&id=${id}`;
        }
      });
    });

    // ✏ Edit
    document.querySelectorAll('.edit-task').forEach(icon => {
      icon.addEventListener('click', function () {
        const card = this.closest('.task-card');
        const id = card.dataset.id;
        window.location.href = `/Principles_Project/public/index.php?route=tasks/edit&id=${id}`;
      });
    });

    //  Toggle Status
    document.querySelectorAll('.toggle-status').forEach(icon => {
      icon.addEventListener('click', function () {
        const card = this.closest('.task-card');
        const id = card.dataset.id;
        window.location.href = `/Principles_Project/public/index.php?route=tasks/toggle-status&id=${id}`;
      });
    });
  </script>
</body>
</html>
