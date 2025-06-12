<?php

class CreateChaptersAndQuizzesTable {
    public function up($pdo) {
        // Create chapters table
        $query = "
            CREATE TABLE IF NOT EXISTS chapters (
                id INT AUTO_INCREMENT PRIMARY KEY,
                course_id INT NOT NULL,
                chapter_title VARCHAR(255) NOT NULL,
                chapter_content TEXT NOT NULL,
                media_type ENUM('none', 'image', 'video') DEFAULT 'none',
                media_path VARCHAR(255),
                media_caption TEXT,
                order_number INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
            )
        ";
        $pdo->exec($query);
        echo "Chapters table created successfully.\n";

        // Create quizzes table
        $query = "
            CREATE TABLE IF NOT EXISTS quizzes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                chapter_id INT NOT NULL,
                question TEXT NOT NULL,
                option_a TEXT NOT NULL,
                option_b TEXT NOT NULL,
                option_c TEXT NOT NULL,
                option_d TEXT NOT NULL,
                correct_answer CHAR(1) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
            )
        ";
        $pdo->exec($query);
        echo "Quizzes table created successfully.\n";

        // Create quiz_attempts table for tracking user answers
        $query = "
            CREATE TABLE IF NOT EXISTS quiz_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                quiz_id INT NOT NULL,
                selected_answer CHAR(1) NOT NULL,
                is_correct BOOLEAN NOT NULL,
                attempt_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
            )
        ";
        $pdo->exec($query);
        echo "Quiz attempts table created successfully.\n";
    }

    public function down($pdo) {
        // Drop tables in reverse order due to foreign key constraints
        $pdo->exec("DROP TABLE IF EXISTS quiz_attempts");
        $pdo->exec("DROP TABLE IF EXISTS quizzes");
        $pdo->exec("DROP TABLE IF EXISTS chapters");
        echo "Tables dropped successfully.\n";
    }
} 