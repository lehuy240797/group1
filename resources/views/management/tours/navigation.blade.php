<!-- _navigation.blade.php -->
<div class="flex justify-center space-x-4 mt-6">
    <a href="{{ route('admin.avail_tour_man.index') }}"
        class="p-4 bg-blue-100 rounded hover:bg-blue-200 transition">Quản Lí Tour Có Sẵn</a>
    <a href="{{ route('admin.cust_tour_man.index') }}"
        class="p-4 bg-green-100 rounded hover:bg-green-200 transition">Quản Lí Tour Tự Tạo</a>
    <a href="{{ route('admin.revenue.index') }}"
        class="p-4 bg-purple-100 rounded hover:bg-purple-200 transition">Quản Lí Doanh Thu</a>
    <a href="{{ route('admin.feedbacks.index') }}"
        class="p-4 bg-yellow-100 rounded hover:bg-yellow-200 transition">Quản Lí Phản Hồi Khách Hàng</a>
</div>