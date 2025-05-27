@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Chọn hình thức thanh toán</h2>

            <p class="text-center text-gray-800 mb-6">
                <span class="font-medium">Tổng tiền:</span>
                <span class="text-lg text-green-600 font-semibold">
                    {{ number_format($bookingData['total_price'], 0, ',', '.') }} VND
                </span>
            </p>

            <form action="{{ route('payment.process') }}" method="POST" class="space-y-4">
                @csrf

                <label
                    class="flex items-center space-x-3 p-4 border border-gray-300 rounded-xl hover:border-blue-500 transition">
                    <input type="radio" name="payment_method" value="card" required class="form-radio text-blue-600">
                    <span class="text-gray-800">Thanh toán bằng thẻ Visa/Mastercard</span>
                </label>

                <label
                    class="flex items-center space-x-3 p-4 border border-gray-300 rounded-xl hover:border-blue-500 transition">
                    <input type="radio" name="payment_method" value="qr" required class="form-radio text-blue-600">
                    <span class="text-gray-800">Thanh toán bằng mã QR</span>
                </label>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition">
                    Tiếp tục
                </button>
            </form>
        </div>
    </div>
@endsection
