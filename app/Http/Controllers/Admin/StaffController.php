<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // Import Rule class

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $loggedInUserId = auth()->id();

    $query = User::whereIn('role', ['tourguide', 'driver'])
        ->where('id', '!=', $loggedInUserId);

    // Thêm filter theo tên nếu có
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // Thêm filter theo số điện thoại nếu có
    if ($request->filled('phone')) {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }

    // Thêm filter theo mã nhân viên nếu có
    if ($request->filled('staff_code')) {
        $query->where('staff_code', 'like', '%' . $request->staff_code . '%');
    }

    $staff = $query->get();

    return view('management.staffs.index', compact('staff'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staff = null;
        return view('management.staffs.create', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@tourgether\.com$/', // Thêm regex cho email
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:tourguide,driver',
            'phone' => 'nullable|string|max:20',
            'staff_code' => ['required', 'string', 'max:20', 'regex:/^T\d{3}$/', 'unique:users,staff_code'], // Thêm regex cho staff_code và unique rule
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'staff_code' => $request->staff_code,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Nhân viên đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $staff)
    {
        return view('management.staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        return view('management.staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        if ($staff->id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể sửa tài khoản của chính mình.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@tourgether\.com$/', 'unique:users,email,' . $staff->id], // Thêm regex cho email và exclude current user
            'role' => 'required|in:tourguide,driver',
            'phone' => 'nullable|string|max:20',
            'staff_code' => ['required', 'string', 'max:20', 'regex:/^T\d{3}$/', Rule::unique('users')->ignore($staff->id)], // Thêm regex và exclude current user
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;
        $staff->phone = $request->phone;
        $staff->staff_code = $request->staff_code;

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Thông tin nhân viên đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Nhân viên đã được xóa thành công!');
    }
}
