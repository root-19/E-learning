<?php
namespace root_dev\Model;

class CourseModel {
    private $db;

    public function __construct() {
        try {
            // Get database connection using the singleton pattern
            $this->db = \DatabaseConnection::connect();
        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }

    public function getCourseById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error getting course: " . $e->getMessage());
            return false;
        }
    }

    public function updateCourse($courseId, $data) {
        try {
            $sql = "UPDATE courses SET 
                    course_title = :course_title,
                    type = :type,
                    description = :description";

            // Only update image if a new one is provided
            if (!empty($data['course_image'])) {
                $sql .= ", course_image = :course_image";
            }

            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $params = [
                ':id' => $courseId,
                ':course_title' => $data['course_title'],
                ':type' => $data['type'],
                ':description' => $data['description']
            ];

            if (!empty($data['course_image'])) {
                $params[':course_image'] = $data['course_image'];
            }

            return $stmt->execute($params);
        } catch (\PDOException $e) {
            error_log("Error updating course: " . $e->getMessage());
            return false;
        }
    }
} 