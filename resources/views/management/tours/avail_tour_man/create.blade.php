@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Thêm mới Tour</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.avail_tour_man.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name_tour" class="block text-gray-700 text-sm font-bold mb-2">Tên Tour</label>
                <select id="name_tour" name="name_tour" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Chọn tên Tour --</option>
                    <option value="Tp.HCM - Hà Nội" {{ old('name_tour') == 'Tp.HCM - Hà Nội' ? 'selected' : '' }}>Tp.HCM -
                        Hà Nội</option>
                    <option value="Tp.HCM - Đà Nẵng" {{ old('name_tour') == 'Tp.HCM - Đà Nẵng' ? 'selected' : '' }}>Tp.HCM -
                        Đà Nẵng</option>
                    <option value="Tp.HCM - Nha Trang" {{ old('name_tour') == 'Tp.HCM - Nha Trang' ? 'selected' : '' }}>
                        Tp.HCM - Nha Trang</option>
                    <option value="Tp.HCM - Huế" {{ old('name_tour') == 'Tp.HCM - Huế' ? 'selected' : '' }}>Tp.HCM - Huế
                    </option>
                    <option value="Tp.HCM - Sapa" {{ old('name_tour') == 'Tp.HCM - Sapa' ? 'selected' : '' }}>Tp.HCM - Sapa
                    </option>
                    <option value="Tp.HCM - Phú Quốc" {{ old('name_tour') == 'Tp.HCM - Phú Quốc' ? 'selected' : '' }}>Tp.HCM
                        - Phú Quốc</option>
                </select>

            </div>

            <div>
                <label for="duration" class="block text-gray-700 text-sm font-bold mb-2">Thời gian Tour:</label>
                <select name="duration" id="duration" onchange="generateItinerary()"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Chọn thời gian --</option>
                    <option value="3n2d" {{ old('duration') == '3n2d' ? 'selected' : '' }}>3 ngày 2 đêm</option>
                    <option value="4n3d" {{ old('duration') == '4n3d' ? 'selected' : '' }}>4 ngày 3 đêm</option>
                </select>
            </div>

            <div>
                <label for="itinerary" class="block text-gray-700 text-sm font-bold mb-2">Lịch trình chi tiết:</label>
                <textarea name="itinerary" id="itinerary" rows="6" readonly
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100 text-sm">{{ old('itinerary') }}</textarea>
            </div>

            <div>
                <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Địa điểm</label>
                <input type="text" name="location" id="location" readonly
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100"
                    value="{{ old('location') }}">
                <input type="hidden" name="location_value" id="location_value" value="{{ old('location_value') }}">
            </div>

            <div id="imagePreview" class="grid grid-cols-3 gap-4 mt-4">
            </div>

            <div>
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Mô tả</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="description" name="description">{{ old('description') }}</textarea>
            </div>
            <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Giá</label>
                <input type="number"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="price" name="price" value="{{ old('price') }}" required min="0">
            </div>



            <div>
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày đi:</label>
                <input type="date" name="start_date" id="start_date"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('start_date') }}">
            </div>

            <div>
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Ngày về:</label>
                <input type="date" name="end_date" id="end_date" readonly
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('end_date') }}">
            </div>

            <div>
                <label for="max_guests" class="block text-gray-700 text-sm font-bold mb-2">Số lượng khách tối đa:</label>
                <input type="number" name="max_guests" id="max_guests"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('max_guests') }}">
            </div>

            <div>
                <label for="transportation" class="block text-gray-700 text-sm font-bold mb-2">Phương tiện di
                    chuyển:</label>
                <select name="transportation" id="transportation"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Chọn phương tiện --</option>
                    <option value="Máy bay" {{ old('transportation') == 'Máy bay' ? 'selected' : '' }}>Máy bay</option>
                    <option value="Xe buýt" {{ old('transportation') == 'Xe buýt' ? 'selected' : '' }}>Xe buýt</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="hotel" class="block text-gray-700 text-sm font-bold mb-2">Khách sạn:</label>
                <select name="hotel" id="hotel"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">-- Chọn khách sạn --</option>
                </select>

                <<div>
                    <label for="tourguide_id" class="block text-gray-700 text-sm font-bold mb-2">Phân công
                        Tourguide:</label>
                    <select name="tourguide_id" id="tourguide_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="">-- Chọn Tourguide --</option>
                        @foreach ($tourguides as $tourguide)
                            <option value="{{ $tourguide->id }}"
                                {{ old('tourguide_id') == $tourguide->id ? 'selected' : '' }}>
                                {{ $tourguide->name }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div>
                <label for="driver_id" class="block text-gray-700 text-sm font-bold mb-2">Phân công Driver:</label>
                <select name="driver_id" id="driver_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">-- Chọn Driver --</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Lưu
                Tour</button>
            <a href="{{ route('admin.avail_tour_man.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Hủy</a>
        </form>
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

        // Ánh xạ tên tour với địa điểm và giá trị lưu vào DB
        const locationMap = {
            "Tp.HCM - Hà Nội": {
                display: "Hà Nội",
                value: "hanoi"
            },
            "Tp.HCM - Đà Nẵng": {
                display: "Đà Nẵng",
                value: "danang"
            },
            "Tp.HCM - Nha Trang": {
                display: "Nha Trang",
                value: "nhatrang"
            },
            "Tp.HCM - Huế": {
                display: "Huế",
                value: "hue"
            },
            "Tp.HCM - Sapa": {
                display: "Sapa",
                value: "sapa"
            },
            "Tp.HCM - Phú Quốc": {
                display: "Phú Quốc",
                value: "phuquoc"
            }
        };

        const nameTourSelect = document.getElementById('name_tour');
        const locationInput = document.getElementById('location');
        const locationValueInput = document.getElementById('location_value');
        const hotelSelect = document.getElementById('hotel');
        const imagePreview = document.getElementById('imagePreview');

        nameTourSelect.addEventListener('change', function() {
            const selectedTour = this.value;
            const selectedLocation = locationMap[selectedTour];

            // Cập nhật field Địa điểm
            if (selectedLocation) {
                locationInput.value = selectedLocation.display;
                locationValueInput.value = selectedLocation.value;

                // Cập nhật danh sách khách sạn
                hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';
                if (hotelOptions[selectedLocation.value]) {
                    hotelOptions[selectedLocation.value].forEach(hotel => {
                        const option = document.createElement('option');
                        option.value = hotel;
                        option.textContent = hotel;
                        hotelSelect.appendChild(option);
                    });
                }

                // Cập nhật hình ảnh
                imagePreview.innerHTML = '';
                if (images[selectedLocation.value]) {
                    images[selectedLocation.value].forEach(img => {
                        const imgElem = document.createElement('img');
                        imgElem.src = `/images/${selectedLocation.value}/${img}`;
                        imgElem.className = 'w-32 h-32 object-cover m-2';
                        imagePreview.appendChild(imgElem);
                    });
                }
            } else {
                locationInput.value = '';
                locationValueInput.value = '';
                hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';
                imagePreview.innerHTML = '';
            }

            // Gọi hàm generateItinerary để cập nhật lịch trình
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
                case '5n4d':
                    daysToAdd = 4;
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


            // Khởi tạo giá trị ban đầu nếu có old('name_tour')
            if (nameTourSelect.value) {
                const event = new Event('change');
                nameTourSelect.dispatchEvent(event);
            }
        });
    </script>
@endsection
