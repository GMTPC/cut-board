<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function employeeshow()
    {
        // ดึงข้อมูลเฉพาะพนักงานที่อยู่ใน Line 3
        $employees = Employee::where('line', '3')->get();
    
        // ส่งข้อมูลไปยัง View
        return view('employees.show', compact('employees'));
    }

    // บันทึกข้อมูลพนักงาน
    public function employeesaveline3(Request $request)
{
    Log::info('Request data:', $request->all()); // เพิ่ม Log เพื่อตรวจสอบข้อมูล
    $validatedData = $request->validate([
        'employees' => 'required|array',
        'employees.*.name' => 'required|string|max:255',
        'employees.*.note' => 'nullable|string|max:255',
    ]);

    $createdEmployees = [];

    foreach ($validatedData['employees'] as $employeeData) {
        $createdEmployees[] = Employee::create([
            'name' => $employeeData['name'],
            'note' => $employeeData['note'],
            'line' => '3',
            'group_name' => null,
        ]);
    }

    return response()->json(['message' => 'บันทึกข้อมูลสำเร็จ!', 'employees' => $createdEmployees]);
}

    
    
    
public function employeesaveline2(Request $request)
{
    // ตรวจสอบข้อมูลที่จำเป็น
    $validatedData = $request->validate([
        'name' => 'required|string|max:255', // ชื่อพนักงานจำเป็นต้องกรอก
        'note' => 'nullable|string|max:255', // หมายเหตุเป็นค่าว่างได้
    ]);

    // เพิ่มค่า line เป็น '3' โดยอัตโนมัติ
    $validatedData['line'] = '2';

    // สร้างข้อมูลใหม่ในตาราง
    $employee = Employee::create($validatedData);

    // ส่งข้อมูลกลับไปยัง Frontend เป็น JSON
    return response()->json($employee);
}
public function employeesaveline1(Request $request)
{
    // ตรวจสอบข้อมูลที่จำเป็น
    $validatedData = $request->validate([
        'name' => 'required|string|max:255', // ชื่อพนักงานจำเป็นต้องกรอก
        'note' => 'nullable|string|max:255', // หมายเหตุเป็นค่าว่างได้
    ]);

    // เพิ่มค่า line เป็น '3' โดยอัตโนมัติ
    $validatedData['line'] = '1';

    // สร้างข้อมูลใหม่ในตาราง
    $employee = Employee::create($validatedData);

    // ส่งข้อมูลกลับไปยัง Frontend เป็น JSON
    return response()->json($employee);
}

    public function updateEmployee(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $employee->name = $request->name;
    $employee->note = $request->note;
    $employee->save();

    return response()->json($employee);
}


    // ฟังก์ชันสำหรับลบข้อมูลพนักงาน
    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
    
        return response()->json(['message' => 'Employee deleted successfully']);
    }
    public function showEmployees()
    {
        // ดึงข้อมูลเฉพาะพนักงานที่อยู่ใน Line 3
        $employees = Employee::where('line', '3')->get();
    
        // ส่งข้อมูลไปยัง View
        return view('employees.show', compact('employees'));
    } 
    
}
