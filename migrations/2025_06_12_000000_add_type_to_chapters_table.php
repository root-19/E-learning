<?php

require_once __DIR__ . '/../config/database.php';

class AddTypeToChaptersTable {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function up() {
        try {
            $sql = "ALTER TABLE chapters ADD COLUMN type VARCHAR(50) DEFAULT 'traditional'";
            $this->pdo->exec($sql);
            echo "'type' column added to chapters table successfully.\n";
        } catch (PDOException $e) {
            echo "Error adding 'type' column to chapters table: " . $e->getMessage() . "\n";
        }
    }

    public function down() {
        try {
            $sql = "ALTER TABLE chapters DROP COLUMN type";
            $this->pdo->exec($sql);
            echo "'type' column removed from chapters table successfully.\n";
        } catch (PDOException $e) {
            echo "Error removing 'type' column from chapters table: " . $e->getMessage() . "\n";
        }
    }
} 