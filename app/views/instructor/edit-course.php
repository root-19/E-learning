<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controller/ModuleController.php';

use app\controller\ModuleController;

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
    $stmt2 = $db->prepare("SELECT *, quiz_type FROM quizzes WHERE chapter_id = ?");
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
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

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
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-lg text-gray-800"><?= htmlspecialchars($chapter['chapter_title']) ?></h3>
                                <button onclick="toggleEditForm(<?= $chapter['id'] ?>)" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i> Edit Chapter
                                </button>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                    <?= $chapter['type'] === 'interactive' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= ucfirst($chapter['type']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    <div id="editForm-<?= $chapter['id'] ?>" class="hidden mt-4 bg-gray-50 p-6 rounded-lg">
                        <form id="editChapterForm-<?= $chapter['id'] ?>" class="space-y-4">
                            <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                            <input type="hidden" name="course_id" value="<?= $course_id ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Chapter Title</label>
                                <input type="text" name="chapter_title" value="<?= htmlspecialchars($chapter['chapter_title']) ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4B793E] focus:ring-[#4B793E]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Chapter Content</label>
                                <textarea name="chapter_content" rows="10" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4B793E] focus:ring-[#4B793E]"><?= htmlspecialchars($chapter['chapter_content']) ?></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Chapter Type</label>
                                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4B793E] focus:ring-[#4B793E]">
                                    <option value="traditional" <?= $chapter['type'] === 'traditional' ? 'selected' : '' ?>>Traditional</option>
                                    <option value="interactive" <?= $chapter['type'] === 'interactive' ? 'selected' : '' ?>>Interactive</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Chapter Image</label>
                                <input type="file" name="chapter_image" accept="image/*" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#4B793E] file:text-white hover:file:bg-[#3d6232]">
                            </div>

                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="toggleEditForm(<?= $chapter['id'] ?>)" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-[#4B793E] rounded-md hover:bg-[#3d6232]">
                                    Save Changes
                                </button>
                            </div>
                        </form>
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

                            <!-- Chapter Media Display -->
                            <?php if ($chapter['media_type'] !== 'none' && $chapter['media_path']): ?>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-800 flex items-center gap-2 mb-3">
                                        <i class="fas fa-paperclip text-purple-600"></i>
                                        Attached Material
                                        <button type="button" onclick="toggleMediaEditForm(<?= $chapter['id'] ?>)" class="ml-auto text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-edit"></i> Edit Media
                                        </button>
                                    </h4>
                                    <div class="mb-6">
                                        <?php if ($chapter['media_type'] === 'image'): ?>
                                            <img src="/<?= htmlspecialchars($chapter['media_path']) ?>" 
                                                 alt="<?= htmlspecialchars($chapter['media_caption'] ?? '') ?>"
                                                 class="w-full rounded-lg shadow-md">
                                        <?php elseif ($chapter['media_type'] === 'video'): ?>
                                            <video controls class="w-full rounded-lg shadow-md">
                                                <source src="/<?= htmlspecialchars($chapter['media_path']) ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php elseif ($chapter['media_type'] === 'pdf'): ?>
                                            <div class="w-full h-[600px] rounded-lg shadow-md overflow-hidden">
                                                <iframe 
                                                    src="/<?= htmlspecialchars($chapter['media_path']) ?>" 
                                                    width="100%" 
                                                    height="100%" 
                                                    frameborder="0"></iframe>
                                            </div>
                                        <?php elseif ($chapter['media_type'] === 'pptx'): ?>
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                                                <i class="fas fa-file-powerpoint text-4xl text-orange-600 mb-2"></i>
                                                <p class="font-medium text-gray-800 mb-2">
                                                    PowerPoint (.pptx) file available for download.
                                                </p>
                                                <a href="/<?= htmlspecialchars($chapter['media_path']) ?>" 
                                                   class="inline-block px-6 py-2 bg-[#4B793E] text-white rounded-lg hover:bg-[#3d6232] transition"
                                                   download>
                                                    Download PPTX
                                                </a>
                                                <?php if ($chapter['media_caption']): ?>
                                                    <p class="text-sm text-gray-600 mt-2 italic">
                                                        <?= htmlspecialchars($chapter['media_caption']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($chapter['media_type'] === 'ppt'): ?>
                                            <div class="w-full h-[600px] rounded-lg shadow-md overflow-hidden">
                                                <iframe 
                                                    src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . $chapter['media_path']) ?>" 
                                                    width="100%" 
                                                    height="100%" 
                                                    frameborder="0"></iframe>
                                            </div>
                                        <?php elseif (in_array($chapter['media_type'], ['doc', 'docx'])): ?>
                                            <div id="docx-container-<?= $chapter['id'] ?>" class="prose max-w-none p-4 rounded-lg border border-gray-200" style="background: #fff; color: #333;"></div>
                                            <script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>
                                            <script>
                                            function renderDocx(url, targetId) {
                                                fetch(url)
                                                    .then(response => response.blob())
                                                    .then(blob => blob.arrayBuffer())
                                                    .then(arrayBuffer => mammoth.convertToHtml({arrayBuffer: arrayBuffer}))
                                                    .then(function(result) {
                                                        document.getElementById(targetId).innerHTML = result.value;
                                                    })
                                                    .catch(function(error) {
                                                        document.getElementById(targetId).innerHTML = '<p class="text-red-600">Failed to load document.</p>';
                                                        console.error('Mammoth.js error:', error);
                                                    });
                                            }
                                            renderDocx('<?= '/' . htmlspecialchars($chapter['media_path']) ?>', 'docx-container-<?= $chapter['id'] ?>');
                                            </script>
                                        <?php elseif (in_array($chapter['media_type'], ['xls', 'xlsx'])): ?>
                                            <div class="w-full h-[600px] rounded-lg shadow-md overflow-hidden">
                                                <iframe 
                                                    src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . $chapter['media_path']) ?>" 
                                                    width="100%" 
                                                    height="100%" 
                                                    frameborder="0"></iframe>
                                            </div>
                                        <?php elseif (in_array($chapter['media_type'], ['txt', 'zip', 'rar'])): ?>
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                <div class="flex items-center gap-3">
                                                    <i class="fas fa-file text-2xl text-gray-600"></i>
                                                    <div>
                                                        <p class="font-medium text-gray-800">File: <?= basename($chapter['media_path']) ?></p>
                                                        <a href="/<?= htmlspecialchars($chapter['media_path']) ?>" 
                                                           class="text-sm text-blue-600 hover:text-blue-800" 
                                                           download>
                                                            Download <?= strtoupper($chapter['media_type']) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if ($chapter['media_caption']): ?>
                                                    <p class="text-sm text-gray-600 mt-2 italic">
                                                        <?= htmlspecialchars($chapter['media_caption']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-sm text-gray-500">No displayable media available for this chapter.</p>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Media Edit Form (Hidden by default) -->
                                    <div id="editMediaForm-<?= $chapter['id'] ?>" class="hidden mt-4 bg-gray-50 p-6 rounded-lg">
                                        <form id="updateMediaForm-<?= $chapter['id'] ?>" class="space-y-4">
                                            <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                                            <input type="hidden" name="course_id" value="<?= $course_id ?>">
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Upload New Media File (Optional)</label>
                                                <input type="file" name="media_file" 
                                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#4B793E] file:text-white hover:file:bg-[#3d6232]">
                                                <p class="mt-1 text-xs text-gray-500">Current File: 
                                                    <?php if (!empty($chapter['media_path'])): ?>
                                                        <a href="/<?= htmlspecialchars($chapter['media_path']) ?>" target="_blank" class="text-blue-600 hover:underline"> <?= basename($chapter['media_path']) ?></a> (Type: <?= htmlspecialchars($chapter['media_type']) ?>)
                                                    <?php else: ?>
                                                        None
                                                    <?php endif; ?>
                                                </p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Media Caption (Optional)</label>
                                                <input type="text" name="media_caption" value="<?= htmlspecialchars($chapter['media_caption'] ?? '') ?>" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4B793E] focus:ring-[#4B793E]">
                                            </div>

                                            <div class="flex justify-end gap-4">
                                                <button type="button" onclick="toggleMediaEditForm(<?= $chapter['id'] ?>)" 
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 text-sm font-medium text-white bg-[#4B793E] rounded-md hover:bg-[#3d6232]">
                                                    Save Media Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>

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
                                                    <th class="px-4 py-2 text-left">Actions</th>
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
                    
                                                    <td class="px-4 py-2 border-t text-center">
                                                        <button onclick="toggleQuizEditForm(<?= $quiz['id'] ?>)" class="text-blue-600 hover:text-blue-800" title="Edit Quiz">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Quiz Edit Form (Hidden by default) -->
                                                <tr id="editQuizForm-<?= $quiz['id'] ?>" class="hidden">
                                                    <td colspan="8" class="p-4 border-t bg-gray-50">
                                                        <form id="updateQuizForm-<?= $quiz['id'] ?>" class="space-y-3">
                                                            <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                                                            <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Question</label>
                                                                <textarea name="question" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= htmlspecialchars($quiz['question']) ?></textarea>
                                                            </div>

                                                            <?php if ($quiz['quiz_type'] === 'fill_blank'): ?>
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                                                                    <input type="text" name="correct_answer" value="<?= htmlspecialchars($quiz['correct_answer']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                </div>
                                                            <?php elseif ($quiz['quiz_type'] === 'true_false'): ?>
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                                                                    <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                        <option value="True" <?= (strtolower($quiz['correct_answer']) === 'true') ? 'selected' : '' ?>>True</option>
                                                                        <option value="False" <?= (strtolower($quiz['correct_answer']) === 'false') ? 'selected' : '' ?>>False</option>
                                                                    </select>
                                                                </div>
                                                            <?php else: // Default to multiple choice if type is not fill_blank or true_false (or explicitly 'multiple_choice') ?>
                                                                <div class="grid grid-cols-2 gap-3">
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700">Option A</label>
                                                                        <input type="text" name="option_a" value="<?= htmlspecialchars($quiz['option_a']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700">Option B</label>
                                                                        <input type="text" name="option_b" value="<?= htmlspecialchars($quiz['option_b']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700">Option C</label>
                                                                        <input type="text" name="option_c" value="<?= htmlspecialchars($quiz['option_c']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-medium text-gray-700">Option D</label>
                                                                        <input type="text" name="option_d" value="<?= htmlspecialchars($quiz['option_d']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                                                                    <select name="correct_answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                        <option value="A" <?= $quiz['correct_answer'] === 'A' ? 'selected' : '' ?>>A</option>
                                                                        <option value="B" <?= $quiz['correct_answer'] === 'B' ? 'selected' : '' ?>>B</option>
                                                                        <option value="C" <?= $quiz['correct_answer'] === 'C' ? 'selected' : '' ?>>C</option>
                                                                        <option value="D" <?= $quiz['correct_answer'] === 'D' ? 'selected' : '' ?>>D</option>
                                                                    </select>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="flex justify-end gap-3">
                                                                <button type="button" onclick="toggleQuizEditForm(<?= $quiz['id'] ?>)" 
                                                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit" 
                                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                                                    Save Changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </td>
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

    <script>
    function toggleEditForm(chapterId) {
        const form = document.getElementById(`editForm-${chapterId}`);
        form.classList.toggle('hidden');
    }

    function toggleQuizEditForm(quizId) {
        const editQuizForm = document.getElementById(`editQuizForm-${quizId}`);
        editQuizForm.classList.toggle('hidden');
    }

    function toggleMediaEditForm(chapterId) {
        const editMediaForm = document.getElementById(`editMediaForm-${chapterId}`);
        editMediaForm.classList.toggle('hidden');
    }

    // Add form submit handlers
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form[id^="editChapterForm-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Show loading state
                Swal.fire({
                    title: 'Updating chapter...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                fetch('/instructor/update-chapter', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Chapter updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update chapter');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message
                    });
                });
            });
        });

        document.querySelectorAll('form[id^="updateQuizForm-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Show loading state
                Swal.fire({
                    title: 'Updating quiz...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                fetch('/instructor/update-quiz', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Quiz updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update quiz');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message
                    });
                });
            });
        });

        document.querySelectorAll('form[id^="updateMediaForm-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Show loading state
                Swal.fire({
                    title: 'Updating media...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                fetch('/instructor/update-chapter-media', { // New endpoint for media update
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Media updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to update media');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message
                    });
                });
            });
        });
    });
    </script>
</body>
</html> 