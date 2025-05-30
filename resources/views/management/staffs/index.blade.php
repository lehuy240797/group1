@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6">Quản lý Nhân viên</h1>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.staff.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <input type="text" name="name" value="{{ request('name') }}" placeholder="Tên nhân viên" class="input-field" />
            <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Số điện thoại"
                class="input-field" />
            <input type="text" name="staff_code" value="{{ request('staff_code') }}" placeholder="Mã nhân viên"
                class="input-field" />
            <div class="flex items-center space-x-2">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    Tìm kiếm</button>
                <a href="{{ route('admin.staff.index') }}"
                    class="ml-2 px-5 py-2 bg-red-500 text-white font-bold rounded hover:bg-red-700 transition duration-200">Xóa</a>
            </div>
        </form>

        <div class="mb-6">
            <a href="{{ route('admin.staff.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow transition">+ Thêm Nhân viên</a>
        </div>

        {{-- Staff Table --}}
        @if($staff->isEmpty())
            <div class="text-gray-600">Chưa có nhân viên nào được tạo.</div>
        @else
            <div class="overflow-x-auto flex justify-center">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm justify-center">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="px-4 py-3 ">ID</th>
                            <th class="px-4 py-3 ">Mã nhân viên</th>
                            <th class="px-4 py-3 ">Tên</th>
                            <th class="px-4 py-3 ">Số điện thoại</th>
                            <th class="px-4 py-3 ">Email</th>
                            <th class="px-4 py-3 ">Vai trò</th>
                            <th class="px-4 py-3 ">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($staff as $user)
                            <tr class="hover:bg-gray-50 border-t">
                                <td class="px-4 py-3 text-center">{{ $user->id }}</td>
                                <td class="px-4 py-3 text-center">{{ $user->staff_code }}</td>
                                <td class="px-4 py-3 text-center">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-center">{{ $user->phone }}</td>
                                <td class="px-4 py-3 text-center">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-center">{{ ucfirst($user->role) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex space-x-2 justify-center">
                                        {{-- View --}}
                                        <a href="{{ route('admin.staff.show', $user->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 p-2 rounded text-white" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.staff.edit', $user->id) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 p-2 rounded text-white" title="Sửa">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST"
                                            class="delete-staff-form" data-staff-id="{{ $user->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="bg-red-500 hover:bg-red-600 p-2 rounded text-white"
                                                title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- SweetAlert Delete Confirm --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-staff-button').forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Hành động này không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Vâng, xóa!',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>



@endsection