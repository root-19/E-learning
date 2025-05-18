<?php

class Module {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function createCourse($course_title, $course_image, $description) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO courses (course_title, course_image, description) VALUES (:course_title, :course_image, :description)");
            $stmt->bindParam(':course_title', $course_title);
            $stmt->bindParam(':course_image', $course_image);
            $stmt->bindParam(':description', $description);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating course: " . $e->getMessage());
            return false;
        }
    }

    public function getAllCourses($page = 1, $limit = 5) {
        try {
            $offset = ($page - 1) * $limit;
            
            // Get total count for pagination
            $countStmt = $this->conn->query("SELECT COUNT(*) FROM courses WHERE status != 'deleted'");
            $total = $countStmt->fetchColumn();
            
            // Get paginated results with chapter count
            $stmt = $this->conn->prepare("
                SELECT c.*, 
                       (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as chapter_count
                FROM courses c
                WHERE c.status != 'deleted' 
                ORDER BY c.id DESC 
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'courses' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching courses: " . $e->getMessage());
            return ['courses' => [], 'total' => 0, 'total_pages' => 0];
        }
    }

    public function getCourseById($courseId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = ? AND status != 'deleted'");
            $stmt->execute([$courseId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching course: " . $e->getMessage());
            return null;
        }
    }

    public function getChaptersByCourseId($courseId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT c.*, 
                       CASE 
                           WHEN EXISTS (SELECT 1 FROM quizzes q WHERE q.chapter_id = c.id) THEN 'interactive'
                           ELSE 'traditional'
                       END as type
                FROM chapters c
                WHERE c.course_id = :course_id
                ORDER BY c.id ASC
            ");
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching chapters: " . $e->getMessage());
            return [];
        }
    }

    public function getCourseStatus($id) {
        try {
            $stmt = $this->conn->prepare("SELECT status FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error fetching course status: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateCourseStatus($id, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE courses SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("Error updating course status: " . $e->getMessage());
            return false;
        }
    }
}
