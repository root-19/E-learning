<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to InsureLearn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .typewriter-text::after {
      content: '|';
      animation: blink 1s infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0; }
    }

    .full-height {
      height: 100vh;
    }

    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-white text-white">
<header class="bg-[#4B793E] text-white fixed top-0 w-full z-50 shadow-lg">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <!-- Logo and Brand -->
    <div class="flex items-center space-x-3 text-2xl font-bold">
      <img src="../resources/image/learninsure-logo.png" alt="Logo" class="h-10 w-10 object-cover rounded-full" />
      <a href="#home">InsureLearn</a>
    </div>

    <!-- Navigation Links -->
    <nav class="space-x-6 text-lg font-medium hidden sm:block">
      <a href="#home" class="hover:text-yellow-400">Home</a>
      <a href="#explore" class="hover:text-yellow-400">Explore</a>
      <a href="my_learning" class="hover:text-yellow-400">My Learning</a>
      <a href="#about" class="hover:text-yellow-400">About Us</a>
      <a href="contact" class="hover:text-yellow-400">Contact</a>
      <a href="announcement" class="hover:text-yellow-400">announcement</a>

    </nav>

    <!-- User Dropdown -->
    <div class="relative ml-4">
      <button id="userMenuButton" class="focus:outline-none">
        <img src="../../resources/image//profile-icon.png" alt="User" class="h-10 w-10 rounded-full border-2 border-white" />
      </button>
      <!-- Dropdown Menu -->
      <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-50">
        <a href="edit-profile" class="block px-4 py-2 hover:bg-gray-200">Edit Profile</a>
        <a href="/logout" class="block px-4 py-2 hover:bg-gray-200">Logout</a>
      </div>
    </div>
  </div>
</header>

<!-- Optional JavaScript for dropdown toggle -->
<script>
  const userBtn = document.getElementById('userMenuButton');
  const dropdown = document.getElementById('userDropdown');

  userBtn.addEventListener('click', () => {
    dropdown.classList.toggle('hidden');
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>
