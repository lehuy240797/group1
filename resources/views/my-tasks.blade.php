@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-4">Công việc của tôi</h1>

        @if ($assignedTours->isEmpty())
            <p class="text-gray-600">Hiện tại bạn chưa có công việc nào được phân công.</p>
        @else
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID Tour</th>
                        <th class="border border-gray-300 px-4 py-2">Mã Tour</th>
                        <th class="border border-gray-300 px-4 py-2">Tên Khách</th>
                        <th class="border border-gray-300 px-4 py-2">SĐT</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày Bắt Đầu</th>
                        <th class="border border-gray-300 px-4 py-2">Ngày Kết Thúc</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignedTours as $tour)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $tour->id }}</td>

                            {{-- Mã Tour --}}
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $tour->source === 'custom' ? 'CT-' : 'AT-' }}{{ $tour->id }}
                            </td>

                            {{-- Tên khách hàng --}}
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($tour->source === 'custom')
                                    {{ optional($tour->customTourBooking)->name ?? '---' }}
                                @else
                                    {{ optional($tour->availableTourBookings->first())->name ?? '---' }}
                                @endif
                            </td>

                            {{-- Số điện thoại --}}
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($tour->source === 'custom')
                                    {{ optional($tour->customTourBooking)->phone ?? '---' }}
                                @else
                                    {{ optional($tour->availableTourBookings->first())->phone ?? '---' }}
                                @endif
                            </td>

                            {{-- Ngày bắt đầu --}}
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $tour->start_date ? \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') : '---' }}
                            </td>

                            {{-- Ngày kết thúc --}}
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $tour->end_date ? \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') : '---' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
