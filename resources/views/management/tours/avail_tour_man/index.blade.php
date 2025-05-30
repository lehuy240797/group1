@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @include('management.tours.navigation')

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="space-y-4 mb-6">
            <h1 class="text-2xl font-bold">Quản lý Tour</h1>
            <a href="{{ route('admin.avail_tour_man.create') }}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow transition">+
                Thêm Tour</a>
        </div>

        {{-- Filter/Search Form --}}
        <form method="GET" action="{{ route('admin.avail_tour_man.index') }}"
            class="bg-white p-6 rounded-lg shadow-md mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div>
                <label for="name_tour" class="block text-sm font-medium text-gray-700">Tên Tour</label>
                <input type="text" name="name_tour" id="name_tour" value="{{ request('name_tour') }}"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Ngày đi</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Ngày về</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Giá tối đa</label>
                <input type="number" name="price" id="price" value="{{ request('price') }}"
                    class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

<div class="w-full sm:w-auto">
            <label for="tourguide_id" class="block text-sm font-medium text-gray-700">Tour Guide</label>
            <select name="tourguide_id" id="tourguide_id" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-48">
                <option value="">All</option>
                @foreach (\App\Models\User::where('role', 'tourguide')->get() as $tourguide)
                    <option value="{{ $tourguide->id }}" {{ request('tourguide_id') == $tourguide->id ? 'selected' : '' }}>{{ $tourguide->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <label for="driver_id" class="block text-sm font-medium text-gray-700">Driver</label>
            <select name="driver_id" id="driver_id" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-48">
                <option value="">All</option>
                @foreach (\App\Models\User::where('role', 'driver')->get() as $driver)
                    <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

            <div class="col-span-full flex gap-4 mt-2">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow">Tìm
                    kiếm</button>
                <a href="{{ route('admin.avail_tour_man.index') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded shadow">Xóa lọc</a>
            </div>
        </form>

        {{-- Tours Table --}}
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-center text-gray-700 border-collapse">
                <thead class="bg-gray-100 uppercase text-xs text-gray-600 tracking-wider">
                    <tr>
                        <th class="px-3 py-2 text-center">ID</th>
                        <th class="px-4 py-2">Tên Tour</th>
                        <th class="px-3 py-2 text-right">Giá</th>
                        <th class="px-3 py-2 text-center">Ngày đi</th>
                        <th class="px-3 py-2 text-center">Ngày về</th>
                        <th class="px-3 py-2 text-center">Tối đa</th>
                        <th class="px-3 py-2 text-center">Đã đặt</th>
                        <th class="px-3 py-2">Phương tiện</th>
                        <th class="px-3 py-2">Khách sạn</th>
                        <th class="px-3 py-2">Hướng dẫn</th>
                        <th class="px-3 py-2">Tài xế</th>
                        <th class="px-3 py-2 text-center">Hành động</th>
                        <th class="px-3 py-2 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($tours as $availableTour)
                        <tr class="hover:bg-cyan-100 transition">
                            <td class="px-3 py-2 text-center">{{ $availableTour->id }}</td>
                            <td class="px-4 py-2">{{ $availableTour->name_tour }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($availableTour->price, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-center">
                                {{ $availableTour->start_date ? \Carbon\Carbon::parse($availableTour->start_date)->format('d/m/Y') : '' }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ $availableTour->end_date ? \Carbon\Carbon::parse($availableTour->end_date)->format('d/m/Y') : '' }}
                            </td>
                            <td class="px-3 py-2 text-center">{{ $availableTour->max_guests }}</td>
                            <td class="px-3 py-2 text-center">{{ $availableTour->booked_guests_count }}</td>
                            <td class="px-3 py-2 text-center">{{ $availableTour->transportation }}</td>
                            <td class="px-3 py-2 text-center">{{ $availableTour->hotel }}</td>
                            <td class="px-3 py-2 text-center">
                                {{ $availableTour->tourguide->name ?? 'Chưa phân công' }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ $availableTour->driver->name ?? 'Chưa phân công' }}
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex justify-center gap-2 text-white text-xs">
                                    <a href="{{ route('admin.avail_tour_man.show', $availableTour) }}"
                                        class="bg-blue-500 hover:bg-blue-600 p-2 rounded" title="Danh sách khách">
                                        <i class="fas fa-users"></i>
                                    </a>

                                    <a href="{{ route('admin.avail_tour_man.edit', $availableTour) }}"
                                        class="bg-green-500 hover:bg-green-600 p-2 rounded" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.avail_tour_man.destroy', $availableTour) }}"
                                        method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 p-2 rounded"
                                            title="Xóa tour">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                            <td class="px-3 py-2 text-center">
                                <form action="{{ route('admin.avail_tour_man.updateStatus', $availableTour->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="handleAvailableStatusChange(this)"
                                        class="available-status-select text-xs px-2 py-1 border rounded bg-white text-black focus:outline-none transition-all duration-300">
                                        <option value="not_started"
                                            {{ $availableTour->status == 'not_started' ? 'selected' : '' }}>Chưa bắt đầu
                                        </option>
                                        <option value="ongoing"
                                            {{ $availableTour->status == 'ongoing' ? 'selected' : '' }}>Đang
                                            diễn ra</option>
                                        <option value="completed"
                                            {{ $availableTour->status == 'completed' ? 'selected' : '' }}>Đã
                                            hoàn thành</option>
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
        function setAvailableStatusColor(select) {
            select.classList.remove('border-yellow-400', 'border-blue-500', 'border-green-500');

            switch (select.value) {
                case 'not_started':
                    select.classList.add('border-yellow-400'); // You can change to another color if you want
                    break;
                case 'ongoing':
                    select.classList.add('border-blue-500');
                    break;
                case 'completed':
                    select.classList.add('border-green-500');
                    break;
            }
        }

        function handleAvailableStatusChange(select) {
            setAvailableStatusColor(select);
            select.form.submit(); // submit after color change
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.available-status-select').forEach(setAvailableStatusColor);
        });
    </script>
@endsection
