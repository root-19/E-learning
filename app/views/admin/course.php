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
   
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 mt-5">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Course Management</h1>
            <!-- <a href="/admin/course/create" 
               class="bg-[#4B793E] text-white px-4 py-2 rounded-lg hover:bg-[#3d6232] transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Add New Course
            </a> -->
        </div>

        <!-- Course List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapters</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">rejected</th>


                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($courses['courses'] as $course): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                                         alt="<?= htmlspecialchars($course['course_title']) ?>"
                                         class="h-16 w-24 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($course['course_title']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 max-w-md truncate">
                                        <?= htmlspecialchars($course['description']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($course['chapter_count']) ?> chapters
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $course['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($course['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 max-w-md truncate">
                                        <?= htmlspecialchars($course['is_rejected']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="/admin/course/view/<?= $course['id'] ?>" 
                                           class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors text-sm">
                                            View
                                        </a>
                                        <button onclick="toggleCourseStatus(<?= $course['id'] ?>, '<?= $course['status'] ?>')" 
                                                class="<?= $course['status'] === 'active' ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600' ?> text-white px-3 py-1 rounded transition-colors text-sm">
                                            <?= $course['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                        <button onclick="rejectCourse(<?= $course['id'] ?>)" 
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors text-sm">
                                            Reject
                                        </button>
                                        <button onclick="deleteCourse(<?= $course['id'] ?>)" 
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors text-sm">
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

    <!-- Pagination Controls -->
    <div class="flex justify-center items-center space-x-4 mt-6">
        <?php if ($courses['total_pages'] > 1): ?>
            <?php if ($courses['total_pages'] > 1 && isset($_GET['page']) && $_GET['page'] > 1): ?>
                <a href="?page=<?= $_GET['page'] - 1 ?>" 
                   class="bg-[#4B793E] text-white px-4 py-2 rounded-lg hover:bg-[#3d6232] transition-colors">
                    Previous
                </a>
            <?php endif; ?>
            
            <span class="text-gray-600">
                Page <?= isset($_GET['page']) ? $_GET['page'] : 1 ?> of <?= $courses['total_pages'] ?>
            </span>
            
            <?php if ($courses['total_pages'] > 1 && (!isset($_GET['page']) || $_GET['page'] < $courses['total_pages'])): ?>
                <a href="?page=<?= isset($_GET['page']) ? $_GET['page'] + 1 : 2 ?>" 
                   class="bg-[#4B793E] text-white px-4 py-2 rounded-lg hover:bg-[#3d6232] transition-colors">
                    Next
                </a>
            <?php endif; ?>
        <?php endif; ?>
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
