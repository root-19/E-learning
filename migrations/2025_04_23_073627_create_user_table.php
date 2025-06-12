<?php

class CreateUserTable {
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('user', 'admin', 'instructor') NOT NULL DEFAULT 'user',
                bio TEXT,
                profile_image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $pdo->exec($query);
    }

    public function down($pdo) {
        $query = "DROP TABLE IF EXISTS users";
        $pdo->exec($query);
    }
}
