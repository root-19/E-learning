<?php
require_once __DIR__ . '/../../../config/database.php';
$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_title = $_POST['course_title'] ?? null;
    $description = $_POST['description'] ?? null;
    $course_type = 'interactive';
    
    if ($course_title && $description) {
        $banner_path = null;
        
        // Handle banner image upload
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../../uploads/courses/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file = $_FILES['banner_image'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($file_extension, $allowed_types)) {
                echo "<script>alert('Invalid file type. Please upload an image (JPG, PNG, GIF).');</script>";
                exit;
            }
            
            $file_name = time() . '_' . basename($file['name']);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $banner_path = 'uploads/courses/' . $file_name;
            } else {
                echo "<script>alert('Failed to upload banner image.');</script>";
                exit;
            }
        }
        
        // Handle PowerPoint file upload
        $ppt_path = null;
        if (isset($_FILES['ppt_file']) && $_FILES['ppt_file']['error'] == 0) {
            $upload_dir = __DIR__ . '/../../../uploads/presentations/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file = $_FILES['ppt_file'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_types = ['ppt', 'pptx'];
            
            if (!in_array($file_extension, $allowed_types)) {
                echo "<script>alert('Invalid file type. Please upload a PowerPoint file (PPT, PPTX).');</script>";
                exit;
            }
            
            $file_name = time() . '_' . basename($file['name']);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $ppt_path = 'uploads/presentations/' . $file_name;
            } else {
                echo "<script>alert('Failed to upload PowerPoint file.');</script>";
                exit;
            }
        }
        
        $insert = $conn->prepare("INSERT INTO courses (course_title, course_image, description, course_type, ppt_path) VALUES (:course_title, :course_image, :description, :course_type, :ppt_path)");
        $insert->bindParam(':course_title', $course_title);
        $insert->bindParam(':course_image', $banner_path);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':course_type', $course_type);
        $insert->bindParam(':ppt_path', $ppt_path);
        
        if ($insert->execute()) {
            echo "<script>alert('Interactive Module Created Successfully!'); window.location.href = '/instructor/module';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to create interactive module.');</script>";
        }
    } else {
        echo "<script>alert('Please complete all required fields.');</script>";
    }
}

include 'layout/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Interactive Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .file-upload-preview {
            max-width: 300px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        
        .file-upload-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .ppt-preview {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Interactive Module</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Course Title</label>
                <input type="text" name="course_title" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400" required></textarea>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Banner Image</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="banner_image" id="bannerImage" class="hidden" accept="image/*">
                    <label for="bannerImage" class="cursor-pointer">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                            <span class="text-gray-600">Click to upload banner image</span>
                            <span class="text-sm text-gray-500 mt-1">Recommended size: 1200×400px (JPG, PNG, GIF)</span>
                        </div>
                    </label>
                    <div id="bannerPreview" class="file-upload-preview mt-4"></div>
                </div>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-700">PowerPoint Presentation</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" name="ppt_file" id="pptFile" class="hidden" accept=".ppt,.pptx">
                    <label for="pptFile" class="cursor-pointer">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-powerpoint text-4xl text-gray-400 mb-2"></i>
                            <span class="text-gray-600">Click to upload PowerPoint file</span>
                            <span class="text-sm text-gray-500 mt-1">Supported formats: PPT, PPTX</span>
                        </div>
                    </label>
                    <div id="pptPreview" class="ppt-preview mt-4 hidden">
                        <i class="fas fa-file-powerpoint text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600" id="pptFileName"></p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-bold shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <i class="fas fa-save mr-2"></i> Create Interactive Module
            </button>
        </form>
    </div>

    <script>
        // Handle banner image preview
        document.getElementById('bannerImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('bannerPreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.innerHTML = `<img src="${e.target.result}" alt="Banner preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.innerHTML = '';
            }
        });

        // Handle PowerPoint file preview
        document.getElementById('pptFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('pptPreview');
            const fileName = document.getElementById('pptFileName');
            
            if (file) {
                preview.classList.remove('hidden');
                fileName.textContent = file.name;
            } else {
                preview.classList.add('hidden');
                fileName.textContent = '';
            }
        });
    </script>
</body>
</html> 