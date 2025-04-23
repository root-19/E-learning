<?php

class AnnouncementController {

    private $announcementModel;

    public function __construct() {
        $this->announcementModel = new Announcement();
    }

    // Create announcement
    public function createAnnouncement($title, $description, $userId) {
        $result = $this->announcementModel->createAnnouncement($title, $description, $userId);
         
        if ($result === true) {
            // Optionally redirect or return success message
            return "Announcement posted successfully!";
        } else {
            return $result;  
        }
    }

    // Get all announcements (return instead of echo)
    public function viewAnnouncements() {
        return $this->announcementModel->getAllAnnouncements();
    }
}
?>
