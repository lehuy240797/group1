@extends('layouts.app')

<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/beach8.mp4') }}" type="video/mp4">
    </video>
</div>

@section('content')
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="container max-w-3xl bg-white bg-opacity-90 rounded-xl shadow-2xl p-6 my-8">
            @if (isset($availableTourBookings))
                <div class="overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 text-center">
                        <h3 class="text-2xl font-bold text-gray-900">Thông Tin Chi Tiết Về Tour Của Bạn</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Mã đặt tour</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->booking_code }}</} 
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Tên khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->name }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Email khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->email }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Số điện thoại khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->phone }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Số lượng khách</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->num_guests }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Tổng giá</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->total_price }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Trạng thái thanh toán</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->payment_status }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Ngày đặt</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->created_at }}
                                </dd>
                            </div>

                            <div class="border-t border-gray-200">
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-700">Tên Tour</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->name_tour ?? 'N/A' }}
                                    </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Mô tả Tour</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->description ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Giá Tour</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->price ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Ngày đi</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->start_date ? \Carbon\Carbon::parse($availableTourBookings->availableTour->start_date)->format('d/m/Y') : 'Chưa xác định' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Ngày về</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->end_date ? \Carbon\Carbon::parse($availableTourBookings->availableTour->end_date)->format('d/m/Y') : 'Chưa xác định' }}
                                </dd>
                            </div>
                        </div>

                        <div class="border-t border-gray-200">
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Tên Tour Guide</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->tourguide->name ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Số điện thoại Tour Guide</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->tourguide->phone ?? 'N/A' }}
                                </dd>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Tên Driver</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->driver->name ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-700">Số điện thoại Driver</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->availableTour->driver->phone ?? 'N/A' }}
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        @elseif(isset($customTourBookings))
            <div class="overflow-hidden">
                <div class="px-4 py-5 sm:px-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Kết quả tra cứu đơn hàng</h2>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <p><strong class="text-gray-700">Mã đặt tour:</strong> {{ $customTourBookings->tracking_code }}</p>
                    <p><strong class="text-gray-700">Loại tour:</strong>
                        {{ $customTourBookings->type === 'custom' ? 'Tour Tự Tạo' : 'Tour có sẵn' }}
                    </p>
                    <p><strong class="text-gray-700">Tên khách hàng:</strong>
                        {{ optional($customTourBookings->customer)->name ?? 'Không có dữ liệu' }}
                    </p>
                    <p><strong class="text-gray-700">Email khách hàng:</strong>
                        {{ optional($customTourBookings->customer)->email ?? 'Không có dữ liệu' }}
                    </p>
                    <p><strong class="text-gray-700">Số điện thoại khách hàng:</strong>
                        {{ optional($customTourBookings->customer)->phone ?? 'Không có dữ liệu' }}
                    </p>
                    <p><strong class="text-gray-700">Tên tour:</strong>
                        {{ $customTourBookings->customTour->destination ?? 'Không có dữ liệu' }}
                    </p>
                    <p><strong class="text-gray-700">Số tiền:</strong>
                        {{ isset($customTourBookings->total_price) ? number_format($customTourBookings->total_price, 0, ',', '.') . ' VND' : 'Không có dữ liệu' }}
                    </p>
                    <p><strong class="text-gray-700">Trạng thái:</strong>
                        @if ($customTourBookings->status === 'pending')
                            <span class="text-yellow-500 font-bold">Đang chờ duyệt</span>
                        @elseif($customTourBookings->status === 'approved')
                            <span class="text-green-500 font-bold">Đã duyệt</span>
                        @elseif($customTourBookings->status === 'rejected')
                            <span class="text-red-500 font-bold">Bị từ chối</span>
                        @else
                            <span class="text-gray-500 font-bold">Không xác định</span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <!-- Nút quay lại -->
        <div class="mt-6">
            <a href="{{ route('my-bookings') }}"
               class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Quay lại tra cứu</a>
        </div>
    </div>
@endsection