<?php
session_start();

use root_dev\Controller\AuthController;
use root_dev\Controller\EnrollmentController;
use root_dev\Controller\QuizController;
use root_dev\Controller\CourseController;
use root_dev\Controller\NotificationController;

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/EnrollmentController.php';
require_once __DIR__ . '/../app/controller/QuizController.php';
require_once __DIR__ . '/../app/controller/CourseController.php';
require_once __DIR__ . '/../app/controller/NotificationController.php';

// Define routes as [handler_type, action, is_protected, required_role] 
$routes = [
    '/' => ['redirect', 'login', false],
    '/login' => [AuthController::class, 'login', false],
    '/logout' => [AuthController::class, 'logout', true],
    '/register' => [AuthController::class, 'register', false],
    '/forget-password' => [AuthController::class, 'forgetPassword', false],

    // Routes accessible to 'user'
    '/dashboard' => ['view', 'dashboard', true, 'user'],
    '/my_learning' => ['view', 'my_learning', true, 'user'],
    '/about' => ['view', 'about', true, 'user'],

    '/contact' => ['view', 'contact', true, 'user'],
    '/edit-profile' => ['view', 'edit-profile', true, 'user'],
    '/announcement' => ['view', 'announcement', true, 'user'],
    '/enroll' => [EnrollmentController::class, 'enroll', true, 'user'],
    '/course-view/{id}' => ['view', 'course-view', true, 'user'],
    '/chapter/{id}' => ['view', 'chapter-view', true, 'user'],

    '/submit-quiz' => [QuizController::class, 'submitQuiz', true, 'user'],

    //Routes for instructor 
    '/instructor/dashboard' => ['view', 'instructor/dashboard', true, 'instructor'],
    '/instructor/module' => ['view', 'instructor/module', true, 'instructor'],
    '/instructor/chapter' => ['view', 'instructor/chapter', true, 'instructor'],
    '/instructor/interactive_chapter' => ['view', 'instructor/interactive_chapter', true, 'instructor'],
    '/instructor/learning-modules' => ['view', 'learning_modules', true, 'instructor'],
    '/instructor/announcements' => ['view', 'instructor/announcements', true, 'instructor'],
    '/instructor/profile' => ['view', 'instructor/profile', true, 'instructor'],
    '/instructor/notifications' => ['view', 'instructor/notifications', true, 'instructor'],

    // Routes accessible to 'admin'
    '/admin/dashboard' => ['view', 'admin/dashboard', true, 'admin'],
    '/admin/users' => ['view', 'admin/users', true, 'admin'],
    '/admin/instractor' => ['view', 'admin/instractor', true, 'admin'],
    '/admin/course' => ['view', 'admin/course', true, 'admin'],
    '/admin/course/view/{id}' => ['view', 'admin/course-view', true, 'admin'],
    '/admin/announcement' => ['view', 'admin/announcement', true, 'admin'],
    '/my-learning' => ['view', 'my_learning', true],
    
    // API Routes
    '/api/course/toggle-status/{id}' => [CourseController::class, 'toggleStatus', true, 'admin'],
    '/api/course/reject/{id}' => [CourseController::class, 'rejectCourse', true, 'admin'],
    '/api/notifications/mark-read' => [NotificationController::class, 'markNotificationsAsRead', true, 'instructor'],
];

// Get the current path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check for dynamic routes first
$dynamicRoute = null;
$params = [];

foreach ($routes as $pattern => $route) {
    if (strpos($pattern, '{') !== false) {
        $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regex = str_replace('/', '\/', $regex);
        if (preg_match('/^' . $regex . '$/', $uri, $matches)) {
            $dynamicRoute = $route;
            array_shift($matches); // Remove full match
            $params = $matches;
            break;
        }
    }
}

// Route handling logic
if ($dynamicRoute) {
    [$handler, $action, $isProtected, $requiredRole] = array_pad($dynamicRoute, 4, null);
    $_GET['id'] = $params[0] ?? null; // Set the ID parameter
} elseif (isset($routes[$uri])) {
    [$handler, $action, $isProtected, $requiredRole] = array_pad($routes[$uri], 4, null);
} else {
    // Fallback for unknown routes
    http_response_code(404);
    echo "404 Not Found: Route [$uri]";
    exit();
}

// Middleware: Check login
if ($isProtected) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit();
    }

    // Middleware: Check role
    if ($requiredRole && (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole)) {
        http_response_code(403);
        echo "403 Forbidden: You do not have access to this page.";
        exit();
    }
}

// Route type logic
if ($handler === 'redirect') {
    header("Location: ./$action");
    exit();
} elseif ($handler === 'view') {
    require_once __DIR__ . "/../app/views/$action.php";
} else {
    // Handle controller actions
    $controller = new $handler();
    if (method_exists($controller, $action)) {
        $controller->$action($_GET['id'] ?? null);
    } else {
        http_response_code(404);
        echo "404 Not Found: Action [$action] not found in controller";
        exit();
    }
}
