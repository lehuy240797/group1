@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">

        <h2 class="text-2xl font-semibold mb-4">Danh sách khách hàng</h2>

        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc số điện thoại"
                class="border px-3 py-2 rounded w-1/3" />
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tìm kiếm</button>
            <a href="{{ route('admin.customers.index') }}"
                class="ml-2 px-5 py-2 bg-orange-500 text-white rounded hover:bg-orange-700 transition duration-200">
                Xóa
            </a>

        </form>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Tên</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Số điện thoại</th>
                    <th class="border px-4 py-2">Loại tour</th>
                    <th class="border px-4 py-2">Mã đặt tour</th>
                    <th class="border px-4 py-2">Tên tour</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td class="border px-4 py-2">{{ $customer->name }}</td>
                        <td class="border px-4 py-2">{{ $customer->email }}</td>
                        <td class="border px-4 py-2">{{ $customer->phone }}</td>
                        <td class="border px-4 py-2">{{ $customer->type }}</td>
                        <td class="border px-4 py-2">{{ $customer->booking_code }}</td>
                        <td class="border px-4 py-2">{{ $customer->tour_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border px-4 py-2 text-center">Không tìm thấy khách hàng</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $customers->withQueryString()->links() }}

    </div>
@endsection
