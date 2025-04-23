<?php

class CreateAnnouncementTable {
    public function up($pdo) {
        // Create the announcements table query
        $query = "
            CREATE TABLE IF NOT EXISTS announcements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                posted_by INT NOT NULL, 
                posted_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
                FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
            );
        ";

        // Execute the query
        try {
            $pdo->exec($query);
            echo "Announcements table created successfully.";
        } catch (PDOException $e) {
            die("Could not create announcements table: " . $e->getMessage());
        }
    }

    public function down($pdo) {
        // Drop the announcements table query
        $query = "DROP TABLE IF EXISTS announcements;";

        // Execute the query
        try {
            $pdo->exec($query);
            echo "Announcements table dropped successfully.";
        } catch (PDOException $e) {
            die("Could not drop announcements table: " . $e->getMessage());
        }
    }
}
