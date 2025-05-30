@extends('layouts.home')
<!-- Background Music -->
<audio id="bg-music" loop>
    <source src="{{ asset('music/intro.mp3') }}" type="audio/mpeg">
</audio>

<!-- Mute/Unmute Button -->
<button id="mute-toggle"
    class="fixed bottom-4 left-4 z-50 bg-white/70 text-black px-4 py-2 rounded-full shadow-lg text-lg hover:bg-white transition-all">
</button>
<!-- Navbar -->
<div class="fixed top-0 left-0 w-full backdrop-blur-md bg-black/60 text-white z-50 shadow-md">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center px-6 h-17">
        <a href="#home" class="flex items-center space-x-1">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo h-5 w-auto">
        </a>

        {{-- Chỉ hiển thị các liên kết nếu không phải admin --}}
        @auth
            @if (auth()->user()->admin_type !== 'admin')
                <a href="#placetogo" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Góc nhìn 360</a>
                <a href="#available-tours" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tour Có Sẵn</a>
                <a href="#custom-tour" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tour Tự Tạo</a>
                <a href="#about" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Giới Thiệu</a>
                <a href="#feedback.form" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Liên Hệ</a>
                <a href="{{ route('my-bookings') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tra Cứu</a>
            @endif
        @else
            <a href="#placetogo" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Góc nhìn 360</a>
            <a href="#available-tours" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tour Có Sẵn</a>
            <a href="#custom-tour" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tour Tự Tạo</a>
            <a href="#about" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Giới Thiệu</a>
            <a href="#feedback.form" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Liên Hệ</a>
            <a href="{{ route('my-bookings') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Tra Cứu</a>
        @endauth

        @auth
            @if (auth()->user()->admin_type === 'admin')
                <a href="{{ route('admin.tours.overview') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Quản Lý Tours</a>
                <a href="{{ route('admin.staff.index') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Quản Lý Nhân Viên</a>
                <a href="{{ route('admin.customers.index') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Quản Lý Khách Hàng</a>
            @elseif(auth()->user()->role === 'tourguide' || auth()->user()->role === 'driver')
                <a href="{{ route('my-tasks') }}" class="font-medium text-white hover:text-blue-400 transition-colors duration-300">Phân Công Của Tôi</a>
            @endif
        @endauth

        {{-- Auth Buttons --}}
        <div class="hidden md:flex items-center space-x-4">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Đăng Xuất</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-medium text-white hover:underline">Đăng Nhập</a>
            @endauth
        </div>
    </div>
</div>

<div class="h-screen w-screen overflow-y-scroll snap-y snap-mandatory scroll-smooth">

    <!-- Section 1 -->
    <section id="home" class="relative h-screen w-screen snap-start pt-16">


        <!-- Background Video -->
        <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
            <source src="{{ asset('images/videos/video3.mp4') }}" type="video/mp4">
        </video>

        <!-- Overlay Content -->
        <div class="absolute top-0 left-0 w-full h-full bg-black/10 z-10 flex items-center justify-center">
            <h1 id="home-title" style="font-family: 'Poppins', 'sans-serif';"
                class="text-6xl font-bold text-white text-center opacity-0" data-animate="animate__fadeInDown">
                Tourgether – Nơi Hành Trình Bắt Đầu
            </h1>



        </div>
        <!-- Bouncing Chevron -->

        <a href="#placetogo"
            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce text-white text-4xl hover:text-blue-400 transition-colors duration-300">
            &or;
        </a>


    </section>

    <!-- Section 2 -->
    <section id="placetogo"
        class="h-screen w-screen relative py-20 flex justify-center items-center snap-start bg-cover bg-center transition-all duration-700"
        style="background-image: url('{{ asset('images/hanoi/hanoi4.1.JPG') }}');">

        <!-- Navigation Buttons -->
        <button id="prevPlaceBtn" onclick="prevPlace()"
            class="absolute left-5 top-1/2 transform -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 p-2 rounded-full backdrop-blur-md">
            <!-- Left Arrow Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button id="nextPlaceBtn" onclick="nextPlace()"
            class="absolute right-5 top-1/2 transform -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 p-2 rounded-full backdrop-blur-md">
            <!-- Right Arrow Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>


        <!-- Card Container -->
        <div id="placeCard"
            class="bg-white/50 backdrop-blur-lg rounded-xl p-8 w-[95%] max-w-6xl flex flex-col md:flex-row items-center gap-8 shadow-lg transition-opacity duration-700">
            <!-- Left: Description -->
            <div class="w-full md:w-1/2 text-gray-800 text-base sm:text-lg leading-relaxed">
                <h2 class="text-3xl font-bold mb-4"  id="placeTitle">Chùa Thiên Mụ</h2>
                <p id="placeDescription">
                    
                </p>

            </div>

            <!-- Right: 360 VR Iframe -->
            <div class="w-full md:w-1/2 h-[300px]">
                <iframe id="placeIframe" class="w-full h-full rounded-lg"
                    src="https://vr360.vietravel.net/vietnam/hue/chua-thien-mu/" frameborder="0"
                    allowfullscreen></iframe>
            </div>
        </div>
    </section>


    <!-- Section 3 -->
    <section id="available-tours"
    class="relative min-h-screen w-full snap-start px-4 py-16 flex items-center justify-center overflow-hidden">

    <!--VIDEO BG (only inside this section) -->
    <div class="absolute inset-0 -z-10">
        <video autoplay muted loop playsinline class="w-full h-full object-cover">
            <source src="{{ asset('images/videos/beach5.mp4') }}" type="video/mp4">
        </video>
        
    </div>

    <!-- MAIN CONTENT  -->
    <div class="text-center opacity-0" data-animate="animate__fadeInUp">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-1000 mb-6">
                Bạn đang không biết đi du lịch ở đâu?
            </h2>
            <p class="text-2xl font-bold text-gray-1000 mb-8">
                Đừng lo! Ở đây có rất nhiều tour du lịch thú vị đang chờ đón bạn.
            </p>
            <a href="{{ route('available-tours') }}"
                class="mt-8 inline-block px-12 py-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold shadow-lg"
                data-animate="animate__fadeInUp" style="animation-delay: 0.2s;" class="opacity-0">
                Bắt đầu hành trình của bạn
            </a>
        </div>
    </section>

    <!-- Section 4 -->
    <section id="custom-tour" class="h-screen w-screen snap-start bg-blue-100 flex items-center justify-center px-6"
        style="background-image: url('{{ asset('images/danang/danang2.jpg') }}');">
        <div class="text-center opacity-0" data-animate="animate__fadeInUp">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Không tìm thấy ngày tour phù hợp?
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                Đừng lo! Bạn có thể tự tạo tour theo ngày mà bạn muốn.
            </p>
            <a href="{{ route('custom-tours') }}"
                class="mt-8 inline-block px-12 py-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold shadow-lg"
                data-animate="animate__fadeInUp" style="animation-delay: 0.2s;" class="opacity-0">
                Tạo Tour Của Bạn
            </a>
        </div>
    </section>


    <!-- Section 5 -->
    <section id="about" class="h-screen w-screen snap-start relative bg-cover bg-center text-black">
        <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
            <source src="{{ asset('images/videos/video.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="h-full w-full flex items-center justify-start px-8 md:px-20">

            <!-- Left: Text Content -->
            <div class="bg-white/40 backdrop-blur-sm p-8 rounded-xl max-w-2xl" data-animate="animate__fadeInUp">

                <h1 class="text-5xl font-bold mb-6">Giới thiệu về Tourgether™</h1>
                <p class="text-lg leading-relaxed text-gray-300">
                <p>
                    Được thành lập với niềm đam mê đưa du khách đến gần hơn với vẻ đẹp sống động của Việt Nam,
                    <strong>Tourgether</strong> là nền tảng dành riêng để kết nối những người muốn khám phá mọi ngóc
                    ngách
                    của đất nước tuyệt vời này — cùng nhau.
                    Từ những con phố nhộn nhịp của Hà Nội đến bờ biển yên bình của Phú Quốc,
                    chúng tôi tin rằng mọi hành trình đều tuyệt vời hơn khi được chia sẻ với những người bạn mới có cùng
                    tinh thần phiêu lưu.
                </p>

                <p>
                    Tại Tourgether, sứ mệnh của chúng tôi là giúp việc đi lại khắp Việt Nam trở nên dễ dàng hơn, an toàn
                    hơn
                    và có ý nghĩa hơn
                    bằng cách giúp các cá nhân và nhóm tìm được người bạn đồng hành lý tưởng và những trải nghiệm địa
                    phương
                    được tuyển chọn.
                    Cho dù bạn đang tìm kiếm ai đó để cùng leo núi sương mù ở Sapa, tham gia tour ẩm thực đường phố ở Đà
                    Nẵng hay du ngoạn qua Đồng bằng sông Cửu Long,
                    Tourgether sẽ đưa mọi người lại gần nhau để có những khoảnh khắc khó quên.
                </p>

                <p>
                    Tourgether tự hào phục vụ du khách trên khắp Việt Nam, hỗ trợ cả những nhà thám hiểm địa phương và
                    du
                    khách quốc tế
                    muốn trải nghiệm nền văn hóa, lịch sử và vẻ đẹp thiên nhiên phong phú của đất nước.
                    Bất cứ nơi nào hành trình của bạn đưa bạn đến — từ những thị trấn cổ kính đến những thành phố hiện
                    đại,
                    từ đỉnh núi đến những bãi biển ẩn mình —
                    Tourgether giúp bạn tìm được những người bạn đồng hành hoàn hảo để cùng chia sẻ chặng đường phía
                    trước.
                </p>


                <a href="#available-tours" class="font-semibold text-center text-blue-700 block hover:underline">
                    Hãy cùng nhau khám phá Việt Nam.
                </a>

            </div>
        </div>
    </section>
    <!-- Section 6 + Section 7 Wrapper with Video Background -->
    <section id="feedback.form" class="relative w-full min-h-screen text-white snap-start overflow-hidden">
        <!-- Video Background (only inside this wrapper) -->
        <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
            <source src="{{ asset('images/videos/beach7.mp4') }}" type="video/mp4">
        </video>

        <!-- Overlay -->
        <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-60 z-0"></div>
        <!-- Section 6 Content -->
        <div class="relative z-10 w-full min-h-screen flex items-center justify-center">
            <div class="relative z-20 flex flex-col items-center justify-center h-full px-6 space-y-14">
                <h2 class="text-4xl font-bold animate__animated" data-animate="animate__fadeIn" style="animation-delay: 0.2s;">
                    TẠI SAO CHỌN CHÚNG TÔI?
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl w-full text-center">
                    <!-- Service 1 -->
                    <div class="flex flex-col items-center space-y-4 animate__animated" data-animate="animate__fadeInUp" style="animation-delay: 0.4s;">
                        <div class="text-5xl bg-white/20 rounded-full p-4">
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Đặt Tour Nhanh Chóng</h3>
                        <p class="text-sm text-gray-200 max-w-xs">Chỉ vài cú nhấp chuột, bạn đã sẵn sàng cho hành trình tiếp theo. Dễ dàng, nhanh chóng và mượt mà.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class="flex flex-col items-center space-y-4 animate__animated" data-animate="animate__fadeInUp" style="animation-delay: 0.6s;">
                        <div class="text-5xl bg-white/20 rounded-full p-4">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Chuyên Gia Bản Địa</h3>
                        <p class="text-sm text-gray-200 max-w-xs">Chúng tôi am hiểu những điểm đến độc đáo và câu chuyện bí ẩn.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class="flex flex-col items-center space-y-4 animate__animated" data-animate="animate__fadeInUp" style="animation-delay: 0.8s;">
                        <div class="text-5xl bg-white/20 rounded-full p-4">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="text-xl font-semibold">An Toàn & Đảm Bảo</h3>
                        <p class="text-sm text-gray-200 max-w-xs">Chúng tôi đặt sự an toàn của bạn lên hàng đầu. Du lịch không lo lắng trên mọi hành trình.</p>
                    </div>
                </div>

                <!-- Contact Button -->
                <a href="{{ route('feedback.form') }}"
                    class="mt-8 inline-block px-10 py-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold shadow-lg animate__animated"
                    data-animate="animate__fadeInUp" style="animation-delay: 1s;">
                    Liên Hệ
                </a>
            </div>
        </div>

        <!-- Section 7: Footer (Full Width + Bottom Sticky Style) -->
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
                    <h3 class="text-lg font-semibold mb-2">Liên hệ</h3>
                    <div>
                        <img src="{{ asset('images/chungnhan.png') }}" alt="Certification" class="w-32 h-auto  rounded" />
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center text-gray-400 text-xs">
                &copy; 2025 Tourgether. All rights reserved.
            </div>
        </footer>

    </section>
</div>

<!--Script-->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const animatedElements = document.querySelectorAll('[data-animate]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const el = entry.target;
                const animation = el.getAttribute('data-animate');

                if (entry.isIntersecting) {
                    el.classList.remove('opacity-0');
                    el.classList.add('animate__animated', animation);
                } else {
                    // Reset animation when element leaves the viewport
                    el.classList.remove('animate__animated', animation);
                    el.classList.add('opacity-0');
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.forEach(el => observer.observe(el));
    });
</script>

<script>
    window.addEventListener("beforeunload", () => window.scrollTo(0, 0));
</script>

<script>
    const places = [
        {
            title: "Chùa Thiên Mụ",
            description: `Chùa Thiên Mụ (hay còn gọi là chùa Linh Mụ) là một trong những ngôi chùa cổ nhất và nổi tiếng nhất tại Huế, 
                        được xây dựng vào năm 1601 dưới triều đại Nguyễn. Chùa nằm trên đồi Hà Khê, bên bờ sông Hương, cách trung tâm thành phố Huế khoảng 5 km về phía Tây Bắc.

                        <br><br>

                        Chùa Thiên Mụ nổi bật với tháp Phước Duyên cao 21 m, có 7 tầng, được xây dựng vào năm 1844. 
                        Tháp được coi là biểu tượng của chùa và của thành phố Huế. Ngoài ra, chùa còn có nhiều công trình kiến trúc độc đáo khác như điện Đại Hùng, điện Quan Âm, điện Thánh Mẫu...

                        <br><br>

                        Chùa Thiên Mụ không chỉ là một địa điểm du lịch nổi tiếng mà còn là nơi tổ chức nhiều lễ hội văn hóa tâm linh quan trọng của người dân Huế.
                    
    `,
            iframe: "https://vr360.vietravel.net/vietnam/hue/chua-thien-mu/",
            background: "{{ asset('images/thienmu.jpg') }}"
        },
        {
            title: "Lăng Cô",
            description: `Lăng Cô là một trong những bãi biển đẹp nhất tại Huế, 
                        nằm cách trung tâm thành phố khoảng 30 km về phía Bắc. Bãi biển Lăng Cô dài khoảng 10 km, 
                        với cát trắng mịn và nước biển trong xanh, là nơi lý tưởng để tắm biển, lặn ngắm san hô và tham gia các hoạt động thể thao dưới nước.

                        <br><br>

                        Lăng Cô còn nổi tiếng với cảnh quan thiên nhiên tuyệt đẹp, 
                        bao gồm núi non hùng vĩ, rừng nguyên sinh và các đảo nhỏ xung quanh. 
                        Đây là một trong những điểm đến không thể bỏ qua khi du lịch Huế.
    `,
            iframe: "https://vr360.vietravel.net/vietnam/hue/lang-co/",
            background: "{{ asset('images/langco.jpg') }}"
        },
        {
            title: "Lăng Tự Đức",
            description: `Lăng Tự Đức là một trong những lăng tẩm nổi tiếng nhất tại Huế, 
                        được xây dựng vào năm 1864 dưới triều đại vua Tự Đức. Lăng nằm trên đồi Thiên Thọ, cách trung tâm thành phố khoảng 7 km về phía Tây.

                        <br><br>

                        Lăng Tự Đức nổi bật với kiến trúc độc đáo, kết hợp giữa phong cách kiến trúc phương Tây và phương Đông. 
                        Lăng có nhiều công trình kiến trúc đẹp mắt như điện Tự Đức, điện Thái Bình, điện Thái Hòa...

                        <br><br>

                        Lăng Tự Đức không chỉ là một công trình kiến trúc độc đáo mà còn mang đậm giá trị văn hóa và lịch sử của dân tộc Việt Nam.
    `,
            iframe: "https://vr360.vietravel.net/vietnam/hue/lang-tu-duc/",
            background: "{{ asset('images/tuduc.jpg') }}"
        },
        {
            title: "Khu phố Tây",
            description: `Khu phố Tây Huế là một trong những điểm đến hấp dẫn nhất tại thành phố Huế, 
                        nơi tập trung nhiều quán cà phê, nhà hàng, cửa hàng lưu niệm và các hoạt động giải trí thú vị.

                        <br><br>

                        Khu phố Tây nằm dọc bờ sông Hương, từ cầu Tràng Tiền đến cầu Phú Xuân. 
                        Đây là nơi lý tưởng để du khách thưởng thức ẩm thực đặc sản của Huế, tham gia các hoạt động văn hóa nghệ thuật và khám phá cuộc sống về đêm của thành phố.

                        <br><br>

                        Khu phố Tây Huế không chỉ là một địa điểm du lịch nổi tiếng mà còn là nơi giao lưu văn hóa giữa người dân địa phương và du khách quốc tế.
                    
    `,
            iframe: "https://vr360.vietravel.net/vietnam/hue/khu-pho-tay/",
            background: "{{ asset('images/hue/photayhue.jpg') }}"
        },
        {
            title: "Lăng Khải Định",
            description: `Lăng Khải Định là một trong những lăng tẩm nổi tiếng nhất tại Huế, 
                        được xây dựng vào năm 1920 dưới triều đại vua Khải Định. Lăng nằm trên đồi Châu Chữ, cách trung tâm thành phố khoảng 10 km về phía Tây.

                        <br><br>

                        Lăng Khải Định nổi bật với kiến trúc độc đáo, kết hợp giữa phong cách kiến trúc phương Tây và phương Đông. 
                        Lăng có nhiều công trình kiến trúc đẹp mắt như điện Khải Thành, điện Thái Bình, điện Thái Hòa...

                        <br><br>

                        Lăng Khải Định không chỉ là một công trình kiến trúc độc đáo mà còn mang đậm giá trị văn hóa và lịch sử của dân tộc Việt Nam.
                    
    `,
            iframe: "https://vr360.vietravel.net/vietnam/hue/lang-khai-dinh/",
            background: "{{ asset('images/langkhaidinh.jpg') }}"
        },

    ];

    let currentIndex = 0;

    const placeTitle = document.getElementById("placeTitle");
    const placeDescription = document.getElementById("placeDescription");
    const placeIframe = document.getElementById("placeIframe");
    const placeCard = document.getElementById("placeCard");
    const placetogo = document.getElementById("placetogo");

    function updatePlace(index) {
        const place = places[index];
        placeCard.classList.add("opacity-0");

        setTimeout(() => {
            placeTitle.textContent = place.title;
            placeDescription.innerHTML = place.description;
            placeIframe.src = place.iframe;
            placetogo.style.backgroundImage = `url('${place.background}')`;
            placeCard.classList.remove("opacity-0");
        }, 300);
    }

    function prevPlace() {
        currentIndex = (currentIndex - 1 + places.length) % places.length;
        updatePlace(currentIndex);
    }

    function nextPlace() {
        currentIndex = (currentIndex + 1) % places.length;
        updatePlace(currentIndex);
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("prevPlaceBtn").addEventListener("click", prevPlace);
        document.getElementById("nextPlaceBtn").addEventListener("click", nextPlace);
        updatePlace(currentIndex);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sections = document.querySelectorAll("section[id]");
        const navLinks = document.querySelectorAll("a[href^='#']");

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    const id = entry.target.getAttribute("id");
                    const link = document.querySelector(`a[href="#${id}"]`);
                    if (entry.isIntersecting) {
                        navLinks.forEach((l) => l.classList.remove("nav-glow"));
                        if (link) link.classList.add("nav-glow");
                    }
                });
            },
            { threshold: 0.5 }
        );

        sections.forEach((section) => observer.observe(section));
    });
</script>

<!-- Script to handle audio autoplay + mute -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const audio = document.getElementById("bg-music");
        const muteBtn = document.getElementById("mute-toggle");

        // Try autoplay after user interaction
        const tryPlay = () => {
            audio.play().catch(() => {});
            document.removeEventListener("click", tryPlay);
        };
        document.addEventListener("click", tryPlay);

        // Toggle mute/unmute
        muteBtn.addEventListener("click", () => {
            if (audio.paused) {
                audio.play();
                muteBtn.textContent = "🔊";
            } else {
                audio.pause();
                muteBtn.textContent = "🔇";
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bgMusic = document.getElementById("bg-music");
        bgMusic.volume = 0.9; // Set volume from 0.0 (silent) to 1.0 (full volume)
        bgMusic.play(); // Optional: autoplay if allowed
    });
</script>

@push('scripts')
    <script src="{{ asset('js/priceData.js') }}"></script>
    <script src="{{ asset('js/custom-tour.js') }}"></script>
@endpush