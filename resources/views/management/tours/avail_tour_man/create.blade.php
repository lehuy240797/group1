@extends('layouts.app')

@section('content')
   <div class="container mx-auto bg-gray-100 max-w-4xl p-6 border border-gray-300 rounded-lg shadow-sm">
        <h1 class="text-3xl font-bold p-2 text-gray-800 mb-6">Thêm mới Tour</h1>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.avail_tour_man.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên Tour -->
                <div>
                    <label for="name_tour" class="block text-sm font-medium text-gray-700 mb-1">Tên Tour</label>
                    <select id="name_tour" name="name_tour" required
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn tên Tour --</option>
                        <option value="Tp.HCM - Hà Nội" {{ old('name_tour') == 'Tp.HCM - Hà Nội' ? 'selected' : '' }}>Tp.HCM - Hà Nội</option>
                        <option value="Tp.HCM - Đà Nẵng" {{ old('name_tour') == 'Tp.HCM - Đà Nẵng' ? 'selected' : '' }}>Tp.HCM - Đà Nẵng</option>
                        <option value="Tp.HCM - Nha Trang" {{ old('name_tour') == 'Tp.HCM - Nha Trang' ? 'selected' : '' }}>Tp.HCM - Nha Trang</option>
                        <option value="Tp.HCM - Huế" {{ old('name_tour') == 'Tp.HCM - Huế' ? 'selected' : '' }}>Tp.HCM - Huế</option>
                        <option value="Tp.HCM - Sapa" {{ old('name_tour') == 'Tp.HCM - Sapa' ? 'selected' : '' }}>Tp.HCM - Sapa</option>
                        <option value="Tp.HCM - Phú Quốc" {{ old('name_tour') == 'Tp.HCM - Phú Quốc' ? 'selected' : '' }}>Tp.HCM - Phú Quốc</option>
                    </select>
                </div>

                <!-- Thời gian Tour -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Thời gian Tour</label>
                    <select name="duration" id="duration" onchange="generateItinerary()"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn thời gian --</option>
                        <option value="3n2d" {{ old('duration') == '3n2d' ? 'selected' : '' }}>3 ngày 2 đêm</option>
                        <option value="4n3d" {{ old('duration') == '4n3d' ? 'selected' : '' }}>4 ngày 3 đêm</option>
                    </select>
                </div>

                <!-- Địa điểm -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Địa điểm</label>
                    <input type="text" name="location" id="location" readonly
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 cursor-not-allowed"
                        value="{{ old('location') }}">
                    <input type="hidden" name="location_value" id="location_value" value="{{ old('location_value') }}">
                </div>

                <!-- Khách sạn -->
                <div>
                    <label for="hotel" class="block text-sm font-medium text-gray-700 mb-1">Khách sạn</label>
                    <select name="hotel" id="hotel"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn khách sạn --</option>
                    </select>
                </div>

                <!-- Ngày đi -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày đi</label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('start_date') }}">
                </div>

                <!-- Ngày về -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày về</label>
                    <input type="date" name="end_date" id="end_date" readonly
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 cursor-not-allowed"
                        value="{{ old('end_date') }}">
                </div>

                <!-- Số lượng khách tối đa -->
                <div>
                    <label for="max_guests" class="block text-sm font-medium text-gray-700 mb-1">Số lượng khách tối đa</label>
                    <input type="number" name="max_guests" id="max_guests"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('max_guests') }}">
                </div>

                <!-- Phương tiện di chuyển -->
                <div>
                    <label for="transportation" class="block text-sm font-medium text-gray-700 mb-1">Phương tiện di chuyển</label>
                    <select name="transportation" id="transportation"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn phương tiện --</option>
                        <option value="Máy bay" {{ old('transportation') == 'Máy bay' ? 'selected' : '' }}>Máy bay</option>
                        <option value="Xe buýt" {{ old('transportation') == 'Xe buýt' ? 'selected' : '' }}>Xe buýt</option>
                    </select>
                </div>

                <!-- Phân công Tourguide -->
                <div>
                    <label for="tourguide_id" class="block text-sm font-medium text-gray-700 mb-1">Phân công Tourguide</label>
                    <select name="tourguide_id" id="tourguide_id" required
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn Tourguide --</option>
                        @foreach ($tourguides as $tourguide)
                            <option value="{{ $tourguide->id }}"
                                {{ old('tourguide_id') == $tourguide->id ? 'selected' : '' }}>
                                {{ $tourguide->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Phân công Driver -->
                <div>
                    <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-1">Phân công Driver</label>
                    <select name="driver_id" id="driver_id" required
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn Driver --</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Giá -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá</label>
                    <input type="number" id="price" name="price" required min="0"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('price') }}">
                </div>
            </div>

            <!-- Lịch trình chi tiết -->
            <div>
                <label for="itinerary" class="block text-sm font-medium text-gray-700 mb-1">Lịch trình chi tiết</label>
                <textarea name="itinerary" id="itinerary" rows="6" readonly
                    class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 cursor-not-allowed">{{ old('itinerary') }}</textarea>
            </div>

            <!-- Mô tả -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea id="description" name="description"
                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            </div>

            <!-- Hình ảnh -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh</label>
                <div id="imagePreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.avail_tour_man.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Hủy</a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">Lưu Tour</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript giữ nguyên như code gốc
        const hotelOptions = {
            "hanoi": ["Mariott", "Lotte Hotel", "InterContinental"],
            "danang": ["Furama Resort", "Novotel", "Danang Golden Bay"],
            "nhatrang": ["Evason Ana Mandara", "Mia Resort", "InterContinental Nha Trang"],
            "hue": ["Pilgrimage Village", "La Residence Hue", "Indochine Palace"],
            "sapa": ["Topas Ecolodge", "Sapa Jade Hill", "Hotel de la Coupole"],
            "phuquoc": ["Vinpearl Resort", "InterContinental Phu Quoc", "Premier Village"]
        };

        const images = {
            hanoi: ["hanoi1.jpg", "hanoi2.jpg", "hanoi3.jpg", "hanoi4.jpg", "hanoi5.jpg", "hanoi6.jpeg"],
            danang: ["danang1.jpg", "danang2.jpg", "danang3.jpg", "bana1.jpg", "bana2.jpg", "bana3.jpg", "bana4.jpg",
                "sontra1.jpg", "sontra2.jpg", "sontra3.jpg"
            ],
            nhatrang: ["nhatrang1.jpg", "nhatrang2.jpg", "nhatrang3.jpg", "nhatrang4.jpg", "nhatrang5.jpg",
                "nhatrang6.png", "nhatrang7.jpg", "nhatrang8.jpg"
            ],
            hue: ["hue1.jpg", "hue2.jpg", "hue3.jpg", "hoian1.jpg", "hoian2.jpg", "hoian3.jpg", "hoian4.jpg",
                "thanhhue1.jpg", "thanhhue2.jpg", "thanhhue3.jpg"
            ],
            sapa: ["sapa1.jpg", "sapa2.jpg", "sapa3.jpg", "sapa4.jpg", "sapa5.jpg"],
            phuquoc: ["phuquoc1.jpg", "phuquoc2.jpg", "phuquoc3.jpg", "phuquoc4.jpg", "phuquoc5.jpg", "phuquoc6.jpg",
                "phuquoc7.jpg"
            ]
        };

        const locationMap = {
            "Tp.HCM - Hà Nội": { display: "Hà Nội", value: "hanoi" },
            "Tp.HCM - Đà Nẵng": { display: "Đà Nẵng", value: "danang" },
            "Tp.HCM - Nha Trang": { display: "Nha Trang", value: "nhatrang" },
            "Tp.HCM - Huế": { display: "Huế", value: "hue" },
            "Tp.HCM - Sapa": { display: "Sapa", value: "sapa" },
            "Tp.HCM - Phú Quốc": { display: "Phú Quốc", value: "phuquoc" }
        };

        const nameTourSelect = document.getElementById('name_tour');
        const locationInput = document.getElementById('location');
        const locationValueInput = document.getElementById('location_value');
        const hotelSelect = document.getElementById('hotel');
        const imagePreview = document.getElementById('imagePreview');

        nameTourSelect.addEventListener('change', function() {
            const selectedTour = this.value;
            const selectedLocation = locationMap[selectedTour];

            if (selectedLocation) {
                locationInput.value = selectedLocation.display;
                locationValueInput.value = selectedLocation.value;

                hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';
                if (hotelOptions[selectedLocation.value]) {
                    hotelOptions[selectedLocation.value].forEach(hotel => {
                        const option = document.createElement('option');
                        option.value = hotel;
                        option.textContent = hotel;
                        hotelSelect.appendChild(option);
                    });
                }

                imagePreview.innerHTML = '';
                if (images[selectedLocation.value]) {
                    images[selectedLocation.value].forEach(img => {
                        const imgElem = document.createElement('img');
                        imgElem.src = `/images/${selectedLocation.value}/${img}`;
                        imgElem.className = 'w-24 h-24 object-cover rounded-lg';
                        imagePreview.appendChild(imgElem);
                    });
                }
            } else {
                locationInput.value = '';
                locationValueInput.value = '';
                hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';
                imagePreview.innerHTML = '';
            }

            generateItinerary();
        });

        function getLocationKey(tourName) {
            return locationMap[tourName]?.value || null;
        }

        async function generateItinerary() {
            const tour = document.getElementById('name_tour').value;
            const duration = document.getElementById('duration').value;
            const itineraryBox = document.getElementById('itinerary');

            itineraryBox.value = '';

            const locationKey = getLocationKey(tour);
            if (!locationKey || !duration) return;

            try {
                const response = await fetch(`/data/itineraries/${locationKey}.json`);
                const data = await response.json();
                itineraryBox.value = data[duration] || 'Không tìm thấy lịch trình cho thời gian này.';
            } catch (error) {
                console.error("Lỗi tải lịch trình:", error);
                itineraryBox.value = 'Không thể tải lịch trình.';
            }
        }

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
                default:
                    break;
            }

            startDate.setDate(startDate.getDate() + daysToAdd);
            const formattedEndDate = startDate.toISOString().split('T')[0];
            endDateInput.value = formattedEndDate;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('start_date').addEventListener('change', calculateEndDate);
            document.getElementById('duration').addEventListener('change', calculateEndDate);
            document.getElementById('name_tour').addEventListener('change', generateItinerary);
            document.getElementById('start_date').addEventListener('change', function() {
                calculateEndDate();
                updateTourguidesAndDrivers();
            });
            document.getElementById('duration').addEventListener('change', function() {
                calculateEndDate();
                updateTourguidesAndDrivers();
            });

            if (nameTourSelect.value) {
                const event = new Event('change');
                nameTourSelect.dispatchEvent(event);
            }
        });
    </script>
@endsection