@extends('layouts.app')

<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/beach6.mp4') }}" type="video/mp4">
    </video>
</div>

@section('content')
<div class="relative z-10 container mx-auto py-8 max-w-lg">
    <div class="bg-white bg-opacity-90 shadow-xl rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-700" data-animate="animate__fadeIn">
            Hãy sáng tạo nơi bạn muốn đến
        </h1>
        <p class="text-gray-600 mb-6 text-center" data-animate="animate__fadeIn">
            Chúng tôi sẽ giúp bạn lên kế hoạch cho chuyến đi của mình.
        </p>

        <!-- Hiển thị thông báo lỗi/success -->
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            </script>
        @endif

        <form method="POST" action="{{ route('custom-tours.store') }}" class="space-y-4" id="custom-tour-form">
            @csrf

            <input type="hidden" name="price_data" id="priceData" value="">
            <input type="hidden" name="flight_price" id="flight_price" value="">

            <!-- Điểm đến và Khách sạn (2 cột ban đầu) -->
            <div class="grid grid-cols-2 gap-4" data-animate="animate__fadeInUp" style="animation-delay: 0.2s;" class="opacity-0">
                <div class="flex flex-col min-h-[80px]">
                    <label for="destination" class="block font-semibold text-gray-700 mb-1 text-sm">Điểm đến:</label>
                    <select name="destination" id="destination"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm box-border appearance-none">
                        <option value="" disabled selected>Chọn điểm đến</option>
                        <option value="Hà Nội">Hà Nội</option>
                        <option value="Huế">Huế</option>
                        <option value="Đà Nẵng">Đà Nẵng</option>
                        <option value="Nha Trang">Nha Trang</option>
                        <option value="Phú Quốc">Phú Quốc</option>
                        <option value="SaPa">SaPa</option>
                    </select>
                    @error('destination')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col min-h-[80px]">
                    <div id="hotel-container" class="space-y-1">
                        <label for="hotel" class="block font-semibold text-gray-700 mb-1 text-sm">Khách sạn:</label>
                        <select name="hotel" id="hotel"
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm box-border appearance-none">
                            <option value="" disabled selected>Chọn khách sạn</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Chọn nơi bạn muốn đến (xuất hiện sau khi chọn Điểm đến) -->
            <div id="places-container" class="space-y-2 hidden" data-animate="animate__fadeInUp" style="animation-delay: 0.4s;" class="opacity-0">
                <label class="block font-semibold text-gray-700">Chọn nơi bạn muốn đến:</label>
                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg"></div>
            </div>

            <!-- Ngày đi, Vé người lớn và Ngày về, Vé trẻ em (2 cột) -->
            <div class="grid grid-cols-2 gap-4" data-animate="animate__fadeInUp" style="animation-delay: 0.6s;" class="opacity-0">
                <div class="flex flex-col space-y-4">
                    <div>
                        <label for="start_date" class="block font-semibold text-gray-700 mb-1 text-sm">Ngày đi:</label>
                        <input type="date" name="start_date" id="start_date" required min="{{ now()->format('Y-m-d') }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm">
                        @error('start_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="adult_tickets" class="block font-semibold text-gray-700 mb-1 text-sm">Vé người lớn:</label>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="adjustTicket('adult_tickets', -1)"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-8 h-8 rounded-full flex items-center justify-center transition-all">-</button>
                            <input type="number" name="adult_tickets" id="adult_tickets" value="0" min="0" max="4"
                                class="border border-gray-300 rounded-lg px-3 py-2 w-16 text-center focus:ring-2 focus:ring-blue-500 h-10 text-sm"
                                oninput="updateTotalCost()">
                            <button type="button" onclick="adjustTicket('adult_tickets', 1)"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-8 h-8 rounded-full flex items-center justify-center transition-all">+</button>
                        </div>
                        @error('adult_tickets')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col space-y-4">
                    <div>
                        <label for="end_date" class="block font-semibold text-gray-700 mb-1 text-sm">Ngày về:</label>
                        <input type="date" name="end_date" id="end_date" required
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm">
                        @error('end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="child_tickets" class="block font-semibold text-gray-700 mb-1 text-sm">Vé trẻ em:</label>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="adjustTicket('child_tickets', -1)"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-8 h-8 rounded-full flex items-center justify-center transition-all">-</button>
                            <input type="number" name="child_tickets" id="child_tickets" value="0" min="0" max="4"
                                class="border border-gray-300 rounded-lg px-3 py-2 w-16 text-center focus:ring-2 focus:ring-blue-500 h-10 text-sm"
                                oninput="updateTotalCost()">
                            <button type="button" onclick="adjustTicket('child_tickets', 1)"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-8 h-8 rounded-full flex items-center justify-center transition-all">+</button>
                        </div>
                        @error('child_tickets')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Vé máy bay -->
            <div class="flex flex-col" data-animate="animate__fadeInUp" style="animation-delay: 0.8s;" class="opacity-0">
                <label class="block font-semibold text-gray-700 mb-1 text-sm">Vé máy bay (đã bao gồm khứ hồi):</label>
                <p id="flight-price" class="text-blue-600 font-semibold">0 VND</p>
            </div>

            <!-- Thông tin cá nhân -->
            <div data-animate="animate__fadeInUp" style="animation-delay: 1s;" class="opacity-0">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block font-semibold text-gray-700 mb-1 text-sm">Tên:</label>
                        <input type="text" name="name" id="name" required
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block font-semibold text-gray-700 mb-1 text-sm">Số điện thoại:</label>
                        <input type="text" name="phone" id="phone" required pattern="[0-9]{10,15}"
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm"
                            placeholder="VD: 0987654321">
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block font-semibold text-gray-700 mb-1 text-sm">Email:</label>
                        <input type="email" name="email" id="email" required
                            class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 h-10 text-sm"
                            placeholder="VD: example@gmail.com">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tổng chi phí -->
            <div class="mt-6 text-center" data-animate="animate__fadeInUp" style="animation-delay: 1.2s;" class="opacity-0">
                <p class="block font-semibold text-gray-700">Tổng chi phí:</p>
                <p id="total-cost" class="text-2xl font-bold text-blue-600">0 VND</p>
            </div>

            <!-- Nút submit -->
            <div class="text-center" data-animate="animate__fadeIn" style="animation-delay: 1.4s;" class="opacity-0">
                <input type="hidden" name="type" value="custom">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg focus:ring-2 focus:ring-blue-300 transition-all">
                    Tiếp tục
                </button>
            </div>
        </form>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
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
@push('scripts')
    <script src="{{ asset('js/priceData.js') }}"></script>
    <script src="{{ asset('js/custom-tour.js') }}"></script>
@endpush
@endsection