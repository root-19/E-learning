<?php


require_once __DIR__ . '/../../models/User.php';
use root_dev\Models\User;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit();
}

$user = new User();
$userData = $user->getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $userId = $_SESSION['user_id'];
    $imageName = $userData['profile_image'];

    // Handle image upload if provided
    if (!empty($_FILES['profile_image']['name'])) {
        $image = $_FILES['profile_image'];
        $uploadDir = __DIR__ . '/../uploads/';
        $imageName = uniqid() . '_' . basename($image['name']);
        $imagePath = $uploadDir . $imageName;

        if (getimagesize($image['tmp_name']) === false) {
            $_SESSION['error'] = "Invalid image.";
        } elseif ($image['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Image too large (max 2MB).";
        } elseif (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $_SESSION['error'] = "Image upload failed.";
        }
    }

    // Update user info
    if ($user->updateProfile($userId, $bio, $imageName)) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: Refresh: 0");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }
}
include 'layout/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<!-- <div class="mb-30 min-h-screen flex items-center justify-center"> -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden w-full max-w-2xl p-12 ml-20">
        <div class="flex items-center space-x-6 mb-6">
            <div class="relative">
                <img src="/uploads/<?= htmlspecialchars($userData['profile_image']); ?>" alt="Profile" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow">
                <label for="profile_image" class="absolute bottom-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 rounded-full cursor-pointer hover:bg-blue-700">
                    Change
                </label>
                <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
            </div>
            <div>
                <h2 class="text-xl font-semibold"><?= htmlspecialchars($userData['username']); ?></h2>
                <p class="text-gray-600"><?= htmlspecialchars($userData['email']); ?></p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="text-sm text-gray-600 block mb-1">Short Bio / Description</label>
                <textarea name="bio" rows="4" class="w-full border rounded p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded shadow hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </form>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mt-4 bg-green-100 text-green-700 p-3 rounded"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="mt-4 bg-red-100 text-red-700 p-3 rounded"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
