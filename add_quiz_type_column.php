<?php
require_once __DIR__ . '/config/database.php';

try {
    $conn = Database::connect();
    
    // Add quiz_type column
    $sql = "ALTER TABLE quizzes ADD COLUMN quiz_type VARCHAR(20) NOT NULL DEFAULT 'multiple_choice'";
    $conn->exec($sql);
    
    echo "Successfully added quiz_type column to quizzes table!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 