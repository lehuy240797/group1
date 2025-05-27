@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            {{-- Hình ảnh tour (Grid) --}}
            <div class="p-4">
                @php
                    $location = $availableTour->location; // Ví dụ: 'hanoi', 'danang'
                    $imagePath = public_path('images/' . $location);
                    $images = File::exists($imagePath) ? collect(File::files($imagePath))->map(fn($file) => $location . '/' . $file->getFilename())->toArray() : [];
                @endphp

                @if (!empty($images))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($images as $image)
                            <img src="{{ asset('images/' . $image) }}" alt="{{ $availableTour->name_tour }}"
                                 class="w-full h-32 object-cover rounded-lg shadow-sm hover:scale-105 transition-transform duration-200">
                        @endforeach
                    </div>
                @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center text-gray-500 text-lg rounded-lg">
                        Không có hình ảnh
                    </div>
                @endif
            </div>

            {{-- Nội dung chi tiết --}}
            <div class="p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $availableTour->name_tour }}</h1>

                {{-- Thông tin tổng quan --}}
                <div class="grid md:grid-cols-2 gap-6 text-gray-700 text-sm mb-6">
                    <p><i class="fas fa-map-marker-alt text-blue-500 mr-2"></i> <strong>Địa điểm:</strong> {{ $availableTour->location ?? 'Đang cập nhật' }}</p>
                    <p><i class="fas fa-clock text-yellow-500 mr-2"></i> <strong>Thời gian:</strong> {{ $availableTour->formatted_duration }}</p>
                    <p><i class="far fa-calendar-alt text-green-500 mr-2"></i> <strong>Lịch khởi hành:</strong>
                        @if ($availableTour->start_date && $availableTour->end_date)
                            {{ \Carbon\Carbon::parse($availableTour->start_date)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($availableTour->end_date)->format('d/m/Y') }}
                        @else
                            Chưa xác định
                        @endif
                    </p>
                    <p><i class="fas fa-users text-indigo-500 mr-2"></i> <strong>Số khách tối đa:</strong> {{ $availableTour->max_guests ?? 'Không giới hạn' }}</p>
                    <p><i class="fas fa-bus text-pink-500 mr-2"></i> <strong>Phương tiện:</strong> {{ $availableTour->transportation ?? 'Đang cập nhật' }}</p>
                    <p><i class="fas fa-hotel text-purple-500 mr-2"></i> <strong>Khách sạn:</strong> {{ $availableTour->hotel ?? 'Đang cập nhật' }}</p>
                    <p><i class="fas fa-user-tie text-orange-500 mr-2"></i> <strong>Tourguide:</strong> {{ $availableTour->tourguide ? $availableTour->tourguide->name : 'Chưa phân công' }}</p>
                    <p><i class="fas fa-steering-wheel text-gray-600 mr-2"></i> <strong>Driver:</strong> {{ $availableTour->driver ? $availableTour->driver->name : 'Chưa phân công' }}</p>
                </div>

                {{-- Lịch trình chi tiết --}}
                @if ($itineraryText)
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">🧭 Lịch trình chi tiết</h2>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 text-sm leading-relaxed whitespace-pre-wrap text-gray-800">
                            {{ $itineraryText }}
                        </div>
                    </div>
                @endif

                {{-- Giá và form đặt tour --}}
                <div class="mt-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Giá Tour</h2>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 mb-4">
                            <div>
                                <label class="block text-sm font-medium">Người lớn</label>
                                <div class="flex items-center mt-1">
                                    <span class="text-2xl font-bold text-red-500">{{ number_format($availableTour->price, 0, ',', '.') }} đ</span>
                                    <div class="ml-4 flex items-center">
                                        <button type="button" id="decreaseAdult" class="px-2 py-1 bg-gray-200 rounded-l">-</button>
                                        <input type="number" id="numAdults" name="num_adults" min="0" value="1" class="w-16 text-center border-t border-b border-gray-300">
                                        <button type="button" id="increaseAdult" class="px-2 py-1 bg-gray-200 rounded-r">+</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Trẻ em</label>
                                <div class="flex items-center mt-1">
                                    <span class="text-2xl font-bold text-red-500">{{ number_format($availableTour->price / 2, 0, ',', '.') }} đ</span>
                                    <div class="ml-4 flex items-center">
                                        <button type="button" id="decreaseChild" class="px-2 py-1 bg-gray-200 rounded-l">-</button>
                                        <input type="number" id="numChildren" name="num_children" min="0" value="0" class="w-16 text-center border-t border-b border-gray-300">
                                        <button type="button" id="increaseChild" class="px-2 py-1 bg-gray-200 rounded-r">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-lg font-medium text-gray-900">Tổng tiền: <span id="totalPrice" class="text-red-500 font-bold">{{ number_format($availableTour->price, 0, ',', '.') }} đ</span></p>
                        </div>
                        <form action="{{ route('booking.create', $availableTour->id) }}" method="GET" class="mt-6">
                            @csrf
                            <input type="hidden" name="num_adults" id="hiddenAdults">
                            <input type="hidden" name="num_children" id="hiddenChildren">
                            <input type="hidden" name="total_price" id="hiddenTotalPrice">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold text-base px-6 py-3 rounded-lg shadow transition-all duration-200">Đặt Tour</button>
                        </form>
                        <div class="mt-4 text-center">
                            <a href="{{ route('available-tours') }}" class="text-blue-600 hover:underline text-sm inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách tour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript để tính tổng giá và cập nhật form
        document.addEventListener('DOMContentLoaded', function() {
            const adultPrice = {{ $availableTour->price ?? 0 }};
            const childPrice = adultPrice / 2; // Giá trẻ em bằng nửa giá người lớn

            function updateTotal() {
                const numAdults = parseInt(document.getElementById('numAdults').value) || 0;
                const numChildren = parseInt(document.getElementById('numChildren').value) || 0;
                const total = (numAdults * adultPrice) + (numChildren * childPrice);
                document.getElementById('totalPrice').textContent = `${total.toLocaleString('vi-VN')} đ`;
                document.getElementById('hiddenAdults').value = numAdults;
                document.getElementById('hiddenChildren').value = numChildren;
                document.getElementById('hiddenTotalPrice').value = total;
            }

            document.getElementById('increaseAdult').addEventListener('click', function() {
                let value = parseInt(document.getElementById('numAdults').value) || 0;
                document.getElementById('numAdults').value = value + 1;
                updateTotal();
            });

            document.getElementById('decreaseAdult').addEventListener('click', function() {
                let value = parseInt(document.getElementById('numAdults').value) || 0;
                if (value > 0) document.getElementById('numAdults').value = value - 1;
                updateTotal();
            });

            document.getElementById('increaseChild').addEventListener('click', function() {
                let value = parseInt(document.getElementById('numChildren').value) || 0;
                document.getElementById('numChildren').value = value + 1;
                updateTotal();
            });

            document.getElementById('decreaseChild').addEventListener('click', function() {
                let value = parseInt(document.getElementById('numChildren').value) || 0;
                if (value > 0) document.getElementById('numChildren').value = value - 1;
                updateTotal();
            });

            // Cập nhật lần đầu
            updateTotal();

            // Cập nhật khi thay đổi giá trị input thủ công
            document.getElementById('numAdults').addEventListener('change', updateTotal);
            document.getElementById('numChildren').addEventListener('change', updateTotal);
        });
    </script>
@endsection