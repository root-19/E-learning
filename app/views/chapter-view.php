<?php
require_once __DIR__ . '/../../config/database.php';

// Get chapter ID from URL
$chapter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

try {
    $conn = Database::connect();
    
    // Get chapter details with course info
    $stmt = $conn->prepare("
        SELECT 
            ch.*,
            c.id as course_id,
            c.course_title,
            (SELECT COUNT(*) FROM quizzes WHERE chapter_id = ch.id) as quiz_count
        FROM chapters ch
        JOIN courses c ON ch.course_id = c.id
        WHERE ch.id = ?
    ");
    $stmt->execute([$chapter_id]);
    $chapter = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chapter) {
        header('Location: /my_learning');
        exit();
    }

    // Check if user is enrolled
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $chapter['course_id']]);
    if (!$stmt->fetch()) {
        header('Location: /my_learning');
        exit();
    }

    // Get quizzes for this chapter
    $quizzes = [];
    if ($chapter['quiz_count'] > 0) {
        $stmt = $conn->prepare("SELECT * FROM quizzes WHERE chapter_id = ?");
        $stmt->execute([$chapter_id]);
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get previous and next chapters
    $stmt = $conn->prepare("
        SELECT id, chapter_title, 
            CASE 
                WHEN id < ? THEN 'prev'
                WHEN id > ? THEN 'next'
            END as type
        FROM chapters 
        WHERE course_id = ? AND (id < ? OR id > ?)
        ORDER BY ABS(id - ?)
        LIMIT 2
    ");
    $stmt->execute([$chapter_id, $chapter_id, $chapter['course_id'], $chapter_id, $chapter_id, $chapter_id]);
    $navigation = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $prev_chapter = null;
    $next_chapter = null;
    foreach ($navigation as $nav) {
        if ($nav['type'] === 'prev') $prev_chapter = $nav;
        if ($nav['type'] === 'next') $next_chapter = $nav;
    }

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
    <title><?= htmlspecialchars($chapter['chapter_title']) ?> - InsureLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
    <style>
        .quiz-option:has(input:checked) {
            border-color: #4B793E;
            background-color: #f0f9f0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 mt-20">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-gray-600" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/my_learning" class="hover:text-[#4B793E]">My Learning</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right mx-2"></i>
                        <a href="/course-view/<?= $chapter['course_id'] ?>" class="hover:text-[#4B793E]">
                            <?= htmlspecialchars($chapter['course_title']) ?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right mx-2"></i>
                        <span class="text-gray-800"><?= htmlspecialchars($chapter['chapter_title']) ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <!-- Chapter Header -->
            <div class="bg-[#4B793E] text-white p-6">
                <h1 class="text-3xl font-bold"><?= htmlspecialchars($chapter['chapter_title']) ?></h1>
                <?php if ($chapter['quiz_count'] > 0): ?>
                    <span class="inline-block mt-2 px-3 py-1 bg-white text-[#4B793E] rounded-full text-sm font-semibold">
                        Interactive Chapter
                    </span>
                <?php endif; ?>
            </div>

            <!-- Chapter Content -->
            <div class="p-8">
                <!-- Media Section -->
                <?php if ($chapter['media_type'] !== 'none' && $chapter['media_path']): ?>
                    <div class="mb-8">
                        <?php if ($chapter['media_type'] === 'image'): ?>
                            <img src="/<?= htmlspecialchars($chapter['media_path']) ?>" 
                                 alt="<?= htmlspecialchars($chapter['media_caption'] ?? '') ?>"
                                 class="w-full rounded-lg shadow-md">
                        <?php elseif ($chapter['media_type'] === 'video'): ?>
                            <video controls class="w-full rounded-lg shadow-md">
                                <source src="/<?= htmlspecialchars($chapter['media_path']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                        <?php if ($chapter['media_caption']): ?>
                            <p class="text-sm text-gray-600 mt-2 text-center italic">
                                <?= htmlspecialchars($chapter['media_caption']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Text Content -->
                <div class="prose max-w-none text-black">
                    <?= $chapter['chapter_content'] ?>
                </div>

                <!-- Quizzes Section -->
                <?php if (!empty($quizzes)): ?>
                    <div class="mt-12 border-t pt-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Chapter Quiz</h2>
                        <form id="quizForm" class="space-y-6">
                            <?php foreach ($quizzes as $index => $quiz): ?>
                                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                        Question <?= $index + 1 ?>: <?= htmlspecialchars($quiz['question']) ?>
                                    </h3>
                                    <div class="space-y-3">
                                        <?php 
                                        $options = [
                                            'a' => $quiz['option_a'],
                                            'b' => $quiz['option_b'],
                                            'c' => $quiz['option_c'],
                                            'd' => $quiz['option_d']
                                        ];
                                        foreach ($options as $key => $option): ?>
                                            <label class="quiz-option flex items-center space-x-3 p-4 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="radio" name="quiz_<?= $quiz['id'] ?>" value="<?= $key ?>" 
                                                       class="h-4 w-4 text-[#4B793E] focus:ring-[#4B793E]">
                                                <span class="text-gray-700"><?= htmlspecialchars($option) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" 
                                    class="w-full bg-[#4B793E] text-white py-3 px-6 rounded-lg hover:bg-[#3d6232] transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Submit Quiz
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8">
            <?php if ($prev_chapter): ?>
                <a href="/chapter/<?= $prev_chapter['id'] ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Previous: <?= htmlspecialchars($prev_chapter['chapter_title']) ?>
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>

            <?php if ($next_chapter): ?>
                <a href="/chapter/<?= $next_chapter['id'] ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#4B793E] text-white rounded-lg hover:bg-[#3d6232] transition-colors">
                    Next: <?= htmlspecialchars($next_chapter['chapter_title']) ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('quizForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect answers
            const answers = {};
            this.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const quizId = input.name.split('_')[1];
                answers[quizId] = input.value;
            });

            // Check if all questions are answered
            const totalQuizzes = <?= count($quizzes) ?>;
            if (Object.keys(answers).length !== totalQuizzes) {
                alert('Please answer all questions before submitting.');
                return;
            }

            // Submit answers
            fetch('/submit-quiz', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    chapter_id: <?= $chapter_id ?>,
                    answers: answers
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Quiz submitted successfully! Score: ' + data.score);
                    // Optionally redirect to next chapter if available
                    <?php if ($next_chapter): ?>
                    if (confirm('Would you like to proceed to the next chapter?')) {
                        window.location.href = '/chapter/<?= $next_chapter['id'] ?>';
                    }
                    <?php endif; ?>
                } else {
                    alert(data.message || 'Failed to submit quiz. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit quiz. Please try again.');
            });
        });
    </script>
</body>
</html> 