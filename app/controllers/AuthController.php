<?php
class AuthController {
    private $db;

    // public function __construct() {
    //     $this->db = Database::getInstance()->getConnection();
    // }

    public function changePassword($user_id, $current_password, $new_password) {
        try {
            // First verify the current password
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($current_password, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ];
            }

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $result = $stmt->execute([$hashed_password, $user_id]);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Password changed successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update password'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'An error occurred while changing password'
            ];
        }
    }
} 