<?php

namespace App\Controller;

use root_dev\Models\User;

class UpdateController {
    private $user;
    private $logFile;
    private $uploadDir;

    public function __construct() {
        $this->user = new User();
        $this->logFile = __DIR__ . '/../views/instructor/error_log.txt';
        $this->uploadDir = __DIR__ . '/../../uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function updateProfile($userId, $bio, $imageName) {
        try {
            error_log("UpdateController - Starting profile update for user $userId");
            error_log("Bio: " . $bio);
            error_log("Image name: " . $imageName);

            // Get current user data
            $currentUser = $this->user->getUserById($userId);

            // Handle image upload if a new image was provided
            if (!empty($imageName) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../uploads/';
                $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);

                // Create uploads directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move the uploaded file
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                    error_log("File uploaded successfully to: " . $uploadFile);
                    $imageName = 'uploads/' . basename($_FILES['profile_image']['name']);
                } else {
                    error_log("Failed to move uploaded file");
                    $_SESSION['error'] = "Failed to upload image";
                    return false;
                }
            } else {
                // No new image uploaded, keep the old image
                $imageName = $currentUser['profile_image'];
            }

            // Update the database
            $user = new User();
            if ($user->updateProfile($userId, $bio, $imageName)) {
                error_log("Profile updated successfully in database");
                return true;
            } else {
                error_log("Failed to update profile in database");
                $_SESSION['error'] = "Failed to update profile";
                return false;
            }
        } catch (\Exception $e) {
            error_log("Error in updateProfile: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while updating profile";
            return false;
        }
    }

    public function updatePassword($userId, $newPassword, $confirmPassword) {
        try {
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match.";
                $this->logError("Password mismatch for user $userId");
                return false;
            }

            if ($this->user->updatePasswordById($userId, $newPassword)) {
                $_SESSION['success'] = "Password updated successfully!";
                $this->logError("Password updated for user $userId");
                return true;
            }

            $_SESSION['error'] = "Failed to update password.";
            $this->logError("Failed to update password for user $userId");
            return false;
        } catch (\Exception $e) {
            $this->logError("Password update error: " . $e->getMessage());
            return false;
        }
    }

    private function logError($message) {
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
    }
} 