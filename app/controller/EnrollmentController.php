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

        if (!$course_id || !$user_id) {
            header('Location: /my_learning?error=invalid_request');
            exit();
        }

        $result = $this->enrollUser($user_id, $course_id);

        if ($result['success']) {
            header('Location: /course-view/' . $course_id);
        } else {
            header('Location: /my_learning?error=' . urlencode($result['message']));
        }
        exit();
    }

    public function enrollUser($user_id, $course_id) {
        try {
            // Check if already enrolled
            $stmt = $this->conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
            $stmt->execute([$user_id, $course_id]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Already enrolled in this course'];
            }

            // Start transaction
            $this->conn->beginTransaction();

            // Create enrollment
            $stmt = $this->conn->prepare("
                INSERT INTO enrollments (user_id, course_id, enrollment_date, completion_percentage, is_completed)
                VALUES (?, ?, CURRENT_TIMESTAMP, 0, FALSE)
            ");
            $stmt->execute([$user_id, $course_id]);

            // Initialize course progress for all chapters
            $stmt = $this->conn->prepare("
                INSERT INTO course_progress (user_id, course_id, chapter_id, is_completed, last_accessed)
                SELECT ?, ?, id, FALSE, CURRENT_TIMESTAMP
                FROM chapters
                WHERE course_id = ?
            ");
            $stmt->execute([$user_id, $course_id, $course_id]);

            $this->conn->commit();
            return ['success' => true, 'message' => 'Successfully enrolled in course'];
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            error_log("Enrollment error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error enrolling in course'];
        }
    }

    public function updateProgress($user_id, $course_id, $chapter_id) {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // First check if this chapter progress already exists
            $stmt = $this->conn->prepare("
                SELECT id FROM course_progress 
                WHERE user_id = ? AND course_id = ? AND chapter_id = ?
            ");
            $stmt->execute([$user_id, $course_id, $chapter_id]);
            
            if (!$stmt->fetch()) {
                // If no progress record exists, create one
                $stmt = $this->conn->prepare("
                    INSERT INTO course_progress (user_id, course_id, chapter_id, is_completed, last_accessed)
                    VALUES (?, ?, ?, TRUE, CURRENT_TIMESTAMP)
                ");
                $stmt->execute([$user_id, $course_id, $chapter_id]);
            } else {
                // Update existing progress
                $stmt = $this->conn->prepare("
                    UPDATE course_progress 
                    SET is_completed = TRUE, last_accessed = CURRENT_TIMESTAMP
                    WHERE user_id = ? AND course_id = ? AND chapter_id = ?
                ");
                $stmt->execute([$user_id, $course_id, $chapter_id]);
            }

            // Calculate new completion percentage
            $stmt = $this->conn->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM course_progress 
                     WHERE user_id = ? AND course_id = ? AND is_completed = TRUE) as completed_chapters,
                    (SELECT COUNT(*) FROM chapters WHERE course_id = ?) as total_chapters
            ");
            $stmt->execute([$user_id, $course_id, $course_id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            $completion_percentage = ($result['completed_chapters'] / $result['total_chapters']) * 100;
            $is_completed = $result['completed_chapters'] == $result['total_chapters'];

            // Update enrollment progress
            $stmt = $this->conn->prepare("
                UPDATE enrollments 
                SET completion_percentage = ?, is_completed = ?
                WHERE user_id = ? AND course_id = ?
            ");
            $stmt->execute([$completion_percentage, $is_completed, $user_id, $course_id]);

            $this->conn->commit();
            return [
                'success' => true,
                'completion_percentage' => $completion_percentage,
                'is_completed' => $is_completed
            ];
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            error_log("Progress update error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error updating progress'];
        }
    }

    public function getCourseProgress($user_id, $course_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT completion_percentage, is_completed
                FROM enrollments
                WHERE user_id = ? AND course_id = ?
            ");
            $stmt->execute([$user_id, $course_id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching course progress: " . $e->getMessage());
            return null;
        }
    }
} 