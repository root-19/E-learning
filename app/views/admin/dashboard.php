<?php
require_once __DIR__ . '/../../../config/database.php';

$db = Database::connect();

// Count instructors
$stmtInstructor = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'instructor'");
$stmtInstructor->execute();
$instructorCount = $stmtInstructor->fetchColumn();

// Count learners
$stmtLearner = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'user'");
$stmtLearner->execute();
$learnerCount = $stmtLearner->fetchColumn();

// Count total courses
$stmtCourses = $db->prepare("SELECT COUNT(*) FROM courses WHERE status = 'active'");
$stmtCourses->execute();
$courseCount = $stmtCourses->fetchColumn();

// Count total enrollments
$stmtEnrollments = $db->prepare("SELECT COUNT(*) FROM enrollments");
$stmtEnrollments->execute();
$enrollmentCount = $stmtEnrollments->fetchColumn();

// Calculate average completion rate
$stmtCompletion = $db->prepare("
    SELECT AVG(completion_percentage) as avg_completion 
    FROM enrollments 
    WHERE completion_percentage > 0
");
$stmtCompletion->execute();
$avgCompletion = round($stmtCompletion->fetchColumn(), 1);

// Get top courses by enrollment
$stmtTopCourses = $db->prepare("
    SELECT c.course_title, COUNT(e.id) as enrollment_count
    FROM courses c
    LEFT JOIN enrollments e ON c.id = e.course_id
    WHERE c.status = 'active'
    GROUP BY c.id
    ORDER BY enrollment_count DESC
    LIMIT 3
");
$stmtTopCourses->execute();
$topCourses = $stmtTopCourses->fetchAll(PDO::FETCH_ASSOC);

// Get user growth data (last 6 months)
$stmtUserGrowth = $db->prepare("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
           COUNT(*) as count
    FROM users
    WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
");
$stmtUserGrowth->execute();
$userGrowthData = $stmtUserGrowth->fetchAll(PDO::FETCH_ASSOC);

// Get enrollment trends (last 6 months)
$stmtEnrollments = $db->prepare("
    SELECT DATE_FORMAT(enrollment_date, '%Y-%m') as month,
           COUNT(*) as count
    FROM enrollments
    WHERE enrollment_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(enrollment_date, '%Y-%m')
    ORDER BY month ASC
");
$stmtEnrollments->execute();
$enrollmentData = $stmtEnrollments->fetchAll(PDO::FETCH_ASSOC);

include "layout/side-header.php";
?>

<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-3 h-screen flex flex-col">
    <!-- Stats Overview -->
    <div class="grid grid-cols-4 gap-3 mb-3">
        <!-- Instructor Count -->
        <div class="bg-white shadow rounded p-3 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-semibold text-gray-700">Instructors</h2>
                <p class="text-xl font-bold text-blue-600"><?= $instructorCount; ?></p>
            </div>
            <div class="text-blue-600 text-2xl">
                📘
            </div>
        </div>

        <!-- Learner Count -->
        <div class="bg-white shadow rounded p-3 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-semibold text-gray-700">Learners</h2>
                <p class="text-xl font-bold text-green-600"><?= $learnerCount; ?></p>
            </div>
            <div class="text-green-600 text-2xl">
                🎓
            </div>
        </div>

        <!-- Course Count -->
        <div class="bg-white shadow rounded p-3 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-semibold text-gray-700">Active Courses</h2>
                <p class="text-xl font-bold text-purple-600"><?= $courseCount; ?></p>
            </div>
            <div class="text-purple-600 text-2xl">
                📚
            </div>
        </div>

        <!-- Enrollment Count -->
        <div class="bg-white shadow rounded p-3 flex items-center justify-between">
            <div>
                <h2 class="text-xs font-semibold text-gray-700">Enrollments</h2>
                <p class="text-xl font-bold text-orange-600"><?= $enrollmentCount; ?></p>
            </div>
            <div class="text-orange-600 text-2xl">
                📝
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-3 gap-3 flex-1">
        <!-- Left Column: Charts -->
        <div class="col-span-2 grid grid-rows-2 gap-3">
            <!-- User Growth Chart -->
            <div class="bg-white shadow rounded p-3">
                <h2 class="text-xs font-semibold text-gray-800 mb-1">User Growth (6 Months)</h2>
                <div class="h-[calc(100%-1.5rem)]">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- Enrollment Trends Chart -->
            <div class="bg-white shadow rounded p-3">
                <h2 class="text-xs font-semibold text-gray-800 mb-1">Enrollment Trends</h2>
                <div class="h-[calc(100%-1.5rem)]">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats -->
        <div class="grid grid-rows-2 gap-3">
            <!-- Completion Rate -->
            <div class="bg-white shadow rounded p-3">
                <h2 class="text-xs font-semibold text-gray-800 mb-1">Completion Rate</h2>
                <div class="flex items-center justify-center h-[calc(100%-1.5rem)]">
                    <div class="relative w-24 h-24">
                        <canvas id="completionChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-xl font-bold text-indigo-600"><?= $avgCompletion ?>%</p>
                                <p class="text-xs text-gray-600">Average</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Courses -->
            <div class="bg-white shadow rounded p-3">
                <h2 class="text-xs font-semibold text-gray-800 mb-1">Top Courses</h2>
                <div class="space-y-1">
                    <?php foreach ($topCourses as $index => $course): ?>
                    <div class="flex items-center justify-between p-1.5 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <span class="text-xs font-bold text-gray-400 mr-1">#<?= $index + 1 ?></span>
                            <span class="text-xs font-medium text-gray-700 truncate max-w-[120px]"><?= htmlspecialchars($course['course_title']) ?></span>
                        </div>
                        <span class="text-xs font-semibold text-indigo-600"><?= $course['enrollment_count'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($userGrowthData, 'month')) ?>,
        datasets: [{
            label: 'New Users',
            data: <?= json_encode(array_column($userGrowthData, 'count')) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 10
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});

// Enrollment Trends Chart
const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
new Chart(enrollmentCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($enrollmentData, 'month')) ?>,
        datasets: [{
            label: 'Course Enrollments',
            data: <?= json_encode(array_column($enrollmentData, 'count')) ?>,
            fill: false,
            borderColor: 'rgb(34, 197, 94)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 10
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});

// Completion Rate Chart
const completionCtx = document.getElementById('completionChart').getContext('2d');
new Chart(completionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Remaining'],
        datasets: [{
            data: [<?= $avgCompletion ?>, <?= 100 - $avgCompletion ?>],
            backgroundColor: [
                'rgba(99, 102, 241, 0.8)',
                'rgba(229, 231, 235, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

