<?php
// session_start();
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Announcement.php';
require_once __DIR__ . '/../../controller/AnnouncementController.php';
// session_start();
$announcementController = new AnnouncementController();
$userId = $_SESSION['user_id'] ?? null;
$successMessage = '';
$errorMessage = '';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!empty($title) && !empty($description)) {
        $result = $announcementController->createAnnouncement($title, $description, $userId);
        if ($result === "Announcement posted successfully!") {
            $successMessage = $result;
        } else {
            $errorMessage = $result;
        }
    } else {
        $errorMessage = "Title and description are required.";
    }
}

// Fetch announcements
$announcements = $announcementController->viewAnnouncements();

// Filter by search
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $announcements = array_filter($announcements, function ($announcement) use ($search) {
        return stripos($announcement['title'], $search) !== false || stripos($announcement['description'], $search) !== false;
    });
}

include 'layout/side-header.php';
?>

<!-- Button to open modal -->
<div class="flex justify-between items-center mb-4">
    <!-- Add Announcement Button (Left Aligned) -->
    <button id="openModalButton" class="bg-green-500 text-white px-4 py-2 rounded-md">
        Create Announcement
    </button>

    <!-- Search Box with Suggestions -->
    <div class="relative w-1/2">
        <input 
            type="text" 
            id="searchInput" 
            class="border rounded px-4 py-2 w-full" 
            placeholder="Search announcements by title..." 
            autocomplete="off"
        >
        <ul 
            id="suggestions" 
            class="absolute z-10 w-full bg-white border border-gray-200 rounded shadow-md hidden"
        ></ul>
    </div>
</div>

<!-- Success/Error Message -->
<!-- <?php if ($successMessage): ?>
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= $successMessage ?></div>
<?php elseif ($errorMessage): ?>
    <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $errorMessage ?></div>
<?php endif; ?> -->

<!-- Announcements Table -->
<div class="overflow-x-auto rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Posted</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($announcement['title']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate"><?= htmlspecialchars($announcement['description']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($announcement['admin_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($announcement['posted_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 bg-gray-50">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>No announcements found.</span>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="announcementModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-md w-1/3">
        <h2 class="text-xl font-bold mb-4">Create Announcement</h2>
        <form method="POST" id="announcementForm">
            <input type="text" name="title" class="border rounded px-4 py-2 mb-4 w-full" placeholder="Title" required>
            <textarea name="description" class="border rounded px-4 py-2 mb-4 w-full" placeholder="Description" required></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
            <button type="button" id="closeModalButton" class="bg-gray-500 text-white px-4 py-2 rounded-md ml-2">Cancel</button>
        </form>
    </div>
</div>

<!-- JS -->
<script>
    const modal = document.getElementById('announcementModal');
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');

    openModalButton.addEventListener('click', () => modal.classList.remove('hidden'));
    closeModalButton.addEventListener('click', () => modal.classList.add('hidden'));

    <?php if (!empty($successMessage)): ?>
        window.addEventListener('DOMContentLoaded', () => modal.classList.add('hidden'));
    <?php endif; ?>

    // Announcements passed from PHP to JS
    const announcements = <?= json_encode($announcements) ?>;

    const searchInput = document.getElementById('searchInput');
    const suggestions = document.getElementById('suggestions');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');

        if (query.length > 0) {
            const matches = announcements.filter(a => a.title.toLowerCase().includes(query));
            
            if (matches.length > 0) {
                matches.forEach(match => {
                    const li = document.createElement('li');
                    li.textContent = match.title;
                    li.classList = "px-4 py-2 hover:bg-gray-100 cursor-pointer";
                    li.addEventListener('click', () => {
                        searchInput.value = match.title;
                        suggestions.classList.add('hidden');
                    });
                    suggestions.appendChild(li);
                });
                suggestions.classList.remove('hidden');
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.classList.add('hidden');
        }
    });
</script>
