<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Task Manager</title>
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
        <h1>Create Account</h1>
        <p class="auth-subtitle">Join us to start managing your tasks</p>

        <form class="auth-form" id="signupForm" method="POST" action="/Principles_Project/public/index.php?route=signup">
          <div id="error-messages" style="color: red; margin-bottom: 15px;"></div>
          <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
            <small class="validation-message">Please enter a valid email address</small>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="password-container">
              <input type="password" id="password" name="password" placeholder="Create a password" 
                     pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,15}$" required>
              <i class="fa-regular fa-eye" id="togglePassword" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
            </div>
            <small class="validation-message">Password must be 6-15 characters long and contain at least one uppercase letter, one lowercase letter, and one number</small>
          </div>

          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <div class="password-container">
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
              <i class="fa-regular fa-eye" id="toggleConfirmPassword" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
            </div>
            <small class="validation-message" id="password-match-message"></small>
          </div>

          <div class="form-group">
            <label class="checkbox-container">
              <input type="checkbox" required>
              <span class="checkmark"></span>
              I agree to the Terms of Service and Privacy Policy
            </label>
          </div>

          <button type="submit" class="new-task-btn auth-btn">Create Account</button>

          <div class="auth-footer">
            <p>Already have an account? <a href="/Principles_Project/views/auth/login.php" class="auth-link">Login here</a></p>
          </div>
        </form>
      </div>
    </section>
  </main>

  <script>
    // Password visibility toggle for main password
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Password visibility toggle for confirm password
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');

    toggleConfirmPassword.addEventListener('click', function() {
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    function validateForm(event) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      const passwordMatchMessage = document.getElementById('password-match-message');
      const errorMessages = document.getElementById('error-messages');
      
      // Clear previous error messages
      errorMessages.innerHTML = '';
      
      // Password validation
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,15}$/;
      if (!passwordRegex.test(password)) {
        errorMessages.innerHTML += 'Password must be 6-15 characters long and contain at least one uppercase letter, one lowercase letter, and one number<br>';
        return false;
      }
      
      // Confirm password validation
      if (password !== confirmPassword) {
        passwordMatchMessage.textContent = 'Passwords do not match';
        passwordMatchMessage.style.color = '#e53935';
        errorMessages.innerHTML += 'Passwords do not match<br>';
        return false;
      } else {
        passwordMatchMessage.textContent = 'Passwords match';
        passwordMatchMessage.style.color = '#4caf50';
      }
      
      // If all validations pass, allow form submission
      return true;
    }

    // Add form submit event listener
    document.getElementById('signupForm').addEventListener('submit', function(event) {
      if (!validateForm(event)) {
        event.preventDefault();
      }
    });

    // Real-time password match validation
    document.getElementById('confirm_password').addEventListener('input', function() {
      const password = document.getElementById('password').value;
      const confirmPassword = this.value;
      const passwordMatchMessage = document.getElementById('password-match-message');
      
      if (confirmPassword) {
        if (password === confirmPassword) {
          passwordMatchMessage.textContent = 'Passwords match';
          passwordMatchMessage.style.color = '#4caf50';
        } else {
          passwordMatchMessage.textContent = 'Passwords do not match';
          passwordMatchMessage.style.color = '#e53935';
        }
      } else {
        passwordMatchMessage.textContent = '';
      }
    });
  </script>
</body>
</html> 