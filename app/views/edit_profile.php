<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controller/UpdateController.php';
use root_dev\Models\User;
use App\Controller\UpdateController;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instractor') {
    header("Location: login.php");
    exit();
}

$user = new User();
$updateController = new UpdateController();
$userData = $user->getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $userId = $_SESSION['user_id'];
    
    // Handle password change if provided
    if (!empty($_POST['new_password'])) {
        $updateController->updatePassword($userId, $_POST['new_password'], $_POST['confirm_password']);
    }

    // Handle profile image upload
    $imageName = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['profile_image']['name'];
        error_log(print_r($_FILES['profile_image'], true));
    }

    // Update profile
    if ($updateController->updateProfile($userId, $bio, $imageName)) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: edit_profile");
        exit();
    }
}

include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .profile-container {
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
        }
        .profile-image-container {
            position: relative;
            transition: all 0.3s ease;
        }
        .profile-image-container:hover .change-overlay {
            opacity: 1;
        }
        .change-overlay {
            opacity: 0;
            transition: all 0.3s ease;
        }
        .custom-input {
            transition: all 0.3s ease;
            color: #000000;
        }
        .custom-input:focus {
            transform: translateY(-1px);
        }
        .custom-input::placeholder {
            color: #6B7280;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="profile-container rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Edit Profile</h1>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-8">
                    <!-- Profile Image Section -->
                    <div class="flex flex-col items-center space-y-4 mb-8">
                        <div class="profile-image-container">
                            <img src="<?= !empty($userData['profile_image']) ? '/' . htmlspecialchars($userData['profile_image']) : '/resources/image/profile-icon.png' ?>" 
                                 alt="Profile" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            <div class="change-overlay absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full">
                                <label for="profile_image" class="text-white text-sm font-medium cursor-pointer px-4 py-2 bg-blue-600 rounded-full hover:bg-blue-700 transition">
                                    Change Photo
                                </label>
                            </div>
                            <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="text-center">
                            <h2 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($userData['username']); ?></h2>
                            <p class="text-gray-600"><?= htmlspecialchars($userData['email']); ?></p>
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <label class="text-sm font-medium text-gray-700 block mb-2">About Me</label>
                        <textarea name="bio" rows="4" 
                                class="custom-input w-full border border-gray-200 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-black"
                                placeholder="Tell us about yourself..."><?= htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
                    </div>

                    <!-- Password Section -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">New Password</label>
                                <input type="password" name="new_password" 
                                       class="custom-input w-full border border-gray-200 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-black"
                                       placeholder="Enter new password">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" 
                                       class="custom-input w-full border border-gray-200 rounded-lg p-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-black"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-3 rounded-lg font-medium shadow-lg hover:bg-blue-700 transform hover:-translate-y-0.5 transition duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['success']; ?>',
                confirmButtonColor: '#3085d6',
                customClass: {
                    popup: 'rounded-lg'
                }
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['error']; ?>',
                confirmButtonColor: '#d33',
                customClass: {
                    popup: 'rounded-lg'
                }
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    input.parentElement.querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
