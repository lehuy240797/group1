@extends('layouts.app')

@section('content')
@include('management.tours.navigation')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Quản lý phản hồi</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg">
            <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                <tr>
                    <th class="px-4 py-3 border-b">Tên khách hàng</th>
                    <th class="px-4 py-3 border-b">Mã đặt tour</th>
                    <th class="px-4 py-3 border-b">Xếp hạng</th>
                    <th class="px-4 py-3 border-b">Tin nhắn</th>
                    <th class="px-4 py-3 border-b">Phản hồi</th>
                    <th class="px-4 py-3 border-b">Thao tác</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800">
                @foreach ($feedbacks as $feedback)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $feedback->name }}</td>
                        <td class="px-4 py-3">{{ $feedback->booking_code ?? $feedback->tracking_code }}</td>
                        <td class="px-4 py-3">{{ $feedback->rating }} sao</td>
                        <td class="px-4 py-3">{{ $feedback->message }}</td>
                        <td class="px-4 py-3">
                            {{ $feedback->admin_reply ?? 'Chưa trả lời' }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.feedbacks.reply', $feedback->id) }}"
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1 rounded">
                                Trả lời
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
