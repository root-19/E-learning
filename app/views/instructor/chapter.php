<?php require_once  __DIR__ . '/../../../config/database.php'; 
$conn = Database::connect();   

$stmt = $conn->query("SELECT id, course_title FROM courses"); 
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $course_id = $_POST['course_id'] ?? null;     
    $chapter_title = $_POST['chapter_title'] ?? null;     
    $chapter_content = $_POST['chapter_content'] ?? null;
    $media_caption = $_POST['media_caption'] ?? null;
    
    if ($course_id && $chapter_title) {
        $media_type = 'none';
        $media_path = null;
        
        // Handle file upload
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../../uploads/chapters/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file = $_FILES['media_file'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif'];
            $allowed_video_types = ['mp4', 'webm', 'ogg'];
            
            if (in_array($file_extension, $allowed_image_types)) {
                $media_type = 'image';
            } elseif (in_array($file_extension, $allowed_video_types)) {
                $media_type = 'video';
            } else {
                echo "<script>alert('Invalid file type. Please upload an image or video.');</script>";
                exit;
            }
            
            $file_name = time() . '_' . basename($file['name']);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $media_path = 'uploads/chapters/' . $file_name;
            } else {
                echo "<script>alert('Failed to upload file.');</script>";
                exit;
            }
        }
        
        $insert = $conn->prepare("INSERT INTO chapters (course_id, chapter_title, chapter_content, media_type, media_path, media_caption) VALUES (:course_id, :chapter_title, :chapter_content, :media_type, :media_path, :media_caption)");         
        $insert->bindParam(':course_id', $course_id);         
        $insert->bindParam(':chapter_title', $chapter_title);         
        $insert->bindParam(':chapter_content', $chapter_content);
        $insert->bindParam(':media_type', $media_type);
        $insert->bindParam(':media_path', $media_path);
        $insert->bindParam(':media_caption', $media_caption);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TinyMCE CDN with your API key -->
    <script src="https://cdn.tiny.cloud/1/cumhefftbnxvul2j7s1d8g0wo3t5p2zsbpboptgpnxqdlgw5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
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

        .file-upload-preview {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }

        .file-upload-preview img, .file-upload-preview video {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Add New Chapter</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Select Course</label>
                <select name="course_id" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Chapter Title</label>
                <input type="text" name="chapter_title" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Chapter Content</label>
                <textarea id="chapter-content" name="chapter_content"></textarea>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Media Upload</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="media_file" id="media_file" class="hidden" accept="image/*,video/*">
                    <label for="media_file" class="cursor-pointer">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <span class="text-gray-600">Click to upload image or video</span>
                            <span class="text-sm text-gray-500 mt-1">Supported formats: JPG, PNG, GIF, MP4, WebM, OGG</span>
                        </div>
                    </label>
                    <div id="file-preview" class="file-upload-preview mt-4"></div>
                </div>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Media Caption</label>
                <input type="text" name="media_caption" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" placeholder="Optional caption for the media">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-bold shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <i class="fas fa-save mr-2"></i> Add Chapter
            </button>
        </form>
    </div>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#chapter-content',
            height: 400,
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
            toolbar_mode: 'sliding',
            branding: true,
            statusbar: true
        });

        // Handle file upload preview
        document.getElementById('media_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('file-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    if (file.type.startsWith('image/')) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    } else if (file.type.startsWith('video/')) {
                        preview.innerHTML = `<video src="${e.target.result}" controls></video>`;
                    }
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.innerHTML = '';
            }
        });
    </script>
</body>
</html>