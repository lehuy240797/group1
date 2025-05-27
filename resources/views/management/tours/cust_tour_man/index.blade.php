@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @include('management.tours.navigation')
        <h1 class="text-2xl font-bold mb-4">Quản lý Tour Khách Tự Tạo</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="min-w-full border border-gray-300 shadow-sm rounded">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Tên Khách</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Điện thoại</th>
                    <th class="p-2 border">Điểm đến</th>
                    <th class="p-2 border">Ngày đi</th>
                    <th class="p-2 border">Ngày về</th>
                    <th class="p-2 border">Vé máy bay</th>
                    <th class="p-2 border">Ngân sách</th>
                    <th class="p-2 border">Hành động</th>
                    <th class="p-2 border">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tours as $tour)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $tour->id }}</td>
                        <td class="p-2 border">{{ $tour->customTourBooking->name ?? '-' }}</td>
                        <td class="p-2 border">{{ $tour->customTourBooking->email ?? '-' }}</td>
                        <td class="p-2 border">{{ $tour->customTourBooking->phone ?? '-' }}</td>
                        <td class="p-2 border">{{ $tour->destination }}</td>
                        <td class="p-2 border">{{ $tour->start_date }}</td>
                        <td class="p-2 border">{{ $tour->end_date }}</td>
                        <td class="p-2 border">{{ $tour->flight_price }}</td>
                        <td class="p-2 border">{{ number_format($tour->budget) }} VND</td>



                        <td class="p-2 border">
                            <a href="{{ route('admin.cust_tour_man.edit', $tour->id) }}"
                                class="text-blue-500 hover:underline">Sửa</a> |
                            <form action="{{ route('admin.cust_tour_man.destroy', $tour->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa tour này?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Xóa</button>
                            </form>
                        </td>
                        </td>
                        <td class="p-2 border">
                            <form method="POST" action="{{ route('admin.cust_tour_man.updateStatus', $tour->id) }}">
                                @csrf
                                @php
                                    $status = $tour->customTourBooking->status ?? 'pending';
                                @endphp
                                <select name="status" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg px-3 py-2">
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected
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
