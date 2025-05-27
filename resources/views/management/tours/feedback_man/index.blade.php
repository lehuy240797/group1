@extends('layouts.app')

@section('content')
@include('management.tours.navigation')
    <div class="container mx-auto px-4 mt-10">
        <h1 class="text-3xl font-bold mb-6">Customer Feedbacks</h1>

        @if (session('success'))
            <div class="alert alert-success bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Reply</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($feedbacks as $feedback)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->name }} ({{ $feedback->email }})</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->tour ? $feedback->tour->name_tour : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->booking_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $feedback->rating ? $feedback->rating . ' Stars' : 'N/A' }}</td>
                            <td class="px-6 py-4">{{ Str::limit($feedback->message, 100) }}</td>
                            <td class="px-6 py-4">{{ $feedback->admin_reply ? Str::limit($feedback->admin_reply, 100) : 'Not replied' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (!$feedback->admin_reply)
                                    <a href="{{ route('admin.feedbacks.reply', $feedback->id) }}" class="text-blue-600 hover:text-blue-800">Reply</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection