<?php
require_once __DIR__ . '/../../config/database.php';
$conn = Database::connect();

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    header('Location: /my_learning');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ? AND course_type = 'interactive'");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header('Location: /my_learning');
    exit;
}

include 'layout/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['course_title']) ?> - Interactive Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .module-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .banner-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 1rem;
            margin-bottom: 2rem;
        }
        
        .ppt-container {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="module-container">
        <!-- Course Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($course['course_title']) ?></h1>
            <p class="text-gray-600 text-lg"><?= htmlspecialchars($course['description']) ?></p>
        </div>

        <!-- Banner Image -->
        <?php if ($course['course_image']): ?>
            <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                 alt="<?= htmlspecialchars($course['course_title']) ?>" 
                 class="banner-image">
        <?php endif; ?>

        <!-- PowerPoint Presentation -->
        <?php if ($course['ppt_path']): ?>
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Interactive Presentation</h2>
                <iframe 
                    src="https://view.officeapps.live.com/op/embed.aspx?src=<?= urlencode($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $course['ppt_path']) ?>" 
                    class="ppt-container"
                    frameborder="0">
                </iframe>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <i class="fas fa-exclamation-circle text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No presentation available for this module.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Add any interactive features here
        document.addEventListener('DOMContentLoaded', function() {
            // You can add JavaScript for additional interactivity
        });
    </script>
</body>
</html> 