<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../app/controller/NotificationController.php';

use root_dev\Controller\NotificationController;

$notificationController = new NotificationController();
$notifications = $notificationController->getNotifications($_SESSION['user_id']);

include 'layout/header.php';
?>

<!-- Main Content -->
<div class="flex-2 p-6 mr-20">
    <div class="max-w-4xl mx-auto ">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-600">Stay updated with your course activities</p>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (empty($notifications)): ?>
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
                    <p class="text-gray-500">You'll see notifications here when you receive them.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="p-6 <?php echo $notification['is_read'] ? 'bg-white' : 'bg-blue-50'; ?> hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-custom-green flex items-center justify-center">
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($notification['message']); ?>
                                    </p>
                                    <?php if (isset($notification['course_title'])): ?>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Course: <?php echo htmlspecialchars($notification['course_title']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="mt-2 text-xs text-gray-500">
                                        <?php echo date('F j, Y g:i a', strtotime($notification['created_at'])); ?>
                                    </p>
                                </div>
                                <?php if (!$notification['is_read']): ?>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Mark notifications as read when viewed
    document.addEventListener('DOMContentLoaded', function() {
        const unreadNotifications = document.querySelectorAll('.bg-blue-50');
        if (unreadNotifications.length > 0) {
            fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: <?php echo $_SESSION['user_id']; ?>
                })
            });
        }
    });
</script>