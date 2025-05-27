<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-green-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-xl p-10 text-center max-w-md w-full">
        <div class="flex justify-center mb-6">
            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-green-600 mb-4">Thanh toán thành công!</h1>
        <p class="text-gray-700 text-lg mb-6">
            Cảm ơn bạn đã đặt tour với chúng tôi.
        </p>

        @if (isset($availableTourBookings->booking_code))
            <p class="text-gray-600 text-sm">Mã đặt tour của bạn là:</p>
            <h2 class="text-xl font-semibold text-indigo-600 tracking-wider mb-4">
                {{ $availableTourBookings->booking_code }}
            </h2>
        @elseif(isset($customTourBookings->tracking_code))
            <p class="text-gray-600 text-sm">Mã theo dõi tour tùy chỉnh của bạn:</p>
            <h2 class="text-xl font-semibold text-indigo-600 tracking-wider mb-4">
                {{ $customTourBookings->tracking_code }}
            </h2>
        @endif

        <a href="{{ route('home') }}"
            class="inline-block mt-4 px-6 py-2 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition">
            Quay về trang chủ
        </a>
    </div>
</body>

</html>
