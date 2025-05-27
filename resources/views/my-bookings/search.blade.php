@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-100">
        <div class="max-w-md mx-auto px-6 py-8 bg-white shadow-md rounded-lg border border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Tra cứu thông tin đặt tour</h2>

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
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Tra cứu</button>
            </form>
        </div>
    </div>
@endsection
