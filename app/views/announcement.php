<?php
require_once __DIR__ . '/../controller/AnnouncementController.php';
$controller = new AnnouncementController();
$announcements = $controller->getAllAnnouncements();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .announcement-card {
            transition: all 0.3s ease;
            background: white;
            border-radius: 1rem;
            overflow: hidden;
        }
        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .announcement-content {
            max-height: 150px;
            overflow: hidden;
            position: relative;
        }
        .announcement-content::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: linear-gradient(to top, white, transparent);
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8 sticky top-0 bg-gray-50 py-4 z-10">
        <h1 class="text-3xl font-bold text-gray-800">Announcements</h1>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search announcements..." class="w-full border border-gray-300 rounded-lg p-2 pl-10 focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="announcementsList">
        <?php foreach ($announcements as $announcement): ?>
        <div class="announcement-card">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($announcement['title']) ?></h3>
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-user mr-2"></i>
                            <?= htmlspecialchars($announcement['admin_name']) ?>
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Announcement
                    </span>
                </div>
                <div class="announcement-content mb-4">
                    <p class="text-gray-600"><?= htmlspecialchars($announcement['description']) ?></p>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-2"></i>
                        <?= date('M d, Y', strtotime($announcement['posted_at'])) ?>
                    </p>
                    <button onclick="showFullAnnouncement(<?= $announcement['id'] ?>)" class="text-blue-600 hover:text-blue-800 transition-colors">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Full Announcement Modal -->
<div id="announcementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
    <div class="modal-content w-full max-w-2xl mx-4 my-8">
        <div class="p-8 bg-white rounded-lg">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h2>
                    <p id="modalAdmin" class="text-sm text-gray-500"></p>
                </div>
                <button onclick="hideAnnouncementModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent" class="text-gray-600 mb-6"></div>
            <div class="flex justify-between items-center">
                <p id="modalDate" class="text-sm text-gray-500"></p>
                <span id="modalPriority" class="px-3 py-1 rounded-full text-sm font-medium"></span>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const announcements = document.querySelectorAll('#announcementsList > div');
    
    announcements.forEach(announcement => {
        const title = announcement.querySelector('h3').textContent.toLowerCase();
        const content = announcement.querySelector('.announcement-content p').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            announcement.style.display = '';
        } else {
            announcement.style.display = 'none';
        }
    });
});

// function showFullAnnouncement(id) {
//     const modal = document.getElementById('announcementModal');
//     // Find the card with the matching data-id
//     const announcement = document.querySelector(`.announcement-card[data-id="${id}"]`);
//     if (announcement) {
//         document.getElementById('modalTitle').textContent = announcement.querySelector('h3').textContent;
//         document.getElementById('modalAdmin').textContent = announcement.querySelector('.text-gray-500').textContent;
//         // Always get the description
//         document.getElementById('modalContent').textContent = announcement.querySelector('.announcement-content p').textContent;
//         document.getElementById('modalDate').textContent = announcement.querySelector('.text-sm.text-gray-500').textContent;
//         document.getElementById('modalPriority').textContent = 'Announcement';
//         document.getElementById('modalPriority').className = 'px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
//     }
//     modal.classList.remove('hidden');
//     modal.classList.add('flex');
//     document.body.style.overflow = 'hidden';
// }

function hideAnnouncementModal() {
    const modal = document.getElementById('announcementModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('announcementModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideAnnouncementModal();
    }
});
</script>
</body>
</html> 