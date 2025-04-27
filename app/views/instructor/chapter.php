<?php require_once  __DIR__ . '/../../../config/database.php'; 
$conn = Database::connect();   

$stmt = $conn->query("SELECT id, course_title FROM courses"); 
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $course_id = $_POST['course_id'] ?? null;     
    $chapter_title = $_POST['chapter_title'] ?? null;     
    $chapter_content = $_POST['chapter_content'] ?? null;      
    
    if ($course_id && $chapter_title) {         
        $insert = $conn->prepare("INSERT INTO chapters (course_id, chapter_title, chapter_content) VALUES (:course_id, :chapter_title, :chapter_content)");         
        $insert->bindParam(':course_id', $course_id);         
        $insert->bindParam(':chapter_title', $chapter_title);         
        $insert->bindParam(':chapter_content', $chapter_content);         
        $insert->execute();          
        
        echo "<script>alert('Chapter Added Successfully!');</script>";         
        exit;     
    } else {         
        echo "<script>alert('Please complete the form.');</script>";     
    } 
} 

include 'layout/header.php';
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Chapter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- TinyMCE CDN with your API key -->
    <script src="https://cdn.tiny.cloud/1/cumhefftbnxvul2j7s1d8g0wo3t5p2zsbpboptgpnxqdlgw5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        /* Custom styles for the editor */
        .tox-tinymce {
            border-radius: 0.375rem !important;
            border: 1px solid #e5e7eb !important;
        }
        
        .tox .tox-toolbar__group {
            border: none !important;
        }
        
        .tox .tox-edit-area__iframe {
            background-color: white !important;
        }
        
        .chapter-content-label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-lg mt-10">
        <!-- <h1 class="text-2xl font-bold mb-4">Add Chapter</h1> -->

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold">Select Course</label>
                <select name="course_id" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Chapter Title</label>
                <input type="text" name="chapter_title" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div>
                <label class="chapter-content-label">Chapter Content</label>
                <textarea id="chapter-content" name="chapter_content"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded font-bold">
                Add Chapter
            </button>
        </form>
    </div>

    <script>
        tinymce.init({
            selector: '#chapter-content',
            height: 300,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            // Use a simple toolbar to match the image
            toolbar_mode: 'sliding',
            branding: true, // Keep the "tiny" branding to match image
            statusbar: true,
            // Create a simplified interface like in the image
            setup: function(editor) {
                editor.on('init', function() {
                    // Simplified toolbar to match the image
                    const simplifiedButtons = document.querySelectorAll('.tox-toolbar__group:not(:nth-child(-n+4))');
                    simplifiedButtons.forEach(button => {
                        button.style.display = 'none';
                    });
                });
            }
        });
    </script>
</body>
</html>