<?php

class Announcement {

    private $db;

    // Constructor to initialize the PDO connection
    public function __construct() {
        $this->db = Database::connect();  
    }

    // Method to create an announcement
    public function createAnnouncement($title, $description, $userId) {

        date_default_timezone_set('Asia/Manila');
        $datePosted = date('Y-m-d H:i:s'); 
        
        // SQL query to insert the announcement into the database
        $query = "INSERT INTO announcements (title, description, user_id, posted_at) 
                  VALUES (:title, :description, :user_id, :posted_at)";
        
        // Prepare the statement
        $stmt = $this->db->prepare($query);
        
        // Bind parameters to the query
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':posted_at', $datePosted);
        
        // Execute the query and return result
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Method to fetch all announcements (optional for viewing)
    public function getAllAnnouncements() {
        $query = "
            SELECT 
                announcements.title,
                announcements.description,
                announcements.posted_at,
                users.username
            FROM announcements
            INNER JOIN users ON announcements.user_id = users.id
            ORDER BY announcements.posted_at DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>

