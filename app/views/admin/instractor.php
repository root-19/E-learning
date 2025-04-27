<?php
// Include the InstructorController
require_once __DIR__ . '/../../controller/InstructorController.php';
// use root_dev\Controller\InstructorController;

// Initialize the instructorController variable outside of POST check
$instructorController = new InstructorController();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Now you can safely call the method on the $instructorController object
    $success = $instructorController->registerInstructor(); // Call the register method

    // Redirect based on the result of registration
    if ($success) {
        $_SESSION['success'] = "Instructor registered successfully!";
        header('Location: /admin/instractor'); // Redirect to instructor page
        exit();
    } else {
        $_SESSION['error'] = "Failed to register instructor. Please try again.";
        header('Location: ' . $_SERVER['PHP_SELF']); // Redirect back to the current page
        exit();
    }
}

// Include the layout header
include 'layout/side-header.php';
?>

<!-- The rest of your HTML for the form -->


<div class="p-6 max-w-md mx-auto bg-white rounded-xl shadow-md space-y-6">
    <h1 class="text-2xl font-bold text-center text-green-700">Register Instructor</h1>

    <!-- Display success or error messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block mb-1 text-gray-600">Username</label>
            <input type="text" name="username" required class="w-full border border-gray-300 p-2 rounded" />
        </div>
        <div>
            <label class="block mb-1 text-gray-600">Email</label>
            <input type="email" name="email" required class="w-full border border-gray-300 p-2 rounded" />
        </div>
        <div>
            <label class="block mb-1 text-gray-600">Password</label>
            <input type="password" name="password" required class="w-full border border-gray-300 p-2 rounded" />
        </div>
        <div>
            <label class="block mb-1 text-gray-600">Profile Image</label>
            <input type="file" name="profile_image" accept="image/*" required class="w-full border border-gray-300 p-2 rounded" />
        </div>

        <button type="submit" class="w-full bg-green-700 text-white py-2 rounded hover:bg-green-800 transition">
            Register
        </button>
    </form>
</div>