<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 shadow rounded">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Đặt Tour: {{ $availableTour->name }}</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('booking.payment.available', $availableTour->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="num_adults" class="block text-sm font-medium text-gray-700">Số lượng người lớn</label>
                <input type="number" name="num_adults" id="num_adults" min="0" value="{{ old('num_adults', request()->input('num_adults', 0)) }}"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('num_adults')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="num_children" class="block text-sm font-medium text-gray-700">Số lượng trẻ em</label>
                <input type="number" name="num_children" id="num_children" min="0" value="{{ old('num_children', request()->input('num_children', 0)) }}"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('num_children')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                <input type="text" name="name" id="name"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input type="text" name="phone" id="phone"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       value="{{ old('phone') }}" required>
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-gray-700 font-medium">
                <strong>Tổng tiền:</strong> {{ request()->input('total_price', number_format($availableTour->price, 0, ',', '.')) }} VND
            </p>

            <div class="flex items-center space-x-3">
                <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                    Tiếp tục thanh toán
                </button>
                <a href="{{ route('tour.details', $availableTour->id) }}" class="text-gray-600 underline hover:text-gray-800">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</body>

</html>