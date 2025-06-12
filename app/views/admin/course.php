<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controller/ModuleController.php';
$db = Database::connect();
$controller = new ModuleController();
$courses = $controller->listCourses();

include 'layout/side-header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - InsureLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }
        .table-container {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 1rem;
            overflow: hidden;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
        }
        .action-button:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 mt-20">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Course Management</h1>
                <p class="text-gray-600 mt-1">Manage and monitor all your courses in one place</p>
            </div>
        </div>

        <!-- Course List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover-scale">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course Image</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rejected</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chapters</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Course Materials</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($courses as $course): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                                         alt="<?= htmlspecialchars($course['course_title']) ?>"
                                         class="h-20 w-32 object-cover rounded-lg shadow-sm">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <?= htmlspecialchars($course['course_title']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-md truncate">
                                        <?= htmlspecialchars($course['description']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $course['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($course['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        <?= htmlspecialchars($course['is_rejected']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $chapterCount = $controller->getChapterCount($course['id']);
                                    ?>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-700"><?= $chapterCount ?> chapters</span>
                                        <a href="/admin/course/view/<?= $course['id'] ?>" 
                                           class="text-blue-500 hover:text-blue-700 transition-colors">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <?php if (!empty($course['pdf_file'])): ?>
                                            <a href="/<?= htmlspecialchars($course['pdf_file']) ?>" 
                                               class="action-button bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors text-sm flex items-center gap-1"
                                               target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                                View PDF
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($course['ppt_file'])): ?>
                                            <a href="/<?= htmlspecialchars($course['ppt_file']) ?>" 
                                               class="action-button bg-orange-500 text-white px-3 py-1 rounded-lg hover:bg-orange-600 transition-colors text-sm flex items-center gap-1"
                                               download>
                                                <i class="fas fa-file-powerpoint"></i>
                                                Download PPT
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                        <a href="/admin/course/view/<?= $course['id'] ?>" 
                                           class="action-button bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                        <button onclick="toggleCourseStatus(<?= $course['id'] ?>, '<?= $course['status'] ?>')" 
                                                class="action-button <?= $course['status'] === 'active' ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600' ?> text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-1">
                                            <i class="fas <?= $course['status'] === 'active' ? 'fa-pause' : 'fa-play' ?>"></i>
                                            <?= $course['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                        <button onclick="rejectCourse(<?= $course['id'] ?>)" 
                                                class="action-button bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm flex items-center gap-1">
                                            <i class="fas fa-ban"></i>
                                            Reject
                                        </button>
                                        <button onclick="deleteCourse(<?= $course['id'] ?>)" 
                                                class="action-button bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm flex items-center gap-1">
                                            <i class="fas fa-trash"></i>
                                            Delete
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

    <script>
        function toggleCourseStatus(courseId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            if (confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this course?`)) {
                fetch(`/api/course/toggle-status/${courseId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        throw new Error(data.error || 'Failed to update course status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Failed to update course status');
                });
            }
        }

        function rejectCourse(courseId) {
            if (confirm('Are you sure you want to reject this course? This will hide it from the learning view.')) {
                fetch(`/api/course/reject/${courseId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to reject course');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to reject course');
                });
            }
        }

        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                fetch(`/admin/course/delete/${courseId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to delete course');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete course');
                });
            }
        }
    </script>
</body>
</html>
