@extends('layouts.app') <!-- Or whatever your layout is named -->

@section('title', 'Thanh toán bằng thẻ')
<div class="relative">
    <!-- Gradient Background -->
    <div class="absolute inset-0 z-[-1] bg-gradient-to-b from-blue-100 to-blue-300"></div>
@section('content')
<div class=" min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4 text-center text-blue-800">Thanh toán bằng thẻ Visa/Mastercard</h2>

        <p class="text-center text-gray-700 mb-6">
            <span class="font-semibold">Tổng tiền:</span>
            {{ number_format($bookingData['total_price'], 0, ',', '.') }} VND
        </p>

        <form action="{{ route('payment.confirm.card') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Số thẻ</label>
                <input type="text" id="card_number" name="card_number" value="4111111111111111"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                @error('card_number')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày hết hạn (MM/YY)</label>
                <input type="text" id="expiry_date" name="expiry_date" value="12/25"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                @error('expiry_date')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                <input type="text" id="cvv" name="cvv" value="123"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                @error('cvv')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Thanh toán
            </button>
        </form>
    </div>
</div>
@endsection
