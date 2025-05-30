@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl flex justify-center font-bold mb-6">Thêm mới Nhân viên</h1>

    <div class="container mx-auto px-4 max-w-xl">
        <form id="create-staff-form" action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Họ và tên --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <p id="name-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Mã nhân viên --}}
            <div>
                <label for="staff_code" class="block text-sm font-medium text-gray-700 mb-1">
                    Mã nhân viên <span class="text-gray-400 text-xs">(VD: T123)</span>
                </label>
                <input type="text" id="staff_code" name="staff_code" value="{{ old('staff_code') }}" disabled
                       class="w-full rounded border-gray-300 shadow-sm bg-gray-100">
                <p id="staff_code-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-gray-400 text-xs">(VD: abc@tourgether.com)</span>
                </label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" disabled
                       class="w-full rounded border-gray-300 shadow-sm bg-gray-100">
                <p id="email-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Số điện thoại --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" disabled
                       class="w-full rounded border-gray-300 shadow-sm bg-gray-100">
                <p id="phone-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Vai trò --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                <select name="role" id="role" required disabled
                        class="w-full rounded border-gray-300 shadow-sm bg-gray-100 ">
                    <option value="">Chọn vai trò</option>
                    <option value="tourguide" {{ old('role') == 'tourguide' ? 'selected' : '' }}>Tour Guide</option>
                    <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                </select>
                <p id="role-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Mật khẩu --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <input type="password" id="password" name="password" required disabled
                       class="w-full rounded border-gray-300 shadow-sm bg-gray-100">
                <p id="password-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required disabled
                       class="w-full rounded border-gray-300 shadow-sm bg-gray-100">
                <p id="password_confirmation-error" class="text-xs text-red-500 mt-1"></p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.staff.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                    Hủy
                </a>
                <button type="submit" disabled
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow disabled:opacity-50">
                    Thêm mới
                </button>
            </div>
        </form>
    </div>
</div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('create-staff-form');
            const nameInput = document.getElementById('name');
            const staffCodeInput = document.getElementById('staff_code');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const roleSelect = document.getElementById('role');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const submitButton = form.querySelector('button[type="submit"]');
            const nameError = document.getElementById('name-error');
            const staffCodeError = document.getElementById('staff_code-error');
            const emailError = document.getElementById('email-error');
            const phoneError = document.getElementById('phone-error');
            const roleError = document.getElementById('role-error');
            const passwordError = document.getElementById('password-error');
            const passwordConfirmationError = document.getElementById('password_confirmation-error');


            nameInput.addEventListener('input', function() {
                const nameValue = this.value.trim();
                const namePattern = /^[a-zA-Z0-9\s]+$/; // Cho phép chữ, số và khoảng trắng
                if (nameValue === '') {
                    nameError.textContent = 'Vui lòng nhập họ và tên.';
                    staffCodeInput.disabled = true;
                    staffCodeInput.value = '';
                } else if (!namePattern.test(nameValue)) {
                    nameError.textContent = 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.';
                    staffCodeInput.disabled = true;
                    staffCodeInput.value = '';
                } else {
                    nameError.textContent = '';
                    staffCodeInput.disabled = false;
                }
            });

            staffCodeInput.addEventListener('input', function() {
                const staffCodeValue = this.value.trim();
                const staffCodePattern = /^T\d{3}$/;
                if (staffCodeValue === '') {
                    staffCodeError.textContent = 'Vui lòng nhập mã nhân viên.';
                    emailInput.disabled = true;
                    emailInput.value = '';
                } else if (!staffCodePattern.test(staffCodeValue)) {
                    staffCodeError.textContent = 'Mã nhân viên phải có định dạng Txxx (ví dụ: T123).';
                    emailInput.disabled = true;
                    emailInput.value = '';
                } else {
                    staffCodeError.textContent = '';
                    emailInput.disabled = false;
                }
            });

            emailInput.addEventListener('input', function() {
                const emailValue = this.value.trim();
                const emailPattern = /^[a-zA-Z0-9._%+-]+@tourgether\.com$/;
                if (emailValue === '') {
                    emailError.textContent = 'Vui lòng nhập email.';
                    phoneInput.disabled = true;
                    phoneInput.value = '';
                } else if (!emailPattern.test(emailValue)) {
                    emailError.textContent = 'Email phải có định dạng abc@tourgether.com.';
                    phoneInput.disabled = true;
                    phoneInput.value = '';
                } else {
                    emailError.textContent = '';
                    phoneInput.disabled = false;
                }
            });

            phoneInput.addEventListener('input', function() {
                const phoneValue = this.value.trim();
                const phonePattern = /^\d{10}$/; // Đúng 10 số
                if (phoneValue === '') {
                    phoneError.textContent = 'Vui lòng nhập số điện thoại.';
                    roleSelect.disabled = true;
                    roleSelect.value = '';
                } else if (!phonePattern.test(phoneValue)) {
                    phoneError.textContent = 'Số điện thoại phải là 10 chữ số.';
                    roleSelect.disabled = true;
                    roleSelect.value = '';
                } else {
                    phoneError.textContent = '';
                    roleSelect.disabled = false;
                }
            });

            roleSelect.addEventListener('change', function() {
                if (this.value === '') {
                    roleError.textContent = 'Vui lòng chọn vai trò.';
                    passwordInput.disabled = true;
                    passwordInput.value = '';
                } else {
                    roleError.textContent = '';
                    passwordInput.disabled = false;
                }
            });

            passwordInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    passwordError.textContent = 'Vui lòng nhập mật khẩu.';
                    passwordConfirmationInput.disabled = true;
                    passwordConfirmationInput.value = '';
                } else {
                    passwordError.textContent = '';
                    passwordConfirmationInput.disabled = false;
                }
            });

            passwordConfirmationInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    passwordConfirmationError.textContent = 'Vui lòng nhập xác nhận mật khẩu.';
                    submitButton.disabled = true;
                } else if (this.value !== passwordInput.value) {
                    passwordConfirmationError.textContent = 'Xác nhận mật khẩu không khớp với mật khẩu.';
                    submitButton.disabled = true;
                } else {
                    passwordConfirmationError.textContent = '';
                    submitButton.disabled = false;
                }
            });


            form.addEventListener('submit', function(event) {
                event.preventDefault();

                let isValid = true;

                if (nameInput.value.trim() === '') {
                    nameError.textContent = 'Vui lòng nhập họ và tên.';
                    isValid = false;
                }
                if (!/^[a-zA-Z0-9\s]+$/.test(nameInput.value.trim())) {
                    nameError.textContent = 'Họ và tên chỉ được chứa chữ cái, số và khoảng trắng.';
                    isValid = false;
                }
                if (staffCodeInput.value.trim() === '') {
                    staffCodeError.textContent = 'Vui lòng nhập mã nhân viên.';
                    isValid = false;
                }
                if (!/^T\d{3}$/.test(staffCodeInput.value.trim())) {
                    staffCodeError.textContent = 'Mã nhân viên phải có định dạng Txxx (ví dụ: T123).';
                    isValid = false;
                }
                if (emailInput.value.trim() === '') {
                    emailError.textContent = 'Vui lòng nhập email.';
                    isValid = false;
                }
                if (!/^[a-zA-Z0-9._%+-]+@tourgether\.com$/.test(emailInput.value.trim())) {
                    emailError.textContent = 'Email phải có định dạng abc@tourgether.com.';
                    isValid = false;
                }
                if (phoneInput.value.trim() === '') {
                    phoneError.textContent = 'Vui lòng nhập số điện thoại.';
                    isValid = false;
                }
                if (!/^\d{10}$/.test(phoneInput.value.trim())) {
                    phoneError.textContent = 'Số điện thoại phải là 10 chữ số.';
                    isValid = false;
                }
                if (roleSelect.value === '') {
                    roleError.textContent = 'Vui lòng chọn vai trò.';
                    isValid = false;
                }
                if (passwordInput.value.trim() === '') {
                    passwordError.textContent = 'Vui lòng nhập mật khẩu.';
                    isValid = false;
                }
                if (passwordConfirmationInput.value.trim() === '') {
                    passwordConfirmationError.textContent = 'Vui lòng nhập xác nhận mật khẩu.';
                    isValid = false;
                }
                if (passwordConfirmationInput.value !== passwordInput.value) {
                    passwordConfirmationError.textContent = 'Xác nhận mật khẩu không khớp với mật khẩu.';
                    isValid = false;
                }

                if (isValid) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
