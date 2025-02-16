<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\GroupEmp; // Import Model GroupEmp

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
    
    public function saveEmployees(Request $request)
    {
        $line = $request->query('line'); // รับค่าจาก query string
    
        try {
            // บันทึกข้อมูลพนักงาน
            if ($request->has('ue_name')) {
                foreach ($request->input('ue_name') as $index => $name) {
                    Employee::create([
                        'name' => $name,
                        'note' => $request->input("ue_remark.{$index}") ?? null,
                        'line' => $line,
                    ]);
                }
            }
    
            // ส่งสถานะสำเร็จกลับไป
            return response()->json([
                'status' => 'success',
                'message' => 'บันทึกข้อมูลพนักงานสำเร็จแล้ว!'
            ]);
        } catch (\Exception $e) {
            // ส่งสถานะข้อผิดพลาดกลับไป
            return response()->json([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()
            ]);
        }
        

    }
    
    public function delete($id)
    {
        try {
            $employee = Employee::findOrFail($id); // ค้นหาพนักงานตาม ID
            $employee->delete(); // ลบข้อมูลพนักงาน

            return response()->json(['message' => 'ลบข้อมูลสำเร็จ'], 200);
        } catch (\Exception $e) {
            \Log::error('ลบข้อมูลไม่สำเร็จ: ' . $e->getMessage()); // Log ข้อผิดพลาด
            return response()->json(['message' => 'ลบข้อมูลไม่สำเร็จ', 'error' => $e->getMessage()], 500);
        }
    }
    public function saveEmpGroup(Request $request, $line)
    {
        try {
            // รับข้อมูลจากฟอร์ม
            $emp1List = $request->input('eg_emp1'); // ชื่อพนักงานคนที่ 1
            $emp2List = $request->input('eg_emp2'); // ชื่อพนักงานคนที่ 2
    
            // ตรวจสอบข้อมูล
            if (empty($emp1List) || empty($emp2List) || count($emp1List) !== count($emp2List)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลพนักงานไม่ครบถ้วน'
                ], 400);
            }
    
            $date = now();
    
            // วนลูปเพื่อบันทึกข้อมูล
            foreach ($emp1List as $index => $emp1) {
                GroupEmp::create([
                    'emp1' => $emp1,
                    'emp2' => $emp2List[$index],
                    'line' => $line,
                    'date' => $date,
                    'status' => 1 // กำหนดให้เปิดใช้งานทันที
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลสำเร็จ'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function toggleStatus(Request $request)
    {
        try {
            // ค้นหา GroupEmp ตาม ID
            $groupEmp = GroupEmp::findOrFail($request->id);
    
            // อัปเดตสถานะ
            $groupEmp->update(['status' => $request->status]);
    
            return response()->json([
                'success' => true,
                'message' => $request->status == 1
                    ? "เปิดการใช้งาน {$groupEmp->emp1} - {$groupEmp->emp2} แล้ว"
                    : "ปิดการใช้งาน {$groupEmp->emp1} - {$groupEmp->emp2} แล้ว",
                'emp1' => $groupEmp->emp1,
                'emp2' => $groupEmp->emp2,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getEmpGroups($line)
    {
        // ดึงข้อมูลเฉพาะ line และ status = 1
        $empGroups = GroupEmp::where('line', $line)
            ->where('status', 1)
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('datawip', compact('empGroups'));
    }

    
    }
    
    
    
    
    



    
    
     
