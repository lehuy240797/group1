@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Sửa thông tin Nhân viên</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form id="edit-staff-form" action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Họ và tên</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $staff->name) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="name-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="staff_code" class="block text-gray-700 text-sm font-bold mb-2">Mã nhân viên <span class="text-gray-500 text-xs italic">(Ví dụ: T123)</span></label>
                    <input type="text" name="staff_code" id="staff_code" value="{{ old('staff_code', $staff->staff_code) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="staff_code-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email <span class="text-gray-500 text-xs italic">(Ví dụ: abc@tourgether.com)</span></label>
                    <input type="text" name="email" id="email" value="{{ old('email', $staff->email) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="email-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->phone) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="phone-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Vai trò</label>
                    <select name="role" id="role" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Chọn vai trò</option>
                        <option value="tourguide" {{ old('role', $staff->role) == 'tourguide' ? 'selected' : '' }}>Tour Guide</option>
                        <option value="driver" {{ old('role', $staff->role) == 'driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                    <p id="role-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu (để trống nếu không thay đổi)</label>
                    <input type="password" name="password" id="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="password-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p id="password_confirmation-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cập nhật</button>
                <a href="{{ route('admin.staff.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
                    Hủy
                </a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('edit-staff-form');
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

            let isNameValid = true;
            let isStaffCodeValid = true;
            let isEmailValid = true;
            let isPhoneValid = true;
            let isRoleValid = true;
            let isPasswordValid = true;
            let isPasswordConfirmationValid = true;


            function validateName() {
                const nameValue = nameInput.value.trim();
                const namePattern = /^[a-zA-Z\s]+$/;
                if (nameValue === '') {
                    nameError.textContent = 'Vui lòng nhập họ và tên.';
                    return false;
                } else if (!namePattern.test(nameValue)) {
                    nameError.textContent = 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.';
                    return false;
                } else {
                    nameError.textContent = '';
                    return true;
                }
            }

            function validateStaffCode() {
                const staffCodeValue = staffCodeInput.value.trim();
                const staffCodePattern = /^T\d{3}$/;
                if (staffCodeValue === '') {
                    staffCodeError.textContent = 'Vui lòng nhập mã nhân viên.';
                    return false;
                } else if (!staffCodePattern.test(staffCodeValue)) {
                    staffCodeError.textContent = 'Mã nhân viên phải có định dạng Txxx (ví dụ: T123).';
                    return false;
                } else {
                    staffCodeError.textContent = '';
                    return true;
                }
            }

            function validateEmail() {
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[a-zA-Z0-9._%+-]+@tourgether\.com$/;
                if (emailValue === '') {
                    emailError.textContent = 'Vui lòng nhập email.';
                    return false;
                } else if (!emailPattern.test(emailValue)) {
                    emailError.textContent = 'Email phải có định dạng abc@tourgether.com.';
                    return false;
                } else {
                    emailError.textContent = '';
                    return true;
                }
            }

            function validatePhone() {
                const phoneValue = phoneInput.value.trim();
                const phonePattern = /^\d{10}$/;
                if (phoneValue === '') {
                    phoneError.textContent = 'Vui lòng nhập số điện thoại.';
                    return false;
                } else if (!phonePattern.test(phoneValue)) {
                    phoneError.textContent = 'Số điện thoại phải là 10 chữ số.';
                    return false;
                } else {
                    phoneError.textContent = '';
                    return true;
                }
            }

            function validateRole() {
                if (roleSelect.value === '') {
                    roleError.textContent = 'Vui lòng chọn vai trò.';
                    return false;
                } else {
                    roleError.textContent = '';
                    return true;
                }
            }

            function validatePassword() {
                const passwordValue = passwordInput.value.trim();
                if (passwordValue === '') {
                    passwordError.textContent = '';
                    return true;
                } else if (passwordValue.length < 8) {
                    passwordError.textContent = 'Mật khẩu phải có ít nhất 8 ký tự.';
                    return false;
                }
                 else {
                    passwordError.textContent = '';
                    return true;
                }
            }

            function validatePasswordConfirmation() {
                const passwordValue = passwordInput.value.trim();
                const passwordConfirmationValue = passwordConfirmationInput.value.trim();
                if (passwordValue === '')
                {
                    passwordConfirmationError.textContent = '';
                    return true;
                }
                else if (passwordConfirmationValue === '') {
                    passwordConfirmationError.textContent = 'Vui lòng nhập xác nhận mật khẩu.';
                    return false;
                } else if (passwordConfirmationValue !== passwordValue) {
                    passwordConfirmationError.textContent = 'Xác nhận mật khẩu không khớp với mật khẩu.';
                    return false;
                } else {
                    passwordConfirmationError.textContent = '';
                    return true;
                }
            }


            nameInput.addEventListener('input', function () {
                isNameValid = validateName();
                toggleSubmitButtonState();
            });

            staffCodeInput.addEventListener('input', function () {
                isStaffCodeValid = validateStaffCode();
                toggleSubmitButtonState();
            });

            emailInput.addEventListener('input', function () {
                isEmailValid = validateEmail();
                toggleSubmitButtonState();
            });

            phoneInput.addEventListener('input', function () {
                isPhoneValid = validatePhone();
                toggleSubmitButtonState();
            });

            roleSelect.addEventListener('change', function () {
                isRoleValid = validateRole();
                toggleSubmitButtonState();
            });

            passwordInput.addEventListener('input', function () {
                isPasswordValid = validatePassword();
                isPasswordConfirmationValid = validatePasswordConfirmation();
                toggleSubmitButtonState();
            });

            passwordConfirmationInput.addEventListener('input', function () {
                isPasswordConfirmationValid = validatePasswordConfirmation();
                toggleSubmitButtonState();
            });

            function toggleSubmitButtonState() {
                submitButton.disabled = !(isNameValid && isStaffCodeValid && isEmailValid && isPhoneValid && isRoleValid && isPasswordValid && isPasswordConfirmationValid);
            }


            form.addEventListener('submit', function (event) {
                event.preventDefault();

                isNameValid = validateName();
                isStaffCodeValid = validateStaffCode();
                isEmailValid = validateEmail();
                isPhoneValid = validatePhone();
                isRoleValid = validateRole();
                isPasswordValid = validatePassword();
                isPasswordConfirmationValid = validatePasswordConfirmation();

                toggleSubmitButtonState();

                if (submitButton.disabled === false) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
