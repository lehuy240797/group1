<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tourgether')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <style>
        /* Reset nền */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: transparent;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -10;
        }

        /* Container nổi trên video */
        .content-overlay {
            position: relative;
            z-index: 10;
        }

        nav,
        footer {
            background-color: rgba(28, 41, 48, 0.7);
            backdrop-filter: blur(5px);
        }

        .nav-link {
            color: white;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #00BFFF;
        }

        .nav-logo {
            height: 64px;
            border-radius: 9999px;
        }

        footer {
            color: white;
        }

        .footer-text {
            font-size: 0.875rem;
            color: white;
        }

        .hamburger-btn {
            color: #e5e7eb;
        }

        .hamburger-btn:hover {
            color: #60a5fa;
        }
    </style>
</head>

<body>

    {{-- Video nền --}}
    <video autoplay muted loop class="video-background">
        <source src="{{ asset('videos/bg1.mp4') }}" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video nền.
    </video>

    {{-- Nội dung nổi trên video --}}
    <div class="content-overlay">
        <nav>
            <div class="container mx-auto px-4 py-2 flex justify-between items-center">
                <a href="/" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo">
                    <span class="text-xl font-semibold">Tourgether</span>
                </a>

                <ul class="hidden md:flex space-x-8 items-center mx-auto">
                    <li><a href="{{ route('available-tours') }}" class="nav-link">Tour Có Sẵn</a></li>
                    <li><a href="{{ route('custom-tours') }}" class="nav-link">Tour Tự Tạo</a></li>
                    <li><a href="{{ route('my-bookings') }}" class="nav-link">Tra Cứu Tour</a></li>
                    <li><a href="{{ route('about') }}" class="nav-link">Giới Thiệu</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link">Liên Hệ</a></li>
                    @auth
                        @if (auth()->user()->admin_type === 'admin')
                            <li><a href="{{ route('admin.tours.overview') }}" class="nav-link">Quản Lí Tours</a></li>
                            <li><a href="{{ route('admin.staff.index') }}" class="nav-link">Quản Lí Nhân Viên</a></li>
                            <li><a href="{{ route('admin.customers.index') }}">Quản Lí Khách Hàng</a></li>
                        @elseif(auth()->user()->role === 'tourguide' || auth()->user()->role === 'driver')
                            <li><a href="{{ route('my-tasks') }}" class="nav-link">Phân Công Của Tôi</a></li>
                        @endif
                    @endauth
                </ul>

                <button class="md:hidden hamburger-btn" id="hamburger-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <ul class="hidden md:flex space-x-4 items-center">
                    @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-red-500 hover:text-red-600">Đăng Xuất</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="nav-link">Đăng Nhập</a></li>
                    @endauth
                </ul>
            </div>

            <div class="md:hidden hidden" id="mobile-navbar">
                <ul class="flex flex-col space-y-4 p-4 bg-white/80 backdrop-blur">
                    <li><a href="{{ route('available-tours') }}" class="nav-link text-black">Tour Có Sẵn</a></li>
                    <li><a href="{{ route('custom-tours') }}" class="nav-link text-black">Tour Tự Tạo</a></li>
                    <li><a href="{{ route('my-bookings') }}" class="nav-link text-black">Tra Cứu Tours</a></li>
                    <li><a href="{{ route('about') }}" class="nav-link text-black">Giới Thiệu</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link text-black">Liên Hệ</a></li>
                    @auth
                        @if (auth()->user()->admin_type === 'admin')
                            <li><a href="{{ route('admin.tours.overview') }}" class="nav-link text-black">Quản Lí Tours</a></li>
                            <li><a href="{{ route('admin.staff.index') }}" class="nav-link text-black">Quản Lí Nhân Viên</a></li>
                            <li><a href="{{ route('admin.customers.index') }}" class="nav-link text-black">Quản Lí Khách Hàng</a></li>
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
                
                <button class="md:hidden hamburger-btn" id="hamburger-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button><ul class="hidden md:flex space-x-4 items-center">
                    @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-red-500 hover:text-red-600">Đăng Xuất</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="nav-link">Đăng Nhập</a></li>
                    @endauth
                </ul>
            </div>
            <div class="md:hidden" id="mobile-nav">
                <ul class="flex flex-col space-y-4 p-4 bg-white/80 backdrop-blur">
                    @if (Route::currentRouteName() === 'home')
                        <li><a href="{{ route('available-tours') }}" class="nav-link text-black">Tour Có Sẵn</a></li>
                        <li><a href="{{ route('custom-tours') }}" class="nav-link text-black">Tour Tự Tạo</a></li>
                        <li><a href="{{ route('my-bookings') }}" class="nav-link text-black">Tra Cứu Tour</a></li>
                        <li><a href="{{ route('about') }}" class="nav-link text-black">Giới Thiệu</a></li>
                        <li><a href="{{ route('contact') }}" class="nav-link text-black">Liên Hệ</a></li>
                    @else
                        <li><a href="{{ route('available-tours') }}" class="nav-link text-black">Tour Có Sẵn</a></li>
                        <li><a href="{{ route('custom-tours') }}" class="nav-link text-black">Tour Tự Tạo</a></li>
                        <li><a href="{{ route('my-bookings') }}" class="nav-link text-black">Tra Cứu Tour</a></li>
                        <li><a href="{{ route('about') }}" class="nav-link text-black">Giới Thiệu</a></li>
                        <li><a href="{{ route('contact') }}" class="nav-link text-black">Liên Hệ</a></li>
                    @endif
                    @auth
                        @if (auth()->user()->admin_type === 'admin')
                            <li><a href="{{ route('admin.tours.overview') }}" class="nav-link text-black">Quản Lí Tours</a></li>
                            <li><a href="{{ route('admin.staff.index') }}" class="nav-link text-black">Quản Lí Nhân Viên</a></li>
                            <li><a href="{{ route('admin.customers.index') }}" class="nav-link text-black">Quản Lí Khách Hàng</a></li>
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
        </nav>

        <main class="container mx-auto px-4 py-6">
            @yield('content')
        </main>

        @include('partials.chatbot_ui')

        <footer>
            <div class="container mx-auto px-4 py-6 text-center space-y-2">
                <p class="footer-text">Bản quyền của Tourgether ® 2025. Bảo lưu mọi quyền.</p>
                <p class="footer-text">Công ty TNHH Du Lịch Tourgether</p>
                <p class="footer-text">Địa chỉ: 123 Đường ABC, Phường XYZ, Quận 1, TP. Hồ Chí Minh</p>
                <p class="footer-text">Điện thoại: 0123 456 789</p>
                <p class="footer-text">GPKD: 0300465937 do Sở Kế Hoạch và Đầu Tư TP. Hồ Chí Minh cấp ngày 05/05/2025</p>
                <p class="footer-text">Số giấy phép lữ hành Quốc tế: 79-234/2022/TCDL-GP LHQT</p>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/chatbot.js') }}"></script>
    @stack('scripts')

    <script>
        // Toggle mobile navbar
        document.addEventListener('DOMContentLoaded', () => {
            const hamburgerBtn = document.getElementById('hamburger-menu');
            const mobileNav = document.getElementById('mobile-nav');

            if (hamburgerBtn && mobileNav) {
                hamburgerBtn.addEventListener('click', () => {
                    mobileNav.classList.toggle('active');
                    mobileNav.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>

</html>
