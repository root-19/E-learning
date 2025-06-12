<?php

require_once __DIR__ . '/../config/database.php';

class AddQuizTypeToQuizzesTable {
    private $pdo;

    public function __construct() {
        $this->pdo = \Database::connect();
    }

    public function up() {
        try {
            $sql = "ALTER TABLE quizzes ADD COLUMN quiz_type VARCHAR(50) DEFAULT 'multiple_choice'";
            $this->pdo->exec($sql);
            echo "'quiz_type' column added to quizzes table successfully.\n";
        } catch (PDOException $e) {
            echo "Error adding 'quiz_type' column to quizzes table: " . $e->getMessage() . "\n";
        }
    }

    public function down() {
        try {
            $sql = "ALTER TABLE quizzes DROP COLUMN quiz_type";
            $this->pdo->exec($sql);
            echo "'quiz_type' column removed from quizzes table successfully.\n";
        } catch (PDOException $e) {
            echo "Error removing 'quiz_type' column from quizzes table: " . $e->getMessage() . "\n";
        }
    }
} 