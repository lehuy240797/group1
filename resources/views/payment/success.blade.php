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
            Cảm ơn bạn đã đặt tour với chúng tôi. Vui lòng kiểm tra email của bạn để nhận mã đặt tour.
        </p>

        <a href="{{ route('home') }}"
            class="inline-block mt-4 px-6 py-2 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition">
            Quay về trang chủ
        </a>
    </div>
</body>

</html>