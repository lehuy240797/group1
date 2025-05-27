@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Thêm mới Nhân viên</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form id="create-staff-form" action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Họ và tên</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="name-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="staff_code" class="block text-gray-700 text-sm font-bold mb-2">Mã nhân viên <span class="text-gray-500 text-xs italic">(Ví dụ: T123)</span></label>
                    <input type="text" name="staff_code" id="staff_code" value="{{ old('staff_code') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" disabled>
                    <p id="staff_code-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email <span class="text-gray-500 text-xs italic">(Ví dụ: abc@tourgether.com)</span></label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" disabled
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="email-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" disabled
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="phone-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Vai trò</label>
                    <select name="role" id="role" required disabled
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Chọn vai trò</option>
                        <option value="tourguide" {{ old('role') == 'tourguide' ? 'selected' : '' }}>Tour Guide</option>
                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                    <p id="role-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                    <input type="password" name="password" id="password" required disabled
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="password-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required disabled
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="password_confirmation-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" disabled>Thêm mới</button>
                <a href="{{ route('admin.staff.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                    Hủy
                </a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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


            nameInput.addEventListener('input', function () {
                const nameValue = this.value.trim();
                const namePattern = /^[a-zA-Z\s]+$/; // Chỉ cho phép chữ và khoảng trắng
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

            staffCodeInput.addEventListener('input', function () {
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

            emailInput.addEventListener('input', function () {
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

            phoneInput.addEventListener('input', function () {
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


            form.addEventListener('submit', function (event) {
                event.preventDefault();

                let isValid = true;

                if (nameInput.value.trim() === '') {
                    nameError.textContent = 'Vui lòng nhập họ và tên.';
                    isValid = false;
                }
                if (!/^[a-zA-Z\s]+$/.test(nameInput.value.trim())) {
                    nameError.textContent = 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.';
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
