<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../../config/database.php';

class CourseController {
    private $db;
    private const STATUS_ACTIVE = 'active';
    private const STATUS_INACTIVE = 'inactive';

    public function __construct() {
        $this->db = \Database::connect();
    }

    /**
     * Validate admin access
     * @throws \Exception
     */
    private function validateAdminAccess(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            throw new \Exception('Unauthorized access', 403);
        }
    }

    /**
     * Get course by ID
     * @param int $courseId
     * @return array|false
     * @throws \Exception
     */
    private function getCourseById(int $courseId) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
        $course = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$course) {
            throw new \Exception('Course not found', 404);
        }
        
        return $course;
    }

    /**
     * Toggle course status between active and inactive
     * @param int $courseId
     * @return void
     */
    public function toggleStatus(int $courseId): void {
        try {
            $this->validateAdminAccess();
            $course = $this->getCourseById($courseId);
            
            $newStatus = $course['status'] === self::STATUS_ACTIVE ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
            
            $stmt = $this->db->prepare("UPDATE courses SET status = ? WHERE id = ?");
            $result = $stmt->execute([$newStatus, $courseId]);
            
            if (!$result) {
                throw new \Exception('Failed to update course status', 500);
            }
            
            echo json_encode([
                'success' => true,
                'new_status' => $newStatus,
                'message' => "Course status updated to {$newStatus}"
            ]);
            
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Reject a course
     * @param int $courseId
     * @return void
     */
    public function rejectCourse(int $courseId): void {
        try {
            $this->validateAdminAccess();
            $this->getCourseById($courseId);
            
            $stmt = $this->db->prepare("UPDATE courses SET is_rejected = 1 WHERE id = ?");
            $result = $stmt->execute([$courseId]);
            
            if (!$result) {
                throw new \Exception('Failed to reject course', 500);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Course rejected successfully'
            ]);
            
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
} 