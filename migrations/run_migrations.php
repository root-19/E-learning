<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/create_enrollments_table.php';
require_once __DIR__ . '/create_chapters_and_quizzes_tables.php';

try {
    $pdo = Database::connect();
    
    // Run enrollments table migration
    $enrollmentsMigration = new CreateEnrollmentsTable();
    $enrollmentsMigration->up($pdo);
    
    // Run chapters and quizzes tables migration
    $chaptersQuizzesMigration = new CreateChaptersAndQuizzesTable();
    $chaptersQuizzesMigration->up($pdo);
    
    echo "All migrations completed successfully!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
} 