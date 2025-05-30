@extends('layouts.app') {{-- Đảm bảo bạn có layout chính sử dụng Tailwind --}}

@section('content')
    <!-- Background Video -->
    <div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
        <video autoplay muted loop class="w-full h-full object-cover">
            <source src="{{ asset('/images/videos/beach5.mp4') }}" type="video/mp4">
        </video>
    </div>

    <!-- Optional: Dark overlay -->
    <div class="fixed top-0 left-0 w-full h-full"></div>
    <div class="container mx-auto px-4 py-10">
        <div class="container mx-auto py-8">
            <h1 class="text-4xl font-bold text-center mb-6 ">Các Tour Hiện Có</h1>

            @if ($availableTours->isEmpty())
                <p class="text-gray-600">Hiện tại không có tour nào.</p>
            @else
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-white bg-opacity-20 rounded-xl shadow-lg p-8 z-10 relative">
                    @foreach ($availableTours as $availableTour)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-500 ease-in-out 
                   hover:-translate-y-1 hover:scale-[1.03] hover:shadow-[0_10px_20px_rgba(59,130,246,0.3)] opacity-0"data-animate="animate__fadeIn"
                            style="animation-delay: 0.4s;" class="opacity-0">
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    @php
                                        $imagePath = public_path('images/' . $availableTour->location);
                                        $images = File::exists($imagePath)
                                            ? collect(File::files($imagePath))->map(fn($file) => $file->getFilename())
                                            : [];
                                    @endphp

                                    @foreach ($images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('images/' . $availableTour->location . '/' . $image) }}"
                                                alt="Ảnh tour {{ $availableTour->name }}" class="w-full h-48 object-cover">
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $availableTour->name_tour }}</h3>
                                <p class="text-gray-700 text-sm mb-2">{{ Str::limit($availableTour->description, 100) }}</p>

                                <div class="mb-2">
                                    @php
                                        $durationMap = [
                                            '3n2d' => '3 ngày 2 đêm',
                                            '4n3d' => '4 ngày 3 đêm',
                                        ];
                                    @endphp
                                    <p class="text-gray-600 text-sm">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $durationMap[$availableTour->duration] ?? 'Chưa rõ thời lượng' }}
                                    </p>
                                </div>

                                <div class="mb-2">
                                    <p class="text-gray-600 text-sm">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $availableTour->start_date ? \Carbon\Carbon::parse($availableTour->start_date)->format('d/m/Y') : 'Chưa xác định' }}
                                        -
                                        {{ $availableTour->end_date ? \Carbon\Carbon::parse($availableTour->end_date)->format('d/m/Y') : 'Chưa xác định' }}
                                    </p>
                                </div>

                                <div class="mb-2">
                                    <p class="text-gray-600 text-sm">
                                        <i class="fas fa-users mr-1"></i> Số khách tối đa:
                                        {{ $availableTour->max_guests ?? 'Không giới hạn' }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-green-500 font-bold text-lg">{{ number_format($availableTour->price, 0, ',', '.') }}
                                        VNĐ</span>
                                    <a href="{{ route('tour.details', $availableTour->id) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm focus:outline-none focus:shadow-outline">Xem
                                        chi tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            var swiper = new Swiper(".mySwiper", {
                loop: true,
                autoplay: {
                    delay: 2000
                },
                speed: 1200,
                effect: "fade",
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
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
    @endsection
