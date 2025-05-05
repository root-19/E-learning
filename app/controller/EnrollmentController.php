<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../../config/database.php';

class EnrollmentController {
    private $conn;

    public function __construct() {
        $this->conn = \Database::connect();
    }

    public function enroll() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /my_learning');
            exit();
        }

        $course_id = $_POST['course_id'] ?? 0;
        $user_id = $_SESSION['user_id'];

        // Check if already enrolled
        $stmt = $this->conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->execute([$user_id, $course_id]);
        if ($stmt->fetch()) {
            header('Location: /course-view/' . $course_id);
            exit();
        }

        // Create enrollment
        $stmt = $this->conn->prepare("INSERT INTO enrollments (user_id, course_id, enrolled_date) VALUES (?, ?, NOW())");
        if ($stmt->execute([$user_id, $course_id])) {
            header('Location: /course-view/' . $course_id);
        } else {
            header('Location: /my_learning?error=enrollment_failed');
        }
        exit();
    }
} 