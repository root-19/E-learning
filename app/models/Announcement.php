<?php

class Announcement {

    private $conn;

    // Constructor to initialize the PDO connection
    public function __construct() {
        $this->conn = Database::connect();  
    }

    // Method to create an announcement
    public function createAnnouncement($title, $description, $user_id) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO announcements (title, description, user_id, posted_at) 
                VALUES (?, ?, ?, NOW())
            ");
            return $stmt->execute([$title, $description, $user_id]);
        } catch (PDOException $e) {
            echo "Error creating announcement: " . $e->getMessage();
            return false;
        }
    }


    public function getAllAnnouncements() {
        try {
            $stmt = $this->conn->query("
                SELECT a.*, u.username as admin_name 
                FROM announcements a 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.posted_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching announcements: " . $e->getMessage();
            return [];
        }
    }

    public function getAnnouncementById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT a.*, u.username as admin_name 
                FROM announcements a 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching announcement: " . $e->getMessage();
            return null;
        }
    }

    public function updateAnnouncement($id, $title, $description) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE announcements 
                SET title = ?, description = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$title, $description, $id]);
        } catch (PDOException $e) {
            echo "Error updating announcement: " . $e->getMessage();
            return false;
        }
    }

    public function deleteAnnouncement($id) {
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM announcements 
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error deleting announcement: " . $e->getMessage();
            return false;
        }
    }
}
?>

