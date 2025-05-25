<?php
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Ensure $tasks is defined
if (!isset($tasks)) {
    $tasks = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Task Manager</title>
  <link rel="stylesheet" href="/Principles_Project/assets/css/viewtask.css" />
</head>

<body>
  <!-- top‑bar -->
  <header class="topbar">
    <span class="logo-box">✓</span>
    <h1>Task Manager</h1>
    <div class="button-container">
      <button class="btn primary home-btn" onclick="window.location.href='/Principles_Project/views/tasks/home.php';">Home</button> 
      <button class="btn primary add-btn" onclick="document.getElementById('taskModal').showModal()">＋ Add New Task</button>
    </div>
  </header>

  <!-- search -->
  <div class="search">
    <input id="searchBox" type="search" placeholder="Search tasks…" onkeyup="filterTasks()" />
  </div>

  <!-- task list -->
  <main id="listWrapper">
    <h2>Your Tasks <small id="taskCounter">(<?= count($tasks) ?>)</small></h2>
    <div id="taskList">
      <?php foreach ($tasks as $task): ?>
        <div class="task-card">
          <div class="task-header">
            <h3><?= h($task['title']) ?></h3>
            <div class="task-actions">
              <a href='/Principles_Project/public/index.php?route=tasks/edit&id=<?= $task['id'] ?>'>Edit</a>
              <a href="/Principles_Project/public/index.php?route=tasks/delete&id=<?= $task['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </div>
          </div>

          <p><?= h($task['description']) ?></p>

          <div class="task-meta">
            <span>Priority: <strong><?= h($task['priority']) ?></strong></span>
            <span>Status:</span>
            <form method="post" action="/Principles_Project/public/index.php?route=tasks/updateStatus&id=<?= $task['id'] ?>">
              <input type="hidden" name="task_id" value="<?= h($task['id']) ?>">
              <select name="status" onchange="this.form.submit()">
              <option value="Pending" <?= ($task['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="In-Progress" <?= ($task['status'] ?? '') === 'In-Progress' ? 'selected' : '' ?>>In-Progress</option>
              <option value="Done" <?= ($task['status'] ?? '') === 'Done' ? 'selected' : '' ?>>Done</option>
            </select>
            </form>
            <span>Due: <?= h($task['due_date']) ?></span>
            <span>Assignee: <?= h($task['assignee_name'] ?? $task['assignee']) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- toast -->
  <div id="toast" class="toast" hidden></div>

  <!-- modal -->
  <dialog id="taskModal" class="modal">
    <form method="post" action="/Principles_Project/public/index.php?route=tasks/store">
      <div class="modal-head">
        <h3>Add Task</h3>
        <button type="button" class="icon-btn" onclick="document.getElementById('taskModal').close()">✕</button>
      </div>

      <label for="mTitle">Task Title
        <input id="mTitle" name="title" type="text" required placeholder="Enter task title" />
      </label>

      <label for="mDesc">Description (Optional)
        <textarea id="mDesc" name="description" rows="3" placeholder="Enter task description"></textarea>
      </label>

      <div class="two-col">
        <label for="mPriority">Priority
          <select id="mPriority" name="priority">
            <option>Low</option>
            <option selected>Medium</option>
            <option>High</option>
          </select>
        </label>

        <label for="mStatus">Status
          <select id="mStatus" name="status" disabled>
            <option selected>Pending</option>
          </select>
        </label>
      </div>

      <div class="two-col">
        <label for="mDue">Due Date
          <input id="mDue" name="due_date" type="date" />
        </label>

        <label for="mAssignee">Assignee
          <input id="mAssignee" name="assignee" type="text" placeholder="👤 Assign to" />
        </label>
      </div>

      <footer class="modal-foot">
        <button type="button" class="btn" onclick="document.getElementById('taskModal').close()">Cancel</button>
        <button type="submit" class="btn primary">Save Task</button>
      </footer>
    </form>
  </dialog>

  <!-- filtering script -->
  <script>
    function filterTasks() {
      const filter = document.getElementById('searchBox').value.toLowerCase();
      const cards = document.querySelectorAll('#taskList .task-card');
      let count = 0;

      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (text.includes(filter)) {
          card.style.display = '';
          count++;
        } else {
          card.style.display = 'none';
        }
      });

      document.getElementById('taskCounter').textContent = '(' + count + ')';
    }
  </script>

  <style>
    .task-card {
      background: #fff;
      padding: 1rem;
      margin: 1rem 0;
      border-radius: 10px;
      box-shadow: 0 0 5px #ccc;
    }

    .task-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .task-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    .task-actions a {
      color: #007BFF;
      text-decoration: none;
      margin-left: 0.5rem;
    }

    .task-actions a:hover {
      text-decoration: underline;
    }
  </style>

</body>
</html>
