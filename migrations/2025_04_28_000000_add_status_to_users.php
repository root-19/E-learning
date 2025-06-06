<?php

class AddStatusToUsers {
    public function up($pdo) {
        $query = "
            ALTER TABLE users
            ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
        ";
        $pdo->exec($query);
        echo "Added status column to users table successfully.\n";
    }

    public function down($pdo) {
        $query = "ALTER TABLE users DROP COLUMN status";
        $pdo->exec($query);
        echo "Removed status column from users table successfully.\n";
    }
} 