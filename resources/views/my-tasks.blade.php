@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">Công việc của tôi</h1>

        @if ($tours->isEmpty())
            <p class="text-gray-600">Hiện tại bạn chưa có công việc nào được phân công.</p>
        @else
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID Tour</th>
                        <th class="border border-gray-300 px-4 py-2">Tên Tour</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày Bắt Đầu</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày Kết Thúc</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tours as $availableTour)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $availableTour->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $availableTour->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $availableTour->start_date ? \Carbon\Carbon::parse($availableTour->start_date)->format('d/m/Y') : '' }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $availableTour->end_date ? \Carbon\Carbon::parse($availableTour->end_date)->format('d/m/Y') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
