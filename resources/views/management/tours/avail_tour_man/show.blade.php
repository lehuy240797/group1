@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        {{-- Tiêu đề --}}
        <h1 class="text-3xl font-bold text-center text-blue-800 mb-8">{{ $availableTour->name_tour }}</h1>
        {{-- Danh sách khách hàng --}}
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Danh sách khách hàng</h2>

            @if ($availableTourBookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Tên khách hàng</th>
                                <th class="px-6 py-3 text-left">Mã đặt tour</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Số điện thoại</th>
                                <th class="px-6 py-3 text-left">Số khách</th>
                                <th class="px-6 py-3 text-left">Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($availableTourBookings as $bookingItem)
                                <tr>
                                    <td class="px-6 py-4">{{ $bookingItem->name }}</td>
                                    <td class="px-6 py-4 font-mono text-blue-700">{{ $bookingItem->booking_code }}</td>
                                    <td class="px-6 py-4">{{ $bookingItem->email }}</td>
                                    <td class="px-6 py-4">{{ $bookingItem->phone }}</td>
                                    <td class="px-6 py-4">{{ $bookingItem->num_guests }}</td>
                                    <td class="px-6 py-4">{{ $bookingItem->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Không có khách hàng nào đặt tour này.</p>
            @endif
        </div>

        {{-- Nút quay lại --}}
        <div class="text-center">
            <a href="{{ route('admin.avail_tour_man.index') }}"
                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                Quay lại danh sách
            </a>
        </div>
    </div>
@endsection
