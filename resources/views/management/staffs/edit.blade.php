@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-3xl">
        <h1 class="text-2xl font-semibold mb-6">Sửa thông tin Nhân viên</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form id="edit-staff-form" action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="space-y-5" novalidate>
                @csrf
                @method('PUT')

                @php
                    // Define inputs once for easier repetition and consistency
                    $fields = [
                        'name' => ['label' => 'Họ và tên', 'type' => 'text', 'placeholder' => '', 'required' => true, 'pattern' => '^[a-zA-Z0-9\s]+$', 'error' => 'Họ và tên chỉ được chứa chữ cái và khoảng trắng.'],
                        'staff_code' => ['label' => 'Mã nhân viên', 'type' => 'text', 'placeholder' => 'Ví dụ: T123', 'required' => true, 'pattern' => '^T\d{3}$', 'error' => 'Mã nhân viên phải có định dạng Txxx (ví dụ: T123).'],
                        'email' => ['label' => 'Email', 'type' => 'email', 'placeholder' => 'Ví dụ: abc@tourgether.com', 'required' => true, 'pattern' => '^[a-zA-Z0-9._%+-]+@tourgether\.com$', 'error' => 'Email phải có định dạng abc@tourgether.com.'],
                        'phone' => ['label' => 'Số điện thoại', 'type' => 'tel', 'placeholder' => '', 'required' => false, 'pattern' => '^\d{10}$', 'error' => 'Số điện thoại phải là 10 chữ số.'],
                    ];
                @endphp

                @foreach ($fields as $field => $attr)
                    <div>
                        <label for="{{ $field }}" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ $attr['label'] }}
                            @if($attr['placeholder'])<span class="text-gray-500 text-xs italic">({{ $attr['placeholder'] }})</span>@endif
                        </label>
                        <input
                            type="{{ $attr['type'] }}"
                            name="{{ $field }}"
                            id="{{ $field }}"
                            value="{{ old($field, $staff->$field) }}"
                            {{ $attr['required'] ? 'required' : '' }}
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            autocomplete="off"
                            pattern="{{ $attr['pattern'] ?? '' }}"
                            >
                        <p id="{{ $field }}-error" class="text-red-500 text-xs italic mt-1"></p>
                    </div>
                @endforeach

                <div>
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Vai trò</label>
                    <select name="role" id="role" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Chọn vai trò</option>
                        <option value="tourguide" {{ old('role', $staff->role) == 'tourguide' ? 'selected' : '' }}>Tour Guide</option>
                        <option value="driver" {{ old('role', $staff->role) == 'driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                    <p id="role-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu (để trống nếu không thay đổi)</label>
                    <input type="password" name="password" id="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           autocomplete="new-password"
                           minlength="8"
                    >
                    <p id="password-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           autocomplete="new-password"
                    >
                    <p id="password_confirmation-error" class="text-red-500 text-xs italic mt-1"></p>
                </div>

                <div class="flex items-center">
                    <button type="submit" id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50" disabled>
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.staff.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-3">
                        Hủy
                    </a>
                </div>
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
                const namePattern = /^[a-zA-Z0-9\s]+$/; // Cho phép chữ, số và khoảng trắng
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
