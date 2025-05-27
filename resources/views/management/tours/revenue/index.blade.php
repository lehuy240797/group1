@extends('layouts.app')

@section('content')
    @include('management.tours.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">📊 Quản Lý Doanh Thu</h1>

        <!-- Form lọc theo ngày -->
        <form method="GET"
            class="flex flex-col md:flex-row items-center justify-center gap-4 mb-10 bg-white shadow rounded-lg p-6">
            <div class="flex flex-col">
                <label for="start_date" class="text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                <input type="date" name="start_date" id="start_date"
                    value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}"
                    class="px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-col">
                <label for="end_date" class="text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}"
                    class="px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="pt-5">
                <button type="submit"
                    class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 shadow">
                    🔍 Lọc
                </button>
            </div>
        </form>

        <!-- Grid chính -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Cột trái: Bảng doanh thu theo ngày (2/3) -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-lg rounded-xl p-6 mb-10 overflow-x-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">📅 Danh Sách Doanh Thu Theo Ngày</h2>
                    @if (empty($dailyRevenue) || count($dailyRevenue) === 0)
                        <p class="text-gray-500">Không có dữ liệu doanh thu.</p>
                    @else
                        <table class="min-w-full table-auto border border-gray-300">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                    <th class="p-3 border-b border-gray-300">Ngày</th>
                                    <th class="p-3 border-b border-gray-300">Tổng số tiền (VND)</th>
                                    <th class="p-3 border-b border-gray-300">Số lượng tour</th>
                                    <th class="p-3 border-b border-gray-300">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailyRevenue as $day)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-3 border-b">{{ $day['date'] }}</td>
                                        <td class="p-3 border-b">{{ number_format($day['total_revenue'], 0, ',', '.') }}</td>
                                        <td class="p-3 border-b">{{ $day['count'] }}</td>
                                        <td class="p-3 border-b">
                                            <button class="text-blue-600 hover:underline view-details-btn"
                                                data-date="{{ $day['date'] }}">
                                                Xem
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Cột phải: Tổng quan doanh thu + Biểu đồ (1/3) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Tổng quan doanh thu -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">📈 Tổng Quan Doanh Thu</h2>
                    <div class="space-y-4">
                        <div class="bg-purple-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Tổng Doanh Thu</p>
                            <p class="text-xl font-bold text-purple-700">{{ number_format($totalRevenue, 0, ',', '.') }} VND
                            </p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Tour Có Sẵn</p>
                            <p class="text-xl font-bold text-blue-700">
                                {{ number_format($availableTourRevenue, 0, ',', '.') }} VND</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Tour Tự Tạo</p>
                            <p class="text-xl font-bold text-green-700">{{ number_format($customTourRevenue, 0, ',', '.') }}
                                VND</p>
                        </div>

                    </div>
                </div>

                <!-- Biểu đồ -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Biểu Đồ</h2>
                    <canvas id="revenueChart" class="mx-auto max-w-full h-64"></canvas>
                </div>
            </div>

        </div>


    </div>

    <!-- Modal chi tiết booking -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-4xl w-full max-h-[80vh] overflow-y-auto relative">
            <button id="closeModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Chi Tiết Booking Ngày <span id="modalDate"></span></h3>
            <div id="modalContent">
                <!-- Chi tiết các tour sẽ hiển thị ở đây -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Biểu đồ pie
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Tour Có Sẵn', 'Tour Tự Tạo'],
                    datasets: [{
                        label: 'Tỉ lệ doanh thu',
                        data: [
                            {{ $availableTourRevenue ?? 0 }},
                            {{ $customTourRevenue ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.6)', // blue-500
                            'rgba(34, 197, 94, 0.6)' // green-500
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(34, 197, 94, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    return context.label + ': ' + value + ' VND';
                                }
                            }
                        }
                    }
                }
            });

            // Dữ liệu booking gộp 2 loại (đã lấy từ PHP sang JS)
            const availableTourBookings = @json($availableTourBookings);
            const customTourBookings = @json($customTourBookings);

            // Hàm định dạng ngày tháng năm
            function formatDateDMY(dateStr) {
                let d = new Date(dateStr);
                let day = d.getDate().toString().padStart(2, '0');
                let month = (d.getMonth() + 1).toString().padStart(2, '0');
                let year = d.getFullYear();
                return `${day}/${month}/${year}`;
            }

            // Tạo event click cho nút xem
            document.querySelectorAll('.view-details-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const date = btn.getAttribute('data-date');
                    document.getElementById('modalDate').innerText = date;

                    // Lọc booking theo ngày
                    const availableForDate = availableTourBookings.filter(b => formatDateDMY(b.created_at) ===
                        date);
                    const customForDate = customTourBookings.filter(b => formatDateDMY(b.payment_date) ===
                        date);

                    let html = '';

                    if (availableForDate.length === 0 && customForDate.length === 0) {
                        html = '<p>Không có booking nào trong ngày này.</p>';
                    } else {
                        if (availableForDate.length > 0) {
                            html += '<h4 class="font-semibold text-blue-700 mb-2">Tour Có Sẵn</h4>';
                            html += '<table class="min-w-full border border-gray-300 mb-4 text-sm">';
                            html +=
                                '<thead><tr class="bg-gray-100"><th class="p-2 border border-gray-300">Mã Đặt Tour</th><th class="p-2 border border-gray-300">Số tiền (VND)</th><th class="p-2 border border-gray-300">Ngày thanh toán</th></tr></thead><tbody>';
                            availableForDate.forEach(b => {
                                html += `<tr>
                            <td class="p-2 border border-gray-300">${b.booking_code}</td>
                            <td class="p-2 border border-gray-300">${Number(b.total_price).toLocaleString('vi-VN')}</td>
                            <td class="p-2 border border-gray-300">${formatDateDMY(b.created_at)}</td>
                        </tr>`;
                            });
                            html += '</tbody></table>';
                        }

                        if (customForDate.length > 0) {
                            html += '<h4 class="font-semibold text-green-700 mb-2">Tour Tự Tạo</h4>';
                            html += '<table class="min-w-full border border-gray-300 text-sm">';
                            html +=
                                '<thead><tr class="bg-gray-100"><th class="p-2 border border-gray-300">Mã Đặt Tour</th><th class="p-2 border border-gray-300">Số tiền (VND)</th><th class="p-2 border border-gray-300">Ngày thanh toán</th></tr></thead><tbody>';
                            customForDate.forEach(b => {
                                html += `<tr>
                            <td class="p-2 border border-gray-300">${b.tracking_code}</td>
                            <td class="p-2 border border-gray-300">${Number(b.total_price).toLocaleString('vi-VN')}</td>
                            <td class="p-2 border border-gray-300">${formatDateDMY(b.payment_date)}</td>
                        </tr>`;
                            });
                            html += '</tbody></table>';
                        }
                    }

                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('detailModal').classList.remove('hidden');
                });
            });

            // Đóng modal
            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('detailModal').classList.add('hidden');
            });

            // Đóng modal khi click ngoài nội dung
            document.getElementById('detailModal').addEventListener('click', e => {
                if (e.target.id === 'detailModal') {
                    document.getElementById('detailModal').classList.add('hidden');
                }
            });
        </script>
    @endpush
@endsection
