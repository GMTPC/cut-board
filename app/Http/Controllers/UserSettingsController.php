<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user(); // ดึงข้อมูลผู้ใช้ปัจจุบัน
        return view('edit', compact('user')); // คืนค่าไปยัง view `settings.edit`
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
        ]);
    
        $user = Auth::user();
        $updatedFields = [];
    
        // ตรวจสอบการเปลี่ยนแปลง
        if ($user->name !== $request->name) {
            $user->name = $request->name;
            $updatedFields[] = 'name';
        }
    
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $updatedFields[] = 'email';
        }
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $updatedFields[] = 'password';
        }
    
        $user->save();
    
        // กรณีเปลี่ยนชื่อ
        if (in_array('name', $updatedFields)) {
            return redirect()
                ->route('settings.edit')
                ->with('swal', [
                    'title' => 'Success',
                    'text' => 'Name updated successfully!',
                    'icon' => 'success',
                    'redirect' => route('mainmenu'),
                ]);
        }
    
        // กรณีเปลี่ยนอีเมลหรือรหัสผ่าน
        if (in_array('email', $updatedFields) || in_array('password', $updatedFields)) {
            session()->flash('swal', [
                'title' => 'Warning',
                'text' => 'Email or password updated. Please login again.',
                'icon' => 'warning', // ใช้ warning สำหรับ SweetAlert
                'redirect' => route('login'),
                'logout' => true,
            ]);
    
            return redirect()->route('settings.edit');
        }
    
        return redirect()
            ->route('settings.edit')
            ->with('swal', [
                'title' => 'No Changes',
                'text' => 'No updates were made.',
                'icon' => 'info',
            ]);
    }
    
    
    
    

    
    
    public function destroy(Request $request)
{
    $user = Auth::user();

    // ลบผู้ใช้ออกจากฐานข้อมูล
    $user->delete();

    // Logout และ Redirect ไปยังหน้า login
    Auth::logout();

    return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
}

}
