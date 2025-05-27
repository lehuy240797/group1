@extends('layouts.app') {{-- Đảm bảo bạn có layout chính sử dụng Tailwind --}}

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-semibold mb-6">Các Tour Hiện Có</h1>

        @if ($availableTours->isEmpty())
            <p class="text-gray-600">Hiện tại không có tour nào.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($availableTours as $availableTour)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
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
@endsection
