@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Phản hồi khách hàng</h1>

    <div class="bg-white shadow-sm rounded-lg p-5 space-y-3 border border-gray-200">
        <div class="text-sm text-gray-700">
            <p><span class="font-semibold">Tên khách hàng:</span> {{ $feedback->name }}</p>
            <p><span class="font-semibold">Mã đặt tour:</span> {{ $feedback->booking_code ?? $feedback->tracking_code }}</p>
            <p><span class="font-semibold">Xếp hạng:</span> {{ $feedback->rating }} sao</p>
            <p><span class="font-semibold">Tin nhắn:</span> {{ $feedback->message }}</p>
        </div>
    </div>

    <form action="{{ route('admin.feedbacks.reply', $feedback->id) }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="admin_reply" class="block text-sm font-medium text-gray-700 mb-1">Phản hồi của Admin</label>
            <textarea name="admin_reply" id="admin_reply" rows="5" required
                      class="w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 resize-y"></textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition duration-150">
                Gửi phản hồi
            </button>
        </div>
    </form>
</div>
@endsection
