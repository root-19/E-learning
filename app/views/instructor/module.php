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
</head>
<body>
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-lg mt-10">
     <!-- <h1 class="text-3xl font-semibold text-center mb-6">Add New Course</h1> -->

        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 text-green-700 p-3 mb-5 rounded"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-5 rounded"><?= $error_message ?></div>
        <?php endif; ?>

        <form action="" method="POST"  enctype="multipart/form-data" class="space-y-4">

            <div>
                <label class="block mb-2 font-medium text-gray-700">Course Title</label>
                <input type="text" name="course_title" class="w-full border border-gray-300 rounded-lg p-6 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter course title" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Course Image</label>
                <input type="file" name="course_image" class="w-full border border-gray-300 rounded-lg p-6 focus:outline-none focus:ring-2 focus:ring-blue-400" accept="image/*" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea name="description" rows="8" class="w-full border border-gray-300 rounded-lg p-6 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter course description" required></textarea>
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition">
                Create Course
            </button>

        </form>
    </div>

</body>
</html>
