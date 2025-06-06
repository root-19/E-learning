<?php
require_once __DIR__ . '/config/database.php';

try {
    $conn = Database::connect();
    
    // First, modify the columns to allow NULL values
    $sql = "ALTER TABLE quizzes 
            MODIFY COLUMN option_a VARCHAR(255) NULL,
            MODIFY COLUMN option_b VARCHAR(255) NULL,
            MODIFY COLUMN option_c VARCHAR(255) NULL,
            MODIFY COLUMN option_d VARCHAR(255) NULL";
    
    $conn->exec($sql);
    
    // Then, update existing records to have NULL values for unused options
    $sql = "UPDATE quizzes 
            SET option_c = NULL, 
                option_d = NULL 
            WHERE quiz_type = 'true_false'";
    $conn->exec($sql);
    
    $sql = "UPDATE quizzes 
            SET option_a = NULL, 
                option_b = NULL, 
                option_c = NULL, 
                option_d = NULL 
            WHERE quiz_type = 'fill_blank'";
    $conn->exec($sql);
    
    echo "Successfully modified quiz table structure!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 