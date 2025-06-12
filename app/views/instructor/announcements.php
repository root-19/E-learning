<?php
require_once __DIR__ . '/../../controller/AnnouncementController.php';
$controller = new AnnouncementController();
$announcements = $controller->getAllAnnouncements();
include 'layout/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background-color: #f8fafc;
        }
        .priority-badge {
            transition: all 0.2s ease;
        }
        .priority-badge:hover {
            transform: scale(1.05);
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

    <!-- Announcements Table -->
    <div class="table-container overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="announcementsList">
                    <?php foreach ($announcements as $announcement): ?>
                    <tr class="table-row" data-id="<?= $announcement['id'] ?>">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($announcement['title']) ?></div>
                            <div class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($announcement['description']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-900"><?= htmlspecialchars($announcement['admin_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-900"><?= date('M d, Y', strtotime($announcement['posted_at'])) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="priority-badge px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Announcement
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="showFullAnnouncement(<?= $announcement['id'] ?>)" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
    const rows = document.querySelectorAll('#announcementsList tr');
    
    rows.forEach(row => {
        const title = row.querySelector('td:first-child').textContent.toLowerCase();
        const content = row.querySelector('td:first-child div:last-child').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function showFullAnnouncement(id) {
    const modal = document.getElementById('announcementModal');
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        document.getElementById('modalTitle').textContent = row.querySelector('td:first-child div:first-child').textContent;
        document.getElementById('modalAdmin').textContent = row.querySelector('td:nth-child(2) span').textContent;
        document.getElementById('modalContent').textContent = row.querySelector('td:first-child div:last-child').textContent;
        document.getElementById('modalDate').textContent = row.querySelector('td:nth-child(3) span').textContent;
        document.getElementById('modalPriority').textContent = 'Announcement';
        document.getElementById('modalPriority').className = 'px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

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