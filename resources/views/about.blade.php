@extends('layouts.app')

@section('content')
<!-- Background Video -->
<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/video.mp4') }}" type="video/mp4">
    </video>
</div>

<!-- Optional: Dark overlay -->
<div class="fixed top-0 left-0 w-full h-full"></div>
    <div class="h-full w-full flex items-center justify-start px-8 md:px-20">

            <!-- Left: Text Content -->
            <div class="bg-white/40 backdrop-blur-sm p-8 rounded-xl max-w-2xl" data-animate="animate__fadeInUp">

                <h1 class="text-5xl font-bold mb-6">About Tourgether™</h1>
                <p class="text-xl leading-relaxed text-gray-600">
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
    <script src="//unpkg.com/alpinejs" defer></script>
    
@endsection
