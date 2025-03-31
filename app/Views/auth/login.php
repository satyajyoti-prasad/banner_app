<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
      }
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      @keyframes progress {
        from {
          transform: scaleX(1);
        }
        to {
          transform: scaleX(0);
        }
      }
      @keyframes twinkle {
        0%,
        100% {
          opacity: 0.2;
        }
        50% {
          opacity: 1;
        }
      }
      .star {
        position: absolute;
        background-color: white;
        border-radius: 50%;
        animation: twinkle 2s infinite;
      }
      .input-field {
        transition: all 0.3s ease;
      }
      .input-field:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
          0 2px 4px -1px rgba(0, 0, 0, 0.06);
      }
      .error-message {
        animation: shake 0.5s;
      }
      @keyframes shake {
        0%,
        100% {
          transform: translateX(0);
        }
        10%,
        30%,
        50%,
        70%,
        90% {
          transform: translateX(-5px);
        }
        20%,
        40%,
        60%,
        80% {
          transform: translateX(5px);
        }
      }
    </style>
  </head>
  <body class="min-h-screen flex">
    <!-- Left Side Background -->
    <div
      class="hidden md:block w-1/2 bg-gradient-to-br from-indigo-900 to-purple-800 relative overflow-hidden"
    >
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-white text-center px-12">
          <h1 class="text-4xl font-bold mb-4">Welcome Back</h1>
          <p class="text-xl opacity-90">
            Manage your banners with our powerful admin dashboard
          </p>
          <div class="mt-8">
            <div
              class="w-16 h-16 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center"
            >
              <i class="fas fa-lock-open text-2xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Animated circles -->
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <div
          class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-purple-600 opacity-10 blur-xl"
        ></div>
        <div
          class="absolute top-2/3 left-1/3 w-40 h-40 rounded-full bg-indigo-600 opacity-10 blur-xl"
        ></div>
        <div
          class="absolute top-1/3 right-1/4 w-24 h-24 rounded-full bg-blue-600 opacity-10 blur-xl"
        ></div>
      </div>
    </div>

    <!-- Right Side with Login Form -->
    <div
      class="w-full md:w-1/2 bg-gradient-to-br from-gray-900 to-gray-800 relative overflow-hidden flex items-center justify-center p-4"
    >
      <!-- Starry background -->
      <div id="stars-container" class="absolute inset-0 overflow-hidden"></div>

      <div
        class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md relative z-10 transform transition-all duration-500 hover:scale-[1.01]"
      >
        <div class="flex justify-center mb-6">
          <div
            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"
          >
            <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
          </div>
        </div>

        <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">
          Admin Portal
        </h1>
        <p class="text-center text-gray-600 mb-6">Sign in to your account</p>

       <?php
// Determine which message to show (priority: error > success > logout)
$message = session()->getFlashdata('error')
    ? ['text' => session()->getFlashdata('error'), 'type' => 'error']
    : (session()->getFlashdata('success')
        ? ['text' => session()->getFlashdata('success'), 'type' => 'success']
        : (!empty($logout_message)
            ? ['text' => $logout_message, 'type' => 'success']
            : null));
?>

        <?php if ($message): ?>
        <div
          class="w-full max-w-md mx-auto mb-6 <?= $message['type'] === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800' ?> border rounded-lg shadow-lg p-4 animate-fade-in relative overflow-hidden"
          id="flash-message"
        >
          <!-- Timer bar at the top -->
          <div class="absolute top-0 left-0 right-0 h-1 <?= $message['type'] === 'error' ? 'bg-red-300' : 'bg-green-300' ?> animate-[progress_5s_linear_forwards]" style="transform-origin: left"></div>
          
          <div class="flex items-start pt-1">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mt-0.5 mr-2 flex-shrink-0"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <?php if ($message['type'] === 'error'): ?>
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
              <?php else: ?>
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
              <?php endif; ?>
            </svg>
            <div class="flex-1">
              <p class="text-sm"><?= esc($message['text']) ?></p>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <form action="/login" method="post" class="space-y-5">
          <?= csrf_field() ?>

          <div>
            <label
              for="username"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Username</label
            >
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <i class="fas fa-user text-gray-400"></i>
              </div>
              <input
                type="text"
                name="username"
                id="username"
                class="input-field pl-10 mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                required
                placeholder="Enter your username"
              />
            </div>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Password</label
            >
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input
                type="password"
                name="password"
                id="password"
                class="input-field pl-10 mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                required
                placeholder="Enter your password"
              />
              <div
                class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                onclick="togglePassword()"
              >
                <i
                  class="fas fa-eye text-gray-400 hover:text-gray-600"
                  id="toggle-icon"
                ></i>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-700"
                >Remember me</label
              >
            </div>
            <div class="text-sm">
              <a
                href="/forgot-password"
                class="font-medium text-blue-600 hover:text-blue-500"
                >Forgot password?</a
              >
            </div>
          </div>

          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-[1.01]"
            >
              <i class="fas fa-sign-in-alt mr-2"></i> Sign in
            </button>
          </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
          <p>
            Don't have an account?
            <a href="#" class="font-medium text-blue-600 hover:text-blue-500"
              >Contact support</a
            >
          </p>
        </div>
      </div>
    </div>

    <script>
      // Auto-dismiss after 5 seconds (matches progress animation)
      setTimeout(() => {
        const message = document.getElementById("flash-message");
        if (message) {
          message.style.opacity = "0";
          message.style.transition = "opacity 0.5s ease-out";
          setTimeout(() => message.remove(), 500);
        }
      }, 5000);

      // Create stars for the background
      function createStars() {
        const container = document.getElementById("stars-container");
        const starsCount = 100;

        for (let i = 0; i < starsCount; i++) {
          const star = document.createElement("div");
          star.classList.add("star");

          // Random size between 1 and 3px
          const size = Math.random() * 2 + 1;
          star.style.width = `${size}px`;
          star.style.height = `${size}px`;

          // Random position
          star.style.left = `${Math.random() * 100}%`;
          star.style.top = `${Math.random() * 100}%`;

          // Random animation duration and delay
          star.style.animationDuration = `${Math.random() * 3 + 1}s`;
          star.style.animationDelay = `${Math.random() * 2}s`;

          container.appendChild(star);
        }
      }

      // Toggle password visibility
      function togglePassword() {
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("toggle-icon");

        if (passwordField.type === "password") {
          passwordField.type = "text";
          toggleIcon.classList.remove("fa-eye");
          toggleIcon.classList.add("fa-eye-slash");
        } else {
          passwordField.type = "password";
          toggleIcon.classList.remove("fa-eye-slash");
          toggleIcon.classList.add("fa-eye");
        }
      }

      // Add input animation effects
      document.querySelectorAll(".input-field").forEach((input) => {
        input.addEventListener("focus", function () {
          this.parentElement.querySelector("i").classList.add("text-blue-500");
        });

        input.addEventListener("blur", function () {
          this.parentElement
            .querySelector("i")
            .classList.remove("text-blue-500");
        });
      });

      // Initialize stars when page loads
      window.addEventListener("load", createStars);
    </script>
  </body>
</html>