<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán bằng mã QR</title>
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Quét mã QR để thanh toán</h2>
        <img src="{{ $qrImage }}" alt="QR Code" class="mx-auto w-64 h-64 border rounded-lg">
        <p class="mt-4 text-gray-600">Hoặc sử dụng mã: <span
                class="font-mono font-semibold text-indigo-600">{{ $token }}</span></p>

        <div class="mt-6">
            <p class="text-sm text-gray-500">Thời gian còn lại để thanh toán:</p>
            <div id="countdown" class="text-3xl font-bold text-red-600">15:00</div>
        </div>
    </div>

    <script>
        let token = '{{ $token }}';
        let remainingTime = 900; // 15 phút = 900 giây

        const countdownTimer = setInterval(() => {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            document.getElementById('countdown').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (remainingTime <= 0) {
                clearInterval(countdownTimer);
                clearInterval(statusChecker);
                alert('Phiên thanh toán đã hết hạn. Vui lòng thực hiện lại!');
                window.location.href = '/payment';
            }

            remainingTime--;
        }, 1000);

        // Kiểm tra trạng thái thanh toán
        const statusChecker = setInterval(function() {
            $.get('/payment/status/' + token, function(data) {
                if (data.expired) {
                    clearInterval(statusChecker);
                    clearInterval(countdownTimer);
                    alert('Phiên thanh toán đã hết hạn.');
                    window.location.href = '/payment';
                }
                if (data.paid) {
                    clearInterval(statusChecker);
                    clearInterval(countdownTimer);
                    alert('Thanh toán thành công!');
                    window.location.href = data.redirect_url;
                }
            });
        }, 3000);
    </script>
</body>

</html>
