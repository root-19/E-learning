<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnInsure - Instructor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Colors */
        .bg-custom-green {
            background-color: #4B793E;
        }
        .text-custom-green {
            color: #4B793E;
        }
        .hover-bg-custom-green:hover {
            background-color: #4B793E;
        }
        
        /* Custom Animations */
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            transform: translateX(5px);
        }
        
        /* Custom Shadows */
        .custom-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Active State */
        .nav-link.active {
            background-color: #ffffff;
            color: #4B793E;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <!-- Header -->
    <header class="bg-custom-green text-white py-4 px-6 custom-shadow">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-graduation-cap text-2xl"></i>
                <div class="text-2xl font-bold">LearnInsure</div>
            </div>
            <div class="flex items-center space-x-6">
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileButton" class="flex items-center space-x-3 hover:bg-white/10 p-2 rounded-lg transition-colors">
                        <img src="/resources/image/profile-icon.png" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                        <span class="font-medium">Profile</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden">
                        <hr class="my-2">
                        <a href="/logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-custom-green text-white p-6 custom-shadow">
            <nav class="mt-8 space-y-2">
                <a href="/instructor/dashboard" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <a href="/instructor/profile" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
                
                <a href="/instructor/module" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-book"></i>
                    <span>Course Management</span>
                </a>
                
                <a href="/instructor/chapter" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-file-alt"></i>
                    <span>Content Management</span>
                </a>
                
                <a href="/instructor/interactive_chapter" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Interactive Management</span>
                </a>
                
                <!-- <a href="/instructor/learning-modules" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-book-open"></i>
                    <span>Learning Modules</span>
                </a> -->
                
                <a href="/instructor/announcements" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                
                <a href="/instructor/notifications" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <?php
                    require_once __DIR__ . '/../../../../config/database.php';
                    require_once __DIR__ . '/../../../../app/controller/NotificationController.php';
                    use root_dev\Controller\NotificationController;
                    $notificationController = new NotificationController();
                    $unreadCount = $notificationController->getUnreadCount($_SESSION['user_id']);
                    if ($unreadCount > 0): ?>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            <?php echo $unreadCount; ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <a href="/admin/users" class="nav-link flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-2 bg-gray-50 p-6 ml-60">
            <!-- Content will be injected here -->
        
    <!-- </div> -->

    <!-- JavaScript -->
    <script>
        // Profile Dropdown Toggle
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // Active Navigation Link
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
