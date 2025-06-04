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
$quizzes = [];
foreach ($chapters as $chapter) {
    // Get quiz count
    $stmt = $db->prepare("SELECT COUNT(*) as quiz_count FROM quizzes WHERE chapter_id = ?");
    $stmt->execute([$chapter['id']]);
    $quizInfo[$chapter['id']] = $stmt->fetch(PDO::FETCH_ASSOC)['quiz_count'];
    // Get all quizzes for the chapter
    $stmt2 = $db->prepare("SELECT * FROM quizzes WHERE chapter_id = ?");
    $stmt2->execute([$chapter['id']]);
    $quizzes[$chapter['id']] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}

// Get chapter types count
$interactiveCount = 0;
$traditionalCount = 0;
foreach ($chapters as $chapter) {
    if ($chapter['type'] === 'interactive') {
        $interactiveCount++;
    } else {
        $traditionalCount++;
    }
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
                            <p class="text-3xl font-bold"><?= $interactiveCount ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-green-600 rounded-xl text-white">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-sm opacity-80">Traditional Chapters</p>
                            <p class="text-3xl font-bold"><?= $traditionalCount ?></p>
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
                        <!-- Chapter Image -->
                        <?php if (!empty($chapter['chapter_image'])): ?>
                        <div class="mb-4">
                            <img src="/uploads/courses/<?= htmlspecialchars($chapter['chapter_image']) ?>" 
                                 alt="<?= htmlspecialchars($chapter['chapter_title']) ?>"
                                 class="w-full h-48 object-cover rounded-lg shadow-md">
                        </div>
                        <?php endif; ?>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="prose max-w-none">
                                <?= $chapter['chapter_content'] ?>
                            </div>

                            <!-- Quiz Section -->
                            <?php if ($chapter['type'] === 'interactive'): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex flex-col gap-3">
                                    <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                        <i class="fas fa-question-circle text-blue-600"></i>
                                        Chapter Quizzes
                                    </h4>
                                    <?php if (!empty($quizzes[$chapter['id']])): ?>
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg mb-4">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Question</th>
                                                    <th class="px-4 py-2 text-left">A</th>
                                                    <th class="px-4 py-2 text-left">B</th>
                                                    <th class="px-4 py-2 text-left">C</th>
                                                    <th class="px-4 py-2 text-left">D</th>
                                                    <th class="px-4 py-2 text-left">Correct</th>
                                                    <th class="px-4 py-2 text-left">Created At</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($quizzes[$chapter['id']] as $quiz): ?>
                                                <tr>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['question']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['option_a']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['option_b']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['option_c']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['option_d']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['correct_answer']) ?></td>
                                                    <td class="px-4 py-2 border-t"><?= htmlspecialchars($quiz['created_at']) ?></td>
                    
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div class="bg-yellow-50 p-4 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-yellow-100 p-2 rounded-full">
                                                    <i class="fas fa-exclamation-circle text-yellow-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-yellow-800">No Quizzes Available</p>
                                                    <p class="text-sm text-yellow-600">
                                                        Add quizzes to make this chapter interactive
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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