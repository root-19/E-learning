<?php
require_once __DIR__ . '/../../controller/ModuleController.php';
// Get all courses
$controller = new ModuleController();
$courses = $controller->listCourses();
include 'layout/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .course-card {
            transition: all 0.3s ease;
            background: white;
            border-radius: 1rem;
            overflow: hidden;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .course-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .course-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
        }
        .search-input {
            background: white;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .filter-select {
            background: white;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .modal-content {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8 sticky top-0 bg-gray-50 py-4 z-10">
        <h1 class="text-3xl font-bold text-gray-800">Course Management</h1>
        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl" onclick="showAddCourseModal()">
            <i class="fas fa-plus"></i>
            <span>Add New Course</span>
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-8 flex gap-4 sticky top-20 bg-gray-50 py-4 z-10">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search courses..." class="search-input w-full pl-12">
            </div>
        </div>
        <div class="w-48">
            <select id="moduleType" class="filter-select w-full">
                <option value="all">All Types</option>
                <option value="traditional">Traditional</option>
                <option value="interactive">Interactive</option>
            </select>
        </div>
    </div>

    <!-- Course List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="courseList">
        <?php foreach ($courses as $course): ?>
        <div class="course-card">
            <div class="course-image" style="background-image: url('/<?= htmlspecialchars($course['course_image']) ?>')">
                <div class="absolute top-4 right-4">
                    <?php 
                    $hasInteractiveChapters = false;
                    $hasTraditionalChapters = false;
                    $chapters = $controller->getChaptersForCourse($course['id']);
                    foreach ($chapters as $chapter) {
                        if ($chapter['type'] === 'interactive') {
                            $hasInteractiveChapters = true;
                        } else {
                            $hasTraditionalChapters = true;
                        }
                    }
                    $courseType = $hasInteractiveChapters ? 'interactive' : 'traditional';
                    ?>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $courseType === 'interactive' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                        <?= ucfirst($courseType) ?>
                    </span>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2 text-gray-800"><?= htmlspecialchars($course['course_title']) ?></h3>
                <p class="text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($course['description']) ?></p>
                <div class="flex justify-between items-center">
                    <div class="flex gap-3">
                        <button onclick="editCourse(<?= $course['id'] ?>)" class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCourse(<?= $course['id'] ?>)" class="text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <a href="/instructor/chapter?course_id=<?= $course['id'] ?>" class="text-green-600 hover:text-green-800 transition-colors flex items-center gap-2">
                        <i class="fas fa-book"></i>
                        <span>Chapters</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Course Modal -->
<div id="addCourseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-y-auto">
    <div class="modal-content w-full max-w-2xl mx-4 my-8">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Add New Course</h2>
                <button onclick="hideAddCourseModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Course Title</label>
                    <input type="text" name="course_title" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Course Type</label>
                    <select name="course_type" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                        <option value="traditional">Traditional</option>
                        <option value="interactive">Interactive</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Course Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" name="course_image" id="courseImage" class="hidden" accept="image/*">
                        <label for="courseImage" class="cursor-pointer">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <span class="text-gray-600">Click to upload image</span>
                                <span class="text-sm text-gray-500 mt-1">Supported formats: JPG, PNG, GIF</span>
                            </div>
                        </label>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img src="" alt="Preview" class="max-h-48 mx-auto">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:border-transparent" required></textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="hideAddCourseModal()" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" name="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Create Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAddCourseModal() {
    document.getElementById('addCourseModal').classList.remove('hidden');
    document.getElementById('addCourseModal').classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function hideAddCourseModal() {
    document.getElementById('addCourseModal').classList.add('hidden');
    document.getElementById('addCourseModal').classList.remove('flex');
    document.body.style.overflow = ''; // Restore background scrolling
}

// Close modal when clicking outside
document.getElementById('addCourseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideAddCourseModal();
    }
});

function editCourse(id) {
    // Implement edit functionality
    console.log('Edit course:', id);
}

function deleteCourse(id) {
    if (confirm('Are you sure you want to delete this course?')) {
        // Implement delete functionality
        console.log('Delete course:', id);
    }
}

// Image preview functionality
document.getElementById('courseImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const courses = document.querySelectorAll('#courseList > div');
    
    courses.forEach(course => {
        const title = course.querySelector('h3').textContent.toLowerCase();
        const description = course.querySelector('p').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            course.style.display = '';
        } else {
            course.style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('moduleType').addEventListener('change', function(e) {
    const filterType = e.target.value;
    const courses = document.querySelectorAll('#courseList > div');
    
    courses.forEach(course => {
        const type = course.querySelector('.absolute span').textContent.toLowerCase();
        
        if (filterType === 'all' || type === filterType) {
            course.style.display = '';
        } else {
            course.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
