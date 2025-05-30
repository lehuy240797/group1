<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tourgether')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
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

<body class="min-h-screen flex flex-col">
    <nav>
        <div class="w-full px-0 py-0 flex justify-between items-center">
            {{-- Nav Links (Desktop) --}}
            <div class="w-full backdrop-blur-md bg-black/60 text-white z-50 shadow-md">
                <div class="max-w-screen-xl mx-auto flex justify-between items-center px-6 h-17">
                    <a href="/" class="flex items-center space-x-1">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo h-5 w-auto">
                    </a>

                    {{-- Chỉ hiển thị các liên kết nếu không phải admin --}}
                    @auth
                        @if (auth()->user()->admin_type !== 'admin')
                            <a href="{{ route('available-tours') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('available-tours') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Tour Có Sẵn
                            </a>
                            <a href="{{ route('custom-tours') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('custom-tours') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Tour Tự Tạo
                            </a>
                            <a href="{{ route('about') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('about') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Giới Thiệu
                            </a>
                            <a href="{{ route('feedback.form') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('feedback.form') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Liên Hệ
                            </a>
                            <a href="{{ route('my-bookings') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('my-bookings') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Tra Cứu Tours
                            </a>
                        @endif
                    @else
                        <a href="{{ route('available-tours') }}"
                            class="font-medium px-2 py-1 transition-colors duration-300
                        {{ request()->routeIs('available-tours') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                            Tour Có Sẵn
                        </a>
                        <a href="{{ route('custom-tours') }}"
                            class="font-medium px-2 py-1 transition-colors duration-300
                        {{ request()->routeIs('custom-tours') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                            Tour Tự Tạo
                        </a>
                        <a href="{{ route('about') }}"
                            class="font-medium px-2 py-1 transition-colors duration-300
                        {{ request()->routeIs('about') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                            Giới Thiệu
                        </a>
                        <a href="{{ route('feedback.form') }}"
                            class="font-medium px-2 py-1 transition-colors duration-300
                        {{ request()->routeIs('feedback.form') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                            Liên Hệ
                        </a>
                        <a href="{{ route('my-bookings') }}"
                            class="font-medium px-2 py-1 transition-colors duration-300
                        {{ request()->routeIs('my-bookings') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                            Tra Cứu Tours
                        </a>
                    @endauth

                    @auth
                        @if (auth()->user()->admin_type === 'admin')
                            <a href="{{ route('admin.tours.overview') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('admin.tours.overview') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Quản Lý Tours
                            </a>
                            <a href="{{ route('admin.staff.index') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('admin.staff.index') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Quản Lý Nhân Viên
                            </a>
                            <a href="{{ route('admin.customers.index') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('admin.customers.index') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Quản Lý Khách Hàng
                            </a>
                        @elseif(auth()->user()->role === 'tourguide' || auth()->user()->role === 'driver')
                            <a href="{{ route('my-tasks') }}"
                                class="font-medium px-2 py-1 transition-colors duration-300
                            {{ request()->routeIs('my-tasks') ? 'nav-glow' : 'text-white hover:text-blue-400' }}">
                                Phân Công Của Tôi
                            </a>
                        @endif
                    @endauth

                    {{-- Auth Buttons --}}
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Đăng
                                    Xuất</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="font-medium text-white hover:underline">Đăng Nhập</a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile Menu Toggle --}}
            <button class="md:hidden text-white" id="hamburger-menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>


    {{-- Mobile Navbar --}}
    <div class="md:hidden hidden" id="mobile-navbar">
        <ul class="flex flex-col space-y-4 p-4 bg-white shadow-md">
            {{-- Chỉ hiển thị các liên kết nếu không phải admin --}}
            @auth
                @if (auth()->user()->admin_type !== 'admin')
                    <li><a href="{{ route('available-tours') }}" class="nav-link text-black">Tour Có Sẵn</a></li>
                    <li><a href="{{ route('custom-tours') }}" class="nav-link text-black">Tour Tự Tạo</a></li>
                    <li><a href="{{ route('about') }}" class="nav-link text-black">Giới Thiệu</a></li>
                    <li><a href="{{ route('feedback.form') }}" class="nav-link text-black">Liên Hệ</a></li>
                    <li><a href="{{ route('my-bookings') }}" class="nav-link text-black">Tra Cứu Tours</a></li>
                @endif
            @else
                <li><a href="{{ route('available-tours') }}" class="nav-link text-black">Tour Có Sẵn</a></li>
                <li><a href="{{ route('custom-tours') }}" class="nav-link text-black">Tour Tự Tạo</a></li>
                <li><a href="{{ route('about') }}" class="nav-link text-black">Giới Thiệu</a></li>
                <li><a href="{{ route('feedback.form') }}" class="nav-link text-black">Liên Hệ</a></li>
                <li><a href="{{ route('my-bookings') }}" class="nav-link text-black">Tra Cứu Tours</a></li>
            @endauth

            @auth
                @if (auth()->user()->admin_type === 'admin')
                    <li><a href="{{ route('admin.tours.overview') }}" class="nav-link text-black">Quản Lí Tours</a></li>
                    <li><a href="{{ route('admin.staff.index') }}" class="nav-link text-black">Quản Lí Nhân Viên</a></li>
                    <li><a href="{{ route('admin.customers.index') }}" class="nav-link text-black">Quản Lí Khách Hàng</a>
                    </li>
                @elseif(auth()->user()->role === 'tourguide' || auth()->user()->role === 'driver')
                    <li><a href="{{ route('my-tasks') }}" class="nav-link text-black">Phân Công Của Tôi</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-red-600 hover:text-red-700">Đăng Xuất</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link text-black">Đăng Nhập</a></li>
            @endauth
        </ul>
    </div>

    <main class="container mx-auto px-4 py-6 flex-1">
        @yield('content')
    </main>

    @include('partials.chatbot_ui')

    <footer class="relative bottom-0 w-full z-5 backdrop-blur-md bg-black/50 text-white py-6">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-6 text-center">

            <!-- Cột 1: Thông tin công ty -->
            <div class="flex flex-col justify-between h-full items-center">
                <h2 class="text-xl font-bold mb-2">Tourgether © 2025</h2>
                <div>
                    <p class="text-sm">Công ty TNHH Du Lịch Tourgether</p>
                    <p class="text-sm">📍 391A Đường Nam Kỳ Khởi Nghĩa, Phường Võ Thị Sáu, Quận 3, TP. HCM</p>
                    <p class="text-sm">📞 0946467455</p>
                </div>
            </div>

            <!-- Cột 2: Giấy phép -->
            <div class="flex flex-col justify-between h-full items-center">
                <h3 class="text-lg font-semibold mb-2">Pháp lý</h3>
                <div>
                    <p class="text-sm">🆔 GPKD: 0300465937</p>
                    <p class="text-sm">Cấp ngày 05/05/2025</p>
                    <p class="text-sm">Sở KH&ĐT TP. HCM</p>
                    <p class="text-sm">🌍 GP lữ hành Quốc tế: 79-234/2022/TCDL-GP LHQT</p>
                </div>
            </div>

            <!-- Cột 3: Social media -->
            <div class="flex flex-col justify-between h-full items-center">
                <h3 class="text-lg font-semibold mb-2">Kết nối</h3>
                <div class="flex justify-center space-x-4 text-4xl">
                    <a href="#" class="hover:text-blue-500 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-sky-400 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Cột 4: Liên hệ -->
            <div class="flex flex-col justify-between h-full items-center">
                <h3 class="text-lg font-semibold mb-2">Chứng Nhận</h3>
                <div>
                    <img src="{{ asset('images/chungnhan.png') }}" alt="Certification"
                        class="w-32 h-auto  rounded" />
                </div>
            </div>
        </div>

        <div class="mt-6 text-center text-gray-400 text-xs">
            &copy; 2025 Tourgether. All rights reserved.
        </div>
    </footer>

    <script>
        function toggleChatbot() {
            const chatbot = document.getElementById('chatbotFrame');
            chatbot.classList.toggle('hidden');
        }
    </script>
    @livewireScripts

</body>


<script src="{{ asset('js/chatbot.js') }}"></script>
@stack('scripts')

</html>
