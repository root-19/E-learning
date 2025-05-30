<?php
include 'layout/header.php';

require_once __DIR__ . '/../../../config/database.php';

// Get all enrolled students with their course details
try {
    $conn = Database::connect();
    
    // First check if there are any enrollments at all
    $check_stmt = $conn->query("SELECT COUNT(*) as count FROM enrollments");
    $enrollment_count = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    error_log('Total enrollments in database: ' . $enrollment_count);
    
    // Modified query to use username instead of firstname/lastname
    $stmt = $conn->prepare("
        SELECT 
            u.id as user_id,
            u.username,
            u.email,
            c.id as course_id,
            c.course_title,
            e.enrollment_date,
            e.completion_percentage,
            e.is_completed,
            COUNT(cp.chapter_id) as completed_chapters,
            (SELECT COUNT(*) FROM chapters WHERE course_id = c.id) as total_chapters
        FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN courses c ON e.course_id = c.id
        LEFT JOIN course_progress cp ON e.course_id = cp.course_id 
            AND e.user_id = cp.user_id 
            AND cp.is_completed = 1
        GROUP BY u.id, c.id
        ORDER BY e.enrollment_date DESC
    ");
    $stmt->execute();
    $enrolled_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log('Number of enrolled students found: ' . count($enrolled_students));
    
    // Debug: Check if we have any data in related tables
    $check_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch(PDO::FETCH_ASSOC)['count'];
    $check_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch(PDO::FETCH_ASSOC)['count'];
    $check_progress = $conn->query("SELECT COUNT(*) as count FROM course_progress")->fetch(PDO::FETCH_ASSOC)['count'];
    
    error_log('Debug counts - Users: ' . $check_users . ', Courses: ' . $check_courses . ', Progress: ' . $check_progress);
    
} catch (PDOException $e) {
    error_log('Error fetching enrolled students: ' . $e->getMessage());
    $enrolled_students = [];
}
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50"> -->
    <div class="container mx-auto px-4 py-8 ">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Enrolled Students</h1>
                
                <!-- Filters -->
                <div class="flex gap-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search students..." 
                               class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <select class="border border-gray-200 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">All Courses</option>
                        <?php foreach ($enrolled_students as $student): ?>
                            <option value="<?= htmlspecialchars($student['course_id']) ?>">
                                <?= htmlspecialchars($student['course_title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <!-- <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($enrolled_students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 font-medium">
                                                    <?= strtoupper(substr($student['username'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($student['username']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($student['email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($student['course_title']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= date('M d, Y', strtotime($student['enrollment_date'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                                 style="width: <?= $student['completion_percentage'] ?>%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            <?= number_format($student['completion_percentage'], 1) ?>%
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?= $student['completed_chapters'] ?> of <?= $student['total_chapters'] ?> chapters completed
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($student['is_completed']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            In Progress
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <!-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/instructor/student-progress/<?= $student['user_id'] ?>/<?= $student['course_id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </a>
                                </td> -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Add search functionality
        document.querySelector('input[type="text"]').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const courseName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (studentName.includes(searchTerm) || courseName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add course filter functionality
        document.querySelector('select').addEventListener('change', function(e) {
            const selectedCourse = e.target.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const courseId = row.querySelector('td:nth-child(2)').getAttribute('data-course-id');
                
                if (!selectedCourse || courseId === selectedCourse) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
