<?php
require_once __DIR__ . '/../models/Announcement.php';

class AnnouncementController {
    private $announcement;

    public function __construct() {
        $this->announcement = new Announcement();
    }

    public function getAllAnnouncements() {
        return $this->announcement->getAllAnnouncements();
    }

    public function getAnnouncementById($id) {
        return $this->announcement->getAnnouncementById($id);
    }

    public function createAnnouncement($title, $content, $priority, $admin_id) {
        return $this->announcement->createAnnouncement($title, $content, $priority, $admin_id);
    }

    public function updateAnnouncement($id, $title, $content, $priority) {
        return $this->announcement->updateAnnouncement($id, $title, $content, $priority);
    }

    public function deleteAnnouncement($id) {
        return $this->announcement->deleteAnnouncement($id);
    }
}
?>
