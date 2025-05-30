@extends('layouts.app')

<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/beach8.mp4') }}" type="video/mp4">
    </video>
</div>

@section('content')
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="container max-w-3xl bg-white bg-opacity-95 rounded-2xl shadow-xl p-8 my-8">
            @if (isset($availableTourBookings))
                <div class="overflow-hidden">
                    <div class="px-6 py-5 text-center">
                        <h3 class="text-3xl font-bold text-gray-900">Thông Tin Chi Tiết Về Tour Của Bạn</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Mã đặt tour</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->booking_code }}
                                </dd>
                            </div>
                            <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Tên khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->name }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Email khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->email }}
                                </dd>
                            </div>
                            <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Số điện thoại khách hàng</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->phone }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Số lượng khách</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->num_guests }}
                                </dd>
                            </div>
                            <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Tổng giá</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ number_format($availableTourBookings->total_price, 0, ',', '.') }} VND
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Trạng thái thanh toán</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $availableTourBookings->payment_status }}
                                </dd>
                            </div>
                            <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-semibold text-gray-700">Ngày đặt</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($availableTourBookings->created_at)->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Tên Tour</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->name_tour ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Giá Tour</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ isset($availableTourBookings->availableTour->price) ? number_format($availableTourBookings->availableTour->price, 0, ',', '.') . ' VND' : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Ngày đi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->start_date ? \Carbon\Carbon::parse($availableTourBookings->availableTour->start_date)->format('d/m/Y') : 'Chưa xác định' }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Ngày về</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->end_date ? \Carbon\Carbon::parse($availableTourBookings->availableTour->end_date)->format('d/m/Y') : 'Chưa xác định' }}
                                    </dd>
                                </div>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Tên Tour Guide</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->tourguide->name ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Số điện thoại Tour Guide</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->tourguide->phone ?? 'N/A' }}
                                    </dd>
                                </div>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Tên Driver</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $availableTourBookings->availableTour->driver->name ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-semibold text-gray-700">Số điện thoại Driver</dt>
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
                    <div class="px-6 py-5 text-center">
                        <h2 class="text-3xl font-bold text-gray-900">Kết Quả Tra Cứu Đơn Hàng</h2>
                    </div>
                    <div class="bg-white rounded-lg p-6 space-y-4">
                        <p class="text-base"><span class="font-semibold text-gray-700">Mã đặt tour:</span> {{ $customTourBookings->tracking_code }}</p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Loại tour:</span>
                            {{ $customTourBookings->type === 'custom' ? 'Tour Tự Tạo' : 'Tour Có Sẵn' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Tên khách hàng:</span>
                            {{ optional($customTourBookings->customer)->name ?? 'Không có dữ liệu' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Email khách hàng:</span>
                            {{ optional($customTourBookings->customer)->email ?? 'Không có dữ liệu' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Số điện thoại khách hàng:</span>
                            {{ optional($customTourBookings->customer)->phone ?? 'Không có dữ liệu' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Tên tour:</span>
                            {{ $customTourBookings->customTour->destination ?? 'Không có dữ liệu' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Số tiền:</span>
                            {{ isset($customTourBookings->total_price) ? number_format($customTourBookings->total_price, 0, ',', '.') . ' VND' : 'Không có dữ liệu' }}
                        </p>
                        <p class="text-base"><span class="font-semibold text-gray-700">Trạng thái:</span>
                            @if ($customTourBookings->status === 'pending')
                                <span class="text-yellow-600 font-semibold">Đang chờ duyệt</span>
                            @elseif($customTourBookings->status === 'approved')
                                <span class="text-green-600 font-semibold">Đã duyệt</span>
                            @elseif($customTourBookings->status === 'rejected')
                                <span class="text-red-600 font-semibold">Bị từ chối</span>
                            @else
                                <span class="text-gray-600 font-semibold">Không xác định</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Nút quay lại -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('my-bookings') }}"
                   class="inline-block bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition text-base font-medium">Quay lại tra cứu</a>
            </div>
        </div>
    </div>
@endsection