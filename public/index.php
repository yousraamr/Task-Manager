<?php

$route = $_GET['route'] ?? '';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/TaskController.php';
require_once __DIR__ . '/../controllers/AdminController.php';


$authController = new AuthController();
$userController = new UserController();
$taskController = new TaskController();
$adminController = new AdminController();


switch ($route) {
    case 'login':
        $authController->login();
        break;
    case 'signup':
        $authController->signup();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'checkLogin':
        $authController->checkLogin();
        break;
    case 'getMembers':
        $userController->getMembers();
        break;
    case 'users/add':
        $userController->addUser();
        break;
    case 'users/delete':
        $userController->deleteUser();
        break;
    case 'users/update':
        $userController->updateUser();
        break;
    case 'users/find':
        $userController->findUserById();
        break;
    case 'users/change-password':
        $userController->changePassword();
        break;

    // New Task routes
    case 'tasks':
        $taskController->index();
        break;
    case 'tasks/create':
        $taskController->create();
        break;
    case 'tasks/store':
        $taskController->store();
        break;

    case 'tasks/delete':
        $taskController->delete();
        break;
    case 'tasks/edit':
        $taskController->edit();
        break;
    case 'tasks/update':
        $taskController->update();
        break;

    case 'tasks/toggle-status':
        $taskController->toggleStatus();
        break;

        
    // Admin routes
    case 'admin_tasks':
        $adminController->index(); // View all tasks
        break;
    case 'admin_task_create':
        $adminController->create();
        break;
    case 'admin_task_store':
        $adminController->store();
        break;
    case 'admin_task_edit':
        $adminController->edit();
        break;
    case 'admin_task_update':
        $adminController->update();
        break;
    case 'admin_task_delete':
        $adminController->delete();
        break;
    case 'admin_task_toggle':
        $adminController->toggleStatus();
        break;
    case 'tasks/updateStatus':
        $adminController->updateStatus();
        break;
        
    default:
        echo json_encode(['error' => 'Invalid route.']);
        break;
}
