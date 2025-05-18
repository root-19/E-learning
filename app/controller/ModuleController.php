<?php
require_once __DIR__ . '/../models/Module.php';

class ModuleController {
    public function create($course_title, $course_image, $description) {
        $module = new Module();
        return $module->createCourse($course_title, $course_image, $description);
    }

    public function listCourses() {
        $module = new Module();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        return $module->getAllCourses($page);
    }

    public function getCourseById($courseId) {
        $module = new Module();
        return $module->getCourseById($courseId);
    }

    public function getChaptersForCourse($courseId) {
        $module = new Module();
        return $module->getChaptersByCourseId($courseId);
    }

    public function getCourseStatus($id) {
        $module = new Module();
        return $module->getCourseStatus($id);
    }

    public function updateCourseStatus($id, $status) {
        $module = new Module();
        return $module->updateCourseStatus($id, $status);
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $course_title = $_POST['course_title'];
    $description = $_POST['description'];

    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../../uploads/courses/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . '_' . basename($_FILES['course_image']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['course_image']['tmp_name'], $file_path)) {
            $course_image = 'uploads/courses/' . $file_name;

            $controller = new ModuleController();
            if ($controller->create($course_title, $course_image, $description)) {
                header("Location: /instructor/module");
                exit();
            } else {
                $error_message = "Failed to create course.";
            }
        } else {
            $error_message = "Failed to upload image.";
        }
    } else {
        $error_message = "Please select an image.";
    }
}

// Get all courses
$controller = new ModuleController();
$courses = $controller->listCourses();
