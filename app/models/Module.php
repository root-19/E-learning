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
}
