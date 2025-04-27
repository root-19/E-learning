<?php
require_once __DIR__ . '/../models/User.php';
use root_dev\Models\User;
class InstructorController {
    public function registerInstructor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "Post request received<br>";

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Collecting data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Assigning role to 'instructor'
            $role = 'instructor';
           error_log($role);

            // Handle file upload
            $image = $_FILES['profile_image'];
            $uploadDir = __DIR__ . '/../../uploads/';
            $imageName = uniqid() . '_' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            if (getimagesize($image['tmp_name']) === false) {
                $_SESSION['error'] = "The uploaded file is not a valid image.";
                return false;
            }

            if ($image['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = "The uploaded image is too large (max 2MB).";
                return false;
            }

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $_SESSION['error'] = "Failed to upload image.";
                return false;
            }

            // Instantiate User and call register method
            $user = new User();
            if ($user->registerInstructor($username, $email, $password, $imageName, $role)) {
                echo "Instructor registered successfully.<br>";
                return true;
            } else {
                $_SESSION['error'] = "Error registering instructor.";
                return false;
            }
        }
    }
}
