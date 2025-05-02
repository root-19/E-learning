<?php

namespace root_dev\Models;


require_once __DIR__ . '/../../config/database.php';
use \Database;



class User {

    // Check if email exists
    public function emailExists($email) {
        $db = Database::connect();
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Get user data by email
    public function getUserByEmail($email) {
        $db = Database::connect();
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }

    // Register a new user
    public function register($username, $email, $password){
        $db = Database::connect();
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }
    
        // Register a new user
        public function registerInstructor($username, $email, $password, $imageName) {
            $db = Database::connect();
        
            // Prepare the query
            $query = "INSERT INTO users (username, email, password, profile_image, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
        
            // Check if the query was successful
            if ($stmt->execute([
                $username, 
                $email, 
                password_hash($password, PASSWORD_DEFAULT), 
                $imageName,
                'instructor'
            ])) {
                return true; // Success
            } else {
                // Get the error if query fails
                $_SESSION['error'] = "Error registering instructor: " . implode(", ", $stmt->errorInfo());
                return false;
            }
        }
        
        

    //update password
    public function updatePasswordByEmail($email, $newPassword) {
        $db = Database::connect();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $db->prepare($query);
    
        return $stmt->execute([$hashedPassword, $email]);
    }

    public function getUserById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $bio, $imageName) {
        $db = Database::connect();
        $query = "UPDATE users SET bio = ?, profile_image = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$bio, $imageName, $id]);
    }
    
}
