<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Task</title>
  <link rel="stylesheet" href="/Principles_Project/assets/css/styles.css" >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <main>
    <section class="main-content">
      <h1>Edit Task</h1>
      <form method="POST" action="/Principles_Project/public/index.php?route=tasks/update" class="task-form">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" required><?= htmlspecialchars($task['description']) ?></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority">
              <option <?= $task['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
              <option <?= $task['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
              <option <?= $task['priority'] === 'High' ? 'selected' : '' ?>>High</option>
            </select>
          </div>
          <div class="form-group">
            <label for="due">Due Date</label>
            <input type="date" name="due" value="<?= $task['due_date'] ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label for="assignee">Assignee</label>
          <input type="text" name="assignee" value="<?= htmlspecialchars($task['assignee']) ?>" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="new-task-btn">Update Task</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
