<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../../config/database.php';

class NotificationController {
    private $db;

    public function __construct() {
        $this->db = \Database::connect();
    }

    /**
     * Get all notifications for a user
     * @param int $userId
     * @return array
     */
    public function getNotifications(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT n.*, c.course_title as course_title 
            FROM notifications n
            LEFT JOIN courses c ON n.course_id = c.id
            WHERE n.user_id = ?
            ORDER BY n.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Mark notifications as read for a user
     * @param int $userId
     * @return bool
     */
    public function markNotificationsAsRead(int $userId): bool {
        $stmt = $this->db->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Get unread notification count for a user
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM notifications 
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
} 