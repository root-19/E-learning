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
  <section id="loginForm" class="hidden flex items-center justify-center min-h-screen bg-gray-100 z-50">
    <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-lg border mx-4">
      <h2 class="text-2xl font-bold mb-2 text-black">Welcome to LearnInsure!</h2>
      <p class="mb-2 text-black">Login to your account to continue your learning journey.</p>
      <p class="mb-4 text-gray-700 text-sm">Please complete this form with your account credentials. Required fields are marked with an asterisk (*).</p>
      <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>
      <form method="POST" action="/login">
        <label class="block text-black font-medium mb-1">Email Address: <span class="text-red-500">*</span></label>
        <input type="email" name="email" placeholder="Enter your email" class="w-full p-3 mb-4 border border-gray-300 rounded text-black bg-white focus:outline-none focus:ring-2 focus:ring-green-500" required>
        <label class="block text-black font-medium mb-1">Password: <span class="text-red-500">*</span></label>
        <input type="password" name="password" placeholder="Enter your password" class="w-full p-3 mb-4 border border-gray-300 rounded text-black bg-white focus:outline-none focus:ring-2 focus:ring-green-500" required>
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded mt-2 transition">Login</button>
        <div class="text-left mt-3">
          <a href="/forget-password" class="text-green-600 hover:underline text-sm">Forgot your password?</a>
        </div>
      </form>
      <p class="text-center mt-6 text-black">
        New to LearnInsure?<br>
        <a href="/register" class="inline-block mt-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded transition">
          Create an Account
        </a>
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
    <div class="max-w-7xl mx-auto">
      <!-- Search and Filter Section -->
      <div class="mb-8 flex flex-col md:flex-row gap-4 justify-center items-center">
        <div class="relative w-full md:w-96">
          <input type="text" 
                 id="searchInput" 
                 placeholder="Search courses..." 
                 class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4B793E] focus:border-[#4B793E]">
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        <select id="categoryFilter" 
                class="w-full md:w-48 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#4B793E] focus:border-[#4B793E]">
          <option value="all">All Categories</option>
          <option value="interactive">Interactive</option>
          <option value="traditional">Traditional</option>
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        require_once __DIR__ . '/../app/controller/ModuleController.php';
        $controller = new ModuleController();
        $courses = $controller->listCourses();
        
        foreach ($courses as $course): 
          if ($course['is_rejected'] == 1) continue;
          $chapters = $controller->getChaptersForCourse($course['id']);
          $courseType = 'traditional';
          foreach ($chapters as $chapter) {
            if ($chapter['type'] === 'interactive') {
              $courseType = 'interactive';
              break;
            }
          }
        ?>
        <div class="course-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
          <div class="relative">
            <img src="/<?= htmlspecialchars($course['course_image']) ?>" 
                 alt="<?= htmlspecialchars($course['course_title']) ?>"
                 class="w-full h-48 object-cover">
            <div class="absolute top-4 right-4">
              <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $courseType === 'interactive' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                <?= ucfirst($courseType) ?>
              </span>
            </div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($course['course_title']) ?></h3>
            <p class="text-gray-600 mb-4"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-500">
                <i class="fas fa-book-open mr-1"></i>
                <?= count($chapters) ?> Chapters
              </span>
              <a href="/course/<?= $course['id'] ?>" class="bg-[#4B793E] text-white px-4 py-2 rounded-lg hover:bg-[#3d6232] transition-colors">
                Available
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="about" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">About Us</h2>
    <p class="text-black">InsureLearn is a modern PHP framework designed to speed up web app development.</p>
  </section>

  <section id="contact" class="min-h-screen pt-32 px-6 text-center">
    <h2 class="text-4xl font-bold mb-6">Contact</h2>
    <main class="px-6 py-12 bg-[#fdfdf9]">
      <h2 class="text-3xl font-bold text-center mb-8">Contact Us</h2>

      <div
        class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 bg-[#FAFAFA] border border-green-200 p-8 rounded-lg shadow-lg"
      >
        <!-- Contact Form -->
        <form
          action="https://formsubmit.co/siybauco_associates@yahoo.com"
          method="POST"
          class="space-y-4"
        >
          <input
            type="hidden"
            name="_subject"
            value="New Contact Form Submission"
          />
          <input
            type="hidden"
            name="_next"
            value="https://siybaucoinsurance.com/thanks.html"
          />

          <div>
            <label class="block font-medium mb-1">Full Name *</label>
            <input
              type="text"
              name="name"
              required
              placeholder="Your Name"
              class="w-full border border-green-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F4C542] focus:border-[#F4C542]"
            />
          </div>
          <div>
            <label class="block font-medium mb-1">Email Address *</label>
            <input
              type="email"
              name="email"
              required
              pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
              placeholder="your.email@example.com"
              class="w-full border border-green-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F4C542] focus:border-[#F4C542]"
            />
          </div>
          <div>
            <label class="block font-medium mb-1">Subject *</label>
            <input
              type="text"
              name="subject"
              required
              placeholder="How can we help?"
              class="w-full border border-green-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F4C542] focus:border-[#F4C542]"
            />
          </div>
          <div>
            <label class="block font-medium mb-1">Your Message *</label>
            <textarea
              name="message"
              required
              placeholder="Please write your message here..."
              class="w-full border border-green-300 px-4 py-2 rounded-lg h-32 resize-none focus:outline-none focus:ring-2 focus:ring-[#F4C542] focus:border-[#F4C542]"
            ></textarea>
          </div>
          <button
            type="submit"
            class="bg-[#F4C542] hover:bg-[#e0b03d] text-white font-bold px-6 py-3 rounded-lg shadow-md transition-transform hover:scale-105 w-full"
          >
            Send Message
          </button>
        </form>

        <!-- Contact Info -->
        <div>
          <h3 class="text-xl font-semibold mb-3">
            Siybauco And Associates Insurance Agency, Inc.
          </h3>
          <p class="mb-2">
            <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
            23 Florida Street, Brgy. Wack-Wack, Mandaluyong, Metro Manila
          </p>
          <p class="mb-2">55 Rizal Avenue, Brgy. Bayambang, Pangasinan</p>
          <p class="mb-2">
            <i class="fas fa-phone-alt text-green-600 mr-2"></i>
            (+63) 917 777 1213
          </p>
          <p class="mb-6">
            <i class="fas fa-envelope text-green-600 mr-2"></i>
            <a
              href="mailto:siybauco_associates@yahoo.com"
              class="text-green-600 hover:underline"
              >siybauco_associates@yahoo.com</a
            >
          </p>

          <!-- Social Links -->
          <div class="mb-6">
            <p class="font-medium mb-3 text-gray-700">Connect with us:</p>
            <div class="flex space-x-3">
              <!-- LinkedIn -->
              <a
                href="https://ph.linkedin.com/in/siybauco-insurance-613007301"
                class="w-8 h-8 flex items-center justify-center bg-[#0077B5] rounded-full hover:bg-[#006097] transition-colors"
                aria-label="LinkedIn"
              >
                <i class="fab fa-linkedin-in text-white text-sm"></i>
              </a>

              <!-- Facebook -->
              <a
                href="https://www.facebook.com/siybauco.ph/"
                class="w-8 h-8 flex items-center justify-center bg-[#4267B2] rounded-full hover:bg-[#365899] transition-colors"
                aria-label="Facebook"
              >
                <i class="fab fa-facebook-f text-white text-sm"></i>
              </a>

              <!-- Instagram -->
              <a
                href="https://www.instagram.com/siybaucoinsurance/"
                class="w-8 h-8 flex items-center justify-center bg-gradient-to-r from-[#833AB4] via-[#C13584] to-[#E1306C] rounded-full hover:opacity-90 transition-opacity"
                aria-label="Instagram"
              >
                <i class="fab fa-instagram text-white text-sm"></i>
              </a>

              <!-- Website -->
              <a
                href="https://siybaucoinsurance.com/"
                class="w-8 h-8 flex items-center justify-center bg-[#F4C542] rounded-full hover:bg-[#e0b03d] transition-colors"
                aria-label="Website"
              >
                <i class="fas fa-globe text-white text-sm"></i>
              </a>
            </div>
          </div>
          <!-- Interactive Map -->
          <iframe
            class="w-full h-64 rounded-lg border-0 shadow-md"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1930.6930889422465!2d121.04304731613898!3d14.59153572017454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c8720d390a2d%3A0xd3e2b43bc65df938!2s23%20Florida%20St%2C%20Mandaluyong%2C%201550%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1715840000000!5m2!1sen!2sph"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </main>
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

  <!-- Add JavaScript for search and filter functionality -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('searchInput');
      const categoryFilter = document.getElementById('categoryFilter');
      const courseCards = document.querySelectorAll('.course-card');

      function filterCourses() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        courseCards.forEach(card => {
          const title = card.querySelector('h3').textContent.toLowerCase();
          const description = card.querySelector('p').textContent.toLowerCase();
          const type = card.querySelector('.absolute.top-4.right-4 span').textContent.toLowerCase();

          const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
          const matchesCategory = selectedCategory === 'all' || type === selectedCategory;

          card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
        });
      }

      searchInput.addEventListener('input', filterCourses);
      categoryFilter.addEventListener('change', filterCourses);
    });
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
