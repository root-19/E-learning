<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to InsureLearn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    
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
    
    /* For third section typewriter */
    .typewriter-cursor::after {
      content: "|";
      color: #F4C542;
      animation: blink-caret 0.75s step-end infinite;
    }
    @keyframes blink-caret {
      from, to { opacity: 0 }
      50% { opacity: 1 }
    }

    /* Login form animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translate(-50%, -45%); }
      to { opacity: 1; transform: translate(-50%, -50%); }
    }

    @keyframes fadeOut {
      from { opacity: 1; transform: translate(-50%, -50%); }
      to { opacity: 0; transform: translate(-50%, -45%); }
    }

    .login-transition-in {
      animation: fadeIn 0.3s ease-out forwards;
    }

    .login-transition-out {
      animation: fadeOut 0.3s ease-in forwards;
    }

    /* Button animations */
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
    @keyframes pulse-once { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
    .blinking-cursor { animation: blink 1s infinite; }
    .animate-pulse-once { animation: pulse-once 1s ease-in-out; }

    /* Card hover effects */
    @keyframes gentleBounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .group:hover .group-hover\:animate-bounce {
      animation: gentleBounce 0.8s ease-in-out infinite;
    }
    
    /* Mobile menu link styles */
    .mobile-nav-link {
      @apply block px-3 py-2 text-white hover:text-[#F4C542] transition-colors flex items-center;
    }
    .mobile-nav-link.active {
      @apply text-[#F4C542];
    }
  </style>
</head>
<body class="bg-white text-white">

    <?php include 'header.php'; ?>

    <!-- Auth Buttons -->
    <div class="hidden md:flex items-center gap-4">
      <a href="#" onclick="goToLogin()" class="text-white text-sm font-bold border-2 border-white rounded px-4 py-1 hover:bg-[#F4C542] hover:text-[#4B793E] hover:border-[#F4C542] transition-all">
        Sign In
      </a>
      <a href="/register" class="text-[#4B793E] text-sm font-bold bg-[#F4C542] rounded px-4 py-1 hover:bg-[#f4d042] transition-all">
        Create Account
      </a>
    </div>


  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-[#3A683A] px-4 py-2">
    <div class="flex flex-col space-y-3">
      <a href="home" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="home">
        <i class="fas fa-home mr-3"></i> Home
      </a>
      <a href="explore" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="explore">
        <i class="fas fa-compass mr-3"></i> Explore
      </a>
      <a href="learning" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="learning">
        <i class="fas fa-book-open mr-3"></i> My Learning
      </a>
      <a href="about" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="about">
        <i class="fas fa-info-circle mr-3"></i> About Us
      </a>
      <a href="contact" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="contact">
        <i class="fas fa-envelope mr-3"></i> Contact
      </a>
      <div class="pt-3 border-t border-[#4B793E] flex gap-3">
        <a href="#" onclick="goToLogin()" class="flex-1 text-center py-2 text-sm font-bold text-white border-2 border-white rounded hover:bg-[#F4C542] hover:text-[#4B793E] hover:border-[#F4C542] transition-all">
          Sign In
        </a>
        <a href="/register" class="flex-1 text-center py-2 text-sm font-bold text-[#4B793E] bg-[#F4C542] rounded hover:bg-[#f4d042] transition-all">
          Create Account
        </a>
      </div>
    </div>
  </div>
</header>

  <!-- Welcome Section -->
  <section id="home" class="relative flex items-center justify-center min-h-screen pt-20 overflow-hidden">
    <!-- Background Image -->
    <img 
      src="../resources/image/heropage-bg.png" 
      alt="LearnInsure Hero Background"
      class="absolute inset-0 w-full h-full object-cover object-center -z-10"
    >

    <!-- Content Container -->
    <div class="text-center px-6">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 text-white">
        Welcome to <span class="text-[#4AAB4C]">LearnInsure!</span>
      </h1>

      <!-- Typewriter Element -->
      <div class="mb-10 min-h-[60px] flex justify-center">
        <p id="typewriter" class="text-lg sm:text-xl text-white inline-block"></p>
      </div>

      <!-- Animated Button -->
      <a  href="explore" 
        class="bg-[#F4C542] hover:bg-[#f4d042] text-black font-semibold text-sm sm:text-base px-6 py-3 rounded-md 
              transition-all duration-300 hover:scale-105 active:scale-95 
              hover:shadow-lg hover:shadow-[#F4C542]/30"
      >
        Let's Get Started Today! →
  </a>
    </div>
  </section>

  <!-- Enhanced Login Form Section -->
  <div id="loginOverlay" class="hidden fixed inset-0 bg-black bg-opacity-70 z-40 transition-opacity"></div>
  
  <section id="loginForm" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#F8F9FA] text-gray-800 w-full max-w-md rounded-xl shadow-2xl z-50 overflow-hidden">
    <div class="relative">
      <!-- Close Button -->
      <button onclick="closeLogin()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-times text-xl"></i>
      </button>
      
      <!-- Form Container -->
      <div class="p-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <img src="../resources/image/learninsure-logo.png" alt="Logo" class="w-16 h-16 mx-auto mb-4">
          <h2 class="text-3xl font-bold text-[#4B793E]">Welcome Back</h2>
          <p class="text-gray-600 mt-2">Sign in to continue your learning journey</p>
        </div>

        <?php if (isset($error)) { echo "<p class='text-red-500 text-center mb-4'>$error</p>"; } ?>
      </div>
    </div>
  </section>
</body>
</html> 