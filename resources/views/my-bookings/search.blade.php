@extends('layouts.app')

<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/beach7.mp4') }}" type="video/mp4">
    </video>
</div>

@section('content')
    <div class="relative z-10 flex items-center justify-center min-h-[60vh] px-4">
        <div class="bg-white shadow-xl rounded-lg p-8 max-w-xl w-full">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800"data-animate="animate__fadeIn">Tra Cứu Mã
                Đặt Tour</h1>

            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('my-bookings.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="search_type" value="booking">
                <div class="mb-4">
                    <label for="booking_code" class="block text-gray-700 mb-1">Mã đặt tour:</label>
                    <input type="text" name="code" id="booking_code"
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Tra
                    cứu</button>
            </form>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
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
