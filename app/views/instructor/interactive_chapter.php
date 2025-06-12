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
        try {
            // Insert the chapter first
            $insertChapter = $conn->prepare("INSERT INTO chapters (course_id, chapter_title) VALUES (:course_id, :chapter_title)");
            $insertChapter->bindParam(':course_id', $course_id);
            $insertChapter->bindParam(':chapter_title', $chapter_title);
            $insertChapter->execute();
            $chapter_id = $conn->lastInsertId(); // get inserted chapter id

            // Insert quizzes
            $insertQuiz = $conn->prepare("INSERT INTO quizzes (chapter_id, question, option_a, option_b, option_c, option_d, correct_answer, quiz_type) VALUES (:chapter_id, :question, :option_a, :option_b, :option_c, :option_d, :correct_answer, :quiz_type)");

            foreach ($quizzes as $quiz) {
                $quizType = $quiz['quiz_type'];
                $params = [
                    ':chapter_id' => $chapter_id,
                    ':question' => $quiz['question'],
                    ':quiz_type' => $quizType,
                    ':correct_answer' => $quiz['correct_answer']
                ];

                // Handle options based on quiz type
                switch($quizType) {
                    case 'multiple_choice':
                        $params[':option_a'] = $quiz['option_a'];
                        $params[':option_b'] = $quiz['option_b'];
                        $params[':option_c'] = $quiz['option_c'];
                        $params[':option_d'] = $quiz['option_d'];
                        break;
                        
                    case 'true_false':
                        $params[':option_a'] = 'True';
                        $params[':option_b'] = 'False';
                        $params[':option_c'] = '';  // Empty string instead of null
                        $params[':option_d'] = '';  // Empty string instead of null
                        break;
                        
                    case 'fill_blank':
                        $params[':option_a'] = '';  // Empty string instead of null
                        $params[':option_b'] = '';  // Empty string instead of null
                        $params[':option_c'] = '';  // Empty string instead of null
                        $params[':option_d'] = '';  // Empty string instead of null
                        break;
                }

                // Debug information
                error_log('Quiz Type: ' . $quizType . ' | Correct Answer: ' . $quiz['correct_answer']);
                error_log("Quiz Type: " . $quizType);
                error_log("Params: " . print_r($params, true));

                $insertQuiz->execute($params);
            }
            
            echo "<script>alert('Chapter and Quizzes Added Successfully!');</script>";         
            exit;
        } catch(PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
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
            
            // Debug: Log form data
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Server response:', data); // Debug log
                if (data.includes('Successfully')) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Chapter and Quizzes Added Successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reset the form
                            form.reset();
                            // Clear all quizzes
                            const quizzesSection = document.getElementById('quizzes-section');
                            quizzesSection.innerHTML = '';
                            quizCount = 0;
                            // Scroll to top
                            window.scrollTo({ top: 0, behavior: 'smooth' });
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
                console.error('Error:', error); // Debug log
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
                        <label class="block text-sm font-semibold mb-1">Quiz Type</label>
                        <select name="quizzes[${quizCount}][quiz_type]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required onchange="handleQuizTypeChange(${quizCount}, this.value)">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="fill_blank">Fill in the Blank</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Question</label>
                        <input type="text" name="quizzes[${quizCount}][question]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div id="options-${quizCount}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <label class="block text-sm font-semibold mb-1">Correct Answer</label>
                        <div id="correct-answer-${quizCount}">
                            <input type="text" name="quizzes[${quizCount}][correct_answer]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
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

        function handleQuizTypeChange(quizId, type) {
            const optionsDiv = document.getElementById(`options-${quizId}`);
            const correctAnswerDiv = document.getElementById(`correct-answer-${quizId}`);
            
            switch(type) {
                case 'multiple_choice':
                    optionsDiv.innerHTML = `
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option A</label>
                            <input type="text" name="quizzes[${quizId}][option_a]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option B</label>
                            <input type="text" name="quizzes[${quizId}][option_b]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option C</label>
                            <input type="text" name="quizzes[${quizId}][option_c]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Option D</label>
                            <input type="text" name="quizzes[${quizId}][option_d]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                        </div>
                    `;
                    correctAnswerDiv.innerHTML = `
                        <input type="text" name="quizzes[${quizId}][correct_answer]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required placeholder="Enter the correct answer">
                    `;
                    break;
                    
                case 'true_false':
                    optionsDiv.innerHTML = `
                        <div>
                            <label class="block text-sm font-semibold mb-1">True</label>
                            <input type="text" name="quizzes[${quizId}][option_a]" value="True" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">False</label>
                            <input type="text" name="quizzes[${quizId}][option_b]" value="False" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" readonly>
                        </div>
                    `;
                    correctAnswerDiv.innerHTML = `
                        <select name="quizzes[${quizId}][correct_answer]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Select correct answer</option>
                            <option value="True">True</option>
                            <option value="False">False</option>
                        </select>
                    `;
                    break;
                    
                case 'fill_blank':
                    optionsDiv.innerHTML = ''; // No options for fill in the blank
                    correctAnswerDiv.innerHTML = `
                        <input type="text" name="quizzes[${quizId}][correct_answer]" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required placeholder="Enter the correct answer text">
                    `;
                    break;
            }
        }
    </script>
</body>
</html>
