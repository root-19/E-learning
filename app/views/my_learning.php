<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controller/ModuleController.php';

// Initialize the controller
$controller = new ModuleController();
$courses = $controller->listCourses();

// Get user's enrolled courses
$user_id = $_SESSION['user_id'];
$enrolled_courses = [];

try {
    $conn = Database::connect();
    $stmt = $conn->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $enrolled_courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // If table doesn't exist or other database error, continue with empty enrollments
    error_log("Error fetching enrollments: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Learning - InsureLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
    <style>
        .course-card {
            transition: transform 0.2s ease-in-out;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 mt-20">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Available Courses</h1>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search courses..." 
                           class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <select id="filterType" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="all">All Types</option>
                    <option value="interactive">Interactive</option>
                    <option value="traditional">Traditional</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="courseGrid">
            <?php foreach ($courses as $course): 
                $chapters = $controller->getChaptersForCourse($course['id']);
                $courseType = 'traditional';
                foreach ($chapters as $chapter) {
                    if ($chapter['type'] === 'interactive') {
                        $courseType = 'interactive';
                        break;
                    }
                }
                $isEnrolled = in_array($course['id'], $enrolled_courses);
            ?>
            <div class="course-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-200" 
                 data-course-type="<?= $courseType ?>">
                <div class="relative">
                    <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                         alt="<?= htmlspecialchars($course['course_title']) ?>" 
                         class="w-full h-48 object-cover">
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold
                        <?= $courseType === 'interactive' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                        <?= ucfirst($courseType) ?>
                    </span>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($course['course_title']) ?></h3>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($course['description']) ?></p>
                    
                    <?php if ($isEnrolled): ?>
                        <a href="/course-view/<?= $course['id'] ?>" 
                           class="w-full bg-[#4B793E] text-white py-2 px-4 rounded-lg hover:bg-[#3d6232] transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-book-reader"></i>
                            <span>View Course</span>
                        </a>
                    <?php else: ?>
                        <form action="/enroll" method="POST" class="w-full">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                <span>Enroll Now</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');
        const courseCards = document.querySelectorAll('.course-card');

        function filterCourses() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = filterType.value;

            courseCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const type = card.dataset.courseType;
                
                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesType = selectedType === 'all' || type === selectedType;

                card.style.display = matchesSearch && matchesType ? 'block' : 'none';
            });
        }

        searchInput.addEventListener('input', filterCourses);
        filterType.addEventListener('change', filterCourses);
    </script>
</body>
</html> 