<?php

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../controller/UpdateController.php';
use root_dev\Models\User;
use App\Controller\UpdateController;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
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
        header("Location: profile");
        exit();
    }
}

include 'layout/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden w-full max-w-2xl p-12 ml-20">
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="flex items-center space-x-6 mb-6">
                <div class="relative">
                    <img src="<?= !empty($userData['profile_image']) ? '/' . htmlspecialchars($userData['profile_image']) : '/resources/image/profile-icon.png' ?>" alt="Profile" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow">
                    <label for="profile_image" class="absolute bottom-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 rounded-full cursor-pointer hover:bg-blue-700">
                        Change
                    </label>
                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div>
                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($userData['username']); ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-600 block mb-1">Short Bio / Description</label>
                <textarea name="bio" rows="4" class="w-full border rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
            </div>
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600 block mb-1">New Password</label>
                        <input type="password" name="new_password" class="w-full border rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="w-full border rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded shadow hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>

        <?php if (isset($_SESSION['success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= $_SESSION['success']; ?>',
                    confirmButtonColor: '#3085d6'
                });
            </script>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= $_SESSION['error']; ?>',
                    confirmButtonColor: '#d33'
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
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
