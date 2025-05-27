<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thanh toán</title>
    @vite('resources/css/app.css') <!-- Nếu bạn dùng Vite -->
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-green-600 mb-6">Xác nhận thanh toán</h1>

        <p class="text-gray-700 mb-2">
            Số tiền:
            <span class="font-semibold text-lg">{{ number_format($data['total_price'], 0, ',', '.') }} VND</span>
        </p>
        <p class="text-gray-700 mb-6">
            Tên liên hệ:
            <span class="font-semibold">{{ $data['name'] }}</span>
        </p>

        <form id="confirmForm" method="POST" action="{{ route('payment.qr.confirm') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                Xác nhận đã thanh toán
            </button>
        </form>
    </div>


</body>

</html>
