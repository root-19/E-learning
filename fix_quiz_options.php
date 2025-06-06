<?php
require_once __DIR__ . '/config/database.php';

try {
    $conn = Database::connect();
    
    // Modify all option columns to allow NULL values
    $sql = "ALTER TABLE quizzes 
            MODIFY COLUMN option_a VARCHAR(255) NULL,
            MODIFY COLUMN option_b VARCHAR(255) NULL,
            MODIFY COLUMN option_c VARCHAR(255) NULL,
            MODIFY COLUMN option_d VARCHAR(255) NULL";
    
    $conn->exec($sql);
    
    echo "Successfully modified quiz option columns to allow NULL values!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 