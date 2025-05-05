<?php

class CreateEnrollmentsTable {
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS enrollments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                course_id INT NOT NULL,
                enrolled_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
                completion_date TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
                UNIQUE KEY unique_enrollment (user_id, course_id)
            )
        ";
        $pdo->exec($query);
        echo "Enrollments table created successfully.\n";
    }

    public function down($pdo) {
        $query = "DROP TABLE IF EXISTS enrollments";
        $pdo->exec($query);
        echo "Enrollments table dropped successfully.\n";
    }
} 