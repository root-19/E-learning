<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controller/ModuleController.php';

$db = Database::connect();
$controller = new ModuleController();

// Get course ID from URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get course and chapters
$course = $controller->getCourseById($course_id);
if (!$course) {
    header('Location: /admin/course');
    exit();
}

$chapters = $controller->getChaptersForCourse($course_id);

// Get quiz information for each chapter
$quizInfo = [];
foreach ($chapters as $chapter) {
    $stmt = $db->prepare("SELECT COUNT(*) as quiz_count FROM quizzes WHERE chapter_id = ?");
    $stmt->execute([$chapter['id']]);
    $quizInfo[$chapter['id']] = $stmt->fetch(PDO::FETCH_ASSOC)['quiz_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['course_title']) ?> - Admin View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/admin/course" class="flex items-center text-[#4B793E] hover:text-[#3d6232] transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Courses
                    </a>
                </div>
                <div class="text-xl font-bold text-gray-800">
                    Course Details
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 mt-20">
        <!-- Course Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8">
            <div class="relative h-64">
                <?php if (!empty($course['course_image'])): ?>
                <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                     alt="<?= htmlspecialchars($course['course_title']) ?>" 
                     class="w-full h-full object-cover">
                <?php else: ?>
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h1 class="text-4xl font-bold mb-3"><?= htmlspecialchars($course['course_title']) ?></h1>
                    <p class="text-gray-200 text-lg max-w-3xl"><?= htmlspecialchars($course['description']) ?></p>
                </div>
            </div>
        </div>

        <!-- Course Overview -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-[#4B793E] rounded-xl text-white">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-sm opacity-80">Total Chapters</p>
                            <p class="text-3xl font-bold"><?= count($chapters) ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-blue-600 rounded-xl text-white">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-sm opacity-80">Interactive Chapters</p>
                            <p class="text-3xl font-bold">
                                <?= count(array_filter($chapters, function($ch) { return $ch['type'] === 'interactive'; })) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-green-600 rounded-xl text-white">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-sm opacity-80">Traditional Chapters</p>
                            <p class="text-3xl font-bold">
                                <?= count(array_filter($chapters, function($ch) { return $ch['type'] === 'traditional'; })) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chapters List -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Chapters</h2>
            
            <div class="space-y-6">
                <?php foreach ($chapters as $index => $chapter): ?>
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-[#4B793E] text-white rounded-xl font-bold text-lg">
                            <?= $index + 1 ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-800"><?= htmlspecialchars($chapter['chapter_title']) ?></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                    <?= $chapter['type'] === 'interactive' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= ucfirst($chapter['type']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pl-16">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="prose max-w-none">
                                <?= $chapter['chapter_content'] ?>
                            </div>
                            <?php if ($chapter['type'] === 'interactive' && isset($quizInfo[$chapter['id']]) && $quizInfo[$chapter['id']] > 0): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        <i class="fas fa-question-circle mr-1"></i>
                                        <?= $quizInfo[$chapter['id']] ?> Quiz<?= $quizInfo[$chapter['id']] > 1 ? 'zes' : '' ?> Available
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html> 