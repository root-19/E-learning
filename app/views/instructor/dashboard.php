<?php
require_once __DIR__ . '/../../controller/InstructorDashboardController.php';

// Get instructor ID from session
$instructor_id = $_SESSION['user_id'] ?? null;

if (!$instructor_id) {
    header('Location: /login');
    exit();
}

// Get dashboard data
$dashboardController = new InstructorDashboardController();
$dashboardData = $dashboardController->getDashboardData($instructor_id);

// Extract data
$total_courses = $dashboardData['total_courses'];
$total_students = $dashboardData['total_students'];
$total_completed_courses = $dashboardData['total_completed_courses'];
$top_courses = $dashboardData['top_courses'];
$recent_activities = $dashboardData['recent_activities'];

include 'layout/header.php';
?>

<style>
    .modern-card {
        transition: box-shadow 0.2s, transform 0.2s;
        border-radius: 1.25rem;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.06);
        background: #fff;
        padding: 2rem 2.5rem;
    }
    .modern-card:hover {
        box-shadow: 0 6px 24px 0 rgba(59,130,246,0.10);
        transform: translateY(-2px) scale(1.01);
    }
    .modern-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .modern-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2563eb;
    }
    .modern-icon {
        font-size: 2.5rem;
        color: #2563eb;
        background: #e0e7ff;
        border-radius: 50%;
        padding: 0.7rem;
        margin-left: 1rem;
    }
    .modern-section {
        background: #f9fafb;
        border-radius: 1.25rem;
        padding: 2rem 2.5rem;
        min-height: 350px;
    }
    .modern-activity, .modern-course {
        background: #f3f4f6;
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modern-activity:last-child, .modern-course:last-child {
        margin-bottom: 0;
    }
    .modern-status {
        padding: 0.3rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #fef3c7;
        color: #b45309;
    }
    .modern-status.completed {
        background: #d1fae5;
        color: #065f46;
    }
    .modern-link {
        color: #6366f1;
        text-decoration: underline;
        font-weight: 500;
        font-size: 0.95rem;
    }
    @media (max-width: 768px) {
        .modern-card, .modern-section {
            padding: 1.2rem 1rem;
        }
        .modern-value {
            font-size: 1.5rem;
        }
    }
</style>

<div class="w-full min-h-screen flex flex-col items-center justify-center bg-gray-50 py-8 px-2 mr-40" >
    <!-- Stats Overview -->
    <div class="w-full max-w-5xl flex flex-col items-center">
        <div class="w-full flex flex-col items-center">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8 w-full justify-center">
                <!-- Total Courses -->
                <div class="modern-card flex items-center justify-between w-full">
                    <div>
                        <div class="modern-title">Total Courses</div>
                        <div class="modern-value"><?php echo $total_courses; ?></div>
                    </div>
                    <div class="modern-icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <!-- Total Students -->
                <div class="modern-card flex items-center justify-between w-full">
                    <div>
                        <div class="modern-title">Total Students</div>
                        <div class="modern-value" style="color:#059669;"><?php echo $total_students; ?></div>
                    </div>
                    <div class="modern-icon" style="color:#059669;background:#d1fae5;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <!-- Total Completed Courses -->
                <div class="modern-card flex items-center justify-between w-full">
                    <div>
                        <div class="modern-title">Completed Courses</div>
                        <div class="modern-value" style="color:#0ea5e9;"><?php echo $total_completed_courses; ?></div>
                    </div>
                    <div class="modern-icon" style="color:#0ea5e9;background:#e0f2fe;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-5xl grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Recent Activities -->
        <div class="modern-section col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div class="modern-title">Recent Activities</div>
                <a href="#" class="modern-link">View All</a>
            </div>
            <?php if(!empty($recent_activities)): ?>
                <?php foreach($recent_activities as $activity): ?>
                <div class="modern-activity">
                    <div class="text-gray-700 text-base"><?php echo htmlspecialchars($activity['description']); ?></div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-500 text-sm"><?php echo $activity['date']; ?></span>
                        <span class="modern-status<?php echo $activity['status'] === 'Completed' ? ' completed' : ''; ?>">
                            <?php echo htmlspecialchars($activity['status']); ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-gray-400 py-12">
                    <i class="fas fa-info-circle mr-2"></i>
                    No recent activities
                </div>
            <?php endif; ?>
        </div>
        <!-- Top Performing Courses -->
        <div class="modern-section">
            <div class="modern-title mb-6">Top Performing Courses</div>
            <?php if(!empty($top_courses)): ?>
                <?php foreach($top_courses as $index => $course): ?>
                <div class="modern-course">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-400">#<?php echo $index + 1; ?></span>
                        <span class="font-medium text-gray-700 truncate max-w-[140px]">
                            <?php echo htmlspecialchars($course['course_title']); ?>
                        </span>
                    </div>
                    <span class="modern-link"><?php echo $course['enrollment_count']; ?> students</span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-gray-400 py-12">
                    <i class="fas fa-info-circle mr-2"></i>
                    No courses yet
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


