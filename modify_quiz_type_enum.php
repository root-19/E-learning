<?php
require_once __DIR__ . '/config/database.php';

try {
    $conn = Database::connect();
    
    // First, modify the quiz_type column to use ENUM
    $sql = "ALTER TABLE quizzes 
            MODIFY COLUMN quiz_type ENUM('multiple_choice', 'true_false', 'fill_blank') NOT NULL DEFAULT 'multiple_choice'";
    
    $conn->exec($sql);
    
    echo "Successfully modified quiz_type column to use ENUM!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 