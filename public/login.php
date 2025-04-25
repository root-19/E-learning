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

  <!-- Navigation -->
  <header class="bg-[#4B793E] text-white fixed top-0 w-full z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center space-x-3 text-2xl font-bold">
        <img src="../resources/image/learninsure-logo.png" alt="Logo" class="h-10 w-10 object-cover rounded-full" />
        <a href="#home">InsureLearn</a>
      </div>
      <nav class="space-x-6 text-lg font-medium hidden sm:block">
        <a href="#home" class="hover:text-yellow-600">Home</a>
        <a href="#explore" class="hover:text-yellow-600">Explore</a>
        <a href="#learning" class="hover:text-yellow-600">My Learning</a>
        <a href="#about" class="hover:text-yellow-600">About Us</a>
        <a href="#contact" class="hover:text-yellow-600">Contact</a>
      </nav>
    </div>
  </header>

  <!-- Welcome Section -->
  <section id="home" class="flex items-center justify-center full-height pt-20">
    <div class="text-center px-6">
      <h1 class="text-6xl font-bold mb-6 text-black">
        Welcome to <span class="text-[#48793E]">InsureLearn</span>
      </h1>
      <p id="typewriter" class="text-xl text-black mb-10 typewriter-text"></p>
      <button onclick="goToLogin()" class="bg-yellow-600 hover:bg-yellow-700 text-white text-lg font-medium px-8 py-3 rounded-full transition">
        Continue →
      </button>
    </div>
  </section>

  <!-- Login Form Section -->
  <section id="loginForm" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-900 text-white w-full max-w-md p-10 rounded-lg shadow-xl z-50">
    <div class="p-10 w-full">
      <h2 class="text-3xl font-bold mb-4 text-center">Login</h2>

      <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

      <form method="POST" action="/login">
        <label class="block text-lg">Email:</label>
        <input type="email" name="email" class="w-full p-3 mb-4 border border-gray-500 rounded bg-black text-white" required>

        <label class="block text-lg">Password:</label>
        <input type="password" name="password" class="w-full p-3 mb-6 border border-gray-500 rounded bg-black text-white" required>

        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-full transition">
          Login
        </button>

        <div class="mb-6 text-center mt-5">
          <a href="/forget-password" class="text-sm text-yellow-600 hover:underline">Forgot Password?</a>
        </div>
      </form>

      <p class="text-center mt-4">Don't have an account?
        <a href="/register" class="text-yellow-600 hover:text-yellow-700">Register here</a>
      </p>
    </div>
  </section>

  <!-- Additional Sections -->
  <section id="explore" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">Explore</h2>
    <p class="text-black">Discover tools and resources to enhance your PHP development journey.</p>
  </section>

  <section id="learning" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">My Learning</h2>
    <p class="text-black">Track your progress and continue learning with InsureLearn.</p>
  </section>

  <section id="about" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">About Us</h2>
    <p class="text-black">InsureLearn is a modern PHP framework designed to speed up web app development.</p>
  </section>

  <section id="contact" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">Contact</h2>
    <p class="text-black">Reach out for support, partnerships, or feedback!</p>
  </section>

  <!-- JavaScript -->
  <script>
    const text = "Transform the way you learn — unlock powerful tools, gain real-world skills, and elevate your journey through the InsureLearn Learning Hub.";

    const element = document.getElementById("typewriter");
    let i = 0;

    function type() {
      if (i < text.length) {
        element.innerHTML += text.charAt(i);
        i++;
        setTimeout(type, 45);
      }
    }

    window.onload = type;

    function goToLogin() {
      // Hide all sections except header
      document.getElementById('home').classList.add('hidden');
      document.getElementById('explore').classList.add('hidden');
      document.getElementById('learning').classList.add('hidden');
      document.getElementById('about').classList.add('hidden');
      document.getElementById('contact').classList.add('hidden');
      document.getElementById('footer').classList.add('hidden');

      
      // Show login
      document.getElementById('loginForm').classList.remove('hidden');
    }
  </script>

  <!-- Live Reload for Development -->
  <script>
    const socket = new WebSocket("ws://localhost:3001");
    socket.onmessage = (event) => {
      if (event.data === "reload") {
        console.log("♻ Auto-reloading...");
        location.reload();
      }
    };
  </script>
  <footer id="footer" class="bg-[#4B793E] text-white py-8 mt-20">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
      <div class="text-center md:text-left">
        <h3 class="text-xl font-semibold">InsureLearn</h3>
        <p class="text-sm">Empowering your learning journey, one click at a time.</p>
      </div>
      <div class="text-center md:text-right text-sm">
        <p>&copy; 2025 InsureLearn. All rights reserved.</p>
        <p>
          <a href="#privacy" class="hover:underline">Privacy Policy</a> ·
          <a href="#terms" class="hover:underline">Terms of Service</a>
        </p>
      </div>
    </div>
  </footer>
</body>
</html>
