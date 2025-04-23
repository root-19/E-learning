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
                <a href="/profile" class="hover:text-gray-300">Profile</a>
                <a href="/logout" class="hover:text-gray-300">Logout</a>
            </div>
        </div>
    </header>

    <div class="flex h-screen">
 
    <div class="w-64 bg-custom-green text-white p-6">
    <div class="mt-8">
        <nav>
           
            <a href="/admin/course" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">Course Management</a>
            <a href="/admin/users" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">User Management</a>
            <a href="/admin/instractor" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">Instractor Management</a>

            <a href="/admin/announcement" class="block py-2 px-4 text-custom-green bg-white rounded-md hover:bg-yellow-500 hover:text-white mt-4 transition-all duration-300 font-bold">Announcements</a>
        </nav>
    </div>
</div>


        <!-- Main content -->
        <div class="flex-1 bg-white p-6">
        
 

    <!-- Footer -->
    <!-- <div class="mt-auto bg-custom-green text-white py-4 text-center">
        LearnInsure. ©2024 by LearnInsure. All rights reserved.
    </div> -->
