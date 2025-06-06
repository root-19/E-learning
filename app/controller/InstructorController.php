<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

use root_dev\Models\User;
use PDO;
use PDOException;

class InstructorController {
    public function registerInstructor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "Post request received<br>";

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Collecting data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Assigning role to 'instructor'
            $role = 'instructor';
           error_log($role);

            // Handle file upload
            $image = $_FILES['profile_image'];
            $uploadDir = __DIR__ . '/../../uploads/';
            $imageName = uniqid() . '_' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            if (getimagesize($image['tmp_name']) === false) {
                $_SESSION['error'] = "The uploaded file is not a valid image.";
                return false;
            }

            if ($image['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = "The uploaded image is too large (max 2MB).";
                return false;
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $_SESSION['error'] = "Failed to upload image.";
                return false;
            }

            // Instantiate User and call register method
            $user = new User();
            if ($user->registerInstructor($username, $email, $password, $imageName, $role)) {
                echo "Instructor registered successfully.<br>";
                return true;
            } else {
                $_SESSION['error'] = "Error registering instructor.";
                return false;
            }
        }
    }

    public function toggleStatus($instructorId) {
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

        try {
            $db = \Database::connect();
            $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'instructor'");
            $result = $stmt->execute([$newStatus, $instructorId]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
            error_log("Error updating instructor status: " . $e->getMessage());
        }
    }

    public function deleteInstructor($instructorId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            $db = \Database::connect();
            
            // Start transaction
            $db->beginTransaction();

            // Delete instructor's courses
            $stmt = $db->prepare("DELETE FROM courses WHERE instructor_id = ?");
            $stmt->execute([$instructorId]);

            // Delete instructor
            $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'instructor'");
            $result = $stmt->execute([$instructorId]);

            if ($result) {
                $db->commit();
                echo json_encode(['success' => true, 'message' => 'Instructor deleted successfully']);
            } else {
                $db->rollBack();
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete instructor']);
            }
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
            error_log("Error deleting instructor: " . $e->getMessage());
        }
    }
}
