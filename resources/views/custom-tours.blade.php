@extends('layouts.app')

@section('content')
    <div class="custom-tour-container">
        <div class="custom-tour-card">
            <h1 class="custom-tour-title">Hãy sáng tạo nơi bạn muốn đến</h1>
            <p class="text-gray-600 mb-6 text-center">Chúng tôi sẽ giúp bạn lên kế hoạch cho chuyến đi của mình.</p>

            <!-- Hiển thị thông báo lỗi/success -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#3085d6'
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#d33'
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('custom-tours.store') }}" class="space-y-6" id="custom-tour-form">
                @csrf

                <input type="hidden" name="priceData" id="priceData" value="">
                <input type="hidden" name="flight_price" id="flight_price" value="">

                <!-- Điểm đến -->
                <div>
                    <label for="destination" class="custom-tour-label">Điểm đến:</label>
                    <select name="destination" id="destination" class="custom-tour-select" required>
                        <option value="" disabled selected>Chọn điểm đến</option>
                        <option value="Hà Nội">Hà Nội</option>
                        <option value="Huế">Huế</option>
                        <option value="Đà Nẵng">Đà Nẵng</option>
                        <option value="Nha Trang">Nha Trang</option>
                        <option value="Phú Quốc">Phú Quốc</option>
                        <option value="SaPa">SaPa</option>
                    </select>
                    @error('destination')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div id="places-container" class="space-y-2" hidden>
                    <label class="custom-tour-label">Chọn nơi bạn muốn đến:</label>
                    <select name="places" id="places" class="custom-tour-select">
                        <option value="" disabled selected>-- Chọn --</option>
                    </select>
                </div>

                <!-- Ngày đi -->
                <div>
                    <label for="start_date" class="custom-tour-label">Ngày đi:</label>
                    <input type="date" name="start_date" id="start_date" required min="{{ now()->format('Y-m-d') }}"
                        class="custom-tour-input">
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Ngày về -->
                <div>
                    <label for="end_date" class="custom-tour-label">Ngày về:</label>
                    <input type="date" name="end_date" id="end_date" required
                        class="custom-tour-input">
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="custom-tour-label">Vé máy bay (đã bao gồm khứ hồi):</label>
                    <p id="flight-price" class="text-blue-600 font-semibold">0 VND</p>
                </div>

                <!-- Khách sạn -->
                <div id="hotel-container" class="space-y-2">
                    <label for="hotel" class="custom-tour-label">Khách sạn:</label>
                    <select name="hotel" id="hotel" class="custom-tour-select" required>
                        <option value="" disabled selected>Chọn khách sạn</option>
                    </select>
                </div>

                <div class="flex justify-between items-center space-x-4">
                    <!-- Vé người lớn -->
                    <div class="flex items-center space-x-2">
                        <label for="adult_tickets" class="custom-tour-label">Người lớn:</label>
                        <button type="button" onclick="adjustTicket('adult_tickets', 1)" class="custom-tour-ticket-btn">+</button>
                        <input type="number" name="adult_tickets" id="adult_tickets" value="0" min="0"
                            class="custom-tour-input w-20 text-center" readonly>
                        <button type="button" onclick="adjustTicket('adult_tickets', -1)" class="custom-tour-ticket-btn">-</button>
                    </div>

                    <!-- Vé trẻ em -->
                    <div class="flex items-center space-x-2">
                        <label for="child_tickets" class="custom-tour-label">Trẻ em:</label>
                        <button type="button" onclick="adjustTicket('child_tickets', 1)" class="custom-tour-ticket-btn">+</button>
                        <input type="number" name="child_tickets" id="child_tickets" value="0" min="0"
                            class="custom-tour-input w-20 text-center" readonly>
                        <button type="button" onclick="adjustTicket('child_tickets', -1)" class="custom-tour-ticket-btn">-</button>
                    </div>
                </div>

                <!-- Thông tin cá nhân -->
                <div>
                    <label for="name" class="custom-tour-label">Tên:</label>
                    <input type="text" name="name" id="name" required
                        class="custom-tour-input">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="custom-tour-label">Số điện thoại:</label>
                    <input type="text" name="phone" id="phone" required pattern="[0-9]{10,15}"
                        class="custom-tour-input" placeholder="VD: 0987654321">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="custom-tour-label">Email:</label>
                    <input type="email" name="email" id="email" required
                        class="custom-tour-input" placeholder="VD: example@gmail.com">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="custom-tour-total">
                    <p class="font-bold text-lg text-gray-700">Tổng chi phí:</p>
                    <p id="total-cost" class="text-2xl font-bold text-blue-600">
                        0 VND
                    </p>
                </div>

                <!-- Nút gửi -->
                <div class="text-center">
                    <input type="hidden" name="type" value="custom">
                    <button type="submit" class="custom-tour-submit">
                        Tiếp tục
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/priceData.js') }}"></script>
    <script src="{{ asset('js/custom-tour.js') }}"></script>
@endpush