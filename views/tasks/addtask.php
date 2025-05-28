<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create New Task</title>
  <link rel="stylesheet" href="/Principles_Project/assets/css/styles.css" >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo"> <span>TaskManager</span></div>
  </header>

  <main>
    <section class="main-content">
      <h1>Create New Task</h1>

      <!-- ✅ Updated form -->
      <form class="task-form" method="POST" action="/Principles_Project/public/index.php?route=tasks/store">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" placeholder="Enter task title" required>
          <small>Give your task a clear, descriptive title.</small>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="4" placeholder="Enter task description" required></textarea>
          <small>Provide details about what needs to be done.</small>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
              <option>Low</option>
              <option selected>Medium</option>
              <option>High</option>
            </select>
            <small>Set the importance level of this task.</small>
          </div>

          <div class="form-group">
            <label for="due">Due Date</label>
            <input type="date" id="due" name="due" required>
            <small>When should this task be completed?</small>
          </div>
        </div>

        <div class="form-group">
          <label for="assignee">Assignee</label>
          <input type="text" id="assignee" name="assignee" placeholder="👤 Assign to" required>
          <small>Who is responsible for this task?</small>
        </div>

        <div class="form-actions">
          <button type="button" class="cancel-btn" onclick="window.location.href='/Principles_Project/public/index.php?route=tasks'">Cancel</button>
          <button type="submit" class="new-task-btn">Create Task</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
