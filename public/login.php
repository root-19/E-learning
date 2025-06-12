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

<!-- Enhanced Navigation/Header Section -->
<header class="bg-[#4B793E] px-4 sm:px-8 py-2 sticky top-0 z-50 shadow-md font-sans">
  <div class="flex flex-wrap items-center justify-between max-w-7xl mx-auto">
    <!-- Logo -->
    <a href="#home" class="flex items-center gap-2 group">
      <img src="../resources/image/learninsure-logo.png" alt="InsureLearn Logo" class="w-10 h-10 transition-transform group-hover:scale-105" />
      <span class="text-white font-bold text-lg group-hover:text-[#F4C542] transition-colors">LearnInsure</span>
    </a>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="md:hidden text-white p-1 rounded hover:bg-white/10 transition">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <!-- Desktop Navigation -->
    <nav id="main-nav" class="hidden md:flex flex-1 justify-center">
      <ul class="flex list-none gap-4 lg:gap-6 m-0 p-0 items-center">
        <li>
          <a href="#home" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300" data-section="home">
            <i class="fas fa-home mr-2"></i> Home
          </a>
        </li>
        <li>
          <a href="#explore" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300" data-section="explore">
            <i class="fas fa-compass mr-2"></i> Explore
          </a>
        </li>
        <li>
          <a href="#learning" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300" data-section="learning">
            <i class="fas fa-book-open mr-2"></i> My Learning
          </a>
        </li>
        <li>
          <a href="#about" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300" data-section="about">
            <i class="fas fa-info-circle mr-2"></i> About Us
          </a>
        </li>
        <li>
          <a href="#contact" class="relative text-white font-bold text-lg px-1 py-2 hover:text-[#F4C542] transition-colors
              after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px]
              after:bg-[#F4C542] hover:after:w-full after:transition-all after:duration-300" data-section="contact">
            <i class="fas fa-envelope mr-2"></i> Contact
          </a>
        </li>
      </ul>
    </nav>

    <!-- Auth Buttons -->
    <div class="hidden md:flex items-center gap-4">
      <a href="#" onclick="goToLogin()" class="text-white text-sm font-bold border-2 border-white rounded px-4 py-1 hover:bg-[#F4C542] hover:text-[#4B793E] hover:border-[#F4C542] transition-all">
        Sign In
      </a>
      <a href="/register" class="text-[#4B793E] text-sm font-bold bg-[#F4C542] rounded px-4 py-1 hover:bg-[#f4d042] transition-all">
        Create Account
      </a>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-[#3A683A] px-4 py-2">
    <div class="flex flex-col space-y-3">
      <a href="#home" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="home">
        <i class="fas fa-home mr-3"></i> Home
      </a>
      <a href="#explore" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="explore">
        <i class="fas fa-compass mr-3"></i> Explore
      </a>
      <a href="#learning" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="learning">
        <i class="fas fa-book-open mr-3"></i> My Learning
      </a>
      <a href="#about" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
          after:content-[''] after:absolute after:left-3 after:bottom-2 after:w-0 after:h-[2px]
          after:bg-[#F4C542] hover:after:w-[calc(100%-1.5rem)] after:transition-all after:duration-300" data-section="about">
        <i class="fas fa-info-circle mr-3"></i> About Us
      </a>
      <a href="#contact" class="relative text-white font-medium px-3 py-2 hover:text-[#F4C542] transition-colors
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
      <button 
        id="welcome-button"
        onclick="goToLogin()" 
        class="bg-[#F4C542] hover:bg-[#f4d042] text-black font-semibold text-sm sm:text-base px-6 py-3 rounded-md 
              transition-all duration-300 hover:scale-105 active:scale-95 
              hover:shadow-lg hover:shadow-[#F4C542]/30"
      >
        Let's Get Started Today! →
      </button>
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

        <!-- Form -->
        <form method="POST" action="/login" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <div class="relative">
              <input 
                type="email" 
                id="email" 
                name="email" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4B793E] focus:border-[#4B793E] transition-all"
                placeholder="your@email.com"
              >
              <i class="fas fa-envelope absolute right-3 top-3.5 text-gray-400"></i>
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <input 
                type="password" 
                id="password" 
                name="password" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4B793E] focus:border-[#4B793E] transition-all"
                placeholder="••••••••"
              >
              <i class="fas fa-lock absolute right-3 top-3.5 text-gray-400"></i>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input 
                id="remember-me" 
                name="remember-me" 
                type="checkbox" 
                class="h-4 w-4 text-[#4B793E] focus:ring-[#4B793E] border-gray-300 rounded"
              >
              <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            <a href="/forget-password" class="text-sm text-[#4B793E] hover:underline">Forgot password?</a>
          </div>

          <button 
            type="submit" 
            class="w-full bg-[#4B793E] hover:bg-[#3A683A] text-white py-3 px-4 rounded-lg font-medium transition-colors shadow-md hover:shadow-lg"
          >
            Sign In
          </button>
        </form>

        <!-- Social Login -->
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-[#F8F9FA] text-gray-500">Or continue with</span>
            </div>
          </div>
<!-- 
          <div class="mt-6 grid grid-cols-2 gap-3">
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <i class="fab fa-google text-red-500 mr-2"></i> Google
            </a>
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <i class="fab fa-facebook-f text-blue-600 mr-2"></i> Facebook
            </a>
          </div>
        </div> -->

        <div class="mt-6 text-center text-sm">
          <p class="text-gray-600">
            Don't have an account? 
            <a href="/register" class="text-[#4B793E] font-medium hover:underline">Sign up</a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose LearnInsure Today?: Second Section -->
  <section id="features" class="relative min-h-screen flex items-center justify-center overflow-hidden">
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
          <h2 class="text-4xl text-[#1E1E1E] font-extrabold leading-tight max-w-4xl mx-auto">
            Why Choose
            <span class="text-[#4B793E]">LearnInsure</span>
            Today?
          </h2>
          <p class="mt-3 text-lg font-medium max-w-md mx-auto">
            <span class="text-[#F5A314]">Learn.</span>
            <span class="text-[#3BAB5A]"> Practice.</span>
            <span class="text-[#EF6461]">Grow.</span>
            <span class="text-[#1E1E1E] font-bold">Succeed with Confidence.</span>
          </p>
          
          <div class="mt-16 flex flex-col sm:flex-row justify-center gap-8">
            <!-- Card 1 -->
            <article class="group bg-white shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
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
            <article class="group bg-white  shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
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
            <article class="group bg-white shadow-md rounded-lg max-w-xs mx-auto sm:max-w-sm p-8 flex flex-col items-center transition-all duration-300 hover:scale-[1.02] hover:shadow-xl relative before:absolute before:inset-0 before:rounded-lg before:border before:border-transparent before:transition-all before:duration-300 hover:before:border-[#3BAB5A] hover:before:border">
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
  </section>
  
  <!-- Explore the Background of LearnInsure: Third Section -->
  <section id="explore-background" class="relative min-h-screen px-6 overflow-hidden">
    <!-- Background Image -->
    <img 
      src="../resources/image/landingpage-bgimage-section3.png"
      alt="section3-bgimage" 
      class="absolute inset-0 w-full h-full object-cover -z-10"  
    />

    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl px-4 text-center">
      <!-- Heading -->
      <h1 class="text-white font-bold text-3xl sm:text-4xl md:text-5xl whitespace-nowrap">
        Explore the Background of <span class="text-[#F4C542]">LearnInsure.</span>
      </h1>

      <!-- Typewriter Text -->
      <div class="mt-4 max-w-3xl mx-auto">
        <p id="typewriter-text" class="text-white font-semibold italic text-lg sm:text-xl md:text-2xl overflow-hidden whitespace-pre-wrap inline-block relative"></p>
      </div>

      <!-- Button -->
      <button class="mt-8 bg-[#F4C542] text-black font-semibold text-sm sm:text-base px-5 py-2 rounded-md hover:bg-[#f4d042] transition">
        Learn More About LearnInsure →
      </button>
    </div>
  </section>

  <!-- Module Cards Recommendation: Fourth Section -->
  <section id="learning" class="relative min-h-screen pt-32 px-6 text-center overflow-hidden">
    <div class="finisher-header absolute inset-0 w-full h-full z-0"></div>

    <div class="relative z-10">
      <h2 class="text-4xl text-[#1E1E1E] md:text-5xl font-bold mb-6">
        See What the Platform Has to Offer
      </h2>
      <p class="text-lg md:text-xl text-[#1E1E1E]">
        Our Learning Modules. Your Learning Journey.
      </p>
    </div>

    <!-- FinisherHeader script -->
    <script src="../resources/js/finisher-header.es5.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      new FinisherHeader({
        count: 60,
        size: { min: 2, max: 8, pulse: 0 },
        speed: {
          x: { min: 0, max: 0.4 },
          y: { min: 0, max: 0.6 }
        },
        colors: {
          background: "#FFFFFF",
          particles: ["#f5a314", "#3bab5a", "#ef6461"]
        },
        blending: "overlay",
        opacity: {
          center: 1,
          edge: 0.3
        },
        skew: -2,
        shapes: ["c"]
      });
    </script>
  </section>

  <!-- Start Your Journey: Fifth Section (Combined Contact + Steps) -->
  <section id="contact" class="min-h-screen pt-32 px-6 text-center relative overflow-hidden">
    <!-- Background Image that Fills -->
    <img 
    src="../resources/image/landingpage-bgimage-section5.png" 
    alt="Background"    
    class="absolute top-0 left-0 w-full h-full object-cover object-center -z-10" />

    <!-- 3 Easy Steps Content -->
    <div class="relative z-10">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">Start Your Journey in 3 Easy Steps</h1>
      <p class="text-lg font-medium text-gray-800 mb-16">How it works. How to start learning.</p>

      <!-- Cards Container with More Top Margin -->
      <div class="relative mt-8 flex flex-col items-center gap-8 md:flex-row md:justify-center md:gap-12 md:mt-16">
        <!-- Card 1 -->
      <div class="bg-white shadow-md rounded-xl p-6 w-80 flex items-center gap-4 md:translate-y-40 transition duration-300 hover:scale-105 hover:shadow-xl border-2 border-transparent hover:border-[#EF6461]">          <div class="text-[48px] text-red-400 font-light leading-none">1</div>
          <div>
            <h3 class="font-bold text-lg mb-2 text-[#000000]">Create an Account</h3>
            <p class="text-sm text-gray-600">
              Experience real-life scenarios through interactive modules that
              sharpen your decision-making and client communication skills.
            </p>
          </div>
        </div>

        <!-- Card 2 -->
      <div class="bg-white shadow-md rounded-xl p-6 w-80 flex items-center gap-4 transition duration-300 hover:scale-105 hover:shadow-xl border-2 border-transparent hover:border-[#4AAB4C]">          <div class="text-[48px] text-green-500 font-light leading-none">2</div>
          <div>
            <h3 class="font-bold text-lg mb-2 text-[#000000]">Browse and Select</h3>
            <p class="text-sm text-gray-600">
              Experience real-life scenarios through interactive modules that
              sharpen your decision-making and client communication skills.
            </p>
          </div>
        </div>

        <!-- Card 3 -->
      <div class="bg-white shadow-md rounded-xl p-6 w-80 flex items-center gap-4 md:translate-y-40 transition duration-300 hover:scale-105 hover:shadow-xl border-2 border-transparent hover:border-[#F5A314]">          <div class="text-[48px] text-yellow-500 font-light leading-none">3</div>
          <div>
            <h3 class="font-bold text-lg mb-2 text-[#000000]">Learn and Accomplish</h3>
            <p class="text-sm text-gray-600">
              Experience real-life scenarios through interactive modules that
              sharpen your decision-making and client communication skills.
            </p>
          </div>
        </div>
      </div>

      <!-- CTA Button with Adjusted Margin -->
      <button class="mt-20 bg-yellow-500 text-black font-semibold py-3 px-6 rounded-lg shadow hover:bg-yellow-400 transition">
        Join Us Now! ➜
      </button>
    </div>
  </section>

  <!-- JavaScript -->
  <script>
    // First section typewriter
    const text1 = "Transform the way you learn - gain knowledge and real-world skills to pass the exam today.";
    const element1 = document.getElementById("typewriter");
    let i1 = 0;

    function type1() {
      if (i1 < text1.length) {
        element1.innerHTML += text1.charAt(i1);
        i1++;
        setTimeout(type1, 45);
      }
    }

    // Third section typewriter
    const text2 = "We offer everything you need to succeed in the real thing. LearnInsure is your trusted online training partner for passing the Insurance Commission Licensure Exam in the Philippines.";
    const element2 = document.getElementById("typewriter-text");
    let i2 = 0;

    function type2() {
      if (i2 < text2.length) {
        element2.innerHTML = text2.substring(0, i2 + 1);
        i2++;
        setTimeout(type2, 20);
      } else {
        element2.classList.add("typewriter-cursor");
      }
    }

    // Start both typewriters
    window.onload = function() {
      type1();
      
      // Start second typewriter when section is visible
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          type2();
          observer.unobserve(entries[0].target);
        }
      }, { threshold: 0.5 });

      observer.observe(document.getElementById("explore-background"));
    };

    // Enhanced Login Functions
    function goToLogin() {
      const loginForm = document.getElementById('loginForm');
      const overlay = document.getElementById('loginOverlay');
      
      // Show overlay and form with animations
      overlay.classList.remove('hidden');
      loginForm.classList.remove('hidden');
      setTimeout(() => {
        overlay.classList.add('opacity-100');
        loginForm.classList.add('login-transition-in');
      }, 10);
      
      // Disable scrolling on body
      document.body.style.overflow = 'hidden';
    }

    function closeLogin() {
      const loginForm = document.getElementById('loginForm');
      const overlay = document.getElementById('loginOverlay');
      
      // Start fade out animations
      loginForm.classList.remove('login-transition-in');
      loginForm.classList.add('login-transition-out');
      overlay.classList.remove('opacity-100');
      overlay.classList.add('opacity-0');
      
      // After animation completes, hide elements
      setTimeout(() => {
        loginForm.classList.add('hidden');
        overlay.classList.add('hidden');
        loginForm.classList.remove('login-transition-out');
        overlay.classList.remove('opacity-0');
        
        // Re-enable scrolling
        document.body.style.overflow = '';
      }, 300);
    }

    // Close login when clicking overlay
    document.getElementById('loginOverlay').addEventListener('click', closeLogin);

    // Prevent closing when clicking inside form
    document.getElementById('loginForm').addEventListener('click', function(e) {
      e.stopPropagation();
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking a link
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.add('hidden');
      });
    });

    // Update active nav link based on scroll position
    window.addEventListener('scroll', function() {
      const sections = document.querySelectorAll('section');
      const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
      
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 200) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.dataset.section === current) {
          link.classList.add('active');
        }
      });
    });
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

  <script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking a link
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.add('hidden');
      });
    });

    // Update active nav link based on scroll position
    window.addEventListener('scroll', function() {
      const sections = document.querySelectorAll('section');
      const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
      
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 200) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.dataset.section === current) {
          link.classList.add('active');
        }
      });
    });
  </script>

<footer class="bg-white border-t border-gray-200 py-12 px-6 sm:px-12 lg:px-20">
  <div class="max-w-7xl mx-auto">
    <!-- Columns Container -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8" style="gap-left: 1rem; gap-right: 1rem;">
      <!-- Column 1: Logo & Description (Spans 3 cols) -->
      <div class="md:col-span-3">
        <div class="flex items-center mb-4">
          <img 
            alt="LearnInsure Logo" 
            class="w-10 h-10 mr-3" 
            src="../resources/image/learninsurelogo-footer.png" 
          />
          <h2 class="text-xl text-[#1E1E1E] font-extrabold">LearnInsure</h2>
        </div>
        <p class="text-gray-700 text-sm leading-relaxed">
          LearnInsure is an eLearning platform designed to help aspiring
          financial advisors in the Philippines prepare for the Insurance
          Commission Licensure Exam (IC Exam).
        </p>
      </div>

      <!-- Column 2: Get Help (Spans 2 cols) -->
      <div class="md:col-span-3 pl-4 md:pl-20 pr-2">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">GET HELP</h3>
        <ul class="space-y-3">
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              Contact Us
            </a>
          </li>
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              FAQ
            </a>
          </li>
        </ul>
      </div>

      <!-- Column 3: Get Started -->
      <div class="md:col-span-3 pl-4 md:pl-18 pr-2">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">GET STARTED</h3>
        <ul class="space-y-3">
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              Home
            </a>
          </li>
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              About Us
            </a>
          </li>
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              Explore Modules
            </a>
          </li>
          <li>
            <a class="text-gray-700 text-sm font-medium hover:text-[#3BAB5A] transition-colors" href="#">
              Create An Account
            </a>
          </li>
        </ul>
      </div>

      <!-- Column 4: Partnered Agency -->
      <div class="md:col-span-3 pl-4 md:pl-18 pr-2">
        <h3 class="font-semibold text-gray-900 text-sm mb-4">PARTNERED NON-LIFE AGENCY</h3>
        <p class="text-gray-700 text-sm leading-relaxed mb-4">
          Address: 23 Florida Street, Brgy. Wack-Wack, Mandaluyong, Metro Manila,<br>
          55 Rizal Avenue, Brgy, Bayambang, Pangasinan
        </p>
        <p class="text-gray-700 text-sm leading-relaxed mb-4">
          Phone: (+63) 917 777 1213<br>
          Mail: siybauco_associates@yahoo.com
        </p>
        <div class="flex space-x-4 text-gray-700 text-lg">
          <a aria-label="LinkedIn" class="hover:text-[#3BAB5A] transition-colors" href="#">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a aria-label="Facebook" class="hover:text-[#3BAB5A] transition-colors" href="#">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a aria-label="Instagram" class="hover:text-[#3BAB5A] transition-colors" href="#">
            <i class="fab fa-instagram"></i>
          </a>
          <a aria-label="YouTube" class="hover:text-[#3BAB5A] transition-colors" href="#">
            <i class="fab fa-youtube"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="mt-12 text-center text-gray-800 text-sm">
      LearnInsure. @2024 by LearnInsure. All rights reserved.
    </div>
  </div>
</footer>

</body>
</html>