<?php 
require_once  __DIR__ . '/../../../config/database.php'; 
$conn = Database::connect();   

$stmt = $conn->query("SELECT id, course_title FROM courses"); 
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $course_id = $_POST['course_id'] ?? null;     
    $chapter_title = $_POST['chapter_title'] ?? null;     
    $quizzes = $_POST['quizzes'] ?? [];  // array of quizzes
    
    if ($course_id && $chapter_title && !empty($quizzes)) {   
        // Insert the chapter first
        $insertChapter = $conn->prepare("INSERT INTO chapters (course_id, chapter_title) VALUES (:course_id, :chapter_title)");
        $insertChapter->bindParam(':course_id', $course_id);
        $insertChapter->bindParam(':chapter_title', $chapter_title);
        $insertChapter->execute();
        $chapter_id = $conn->lastInsertId(); // get inserted chapter id

        // Insert quizzes
        $insertQuiz = $conn->prepare("INSERT INTO quizzes (chapter_id, question, option_a, option_b, option_c, option_d, correct_answer) VALUES (:chapter_id, :question, :option_a, :option_b, :option_c, :option_d, :correct_answer)");

        foreach ($quizzes as $quiz) {
            $insertQuiz->execute([
                ':chapter_id' => $chapter_id,
                ':question' => $quiz['question'],
                ':option_a' => $quiz['option_a'],
                ':option_b' => $quiz['option_b'],
                ':option_c' => $quiz['option_c'],
                ':option_d' => $quiz['option_d'],
                ':correct_answer' => $quiz['correct_answer']
            ]);
        }
        
        echo "<script>alert('Chapter and Quizzes Added Successfully!');</script>";         
        exit;     
    } else {         
        echo "<script>alert('Please complete all fields and add at least one quiz.');</script>";     
    } 
} 

include 'layout/header.php';
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Chapter with Quizzes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-out {
            animation: fadeOut 0.4s forwards;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; height: 0; margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-2xl mt-12 mb-12 border border-gray-200">
        <h1 class="text-3xl font-extrabold text-blue-700 mb-8 text-center tracking-tight">Add Chapter with Quizzes</h1>
        <form method="POST" class="space-y-8" id="chapter-form" onsubmit="return handleSubmit(event)">
            <div>
                <label class="block mb-2 font-semibold text-lg text-gray-700">Select Course</label>
                <select name="course_id" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-lg text-gray-700">Chapter Title</label>
                <input type="text" name="chapter_title" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div id="quizzes-section" class="space-y-6">
                <h2 class="text-2xl font-bold mb-4 text-green-700 flex items-center gap-2"><i class="fa-solid fa-list-check"></i> Quizzes</h2>
                <!-- Dynamic quiz fields go here -->
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="addQuiz()" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <i class="fa-solid fa-plus"></i> Add Quiz
                </button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-bold shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 mt-0">
                    <i class="fa-solid fa-paper-plane"></i> Submit Chapter with Quizzes
                </button>
            </div>
        </form>
    </div>

    <script>
        let quizCount = 0;

        function handleSubmit(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Successfully')) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Chapter and Quizzes Added Successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'add_chapter_quiz.php';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please complete all fields and add at least one quiz.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
            
            return false;
        }

        function addQuiz() {
            quizCount++;
            const quizzesSection = document.getElementById('quizzes-section');

            const quizHTML = `
                <div class="border border-gray-300 bg-gray-50 p-6 rounded-2xl mb-4 shadow fade-in relative" id="quiz-${quizCount}">
                    <div class="absolute top-3 right-3">
                        <button type="button" onclick="removeQuiz(${quizCount})" class="text-red-500 hover:text-red-700 text-lg" title="Remove Quiz"><i class='fa-solid fa-trash'></i></button>
                    </div>
                    <h3 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2"><i class='fa-solid fa-question'></i> Quiz #${quizCount}</h3>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Question</label>
                        <input type="text" name="quizzes[${quizCount}][question]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option A</label>
                            <input type="text" name="quizzes[${quizCount}][option_a]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option B</label>
                            <input type="text" name="quizzes[${quizCount}][option_b]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option C</label>
                            <input type="text" name="quizzes[${quizCount}][option_c]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option D</label>
                            <input type="text" name="quizzes[${quizCount}][option_d]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-semibold mb-1">Correct Answer <span class='text-xs'>(A, B, C, or D)</span></label>
                        <input type="text" name="quizzes[${quizCount}][correct_answer]" maxlength="1" class="w-full border border-gray-300 p-3 rounded-lg uppercase focus:ring-2 focus:ring-blue-400" pattern="[A-D]" required>
                    </div>
                </div>
            `;

            quizzesSection.insertAdjacentHTML('beforeend', quizHTML);
            // Animate scroll to new quiz
            setTimeout(() => {
                document.getElementById('quiz-' + quizCount).scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }

        function removeQuiz(id) {
            const quizDiv = document.getElementById('quiz-' + id);
            if (quizDiv) {
                quizDiv.classList.add('fade-out');
                setTimeout(() => quizDiv.remove(), 400);
            }
        }
    </script>
</body>
</html>
