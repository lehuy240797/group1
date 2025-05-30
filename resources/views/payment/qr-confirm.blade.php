<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-green-50 to-white min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md text-center border border-green-100">
        <div class="flex justify-center mb-4">
            <svg class="w-14 h-14 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-3xl font-extrabold text-green-600 mb-4">Xác nhận thanh toán</h1>

        <p class="text-gray-700 mb-2 text-lg">
            Số tiền: 
            <span class="font-bold text-xl text-gray-900">
                {{ number_format($data['total_price'], 0, ',', '.') }} VND
            </span>
        </p>

        <p class="text-gray-700 mb-6 text-lg">
            Tên liên hệ: 
            <span class="font-semibold text-gray-900">{{ $data['name'] }}</span>
        </p>

        <form id="confirmForm" method="POST" action="{{ route('payment.qr.confirm') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 text-white text-xl font-bold py-4 px-8 rounded-xl transition duration-200">
                    ✅ Tôi đã thanh toán
                </button>
            </div>
        </form>
    </div>

</body>
</html>
