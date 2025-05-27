@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Quản lý Nhân viên</h1>

    {{-- Hiển thị thông báo --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form tìm kiếm --}}
    <form method="GET" action="{{ route('admin.staff.index') }}" class="mb-6 flex space-x-4 items-center">
        <input type="text" name="name" placeholder="Tên nhân viên" value="{{ request('name') }}"
            class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <input type="text" name="phone" placeholder="Số điện thoại" value="{{ request('phone') }}"
            class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <input type="text" name="staff_code" placeholder="Mã nhân viên" value="{{ request('staff_code') }}"
            class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Tìm kiếm
        </button>
        <a href="{{ route('admin.staff.index') }}"
            class="ml-2 px-5 py-2 bg-orange-500 text-white rounded hover:bg-orange-700 transition duration-200">Xóa</a>
    </form>

    <a href="{{ route('admin.staff.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Thêm mới Nhân viên</a>

    @if($staff->isEmpty())
        <p>Chưa có nhân viên nào được tạo.</p>
    @else
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã nhân viên</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Số điện thoại</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vai trò</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $user)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->staff_code }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->phone }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->email }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($user->role) }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.staff.show', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">Xem</a>
                                <a href="{{ route('admin.staff.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">Sửa</a>
                                <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" class="inline delete-staff-form" data-staff-id="{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs delete-staff-button">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteStaffForms = document.querySelectorAll('.delete-staff-form');

        deleteStaffForms.forEach(form => {
            const deleteButton = form.querySelector('.delete-staff-button');
            if (deleteButton) {
                deleteButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa nhân viên này?',
                        text: "Hành động này sẽ không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Vâng, xóa!',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            }
        });
    });
</script>
@endsection
