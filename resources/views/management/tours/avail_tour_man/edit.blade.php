@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col items-center">
        <h1 class="text-3xl font-bold mb-6">Chỉnh sửa Tour</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 w-full max-w-2xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.avail_tour_man.update', $availableTour) }}" method="POST" class="w-full max-w-2xl bg-white p-6 rounded shadow space-y-5">
            @csrf
            @method('PUT')

            <!-- Các field ẩn -->
            <input type="hidden" name="name_tour" value="{{ old('name_tour', $availableTour->name_tour) }}">
            <input type="hidden" name="location_value" value="{{ old('location_value', $availableTour->location) }}">
            <input type="hidden" name="duration" id="duration" value="{{ old('duration', $availableTour->duration) }}">
            <input type="hidden" name="description" value="{{ old('description', $availableTour->description) }}">
            <input type="hidden" name="price" value="{{ old('price', $availableTour->price) }}">
            <input type="hidden" name="max_guests" value="{{ old('max_guests', $availableTour->max_guests) }}">
            <input type="hidden" name="transportation" value="{{ old('transportation', $availableTour->transportation) }}">
            <input type="hidden" name="image" value="{{ old('image', $availableTour->image) }}">

            <!-- Ngày đi -->
            <div>
                <label for="start_date" class="block text-gray-700 font-semibold mb-1">Ngày đi:</label>
                <input type="date" name="start_date" id="start_date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('start_date', $availableTour->start_date ? \Carbon\Carbon::parse($availableTour->start_date)->format('Y-m-d') : '') }}"
                    required>
            </div>

            <!-- Ngày về -->
            <div>
                <label for="end_date" class="block text-gray-700 font-semibold mb-1">Ngày về:</label>
                <input type="date" name="end_date" id="end_date" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2"
                    value="{{ old('end_date', $availableTour->end_date ? \Carbon\Carbon::parse($availableTour->end_date)->format('Y-m-d') : '') }}">
            </div>

            <!-- Khách sạn -->
            <div>
                <label for="hotel" class="block text-gray-700 font-semibold mb-1">Khách sạn:</label>
                <select name="hotel" id="hotel"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">-- Chọn khách sạn --</option>
                </select>
            </div>

            <!-- Tourguide -->
            <div>
                <label for="tourguide_id" class="block text-gray-700 font-semibold mb-1">Phân công Tourguide:</label>
                <select name="tourguide_id" id="tourguide_id"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">-- Chọn Tourguide --</option>
                    @foreach ($tourguides as $tourguide)
                        <option value="{{ $tourguide->id }}" {{ old('tourguide_id', $availableTour->tourguide_id) == $tourguide->id ? 'selected' : '' }}>
                            {{ $tourguide->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Driver -->
            <div>
                <label for="driver_id" class="block text-gray-700 font-semibold mb-1">Phân công Driver:</label>
                <select name="driver_id" id="driver_id"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">-- Chọn Driver --</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id', $availableTour->driver_id) == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                    Cập nhật Tour
                </button>
                <a href="{{ route('admin.avail_tour_man.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded shadow">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const hotelOptions = {
        "hanoi": ["Mariott", "Lotte Hotel", "InterContinental"],
        "danang": ["Furama Resort", "Novotel", "Danang Golden Bay"],
        "nhatrang": ["Evason Ana Mandara", "Mia Resort", "InterContinental Nha Trang"],
        "hue": ["Pilgrimage Village", "La Residence Hue", "Indochine Palace"],
        "sapa": ["Topas Ecolodge", "Sapa Jade Hill", "Hotel de la Coupole"],
        "phuquoc": ["Vinpearl Resort", "InterContinental Phu Quoc", "Premier Village"]
    };

    function calculateEndDate() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const duration = document.getElementById('duration').value;

        if (!startDateInput.value || !duration) return;

        const startDate = new Date(startDateInput.value);
        let daysToAdd = 0;

        switch (duration) {
            case '3n2d':
                daysToAdd = 2;
                break;
            case '4n3d':
                daysToAdd = 3;
                break;
        }

        startDate.setDate(startDate.getDate() + daysToAdd);
        const formattedEndDate = startDate.toISOString().split('T')[0];
        endDateInput.value = formattedEndDate;
    }

    function loadHotels() {
        const locationValue = '{{ old('location_value', $availableTour->location) }}';
        const hotelSelect = document.getElementById('hotel');
        hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';

        if (hotelOptions[locationValue]) {
            hotelOptions[locationValue].forEach(hotel => {
                const option = document.createElement('option');
                option.value = hotel;
                option.textContent = hotel;
                if (hotel === '{{ old('hotel', $availableTour->hotel) }}') {
                    option.selected = true;
                }
                hotelSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadHotels();
        calculateEndDate();
        document.getElementById('start_date').addEventListener('change', calculateEndDate);
    });
</script>
@endsection
