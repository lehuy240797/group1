<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tourgether')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
        }

        /* ===== Layout ===== */
        .container {
            padding: 0px 0;
        }

        /* ===== Navbar ===== */
        .nav-glow {
            position: relative;
            color: #2563eb;
            /* Tailwind blue-600 */
            font-weight: bold;
        }

        .nav-glow::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -5px;
            transform: translateX(-50%);
            width: 50%;
            height: 3px;
            background: #2563eb;
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
            border-radius: 9999px;
            opacity: 1;
            transition: all 0.3s ease;
        }

        nav {
            background: #1C2930;
            padding: 20px;
            color: white;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);

        }

        .nav-logo {
            height: 64px;
            border-radius: 9999px;
        }

        .nav-link {
            color: white;
            font-size: 1rem;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .nav-link:hover {
            color: #00BFFF;
            /* Màu xanh dương nổi bật */
        }


        .hamburger-btn {
            color: #e5e7eb;
            transition: color 0.3s ease;
        }

        .hamburger-btn:hover {
            color: #60a5fa;
        }

        /* ===== Footer ===== */
        footer {
            background: #1C2930;
            padding: 20px;
            color: white;
            box-shadow: 0px -4px 15px rgba(0, 0, 0, 0.2);
            border-top: 2px solid rgba(255, 255, 255, 0.6);
        }

        .footer-text {
            font-size: 0.875rem;
            color: white;
        }

        /* ===== Swiper custom ===== */
        .swiper-pagination-bullet {
            background-color: white;
            opacity: 0.7;
        }

        .swiper-pagination-bullet-active {
            background-color: #4D9DE0;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
            });

            // Toggle mobile navbar
            const toggleBtn = document.getElementById('hamburger-menu');
            const mobileNavbar = document.getElementById('mobile-navbar');
            if (toggleBtn && mobileNavbar) {
                toggleBtn.addEventListener('click', function() {
                    mobileNavbar.classList.toggle('hidden');
                });
            }
        });
    </script>
</head>

<body>


   

</body>

</html>