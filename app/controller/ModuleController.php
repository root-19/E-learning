<?php
require_once __DIR__ . '/../models/Module.php';
// use root_dev\Models\Module;
class ModuleController {
    public function create($course_title, $course_image, $description) {
        $module = new Module();
        return $module->createCourse($course_title, $course_image, $description);
    }

    public function listCourses() {
        $module = new Module();
        return $module->getAllCourses();
    }

    public function getChaptersForCourse($courseId) {
        $module = new Module();
        return $module->getChaptersByCourseId($courseId);
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $course_title = $_POST['course_title'];
    $description = $_POST['description'];

    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        // Create uploads directory if it doesn't exist
        $upload_dir = __DIR__ . '/../../uploads/courses/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $file_name = time() . '_' . basename($_FILES['course_image']['name']);
        $file_path = $upload_dir . $file_name;

        // Move uploaded file
        if (move_uploaded_file($_FILES['course_image']['tmp_name'], $file_path)) {
            // Save relative path to database
            $course_image = 'uploads/courses/' . $file_name;

            $controller = new ModuleController();
            if ($controller->create($course_title, $course_image, $description)) {
                $success_message = "Course created successfully!";
                header("location: /instructor/module");
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
// $controller = new ModuleController();
// $courses = $controller->listCourses();
