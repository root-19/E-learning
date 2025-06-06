<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/User.php';

use root_dev\Models\User;

$db = Database::connect();

// Get all instructors
$stmt = $db->prepare("
    SELECT u.*, 
           COUNT(DISTINCT c.id) as total_courses,
           COUNT(DISTINCT e.user_id) as total_students
    FROM users u
    LEFT JOIN courses c ON u.id = c.instructor_id
    LEFT JOIN enrollments e ON c.id = e.course_id
    WHERE u.role = 'instructor'
    GROUP BY u.id
");
$stmt->execute();
$instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'layout/side-header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Instructor Management</h1>
                <a href="/admin/instractor" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Add New Instructor</span>
                </a>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-8 flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search instructors..." class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Instructors Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($instructors as $instructor): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <?php if (!empty($instructor['profile_image'])): ?>
                                                    <img class="h-10 w-10 rounded-full object-cover" src="/uploads/<?= htmlspecialchars($instructor['profile_image']) ?>" alt="Profile">
                                                <?php else: ?>
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-500 font-medium">
                                                            <?= strtoupper(substr($instructor['username'], 0, 1)) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($instructor['username']) ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Joined <?= date('M Y', strtotime($instructor['created_at'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($instructor['email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= $instructor['total_courses'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= $instructor['total_students'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $instructor['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($instructor['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-3">
                                            <!-- <a href="/admin/instructor/view/<?= $instructor['id'] ?>" class="text-blue-600 hover:text-blue-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                            <button onclick="toggleInstructorStatus(<?= $instructor['id'] ?>, '<?= $instructor['status'] ?>')" 
                                                    class="<?= $instructor['status'] === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' ?>" 
                                                    title="Toggle Status">
                                                <i class="fas <?= $instructor['status'] === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                            </button>
                                            <button onclick="deleteInstructor(<?= $instructor['id'] ?>)" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const instructorName = row.querySelector('td:first-child').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (instructorName.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            const selectedStatus = e.target.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const status = row.querySelector('td:nth-child(5) span').textContent.toLowerCase();
                
                if (selectedStatus === 'all' || status === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Toggle instructor status
        function toggleInstructorStatus(instructorId, currentStatus) {
            if (confirm('Are you sure you want to change this instructor\'s status?')) {
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                
                fetch(`/api/instructor/toggle-status/${instructorId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update instructor status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating instructor status');
                });
            }
        }

        // Delete instructor
        function deleteInstructor(instructorId) {
            if (confirm('Are you sure you want to delete this instructor? This action cannot be undone.')) {
                fetch(`/api/instructor/delete/${instructorId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete instructor');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting instructor');
                });
            }
        }
    </script>
</body>
</html>
