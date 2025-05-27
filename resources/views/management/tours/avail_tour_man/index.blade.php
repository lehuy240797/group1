@extends('layouts.app')
@section('content')
    <div class="container mx-auto">
        @include('management.tours.navigation')
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <a href="{{ route('admin.avail_tour_man.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Thêm mới Tour</a>
        <form method="GET" action="{{ route('admin.avail_tour_man.index') }}" class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label for="name_tour" class="block text-sm font-medium text-gray-700">Tên Tour</label>
                <input type="text" name="name_tour" id="name_tour" value="{{ request('name_tour') }}"
                    class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-48">
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Ngày đi</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-48">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Ngày về</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-48">
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Giá tối đa</label>
                <input type="number" name="price" id="price" value="{{ request('price') }}"
                    class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-40">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded">Tìm
                    kiếm</button>

                <a href="{{ route('admin.avail_tour_man.index') }}"
                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Xóa</a>
            </div>
        </form>


        <table class="table-auto w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs select-none">
                    <th class="border border-gray-300 px-2 py-2 w-8 text-center">ID</th>
                    <th class="border border-gray-300 px-3 py-2 min-w-[180px]">Tên Tour</th>
                    <th class="border border-gray-300 px-3 py-2 w-20 text-center">Giá</th>
                    <th class="border border-gray-300 px-3 py-2 w-24 text-center">Ngày đi</th>
                    <th class="border border-gray-300 px-3 py-2 w-24 text-center">Ngày về</th>
                    <th class="border border-gray-300 px-3 py-2 w-12 text-center">Khách tối đa</th>
                    <th class="border border-gray-300 px-3 py-2 w-12 text-center">Đã đặt</th>
                    <th class="border border-gray-300 px-3 py-2 min-w-[120px]">Phương tiện</th>
                    <th class="border border-gray-300 px-3 py-2 min-w-[120px]">Khách sạn</th>
                    <th class="border border-gray-300 px-3 py-2 min-w-[120px]">Tourguide</th>
                    <th class="border border-gray-300 px-3 py-2 min-w-[120px]">Driver</th>
                    <th class="border border-gray-300 px-3 py-2 w-36 text-center">Hành động</th>
                    <th class="border border-gray-300 px-3 py-2 w-36 text-center">Trạng Thái</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tours as $availableTour)
                    <tr class="hover:bg-cyan-400">
                        <td class="border border-gray-300 px-2 py-1 text-center">{{ $availableTour->id }}</td>
                        <td class="border border-gray-300 px-3 py-1">{{ $availableTour->name_tour }}</td>
                        <td class="border border-gray-300 px-3 py-1 text-right">
                            {{ number_format($availableTour->price, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-3 py-1 text-center">
                            {{ $availableTour->start_date ? \Carbon\Carbon::parse($availableTour->start_date)->format('d/m/Y') : '' }}
                        </td>
                        <td class="border border-gray-300 px-3 py-1 text-center">
                            {{ $availableTour->end_date ? \Carbon\Carbon::parse($availableTour->end_date)->format('d/m/Y') : '' }}
                        </td>
                        <td class="border border-gray-300 px-3 py-1 text-center">{{ $availableTour->max_guests }}</td>
                        <td class="border border-gray-300 px-3 py-1 text-center">{{ $availableTour->booked_guests_count }}
                        </td>
                        <td class="border border-gray-300 px-3 py-1 text-center">{{ $availableTour->transportation }}</td>
                        <td class="border border-gray-300 px-3 py-1 text-center">{{ $availableTour->hotel }}</td>
                        <td class="border border-gray-300 px-3 py-1 text-center"  >
                            {{ $availableTour->tourguide ? $availableTour->tourguide->name : 'Chưa phân công' }}
                        </td>
                        <td class="border border-gray-300 px-3 py-1 text-center">
                            {{ $availableTour->driver ? $availableTour->driver->name : 'Chưa phân công' }}
                        </td>
                        <td class="border border-gray-300 px-3 py-1">
                            <div class="flex space-x-1 justify-center">
                                <a href="{{ route('admin.avail_tour_man.show', $availableTour) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded text-xs">DSKH</a>
                                <a href="{{ route('admin.avail_tour_man.edit', $availableTour) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-semibold py-1 px-2 rounded text-xs">SỬA</a>
                                <form action="{{ route('admin.avail_tour_man.destroy', $availableTour) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded text-xs">XÓA</button>
                                </form>
                            </div>
                        </td>
                        <td class="border border-gray-300 px-3 py-1 text-center">
                            <form action="{{ route('admin.avail_tour_man.updateStatus', $availableTour->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-xs font-semibold px-2 py-1 border border-gray-300 rounded bg-white cursor-pointer">
                                    <option value="not_started"
                                        {{ $availableTour->status == 'not_started' ? 'selected' : '' }}>Chưa bắt đầu
                                    </option>
                                    <option value="ongoing" {{ $availableTour->status == 'ongoing' ? 'selected' : '' }}>
                                        Đang diễn ra</option>
                                    <option value="completed"
                                        {{ $availableTour->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành
                                    </option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
