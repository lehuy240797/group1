<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt tour</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;">
        <h2 style="color: #2e7d32; text-align: center;">Xác nhận đặt tour thành công!</h2>
        <p>Xin chào {{ $bookingDetails['name'] }},</p>
        <p>Cảm ơn bạn đã đặt tour với chúng tôi. Dưới đây là thông tin đặt tour của bạn:</p>

        @if (isset($bookingDetails['booking_code']))
            <p><strong>Mã đặt tour:</strong> {{ $bookingDetails['booking_code'] }}</p>
        @elseif (isset($bookingDetails['tracking_code']))
            <p><strong>Mã đặt tour:</strong> {{ $bookingDetails['tracking_code'] }}</p>
        @endif

        <p><strong>Email:</strong> {{ $bookingDetails['email'] }}</p>
        <p><strong>Số điện thoại:</strong> {{ $bookingDetails['phone'] }}</p>
        <p><strong>Tổng giá:</strong> {{ number_format($bookingDetails['total_price'], 0, ',', '.') }} VND</p>

        <p>Vui lòng lưu giữ mã này để tra cứu thông tin đặt tour của bạn.</p>
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email: tourgethervn@gmail.com.</p>

        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/') }}" style="background-color: #2e7d32; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Quay về trang chủ
            </a>
        </p>
    </div>
</body>
</html>