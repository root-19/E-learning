<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../models/User.php'; 
use root_dev\Models\User;  

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $user = new User();
    
            if ($user->emailExists($email)) {
                $userData = $user->getUserByEmail($email);
    
                if (password_verify($password, $userData['password'])) {
                    // Start session
                    session_start();
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['role'] = $userData['role']; 

                    // Redirect based on role
                    if ($userData['role'] === 'admin') {
                        header('Location: /admin/dashboard');
                    } elseif ($userData['role'] === 'instructor') {
                        header('Location: /instructor/dashboard');
                    } else {
                        header('Location: /home'); // Default user role
                    }
                    exit();
                } else {
                    $error = "Invalid password.";
                    require_once __DIR__ . '/../../public/login.php';
                }
            } else {
                $error = "Email not found.";
                require_once __DIR__ . '/../../public/login.php';
            }
        } else {
            require_once __DIR__ . '/../../public/login.php';
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = isset($_POST['role']) ? $_POST['role'] : 'user'; // Default to 'user'
    
            $user = new User();
    
            if ($user->emailExists($email)) {
                $error = "Email is already registered.";
                require_once __DIR__ . '/../views/register.php';
            } else {
                if ($user->register($username, $email, $password, $role)) {
                    $userData = $user->getUserByEmail($email);
                    session_start();
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['role'] = $userData['role']; 
                    
                    header('Location: /dashboard');
                    exit();
                } else {
                    $error = "Failed to register. Please try again.";
                    require_once __DIR__ . '/../../public/register.php';
                }
            }
        } else {
            require_once __DIR__ . '/../../public/register.php';
        }
    }

    // Handle user logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit();
    }

    public function forgetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];
            $retypePassword = $_POST['retype_password'];
    
            $user = new User();
    
            if (!$user->emailExists($email)) {
                $error = "Email does not exist.";
                require_once __DIR__ . '/../../public/forget-password.php';
                return;
            }
    
            if ($newPassword !== $retypePassword) {
                $error = "Passwords do not match.";
                require_once __DIR__ . '/../../public/forget-password.php';
                return;
            }
    
            if ($user->updatePasswordByEmail($email, $newPassword)) {
                $success = "Password updated successfully. You can now log in.";
            } else {
                $error = "Failed to update password.";
            }
    
            require_once __DIR__ . '/../../public/forget-password.php';
        } else {
            require_once __DIR__ . '/../../public/forget-password.php';
        }
    }

    public function toggleUserStatus($userId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get the new status from the request body
        $data = json_decode(file_get_contents('php://input'), true);
        $newStatus = $data['status'] ?? null;

        if (!in_array($newStatus, ['active', 'inactive'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }

        $user = new User();
        if ($user->updateStatus($userId, $newStatus)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
        }
    }

    public function deleteUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user = new User();
        if ($user->delete($userId)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    }
}
