<?php
// views/dashboard/dashboard.php

session_start();
if (empty($_SESSION['id'])) {
    header('Location: /Principles_Project/public/login');
    exit;
}
if($_SESSION['role'] !== 'admin') {
    header('Location: /Principles_Project/views/tasks/home.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Task.php';


$adminFactory = new AdminUserFactory($conn);
$userModel = $adminFactory->getUser();

$members   = $userModel->getMembers();
$memberCount = $userModel->getMemberCount();
$taskStats = $userModel->getTaskStats();

// Add near the top with other initializations
$recentTasks =$userModel->getRecentTasks($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster</title>
    <link rel="stylesheet" href="/Principles_Project/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div>
                <div class="logo">TaskMaster</div>
                <ul class="nav-menu">
                    <li class="nav-item active">
                       <i class="fa-solid fa-bars"></i> Dashboard
                    </li>
                  <a href="/Principles_Project/public/index.php?route=admin_tasks" style="text-decoration: none; color: inherit;">  
                    <li class="nav-item">
                        <i class="fa-solid fa-check"></i> Tasks
                     
                    </li>
</a>
                    <li class="nav-item">
                       <i class="fa-solid fa-users-line"></i> Team
                    </li>
                    <a href="/Principles_Project/views/user/user_settings.php" style="text-decoration: none; color: inherit;">  
                    <li class="nav-item">
                        <i class="fa-solid fa-gear"></i> Settings
                    </li>
</a>
                </ul>
            </div>
            
           <div class="user-profile">
      <div class="avatar">
        <?php 
           // initials from fullname
           $parts = explode(' ', $_SESSION['fullname']);
           echo strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
        ?>
      </div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($_SESSION['fullname']) ?></div>
        <div class="user-email"><?= htmlspecialchars($_SESSION['email']) ?></div>
      </div>
      <a class="logout-btn" href="/Principles_Project/public/index.php?route=logout" title="Log out">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </a>
    </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Dashboard View -->
            <div class="dashboard-view">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                        <p class="page-subtitle">Welcome back! Here's an overview of your tasks and projects.</p>
                    </div>
                    <button class="add-task-btn" onclick="window.location.href='/Principles_Project/views/tasks/home.php'">
                        <span>+</span> Add New Task
                    </button>
                </div>
                
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="card-title">Total Tasks</div>
                        <div class="card-value">
                            <?= $taskStats['total_tasks'] ?>
                            <div class="card-icon">📋</div>
                        </div>
                        <div class="card-subtitle <?= $taskStats['trend'] ?>">
                            <span><?= $taskStats['trend'] === 'positive' ? '↗' : '↘' ?></span>
                            <?= abs($taskStats['percentage_change']) ?>% from last month
                        </div>
                    </div>
                    
        
                    
                    <div class="dashboard-card">
                        <div class="card-title">Members</div>
                        <div class="card-value">
                            <?= $memberCount ?>
                            <div class="card-icon">👥</div>
                        </div>
                        <?php
                        // Calculate recent members (joined in last 30 days)
                        $recentCount = array_reduce($members, function($count, $member) {
                            $joinDate = strtotime($member['created_at'] ?? '');
                            if ($joinDate && (time() - $joinDate) < (30 * 24 * 60 * 60)) {
                                return $count + 1;
                            }
                            return $count;
                        }, 0);
                        ?>
                        <div class="card-subtitle <?= $recentCount > 0 ? 'positive' : '' ?>">
                            <?php if ($recentCount > 0): ?>
                                <span>↗</span> <?= $recentCount ?> joined recently
                            <?php else: ?>
                                <span>→</span> No new members
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-title">Upcoming Deadlines</div>
                        <div class="card-value">
                            8
                            <div class="card-icon">📅</div>
                        </div>
                        <div class="card-subtitle warning">
                            <span>→</span> Next: Marketing Campaign
                        </div>
                    </div>
                </div>
                
                <div class="content-layout">
                    <!-- <div class="main-section">
                        <div class="section">
                            <div class="section-header">
                                <h2 class="section-title">Project Progress</h2>
                            </div>
                            
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">Website Redesign</div>
                                    <div class="project-count">15/20 tasks</div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill high" style="width: 75%"></div>
                                </div>
                                <div class="progress-label">Progress: 75%</div>
                            </div>
                            
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">Mobile App Development</div>
                                    <div class="project-count">9/20 tasks</div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill medium" style="width: 45%"></div>
                                </div>
                                <div class="progress-label">Progress: 45%</div>
                            </div>
                            
                            <div class="project-card">
                                <div class="project-header">
                                    <div class="project-title">Marketing Campaign</div>
                                    <div class="project-count">18/20 tasks</div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill high" style="width: 90%"></div>
                                </div>
                                <div class="progress-label">Progress: 90%</div>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="side-section">
                        <div class="section recent-tasks">
                            <div class="section-header">
                                <h2 class="section-title">Recent Tasks</h2>
                                <div class="section-count"><?= count($recentTasks) ?> tasks</div>
                            </div>
                            
                            <?php foreach ($recentTasks as $task): ?>
                                <div class="task-item">
                                    <div class="task-icon <?= strtolower($task['status']) ?>">
                                        <?php
                                        switch(strtolower($task['status'])) {
                                            case 'completed':
                                                echo '✓';
                                                break;
                                            case 'overdue':
                                                echo '⏰';
                                                break;
                                            default:
                                                echo '⏱️';
                                        }
                                        ?>
                                    </div>
                                    <div class="task-content">
                                        <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                                        <div class="task-date">
                                            <?php
                                            $date = new DateTime($task['created_at']);
                                            $now = new DateTime();
                                            $interval = $date->diff($now);
                                            
                                            if ($interval->days == 0) {
                                                echo 'Today';
                                            } elseif ($interval->days == 1) {
                                                echo 'Yesterday';
                                            } else {
                                                echo $date->format('M d');
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <span class="task-status status-<?= strtolower($task['status']) ?>">
                                        <?= ucfirst($task['status']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Members View (Hidden by default, shown when active) -->
            <div class="team-view" style="display:none;">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Team Members</h1>
                    </div>
                    <button class="add-user-btn">
                        <i class="fa-solid fa-user-plus"></i> Add New User
                    </button>
                </div>
                
                
                <div class="section">
                    <table class="team-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Tasks</th>
                                <th>Actions</th>  <!-- New column -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($members)): ?>
                            <tr><td colspan="5">No members found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($members as $m): ?>
                                <tr>
                                    <td>
                                        <div class="member-info">
                                            <div class="member-avatar">
                                                <?php 
                                                  // initials
                                                  $parts = explode(' ', $m['fullname']);
                                                  echo strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
                                                ?>
                                            </div>
                                            <div>
                                                <div class="member-name"><?= htmlspecialchars($m['fullname']) ?></div>
                                                <div class="member-email"><?= htmlspecialchars($m['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($m['role']) ?></td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td>—</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit-user-btn" data-id="<?= $m['id'] ?>" 
                                                    data-name="<?= htmlspecialchars($m['fullname']) ?>" 
                                                    data-email="<?= htmlspecialchars($m['email']) ?>" 
                                                    data-role="<?= htmlspecialchars($m['role']) ?>">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button class="delete-user-btn" data-id="<?= $m['id'] ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Add User Popup -->
    <div class="popup-overlay" id="addUserPopup" style="display: none;">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Add New Team Member</h2>
                <button class="close-popup"><i class="fa-solid fa-times"></i></button>
            </div>
            <form id="addUserForm" method="post">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Add Member</button>
                    <button type="button" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Popup -->
    <div class="popup-overlay" id="editUserPopup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Edit Team Member</h2>
            <button class="close-popup"><i class="fa-solid fa-times"></i></button>
        </div>
        <form id="editUserForm" method="post">
            <input type="hidden" id="editUserId" name="userId">
            <div class="form-group">
                <label for="editFullName">Full Name</label>
                <input type="text" id="editFullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" id="editEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="editRole">Role</label>
                <select id="editRole" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-btn">Save Changes</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>

    <!-- Delete Confirmation Popup -->
    <div class="popup-overlay" id="deleteConfirmPopup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Confirm Delete</h2>
            <button class="close-popup"><i class="fa-solid fa-times"></i></button>
        </div>
        <div class="popup-body">
            <p>Are you sure you want to delete this user?</p>
            <div class="form-buttons">
                <button type="button" class="delete-confirm-btn">Delete</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>
</div>

    <!-- User Created Confirmation Popup -->
    <div class="popup-overlay" id="addUserConfirmPopup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>User Created Successfully</h2>
            <button class="close-popup"><i class="fa-solid fa-times"></i></button>
        </div>
        <div class="popup-body">
            <p>New user has been created successfully!</p>
            <div class="password-box">
                <p>Temporary Password:</p>
                <code id="tempPassword"></code>
                <button id="copyPassword" class="copy-btn">
                    <i class="fa-solid fa-copy"></i> Copy
                </button>
            </div>
            <div class="form-buttons">
                <button type="button" class="submit-btn">OK</button>
            </div>
        </div>
    </div>
</div>

    <div id="notification" class="notification" style="display: none;">
        <div class="notification-content">
            <i class="fa-solid fa-check-circle"></i>
            <span id="notification-message"></span>
        </div>
    </div>

    <script src="/Principles_Project/assets/js/dashboard.js"></script>
</body>
</html>