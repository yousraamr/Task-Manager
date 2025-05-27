<?php
// Start session if not already started
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Redirect based on user role
    if ($_SESSION['role'] === 'admin') {
        header('Location: /Principles_Project/views/dashboard/dashboard.php');
    } else {
        header('Location: /Principles_Project/views/tasks/home.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Task Manager</title>
  <link rel="stylesheet" href="/Principles_Project/assets/css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo"> <span>TaskManager</span></div>
  </header>
<main>
    <section class="main-content">
        <div class="auth-container">
            <h1>Welcome Back</h1>
            <p class="auth-subtitle">Please login to your account</p>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="error-messages"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="/Principles_Project/public/index.php?route=login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <i class="fa-regular fa-eye" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" class="new-task-btn auth-btn">Login</button>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="/Principles_Project/views/auth/signup.php" class="auth-link">Sign up here</a></p>
                </div>
            </form>
        </div>
    </section>
</main>
<script>
   
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
     
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault();
      
      const formData = new FormData(this);
      const errorMessages = document.getElementById('error-messages');
      
      fetch('/Principles_Project/public/process_login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success === false) {
          errorMessages.innerHTML = '';
          if (data.email_err) errorMessages.innerHTML += data.email_err + '<br>';
          if (data.password_err) errorMessages.innerHTML += data.password_err + '<br>';
        } else {
          window.location.href = '/Principles_Project/views/tasks/home.php';
        }
      })
      .catch(error => {
        errorMessages.innerHTML = 'An error occurred. Please try again.';
      });
    });
  </script>
</body>
</html>
