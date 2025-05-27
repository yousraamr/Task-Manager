<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /Principles_Project/views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';

// Initialize User model
$regularFactory = new RegularUserFactory($conn);
$userModel = $regularFactory->getUser();

// Fetch user data using correct session variable
$userId = $_SESSION['id'];
$user = $userModel->findById($userId);

if (!$user) {
    // Handle case where user is not found
    header('Location: /Principles_Project/views/auth/login.php');
    exit;
}

// Fallbacks if not found
$fullname = htmlspecialchars($user['fullname'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Settings - Task Manager</title>
    <link rel="stylesheet" href="/Principles_Project/assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message { color: #e53935; font-size: 12px; margin-top: 4px; display: none; }
        input.error { border: 1px solid #e53935; }
        .success-toast {
            position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white;
            padding: 12px 20px; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: none; z-index: 1000;
        }
        .danger-zone {
            border: 1px solid #ff4444;
            border-radius: 4px;
            padding: 20px;
            margin-top: 20px;
        }
        .delete-account-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .settings-content {
            display: none;
        }
        .settings-content.active {
            display: block;
        }
        .settings-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .settings-tab {
            padding: 10px 20px;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .settings-tab.active {
            border-bottom: 2px solid #007bff;
            color: #007bff;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo"><span>TaskManager</span></div>
        <div class="header-buttons">
               <?php if ($_SESSION['role'] === 'admin'): ?>

      <button class="crud-task-btn" onclick="window.location.href='/Principles_Project/public/index.php?route=admin_tasks'"> View all Tasks</button>
      <button class="crud-task-btn" onclick="window.location.href='/Principles_Project/views/dashboard/dashboard.php'"> View Users</button>
            <?php endif; ?>
            <button class="new-task-btn" onclick="window.location.href='/Principles_Project/views/tasks/home.php'">Home</button>
        </div>
    </header>

    <main>
        <section class="main-content">
            <h1>User Settings</h1>
            <div class="success-toast" id="success-toast">Changes saved successfully!</div>
            <div class="settings-container">
                <div class="settings-tabs">
                    <button class="settings-tab active" data-tab="profile">Profile</button>
                    <button class="settings-tab" data-tab="password">Password</button>
                    <button class="settings-tab" data-tab="account">Account</button>
                </div>
                <div class="settings-content active" id="profile-settings">
                    <h2>Profile Information</h2>
                    <form class="settings-form" id="profile-form">
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" required>
                            <small class="error-message" id="fullname-error">Please enter a valid name</small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                            <small class="error-message" id="email-error">Please enter a valid email address</small>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="cancel-btn" id="profile-cancel-btn" onclick="window.location.href='/Principles_Project/views/tasks/home.php'">Cancel</button>
                            <button type="submit" class="new-task-btn">Save Changes</button>
                        </div>
                    </form>
                </div>

                <!-- Password Change Section -->
                <div class="settings-content" id="password-settings">
                    <h2>Change Password</h2>
                    <form class="settings-form" id="password-form">
                        <div class="form-group">
                            <label for="current-password">Current Password</label>
                            <input type="password" id="current-password" name="current-password" required>
                            <small class="error-message" id="current-password-error">Current password is required</small>
                        </div>

                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <input type="password" id="new-password" name="new-password" required>
                            <small class="error-message" id="new-password-error">New password must be at least 6 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                            <small class="error-message" id="confirm-password-error">Passwords do not match</small>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="cancel-btn" onclick="window.location.href='/Principles_Project/views/tasks/home.php'">Cancel</button>
                            <button type="submit" class="new-task-btn">Update Password</button>
                        </div>
                    </form>
                </div>

                <!-- Account Deletion Section -->
                <div class="settings-content" id="account-settings">
                    <h2>Delete Account</h2>
                    <div class="danger-zone">
                        <h3>Danger Zone</h3>
                        <p>Once you delete your account, there is no going back. Please be certain.</p>
                        <button type="button" class="delete-account-btn" id="delete-account-btn">Delete My Account</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const fullname = this.elements['fullname'].value.trim();
            const email = this.elements['email'].value.trim();

            // Validation
            if (!fullname) {
                document.getElementById('fullname-error').style.display = 'block';
                return;
            }
            if (!email || !email.includes('@')) {
                document.getElementById('email-error').style.display = 'block';
                return;
            }

            fetch('/Principles_Project/public/index.php?route=users/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `userId=<?php echo $userId; ?>&fullName=${encodeURIComponent(fullname)}&email=${encodeURIComponent(email)}&role=user`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-toast').style.display = 'block';
                    setTimeout(() => { 
                        document.getElementById('success-toast').style.display = 'none';
                    }, 3000);
                } else {
                    alert(data.message || 'Update failed.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the profile.');
            });
        });

        // Reset error messages on input
        document.getElementById('fullname').addEventListener('input', function() {
            document.getElementById('fullname-error').style.display = 'none';
        });
        document.getElementById('email').addEventListener('input', function() {
            document.getElementById('email-error').style.display = 'none';
        });

        // Password form handling
        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const currentPassword = document.getElementById('current-password').value.trim();
            const newPassword = document.getElementById('new-password').value.trim();
            const confirmPassword = document.getElementById('confirm-password').value.trim();

            // Reset error messages
            document.getElementById('current-password-error').style.display = 'none';
            document.getElementById('new-password-error').style.display = 'none';
            document.getElementById('confirm-password-error').style.display = 'none';

            // Validation
            if (!currentPassword) {
                document.getElementById('current-password-error').style.display = 'block';
                return;
            }
            if (newPassword.length < 6) {
                document.getElementById('new-password-error').style.display = 'block';
                return;
            }
            if (newPassword !== confirmPassword) {
                document.getElementById('confirm-password-error').style.display = 'block';
                return;
            }

            fetch('/Principles_Project/public/index.php?route=users/change-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `userId=<?php echo $userId; ?>&currentPassword=${encodeURIComponent(currentPassword)}&newPassword=${encodeURIComponent(newPassword)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-toast').textContent = 'Password updated successfully!';
                    document.getElementById('success-toast').style.display = 'block';
                    setTimeout(() => {
                        document.getElementById('success-toast').style.display = 'none';
                        document.getElementById('success-toast').textContent = 'Changes saved successfully!';
                    }, 3000);
                    this.reset();
                } else {
                    alert(data.message || 'Failed to update password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the password.');
            });
        });

        // Delete account handling
        document.getElementById('delete-account-btn').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                fetch('/Principles_Project/public/index.php?route=users/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `userId=<?php echo $userId; ?>`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Account deleted successfully');
                        window.location.href = '/Principles_Project/views/auth/login.php';
                    } else {
                        alert(data.message || 'Failed to delete account');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the account.');
                });
            }
        });

        // Add these event listeners for password form validation
        document.getElementById('current-password').addEventListener('input', function() {
            document.getElementById('current-password-error').style.display = 'none';
        });
        document.getElementById('new-password').addEventListener('input', function() {
            document.getElementById('new-password-error').style.display = 'none';
        });
        document.getElementById('confirm-password').addEventListener('input', function() {
            document.getElementById('confirm-password-error').style.display = 'none';
        });

        // Tab switching
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.settings-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(this.dataset.tab + '-settings').classList.add('active');
            });
        });
    </script>
</body>
</html>