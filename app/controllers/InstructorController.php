<?php

class InstructorController {
    public function editCourse() {
        if (!isset($_GET['id'])) {
            header('Location: /instructor/module');
            exit;
        }

        $courseId = $_GET['id'];
        $course = $this->courseModel->getCourseById($courseId);

        if (!$course) {
            header('Location: /instructor/module');
            exit;
        }

        require_once 'app/views/instructor/edit-course.php';
    }

    public function updateCourse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /instructor/module');
            exit;
        }

        $courseId = $_POST['course_id'];
        $courseTitle = $_POST['course_title'];
        $courseType = $_POST['course_type'];
        $description = $_POST['description'];

        // Handle image upload if a new image is provided
        $courseImage = null;
        if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/courses/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['course_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['course_image']['tmp_name'], $uploadPath)) {
                    $courseImage = $uploadPath;
                }
            }
        }

        // Update course in database
        $success = $this->courseModel->updateCourse($courseId, [
            'course_title' => $courseTitle,
            'type' => $courseType,
            'description' => $description,
            'course_image' => $courseImage
        ]);

        if ($success) {
            $_SESSION['success_message'] = 'Course updated successfully';
        } else {
            $_SESSION['error_message'] = 'Failed to update course';
        }

        header('Location: /instructor/module');
        exit;
    }
} 