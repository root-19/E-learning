<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/create_enrollments_table.php';
require_once __DIR__ . '/create_chapters_and_quizzes_tables.php';
require_once __DIR__ . '/2025_06_12_000000_add_type_to_chapters_table.php';
require_once __DIR__ . '/2025_06_13_000000_add_quiz_type_to_quizzes_table.php';

try {
    $pdo = Database::connect();
    
    // Run enrollments table migration
    $enrollmentsMigration = new CreateEnrollmentsTable();
    $enrollmentsMigration->up($pdo);
    
    // Run chapters and quizzes tables migration
    $chaptersQuizzesMigration = new CreateChaptersAndQuizzesTable();
    $chaptersQuizzesMigration->up($pdo);

    // Run add type to chapters table migration
    $addTypeToChaptersMigration = new AddTypeToChaptersTable();
    $addTypeToChaptersMigration->up();

    // Run add quiz_type to quizzes table migration
    $addQuizTypeToQuizzesMigration = new AddQuizTypeToQuizzesTable();
    $addQuizTypeToQuizzesMigration->up();
    
    echo "All migrations completed successfully!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
} 