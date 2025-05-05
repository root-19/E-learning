<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controller/ModuleController.php';

// Get course ID from URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

try {
    // Check if user is enrolled
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    $enrollment = $stmt->fetch();

    if (!$enrollment) {
        header('Location: /my_learning');
        exit();
    }

    // Get course and chapters
    $controller = new ModuleController();
    $course = $controller->getCourseById($course_id);
    
    if (!$course) {
        header('Location: /my_learning');
        exit();
    }
    
    $chapters = $controller->getChaptersForCourse($course_id);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header('Location: /my_learning?error=database_error');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['course_title']) ?> - InsureLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
    <style>
        .chapter-item.active {
            border-color: #4B793E;
            background-color: #f0f9f0;
        }
        .chapter-item:hover {
            border-color: #4B793E;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen pt-20">
        <!-- Sidebar -->
        <div class="w-80 bg-white border-r border-gray-200 h-[calc(100vh-5rem)] fixed overflow-y-auto">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Course Chapters</h2>
                <div class="space-y-2">
                    <?php foreach ($chapters as $index => $chapter): ?>
                    <a href="/chapter/<?= $chapter['id'] ?>" 
                       class="chapter-item block p-4 rounded-lg border border-gray-200 hover:shadow-md transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-[#4B793E] text-white rounded-full font-bold text-sm">
                                <?= $index + 1 ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-black line-clamp-2"><?= htmlspecialchars($chapter['chapter_title']) ?></h3>
                                <?php if ($chapter['type'] === 'interactive'): ?>
                                <span class="inline-block mt-1 text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                    Interactive
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-80">
            <div class="container mx-auto px-8 py-6">
                <!-- Course Header -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8">
                    <div class="relative h-48">
                        <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                             alt="<?= htmlspecialchars($course['course_title']) ?>" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                        <div class="absolute bottom-4 left-6 text-black">
                            <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($course['course_title']) ?></h1>
                            <p class="text-black"><?= htmlspecialchars($course['description']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Course Overview -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Course Overview</h2>
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-book-open text-[#4B793E] text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Total Chapters</p>
                                    <p class="text-xl font-bold text-gray-800"><?= count($chapters) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-tasks text-[#4B793E] text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Interactive Chapters</p>
                                    <p class="text-xl font-bold text-gray-800">
                                        <?= count(array_filter($chapters, function($ch) { return $ch['type'] === 'interactive'; })) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-clock text-[#4B793E] text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Enrolled Date</p>
                                    <p class="text-xl font-bold text-gray-800">
                                        <?= date('M d, Y', strtotime($enrollment['enrolled_date'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <h3 class="text-xl font-bold text-black mb-3">Course Description</h3>
                        <p class="text-black"><?= htmlspecialchars($course['description']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Highlight current chapter if we're on a chapter page
        const urlParams = new URLSearchParams(window.location.search);
        const currentChapterId = urlParams.get('chapter_id');
        if (currentChapterId) {
            document.querySelector(`a[href="/chapter/${currentChapterId}"]`)?.classList.add('active');
        }
    </script>
</body>
</html> 