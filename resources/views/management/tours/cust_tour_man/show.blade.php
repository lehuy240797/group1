
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @include('management.tours.navigation')
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Chi tiết Tour Khách Tự Tạo</h1>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto">
            <div class="space-y-8">
                <!-- Customer Information -->
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Thông tin khách hàng</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tên:</span>
                            <p class="text-gray-900">{{ $tour->customTourBooking->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <p class="text-gray-900">{{ $tour->customTourBooking->email ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Số điện thoại:</span>
                            <p class="text-gray-900">{{ $tour->customTourBooking->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Mã đặt tour:</span>
                            <p class="text-gray-900">{{ $tour->customTourBooking->tracking_code ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tour Information -->
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Thông tin tour</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Điểm đến:</span>
                            <p class="text-gray-900">{{ $tour->destination }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Ngày đi:</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Ngày về:</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Số vé:</span>
                            <p class="text-gray-900">{{ $tour->adult_tickets }} người lớn, {{ $tour->child_tickets }} trẻ em</p>
                        </div>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Chi tiết dịch vụ</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Khách sạn:</span>
                            <p class="text-gray-900">{{ $tour->hotel ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Địa điểm tham quan:</span>
                            <p class="text-gray-900">
                                @if ($places && count($places) > 0)
                                    {{ implode(', ', $places) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Giá vé máy bay (khứ hồi):</span>
                            <p class="text-gray-900">{{ number_format($tour->flight_price, 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tổng chi phí:</span>
                            <p class="text-gray-900 font-semibold">{{ number_format($tour->budget, 0, ',', '.') }} VND</p>
                        </div>
                    </div>
                </div>

                <!-- Assignments -->
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Phân công</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Hướng dẫn viên:</span>
                            <p class="text-gray-900">{{ $tour->tourGuide->name ?? 'Chưa phân công' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tài xế:</span>
                            <p class="text-gray-900">{{ $tour->driver->name ?? 'Chưa phân công' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Trạng thái</h2>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Trạng thái:</span>
                        <p class="inline-block">
                            @if ($tour->customTourBooking->status == 'pending')
                                <span class="text-yellow-600 font-medium">Đang chờ</span>
                            @elseif ($tour->customTourBooking->status == 'approved')
                                <span class="text-green-600 font-medium">Đã duyệt</span>
                            @else
                                <span class="text-red-600 font-medium">Bị từ chối</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('admin.cust_tour_man.index') }}"
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Quay lại
                </a>
            </div>
        </div>
    </div>
@endsection
