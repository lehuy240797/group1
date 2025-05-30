@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @include('management.tours.navigation')
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Quản lý Tour Khách Tự Tạo</h1>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Tên Khách</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Điện thoại</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Điểm đến</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ngày đi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ngày về</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Vé máy bay</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Ngân sách</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Mã Tạo Tour</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Tour Guide</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Driver</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($tours as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $item->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $item->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $item->email ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $item->phone ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $item->destination }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ number_format($item->flight_price, 0, ',', '.') }} VND</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ number_format($item->budget, 0, ',', '.') }} VND</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $item->tracking_code ?? '-' }}</td>
                            <td class="px-4 py-4">
                                <form method="POST" action="{{ route('admin.cust_tour_man.assignTourGuide', $item->id) }}">
                                    @csrf
                                    @if (isset($item->date_error))
                                        <div class="text-sm text-red-600 mb-2">{{ $item->date_error }}</div>
                                    @elseif ($item->availableTourGuides->isEmpty())
                                        <div class="text-sm text-red-600 mb-2">Không có Tour Guide khả dụng.</div>
                                    @else
                                        <select name="tourguide_id" onchange="this.form.submit()"
                                                class="block w-full min-w-[150px] max-w-[250px] border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 bg-white hover:bg-gray-50 transition-colors">
                                            <option value="">-- Chọn Tour Guide --</option>
                                            @foreach ($item->availableTourGuides as $guide)
                                                <option value="{{ $guide->id }}" {{ $item->tourguide_id == $guide->id ? 'selected' : '' }} class="truncate">
                                                    {{ $guide->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-sm text-gray-500 mt-1 truncate">
                                            {{ $item->tour_guide_name ?? 'Chưa phân công' }}
                                        </div>
                                    @endif
                                </form>
                            </td>
                            <td class="px-4 py-4">
                                <form method="POST" action="{{ route('admin.cust_tour_man.assignDriver', $item->id) }}">
                                    @csrf
                                    @if (isset($item->date_error))
                                        <div class="text-sm text-red-600 mb-2">{{ $item->date_error }}</div>
                                    @elseif ($item->availableDrivers->isEmpty())
                                        <div class="text-sm text-red-600 mb-2">Không có Driver khả dụng.</div>
                                    @else
                                        <select name="driver_id" onchange="this.form.submit()"
                                                class="block w-full min-w-[150px] max-w-[250px] border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 bg-white hover:bg-gray-50 transition-colors">
                                            <option value="">-- Chọn Driver --</option>
                                            @foreach ($item->availableDrivers as $driver)
                                                <option value="{{ $driver->id }}" {{ $item->driver_id == $driver->id ? 'selected' : '' }} class="truncate">
                                                    {{ $driver->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-sm text-gray-500 mt-1 truncate">
                                            {{ $item->driver_name ?? 'Chưa phân công' }}
                                        </div>
                                    @endif
                                </form>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.cust_tour_man.show', $item->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">Xem</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.cust_tour_man.destroy', $item->id) }}" method="POST" class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Xóa</button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <form method="POST" action="{{ route('admin.cust_tour_man.updateStatus', $item->id) }}">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()"
                                            class="block w-full min-w-[120px] max-w-[150px] border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2 px-3 bg-white hover:bg-gray-50 transition-colors {{ $item->status == 'approved' || $item->status == 'rejected' ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                                            {{ $item->status == 'approved' || $item->status == 'rejected' ? 'disabled' : '' }}
                                            title="{{ $item->status == 'approved' || $item->status == 'rejected' ? 'Trạng thái không thể thay đổi sau khi đã duyệt hoặc bị từ chối' : '' }}">
                                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                        <option value="approved" {{ $item->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                        <option value="rejected" {{ $item->status == 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lắng nghe sự kiện submit của các form có class delete-form
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Ngăn submit mặc định

                Swal.fire({
                    title: 'Bạn có chắc không?',
                    text: 'Bạn có chắc muốn xóa tour này? Hành động này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form nếu người dùng xác nhận
                    }
                });
            });
        });
    });
</script>
@endsection