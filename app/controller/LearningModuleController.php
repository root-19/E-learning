<?php

namespace App\Controller;

class LearningModuleController {
    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new \PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    }

    public function index() {
        // Get all learning modules
        $stmt = $this->db->query("SELECT * FROM learning_modules WHERE status = 'active'");
        $modules = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Get chapters for each module
        foreach ($modules as &$module) {
            $stmt = $this->db->prepare("SELECT * FROM chapters WHERE module_id = ?");
            $stmt->execute([$module['id']]);
            $module['chapters'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $modules;
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Insert new module
            $stmt = $this->db->prepare("
                INSERT INTO learning_modules (title, type, description, status)
                VALUES (?, ?, ?, 'active')
            ");
            $stmt->execute([
                $data['title'],
                $data['type'],
                $data['description']
            ]);

            $moduleId = $this->db->lastInsertId();

            $this->db->commit();
            return ['success' => true, 'id' => $moduleId];
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE learning_modules 
                SET title = ?, type = ?, description = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['title'],
                $data['type'],
                $data['description'],
                $id
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            // Soft delete - update status to 'deleted'
            $stmt = $this->db->prepare("
                UPDATE learning_modules 
                SET status = 'deleted'
                WHERE id = ?
            ");
            $stmt->execute([$id]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function search($query) {
        $stmt = $this->db->prepare("
            SELECT * FROM learning_modules 
            WHERE (title LIKE ? OR description LIKE ?)
            AND status = 'active'
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function filterByType($type) {
        if ($type === 'all') {
            return $this->index();
        }

        $stmt = $this->db->prepare("
            SELECT * FROM learning_modules 
            WHERE type = ? AND status = 'active'
        ");
        $stmt->execute([$type]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 