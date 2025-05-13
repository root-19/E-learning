<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to InsureLearn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
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

<!-- Navigation/Header Section -->
<header class="bg-[#4B793E] px-8 py-2">
    <div class="flex flex-wrap items-center justify-between">   
      <!-- Left: Logo and Brand -->
      <div class="flex items-center gap-3">
        <img src="../resources/image/learninsure-logo.png" alt="InsureLearn Logo" class="w-[45px] h-[45px]" />
        <span class="text-white font-bold text-[20px] font-serif">LearnInsure</span>
      </div>
      <!-- Center: Navigation Links -->
      <nav class="flex-1 flex justify-center">
        <ul class="flex list-none gap-6 m-0 p-0">
          <li>
            <a href="#home" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300">
              Home
            </a>
          </li>
          <li>
            <a href="#explore" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300">
              Explore
            </a>
          </li>
          <li>
            <a href="#learning" class="relative text-[#F4C542] font-bold text-lg px-1 py-2 transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-full after:h-[2px]
              after:bg-[#F4C542] after:transition-all after:duration-300">
              My Learning
            </a>
          </li>
          <li>
            <a href="#about" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300">
              About Us
            </a>
          </li>
          <li>
            <a href="#contact" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300">
              Contact
            </a>
          </li>
        </ul>
      </nav>
      <!-- Right: Profile Icon and Login -->
      <div class="flex items-center gap-4 relative group">
        <div class="relative flex items-center cursor-pointer">
          <img src="../resources/image/profile-icon.png" alt="Profile Icon" class="w-[35px] h-[35px] rounded-full object-cover" />
          <!-- Dropdown -->
          <div class="absolute top-[45px] right-0 w-[180px] bg-white rounded-lg shadow-lg opacity-0 invisible transform -translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible z-50">
            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-800 hover:bg-[#56CCF2] hover:text-white transition-colors">Dashboard</a>
            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-800 hover:bg-[#56CCF2] hover:text-white transition-colors">Settings</a>
            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-800 hover:bg-[#56CCF2] hover:text-white transition-colors">Logout</a>
          </div>
        </div>
        <a href="#" class="text-white text-sm font-bold border-2 border-white rounded px-3 py-1 hover:bg-[#F4C542] hover:text-[#4B793E] hover:border-[#F4C542] transition-all">
          Login
        </a>
      </div>
    </div>
  </header>

  <!-- Welcome Section -->
  <section id="home" class="relative flex items-center justify-center full-height pt-20 overflow-hidden">
  <!-- Background Image that fills the container -->
  <img 
    src="../resources/image/heropage-bg.png" 
    alt=""
    class="absolute inset-0 w-full h-full object-cover object-center -z-10"
  >
    <div class="text-center px-6">
      <h1 class="text-6xl font-bold mb-6 text-[#FFFFFF]">
        Welcome to <span class="text-[#48793E]">LearnInsure!</span>
      </h1>
      <p id="typewriter" class="text-xl text-[#FFFFFF] mb-10 typewriter-text"></p>
      <button onclick="goToLogin()" class="bg-yellow-600 hover:bg-yellow-700 text-white text-lg font-medium px-8 py-3 rounded-full transition">
        Let’s Get Started Today! →
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

  <!-- Why Choose LearnInsure Today?: Second Section -->
  <section id="explore" class="relative min-h-screen flex items-center justify-center overflow-hidden font-sans">
    <!-- Background image container (now relative to section) -->
    <div class="absolute inset-0 z-0">
      <img 
        src="../resources/image/landingpage-bgimage-section2.png"
        alt="Decorative background" 
        class="w-full h-full object-cover object-center"
      >
    </div>

    <!-- Content section -->
    <div class="relative z-10 w-full">
      <div class="bg-white bg-opacity-20 py-28">
        <div class="max-w-7xl mx-auto px-6 text-center">
          <h2 class="text-4xl text-[#1E1E1E] font-extrabold leading-tight max-w-4xl mx-auto font-inter">
            Why Choose
            <span class="text-[#4B793E]">LearnInsure</span>
            Today?
          </h2>
          <p class="mt-3 text-lg font-medium max-w-md mx-auto font-inter">
            <span class="text-[#F5A314]">Learn.</span>
            <span class="text-[#3BAB5A]"> Practice.</span>
            <span class="text-[#EF6461]">Grow.</span>
            <span class="text-[#1E1E1E] font-bold">Succeed with Confidence.</span>
          </p>
          
          <div class="mt-16 flex flex-col sm:flex-row justify-center gap-8">
            <!-- Card 1 -->
            <article class="group bg-white bg-opacity-95 shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl font-inter relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
              <div class="mb-6 p-4 rounded-full">
                <img 
                  src="../resources/image/icons/featuresicon1.png" 
                  alt="Decision making icon" 
                  class="w-20 h-20 group-hover:animate-bounce transition-transform"
                >
              </div>
              <h3 class="font-bold text-center text-gray-800 text-sm sm:text-base max-w-[14rem]">
                Learn and Decide by Doing
              </h3>
              <p class="mt-3 text-center text-gray-600 text-xs sm:text-sm max-w-[14rem] leading-relaxed">
                Experience real-life scenarios through interactive modules that sharpen your decision-making and client communication skills.
              </p>
            </article>

            <!-- Card 2 -->
            <article class="group bg-white bg-opacity-95 shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl font-inter relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
              <div class="mb-6 p-4 rounded-full">
                <img 
                  src="../resources/image/icons/featuresicon2.png"
                  alt="Knowledge icon" 
                  class="w-20 h-20 group-hover:animate-bounce transition-transform"
                >
              </div>
              <h3 class="font-bold text-center text-gray-800 text-sm sm:text-base max-w-[14rem]">
                Build Your Knowledge Core
              </h3>
              <p class="mt-3 text-center text-gray-600 text-xs sm:text-sm max-w-[14rem] leading-relaxed">
                Strengthen your understanding of insurance concepts with engaging lessons, quizzes, videos, and case-based learning.
              </p>
            </article>

            <!-- Card 3 -->
            <article class="group bg-white bg-opacity-95 shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl font-inter relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
              <div class="mb-6 p-4 rounded-full">
                <img 
                  src="../resources/image/icons/featuresicon3.png"
                  alt="Crafted courses icon" 
                  class="w-20 h-20 group-hover:animate-bounce transition-transform"
                >
              </div>
              <h3 class="font-bold text-center text-gray-800 text-sm sm:text-base max-w-[14rem]">
                Crafted with Care
              </h3>
              <p class="mt-3 text-center text-gray-600 text-xs sm:text-sm max-w-[14rem] leading-relaxed">
                Each course is thoughtfully created by expert instructors to deliver focused, industry-relevant training.
              </p>
            </article>
          </div>
        </div>
      </div>
    </div>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
      
      @keyframes gentleBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
      }
      .group:hover .group-hover\:animate-bounce {
        animation: gentleBounce 0.8s ease-in-out infinite;
      }
      .font-inter {
        font-family: 'Inter', sans-serif;
      }
    </style>
  </section>
  
  <!-- Explore the Background of LearnInsure: Third Section -->
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
    const text = "Transform the way you learn - gain knowledge and real-world skills to pass the exam today.";

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
