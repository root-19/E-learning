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
    
    // Get enrolled courses
    $stmt = $conn->prepare("
        SELECT course_id 
        FROM enrollments 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $enrolled_courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    error_log("Error fetching enrollments: " . $e->getMessage());
}

include 'header.php';
?>

<!-- Add SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 text-white py-16 mt-14">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Explore New Courses</h1>
                <p class="text-xl text-green-100 mb-8">Discover and enroll in courses that match your interests and career goals</p>
                
                <!-- Search Bar -->
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" 
                           id="courseSearch"
                           placeholder="Search for courses..." 
                           class="w-full pl-12 pr-4 py-4 text-gray-800 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Filters Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-wrap gap-3">
                    <button class="filter-btn px-4 py-2 rounded-full border-2 border-blue-600 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors" data-filter="all">
                        All Courses
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-200 text-sm font-semibold text-gray-600 hover:border-blue-600 hover:text-blue-600 transition-colors" data-filter="alphabetical">
                        <i class="fas fa-sort-alpha-down mr-2"></i> Alphabetical
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-200 text-sm font-semibold text-gray-600 hover:border-blue-600 hover:text-blue-600 transition-colors" data-filter="newest">
                        <i class="fas fa-clock mr-2"></i> Newest First
                    </button>
                </div>
                
                <div class="text-sm text-gray-600">
                    <span id="courseCount">0</span> courses available
                </div>
            </div>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="courseGrid">
            <?php foreach ($courses as $course): 
                // Skip if course is rejected or user is already enrolled
                if ($course['is_rejected'] == 1 || in_array($course['id'], $enrolled_courses)) {
                    continue;
                }
                
                $chapters = $controller->getChaptersForCourse($course['id']);
                $courseType = 'traditional';
                foreach ($chapters as $chapter) {
                    if ($chapter['type'] === 'interactive') {
                        $courseType = 'interactive';
                        break;
                    }
                }
            ?>
            <div class="course-card bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300" 
                 data-course-type="<?= $courseType ?>">
                <div class="relative">
                    <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                         alt="<?= htmlspecialchars($course['course_title']) ?>"
                         class="w-full h-56 object-cover">
                    <div class="absolute top-4 right-4 flex gap-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            <?= $courseType === 'interactive' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-blue-800' ?>">
                            <?= ucfirst($courseType) ?>
                        </span>
                        <?php if ($course['status'] === 'active'): ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2"><?= htmlspecialchars($course['course_title']) ?></h3>
                    <p class="text-gray-600 mb-6 line-clamp-3"><?= htmlspecialchars($course['description']) ?></p>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-book-open text-gray-400"></i>
                            <span class="text-sm text-gray-600"><?= count($chapters) ?> chapters</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-gray-400"></i>
                            <span class="text-sm text-gray-600">Self-paced</span>
                        </div>
                    </div>
                    
                    <?php if ($course['status'] === 'inactive'): ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-4 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>This course is currently inactive</span>
                        </div>
                    <?php else: ?>
                        <button type="button" 
                                onclick="confirmEnrollment(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_title']) ?>')"
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center gap-2 font-semibold">
                            <i class="fas fa-user-plus"></i>
                            <span>Enroll Now</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('courseSearch');
    const courseGrid = document.getElementById('courseGrid');
    const courseCards = courseGrid.getElementsByClassName('course-card');
    const courseCount = document.getElementById('courseCount');
    
    // Update course count
    courseCount.textContent = courseCards.length;
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let visibleCount = 0;
        
        Array.from(courseCards).forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        courseCount.textContent = visibleCount;
    });
    
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'bg-blue-50');
                btn.classList.add('border-gray-200', 'text-gray-600');
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-blue-600', 'text-blue-600', 'bg-blue-50');
            
            // Sort courses
            const cards = Array.from(courseCards);
            if (filter === 'alphabetical') {
                cards.sort((a, b) => {
                    const titleA = a.querySelector('h3').textContent;
                    const titleB = b.querySelector('h3').textContent;
                    return titleA.localeCompare(titleB);
                });
            } else if (filter === 'newest') {
                cards.sort((a, b) => {
                    const idA = parseInt(a.querySelector('input[name="course_id"]').value);
                    const idB = parseInt(b.querySelector('input[name="course_id"]').value);
                    return idB - idA;
                });
            } else {
                // Default order (by course ID)
                cards.sort((a, b) => {
                    const idA = parseInt(a.querySelector('input[name="course_id"]').value);
                    const idB = parseInt(b.querySelector('input[name="course_id"]').value);
                    return idA - idB;
                });
            }
            
            // Reappend sorted cards
            cards.forEach(card => courseGrid.appendChild(card));
        });
    });
});

// Add new enrollment confirmation function
function confirmEnrollment(courseId, courseTitle) {
    Swal.fire({
        title: 'Confirm Enrollment',
        html: `
            <div class="text-left">
                <p class="mb-4">Are you sure you want to enroll in:</p>
                <p class="font-semibold text-lg mb-4">${courseTitle}</p>
                <p class="text-sm text-gray-600">You will be redirected to the course after enrollment.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, enroll me',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Create form data
            const formData = new FormData();
            formData.append('course_id', courseId);

            return fetch('/enroll', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    // If the response is a redirect, follow it
                    window.location.href = response.url;
                    return false;
                }
                if (!response.ok) {
                    throw new Error('Enrollment failed');
                }
                return true;
            })
            .catch(error => {
                Swal.showValidationMessage(`Enrollment failed: ${error.message}`);
                return false;
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // If we get here, the enrollment was successful
            Swal.fire({
                title: 'Successfully Enrolled!',
                text: 'Redirecting to the course...',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = `/course-view/${courseId}`;
            });
        }
    });
}

// Add error handling for failed enrollments
window.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        e.target.src = '/assets/images/course-placeholder.jpg';
    }
});
</script>

<style>
.course-card {
    transition: all 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Add SweetAlert custom styles */
.swal2-popup {
    font-size: 1rem !important;
    font-family: inherit !important;
}

.swal2-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
}

.swal2-html-container {
    margin: 1rem 0 !important;
}

.swal2-confirm {
    padding: 0.75rem 1.5rem !important;
    font-weight: 600 !important;
}

.swal2-cancel {
    padding: 0.75rem 1.5rem !important;
    font-weight: 600 !important;
}
</style>