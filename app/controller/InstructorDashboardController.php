<?php
require_once __DIR__ . '/../config/database.php';

class InstructorDashboardController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getDashboardData($instructor_id) {
        try {
            // Get total courses
            $stmtCourses = $this->db->prepare("
                SELECT COUNT(*) FROM courses WHERE instructor_id = ?
            ");
            $stmtCourses->execute([$instructor_id]);
            $total_courses = $stmtCourses->fetchColumn();

            // Get total students (unique students across all courses)
            $stmtStudents = $this->db->prepare("
                SELECT COUNT(DISTINCT e.user_id)
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE c.instructor_id = ?
            ");
            $stmtStudents->execute([$instructor_id]);
            $total_students = $stmtStudents->fetchColumn();

            // Get total completed courses (at least one student completed)
            $stmtCompletedCourses = $this->db->prepare("
                SELECT COUNT(DISTINCT c.id)
                FROM courses c
                JOIN enrollments e ON c.id = e.course_id
                WHERE c.instructor_id = ? AND e.is_completed = 1
            ");
            $stmtCompletedCourses->execute([$instructor_id]);
            $total_completed_courses = $stmtCompletedCourses->fetchColumn();

            // Get top performing courses based on enrollment count
            $stmtTopCourses = $this->db->prepare("
                SELECT c.course_title, COUNT(DISTINCT e.user_id) as enrollment_count
                FROM courses c
                LEFT JOIN enrollments e ON c.id = e.course_id
                WHERE c.instructor_id = ?
                GROUP BY c.id, c.course_title
                ORDER BY enrollment_count DESC
                LIMIT 3
            ");
            $stmtTopCourses->execute([$instructor_id]);
            $top_courses = $stmtTopCourses->fetchAll(PDO::FETCH_ASSOC);

            // Get recent activities
            $stmtActivities = $this->db->prepare("
                SELECT 
                    'New enrollment in ' || c.course_title as description,
                    e.enrollment_date as date,
                    'Active' as status
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE c.instructor_id = ?
                ORDER BY e.enrollment_date DESC
                LIMIT 5
            ");
            $stmtActivities->execute([$instructor_id]);
            $recent_activities = $stmtActivities->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total_courses' => $total_courses,
                'total_students' => $total_students,
                'total_completed_courses' => $total_completed_courses,
                'top_courses' => $top_courses,
                'recent_activities' => $recent_activities
            ];

        } catch (PDOException $e) {
            error_log("Error in getDashboardData: " . $e->getMessage());
            return [
                'total_courses' => 0,
                'total_students' => 0,
                'total_completed_courses' => 0,
                'top_courses' => [],
                'recent_activities' => []
            ];
        }
    }
} 