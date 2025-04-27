<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elearning</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom Green Color */
        .bg-custom-green {
            background-color: #4B793E;
        }
        .text-custom-green {
            color: #4B793E;
        }
        .hover-bg-custom-green:hover {
            background-color: #4B793E;
        }
    </style>
</head>
<body class="bg-white font-sans leading-normal tracking-normal">

    <!-- Header -->
    <header class="bg-custom-green text-white py-4 px-6">
        <div class="flex justify-between items-center">
            <div class="text-2xl font-semibold">LearnInsure</div>
            <div class="space-x-4">
                <!-- Profile as a clickable logo -->
                <a href="javascript:void(0);" id="profileLogo" class="flex items-center space-x-2">
                    <img src="../../../resources/image/profile-icon.png" alt="Profile Logo" class="w-8 h-8 rounded-full">
                    <span>Profile</span>
                </a>
                <!-- Logout button with an icon, initially hidden -->
                <a href="/logout" id="logoutButton" class="flex items-center space-x-2 hover:text-gray-300 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m10 0a2 2 0 00-2-2h-6a2 2 0 00-2 2V5a2 2 0 012-2h6a2 2 0 012 2v11z"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <div class="flex h-screen">
 
    <div class="w-64 bg-custom-green text-white p-6">
    <div class="mt-8">
    <nav>
            <!-- Course Management with Dropdown -->
            <div class="relative group">
                <a href="/instructor/module" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">
                    Course Management
                </a>
                <a href="/instructor/chapter" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">
                    Content Management
                </a>
    <a href="/admin/users" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">User Management</a>
            
            <a href="/admin/announcement" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">Announcements</a>
        </nav>
    </div>
</div>


        <!-- Main content -->
        <div class="flex-2 bg-white p-6">
        
        </div>


    <!-- Footer -->
    <!-- <div class="mt-auto bg-custom-green text-white py-4 text-center">
        LearnInsure. ©2024 by LearnInsure. All rights reserved.
    </div> -->

    <!-- JavaScript -->
    <script>
        // Get references to the profile logo and logout button
        const profileLogo = document.getElementById('profileLogo');
        const logoutButton = document.getElementById('logoutButton');

        // Add event listener to profile logo for toggling logout button visibility
        profileLogo.addEventListener('click', () => {
            // Toggle the 'hidden' class to show/hide the logout button
            logoutButton.classList.toggle('hidden');
        });
    </script>

</body>
</html>
