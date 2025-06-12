<?php

class AddMediaToChapters {
    public function up($pdo) {
        $query = "
            ALTER TABLE chapters
            ADD COLUMN media_type ENUM('none', 'image', 'video', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar') DEFAULT 'none',
            ADD COLUMN media_path VARCHAR(255) DEFAULT NULL,
            ADD COLUMN media_caption TEXT DEFAULT NULL
        ";
        $pdo->exec($query);
        echo "Added media support to chapters table successfully.\n";
    }

    public function down($pdo) {
        $query = "
            ALTER TABLE chapters
            DROP COLUMN media_type,
            DROP COLUMN media_path,
            DROP COLUMN media_caption
        ";
        $pdo->exec($query);
        echo "Removed media support from chapters table successfully.\n";
    }
} 