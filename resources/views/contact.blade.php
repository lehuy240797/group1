@extends('layouts.app')

<div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
    <video autoplay muted loop class="w-full h-full object-cover">
        <source src="{{ asset('/images/videos/beach7.mp4') }}" type="video/mp4">
    </video>
</div>
@section('content')
    <div class="max-w-screen-xl mx-auto px-6 md:px-12 lg:px-20 mt-12">
        <h1 class="text-4xl font-bold text-center mb-4">Contact Us</h1>
        <p class="text-center mb-8 text-lg text-gray-600">We'd love to hear from you! Send us your feedback or questions
            below.</p>

        @if (session('success'))
            <div class="bg-green-500 text-white text-center py-3 px-6 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <!-- Google Map -->
            <div class="relative w-full pt-[100%]">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.345265354938!2d106.68481267505258!3d10.783395889354193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528d4a6585219%3A0x33cb80de466dd220!2s541A%20%C4%90.%20Ho%C3%A0ng%20Sa%2C%20Ph%C6%B0%E1%BB%9Dng%2014%2C%20Qu%E1%BA%ADn%203%2C%20H%E1%BB%93%20Ch%C3%AD%20Minh%2C%20Vietnam!5e0!3m2!1sen!2s!4v1714422464198!5m2!1sen!2s"
                    class="absolute top-0 left-0 w-full h-full border-0 rounded-lg shadow-lg" allowfullscreen loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Feedback Form -->
            <div>
                <h2 class="text-2xl font-semibold mb-4">Gửi phản hồi</h2>

                <!-- Form for viewing feedback -->
                <form action="{{ route('feedback.submit') }}" method="POST" class="space-y-4 mt-4">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Mã đặt tour</label>
                        <input type="text" name="code" id="code" required
                            class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                            value="{{ old('code') }}" />
                    </div>
                    <input type="hidden" name="action" value="view" />
                    <button type="submit"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-lg transition">
                        Xem phản hồi
                    </button>
                </form>

                @if (isset($feedback))
                    <div class="space-y-2 text-gray-800 mt-4">
                        <p><span class="font-semibold">Loại tour:</span>
                            {{ isset($tourType) ? ($tourType === 'available' ? 'Tour có sẵn' : 'Tour tùy chỉnh') : 'Không xác định' }}
                        </p>
                        <p><span class="font-semibold">Tên:</span> {{ $feedback->name }}</p>
                        <p><span class="font-semibold">Tin nhắn:</span> {{ $feedback->message }}</p>
                        <p><span class="font-semibold">Xếp hạng:</span> {{ $feedback->rating }} sao</p>
                        @if ($feedback->admin_reply)
                            <p><span class="font-semibold">Phản hồi từ admin:</span> {{ $feedback->admin_reply }}</p>
                            <p><span class="font-semibold">Thời gian trả lời:</span> {{ $feedback->replied_at }}</p>
                        @else
                            <p class="italic text-gray-500">Chưa có phản hồi từ admin.</p>
                        @endif
                    </div>
                @endif

                @if (isset($canSubmit) && $canSubmit)
                    <!-- Form for submitting feedback -->
                    <form action="{{ route('feedback.submit') }}" method="POST" class="space-y-4 mt-4">
                        @csrf
                        <div>
                            <label for="code_submit" class="block text-sm font-medium text-gray-700 mb-1">Mã đặt
                                tour</label>
                            <input type="text" name="code" id="code_submit" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('code') }}" />
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên khách
                                hàng</label>
                            <input type="text" name="name" id="name" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('name') }}" />
                        </div>
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Xếp hạng</label>
                            <select name="rating" id="rating" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 sao</option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 sao</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 sao</option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 sao</option>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 sao</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Tin nhắn</label>
                            <textarea name="message" id="message" required rows="4"
                                class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">{{ old('message') }}</textarea>
                        </div>
                        <input type="hidden" name="action" value="submit" />
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                            Gửi phản hồi
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Team Section -->
        <div class="mt-16 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-8">Meet Our Team</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ([['name' => 'Gia Huy', 'img' => 'team1.jpg'], ['name' => 'Trung Anh', 'img' => 'team2.jpg'], ['name' => 'Quốc Khánh', 'img' => 'team3.jpg'], ['name' => 'Nhân', 'img' => 'team2.jpg']] as $member)
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
@endsection
