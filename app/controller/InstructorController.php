<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/database.php';

use root_dev\Models\User;
use PDO;
use PDOException;

class InstructorController {
    private $courseModel;

    public function __construct() {
        // Initialize the course model
        require_once 'app/models/CourseModel.php';
        $this->courseModel = new \root_dev\Model\CourseModel();
    }

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

    public function editCourse() {
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = 'Course ID is required';
            header('Location: /instructor/module');
            exit;
        }

        $courseId = $_GET['id'];
        $course = $this->courseModel->getCourseById($courseId);

        if (!$course) {
            $_SESSION['error_message'] = 'Course not found';
            header('Location: /instructor/module');
            exit;
        }

        // Make course data available to the view
        $viewData = [
            'course' => $course
        ];
        
        // Load the view with the data
        require_once 'app/views/instructor/edit-course.php';
    }

    public function updateCourse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /instructor/module');
            exit;
        }

        $courseId = $_POST['course_id'];
        $courseTitle = $_POST['course_title'];
        $courseType = $_POST['course_type'];
        $description = $_POST['description'];

        // Handle image upload if a new image is provided
        $courseImage = null;
        if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/courses/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['course_image']['tmp_name'], $uploadPath)) {
                    $courseImage = 'uploads/courses/' . $newFileName;
                }
            }
        }

        // Update course in database
        $success = $this->courseModel->updateCourse($courseId, [
            'course_title' => $courseTitle,
            'type' => $courseType,
            'description' => $description,
            'course_image' => $courseImage
        ]);

        if ($success) {
            $_SESSION['success_message'] = 'Course updated successfully';
        } else {
            $_SESSION['error_message'] = 'Failed to update course';
        }

        header('Location: /instructor/module');
        exit;
    }
}
