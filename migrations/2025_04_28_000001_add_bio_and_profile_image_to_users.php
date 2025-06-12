<?php

class AddBioAndProfileImageToUsers {
    public function up($pdo) {
        $query = "
            ALTER TABLE users
            ADD COLUMN IF NOT EXISTS bio TEXT,
            ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255)
        ";
        $pdo->exec($query);
        echo "Added bio and profile_image columns to users table successfully.\n";
    }

    public function down($pdo) {
        $query = "
            ALTER TABLE users
            DROP COLUMN IF EXISTS bio,
            DROP COLUMN IF EXISTS profile_image
        ";
        $pdo->exec($query);
        echo "Removed bio and profile_image columns from users table successfully.\n";
    }
} 