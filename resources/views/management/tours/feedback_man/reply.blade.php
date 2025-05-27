@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 mt-10">
        <h1 class="text-3xl font-bold mb-6">Reply to Feedback</h1>

        @if (session('success'))
            <div class="alert alert-success bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <h3 class="text-lg font-medium">Feedback Details</h3>
            <p><strong>Customer:</strong> {{ $feedback->name }} ({{ $feedback->email }})</p>
            <p><strong>Tour:</strong> {{ $feedback->tour ? $feedback->tour->name_tour : 'N/A' }}</p>
            <p><strong>Booking Code:</strong> {{ $feedback->booking_code ?? 'N/A' }}</p>
            <p><strong>Rating:</strong> {{ $feedback->rating ? $feedback->rating . ' Stars' : 'N/A' }}</p>
            <p><strong>Message:</strong> {{ $feedback->message }}</p>

            <form method="POST" action="{{ route('admin.feedbacks.storeReply', $feedback->id) }}" class="mt-6">
                @csrf
                <div class="mb-4">
                    <label for="admin_reply" class="block text-sm font-medium text-gray-700">Your Reply</label>
                    <textarea
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        name="admin_reply" id="admin_reply" rows="5" placeholder="Write your reply" required></textarea>
                    @error('admin_reply')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="py-3 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Send Reply
                    </button>
                    <a href="{{ route('admin.feedbacks.index') }}"
                        class="py-3 px-6 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection