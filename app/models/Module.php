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
            echo "Error creating course: " . $e->getMessage();
            return false;
        }
    }

    public function getAllCourses() {
        $stmt = $this->conn->query("SELECT * FROM courses ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            ");
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching chapters: " . $e->getMessage();
            return [];
        }
    }

    public function getCourseStatus($id) {
        $stmt = $this->conn->prepare("SELECT status FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
    
    public function updateCourseStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE courses SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
}
