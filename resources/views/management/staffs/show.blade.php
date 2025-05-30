@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📋 Chi tiết Nhân viên</h1>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-5">
                <h3 class="text-xl font-semibold text-white">{{ $staff->name }}</h3>
                <p class="text-sm text-indigo-100 mt-1">Vai trò: <span class="font-medium">{{ ucfirst($staff->role) }}</span></p>
            </div>

            <div class="divide-y divide-gray-200">
                <dl class="text-sm text-gray-700">
                    <div class="grid grid-cols-3 gap-4 px-6 py-4 bg-gray-50">
                        <dt class="font-semibold text-gray-600"> Mã nhân viên</dt>
                        <dd class="col-span-2">{{ $staff->staff_code }}</dd>
                    </div>

                    <div class="grid grid-cols-3 gap-4 px-6 py-4 bg-white">
                        <dt class="font-semibold text-gray-600">Tên</dt>
                        <dd class="col-span-2">{{ $staff->name }}</dd>
                    </div>

                    <div class="grid grid-cols-3 gap-4 px-6 py-4 bg-gray-50">
                        <dt class="font-semibold text-gray-600">Số điện thoại</dt>
                        <dd class="col-span-2">{{ $staff->phone }}</dd>
                    </div>

                    <div class="grid grid-cols-3 gap-4 px-6 py-4 bg-white">
                        <dt class="font-semibold text-gray-600">Email</dt>
                        <dd class="col-span-2">{{ $staff->email }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50">
                <a href="{{ route('admin.staff.edit', $staff->id) }}"
                   class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition duration-150 ease-in-out">
                    Sửa
                </a>
                <a href="{{ route('admin.staff.index') }}"
                   class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition duration-150 ease-in-out">
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection
