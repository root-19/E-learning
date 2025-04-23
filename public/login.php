<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to Root-Dev</title>
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
  </style>
</head>
<body class="bg-black text-white">

  <!-- Welcome Section -->
  <div class="flex items-center justify-center full-height">
    <div class="text-center px-6">
      <h1 class="text-6xl font-bold mb-6">
        <span class="text-red-600">Root-Dev</span> PHP Framework
      </h1>

      <p id="typewriter" class="text-xl text-gray-300 mb-10 typewriter-text"></p>

      <button onclick="goToLogin()" class="inline-block bg-red-600 hover:bg-red-700 text-white text-lg font-medium px-8 py-3 rounded-full transition">
        Continue →
      </button>
    </div>
  </div>

  <!-- Login Form Section -->
  <div id="loginForm" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-800 text-white w-full max-w-md p-10 rounded-lg shadow-xl">
    <div class="text-gray-800 p-10 rounded-lg shadow-xl w-full">
      <h2 class="text-3xl font-bold mb-4 text-center text-white">Login</h2>
      
      <!-- Error Message -->
      <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

      <form method="POST" action="/login">
        <label class="block text-lg text-white">Email:</label>
        <input type="email" name="email" class="w-full p-3 mb-4 border border-gray-300 rounded" required>

        <label class="block text-lg text-white">Password:</label>
        <input type="password" name="password" class="w-full p-3 mb-6 border border-gray-300 rounded" required>

        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-full hover:bg-red-700 transition">
          Login
        </button>

        <div class="mb-6 text-center mt-5">
      <a href="/forget-password" class="text-sm text-blue-400 hover:underline">Forgot Password?</a>
    </div>
      </form>

      <p class="text-center mt-4 text-white">Don't have an account? <a href="/register" class="text-red-600 hover:text-red-700">Register here</a></p>
    </div>
  </div>

  <script>
    const text = "Why settle for the old ways? Start better with PHP and build modern web apps faster, easier, and cleaner — only with Root-Dev.";
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
      // Hide the welcome text and show the login form
      document.querySelector('.text-center').classList.add('hidden');
      document.getElementById('loginForm').classList.remove('hidden');
    }
  </script>
<script>
const socket = new WebSocket("ws://localhost:3001");
socket.onmessage = (event) => {
  if (event.data === "reload") {
    console.log("♻ Auto-reloading...");
    location.reload();
  }
};
</script>



</body>
</html>
