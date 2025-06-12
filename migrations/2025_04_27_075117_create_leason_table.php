<?php

class CreateLessonTable {
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS lessons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                course_id INT NOT NULL,
                lesson_title VARCHAR(255) NOT NULL,
                lesson_content TEXT NOT NULL,
                lesson_video VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
            )
        ";
        $pdo->exec($query);
        echo "Lessons table created successfully.\n";
    }

    public function down($pdo) {
        $query = "DROP TABLE IF EXISTS lessons";
        $pdo->exec($query);
        echo "Lessons table dropped successfully.\n";
    }
}
