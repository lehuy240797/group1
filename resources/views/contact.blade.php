@extends('layouts.app')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 md:px-12 lg:px-20 mt-12">
    <h1 class="text-4xl font-bold text-center mb-4">Contact Us</h1>
    <p class="text-center mb-8 text-lg text-gray-600">We'd love to hear from you! Send us your feedback or questions below.</p>

    @if (session('success'))
        <div class="bg-green-500 text-white text-center py-3 px-6 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white text-center py-3 px-6 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
        <!-- Google Map -->
        <div class="relative w-full" style="padding-top: 100%;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.345265354938!2d106.68481267505258!3d10.783395889354193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528d4a6585219%3A0x33cb80de466dd220!2s541A%20%C4%90.%20Ho%C3%A0ng%20Sa%2C%20Ph%C6%B0%E1%BB%9Dng%2014%2C%20Qu%E1%BA%ADn%203%2C%20H%E1%BB%93%20Ch%C3%AD%20Minh%2C%20Vietnam!5e0!3m2!1sen!2s!4v1714422464198!5m2!1sen!2s"
                class="absolute top-0 left-0 w-full h-full border-0 rounded-lg shadow-lg"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <!-- Feedback Form -->
        <div class="w-full">
            <form method="POST" action="{{ route('feedback.submit') }}" id="feedbackForm" class="space-y-5 bg-white p-6 rounded-xl shadow-md">
                @csrf
                <div>
                    <label for="booking_code" class="block text-sm font-medium text-gray-700 mb-1">Booking Code hoặc Tracking Code</label>
                    <input type="text" id="booking_code" name="booking_code" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('booking_code')
                        <span class="text-red-500 text-sm mt-1 block bg-red-50 p-2 rounded">{{ $message }}</span>
                    @enderror
                </div>

                <div id="emailField" class="hidden">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block bg-red-50 p-2 rounded">{{ $message }}</span>
                    @enderror
                </div>

                <div id="ratingField" class="hidden">
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Đánh giá</label>
                    <select id="rating" name="rating" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Chọn số sao</option>
                        <option value="1">1 Sao</option>
                        <option value="2">2 Sao</option>
                        <option value="3">3 Sao</option>
                        <option value="4">4 Sao</option>
                        <option value="5">5 Sao</option>
                    </select>
                    @error('rating')
                        <span class="text-red-500 text-sm mt-1 block bg-red-50 p-2 rounded">{{ $message }}</span>
                    @enderror
                </div>

                <div id="messageField" class="hidden">
                    <label for="tour_message" class="block text-sm font-medium text-gray-700 mb-1">Your Feedback</label>
                    <textarea id="tour_message" name="tour_message" rows="4" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Write your feedback..."></textarea>
                    @error('tour_message')
                        <span class="text-red-500 text-sm mt-1 block bg-red-50 p-2 rounded">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="button" id="checkBookingCode"
                        class="flex-1 py-3 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition flex items-center justify-center">
                        <span id="checkButtonText">Check Code</span>
                        <svg id="checkLoading" class="hidden animate-spin h-5 w-5 ml-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="submit" id="submitFeedback"
                        class="flex-1 py-3 px-6 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition flex items-center justify-center"
                        disabled>
                        <span id="submitButtonText">Submit</span>
                        <svg id="submitLoading" class="hidden animate-spin h-5 w-5 ml-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="button" id="resetFeedback"
                        class="flex-1 py-3 px-6 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Team Section -->
    <div class="mt-16 text-center">
        <h3 class="text-3xl font-bold text-gray-800 mb-8">Meet Our Team</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ([
                ['name' => 'Gia Huy', 'img' => 'team1.jpg'],
                ['name' => 'Trung Anh', 'img' => 'team2.jpg'],
                ['name' => 'Quốc Khánh', 'img' => 'team3.jpg'],
                ['name' => 'Nhân', 'img' => 'team2.jpg'],
            ] as $member)
                <div class="text-center">
                    <img src="{{ asset('images/' . $member['img']) }}"
                        class="w-32 h-32 md:w-40 md:h-40 rounded-full mx-auto object-cover mb-3 shadow-md"
                        alt="{{ $member['name'] }}">
                    <h5 class="font-medium text-lg text-gray-800">{{ $member['name'] }}</h5>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- JS for form interaction -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkBookingCodeBtn = document.getElementById('checkBookingCode');
        const submitFeedbackBtn = document.getElementById('submitFeedback');
        const resetFeedbackBtn = document.getElementById('resetFeedback');
        const feedbackForm = document.getElementById('feedbackForm');
        const bookingCodeInput = document.getElementById('booking_code');
        const emailField = document.getElementById('emailField');
        const ratingField = document.getElementById('ratingField');
        const messageField = document.getElementById('messageField');
        const emailInput = document.getElementById('email');
        const ratingInput = document.getElementById('rating');
        const messageInput = document.getElementById('tour_message');
        const checkButtonText = document.getElementById('checkButtonText');
        const checkLoading = document.getElementById('checkLoading');
        const submitButtonText = document.getElementById('submitButtonText');
        const submitLoading = document.getElementById('submitLoading');

        function resetForm() {
            feedbackForm.reset();
            emailField.classList.add('hidden');
            ratingField.classList.add('hidden');
            messageField.classList.add('hidden');
            emailInput.disabled = true;
            ratingInput.disabled = true;
            messageInput.disabled = true;
            submitFeedbackBtn.disabled = true;
        }

        checkBookingCodeBtn.addEventListener('click', function () {
            const bookingCode = bookingCodeInput.value.trim();

            if (!bookingCode) {
                alert('Vui lòng nhập mã booking hoặc tracking code.');
                return;
            }

            checkButtonText.textContent = 'Checking...';
            checkLoading.classList.remove('hidden');
            checkBookingCodeBtn.disabled = true;

            fetch('{{ route('admin.feedback.check-booking') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ booking_code: bookingCode })
            })
                .then(response => response.json())
                .then(data => {
                    checkButtonText.textContent = 'Check Code';
                    checkLoading.classList.add('hidden');
                    checkBookingCodeBtn.disabled = false;

                    if (data.success && data.status === 'completed') {
                        emailField.classList.remove('hidden');
                        ratingField.classList.remove('hidden');
                        messageField.classList.remove('hidden');
                        emailInput.disabled = false;
                        ratingInput.disabled = false;
                        messageInput.disabled = false;
                        submitFeedbackBtn.disabled = false;
                        alert('Mã hợp lệ. Vui lòng điền thông tin để gửi feedback.');
                    } else {
                        resetForm();
                        alert(data.message || 'Tour chưa hoàn thành hoặc mã không hợp lệ.');
                    }
                })
                .catch(error => {
                    checkButtonText.textContent = 'Check Code';
                    checkLoading.classList.add('hidden');
                    checkBookingCodeBtn.disabled = false;
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                });
        });

        feedbackForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn load lại trang

            const formData = new FormData(feedbackForm);
            submitButtonText.textContent = 'Submitting...';
            submitLoading.classList.remove('hidden');
            submitFeedbackBtn.disabled = true;

            fetch('{{ route('feedback.submit') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json()) // Giả sử controller trả về JSON
                .then(data => {
                    submitButtonText.textContent = 'Submit';
                    submitLoading.classList.add('hidden');
                    submitFeedbackBtn.disabled = false;

                    if (data.success) {
                        alert('Cảm ơn bạn đã gửi feedback!');
                        resetForm();
                    } else {
                        alert(data.message || 'Đã xảy ra lỗi khi gửi feedback.');
                    }
                })
                .catch(error => {
                    submitButtonText.textContent = 'Submit';
                    submitLoading.classList.add('hidden');
                    submitFeedbackBtn.disabled = false;
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                });
        });

        resetFeedbackBtn.addEventListener('click', resetForm);
    });
</script>
@endsection