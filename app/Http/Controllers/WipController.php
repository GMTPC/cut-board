<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC,GroupEmp,Skumaster,AmountNg};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WipController extends Controller
{
    public function insertWip(Request $request, $line, $work_id)
    {
        try {
            // ตรวจสอบว่า $line และ $work_id ถูกต้องหรือไม่
            if (!is_numeric($work_id) || !is_numeric($line)) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ข้อมูลไม่ถูกต้อง',
                    'message' => 'Line หรือ Work ID ไม่ถูกต้อง'
                ], 400);
            }
    
            // Debug ข้อมูล
            Log::info('Request Data:', $request->all());
            Log::info('Line:', ['line' => $line]);
            Log::info('Work ID:', ['work_id' => $work_id]);
    
            // เช็ค WorkProcess
            $workProcess = WorkProcessQC::find($work_id);
            if (!$workProcess) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล',
                    'message' => 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้'
                ], 400);
            }
    
            // Validate ข้อมูล
            $request->validate([
                'wip_barcode' => 'required|string|min:24',
                'wip_empgroup_id' => 'required|integer|min:1',
                'wp_working_id' => 'required|integer',
            ]);
    
            // ดึงข้อมูลจาก Request
            $input = $request->all();
            DB::beginTransaction();
    
            // ตัดบาร์โค้ด 11 ตัวแรกเพื่อค้นหา SKU_NAME
            $barcode11 = substr($input['wip_barcode'], 0, 11);
    
            // ดึง SKU_NAME จาก Skumaster
            $skuNameFull = Skumaster::where('SKU_CODE', $barcode11)->value('SKU_NAME');
            if (!$skuNameFull) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล SKU',
                    'message' => 'ไม่พบข้อมูลใน SKUMASTER ที่ตรงกับบาร์โค้ดนี้'
                ], 400);
            }
    
            // ตัดคำว่า "แผ่นรอคัด Line X" ออก
            $skuNameClean = preg_replace('/^แผ่นรอคัด\s*line\s*\d+\s*/iu', '', $skuNameFull);
            $skuName = mb_substr($skuNameClean, 0, 35);
    
            // ตัด 5 ตัวแรกออกจาก 11 ตัวแรก (ให้เหลือ 6 ตัวท้าย)
            $typeCode = substr($barcode11, 5);
    
            // คำนวณ pe_index ต่อจากเดิม
            $peIndex = ProductTypeEmp::max('pe_index') + 1;
    
            // ตรวจสอบว่าบาร์โค้ดซ้ำหรือไม่
            $existingWip = Wipbarcode::where('wip_barcode', $input['wip_barcode'])->first();
            if ($existingWip) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'title' => 'บาร์โค้ดซ้ำ',
                    'message' => 'บาร์โค้ดนี้ถูกบันทึกแล้ว'
                ], 400);
            }
    
            // บันทึกข้อมูลลง Wipbarcode
            Wipbarcode::create([
                'wip_barcode'    => $input['wip_barcode'],
                'wip_amount'     => (int) ltrim(substr($input['wip_barcode'], -3), '0'),
                'wip_working_id' => $input['wp_working_id'],
                'wip_empgroup_id'=> $input['wip_empgroup_id'],
                'wip_sku_name'   => $skuName,
                'wip_index'      => $peIndex,
            ]);
    
            // บันทึกข้อมูลลง ProductTypeEmp
            ProductTypeEmp::create([
                'pe_working_id' => $work_id,
                'pe_type_code'  => $typeCode,
                'pe_type_name'  => $skuName,
                'pe_index'      => $peIndex,
            ]);
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'title' => 'บันทึกเรียบร้อย',
                'message' => 'ข้อมูลถูกบันทึกสำเร็จ'
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
    
            return response()->json([
                'status' => 'error',
                'title' => 'เกิดข้อผิดพลาด',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    
    
  
    
    



    public function updateEmpGroup(Request $request, $id)
    {
        try {
            // ตรวจสอบข้อมูลที่รับมา
            $request->validate([
                'wip_empgroup_id' => 'required|integer',
            ]);
    
            // ค้นหาข้อมูลโดยใช้ wip_working_id
            $wipBarcode = Wipbarcode::where('wip_working_id', $id)->first();
    
            if (!$wipBarcode) {
                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล WIP Barcode']);
            }
    
            // อัปเดตข้อมูล
            $wipBarcode->update(['wip_empgroup_id' => $request->wip_empgroup_id]);
    
            return response()->json(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function addng(Request $request)
    {
        try {
            // ✅ ตรวจสอบ Validation
            $validatedData = $request->validate([
                'amg_wip_id'    => 'required|array|min:1',
                'amg_ng_id'     => 'required|array|min:1',
                'amg_amount'    => 'required|array|min:1',
            ], [
                'amg_wip_id.required' => 'กรุณาระบุ WIP ID',
                'amg_ng_id.required'  => 'กรุณาเลือกของเสีย',
                'amg_amount.required' => 'กรุณากรอกจำนวนของเสีย',
                'amg_wip_id.array'    => 'ข้อมูล WIP ID ต้องอยู่ในรูปแบบ array',
                'amg_ng_id.array'     => 'ข้อมูลของเสียต้องอยู่ในรูปแบบ array',
                'amg_amount.array'    => 'จำนวนต้องอยู่ในรูปแบบ array',
                'amg_wip_id.min'      => 'กรุณาระบุอย่างน้อย 1 รายการ',
                'amg_ng_id.min'       => 'กรุณาเลือกของเสียอย่างน้อย 1 รายการ',
                'amg_amount.min'      => 'กรุณากรอกจำนวนของเสียอย่างน้อย 1 รายการ',
            ]);
    
            // ✅ ตรวจสอบค่าทั้งหมดและบันทึก
            foreach ($request->amg_wip_id as $key => $wipId) {
                $ngId = $request->amg_ng_id[$key] ?? null;
                $amount = $request->amg_amount[$key] ?? null;
    
                // ตรวจสอบว่าข้อมูลครบถ้วน
                if (empty($wipId)) {
                    throw new \Exception('ไม่พบ WIP ID ในรายการที่ ' . ($key + 1));
                }
    
                if (empty($ngId)) {
                    throw new \Exception('ไม่พบข้อมูล NG ID ในรายการที่ ' . ($key + 1));
                }
    
                if (empty($amount)) {
                    throw new \Exception('จำนวนของเสียว่างในรายการที่ ' . ($key + 1));
                }
    
                // ✅ บันทึกข้อมูล
                AmountNg::create([
                    'amg_wip_id' => $wipId,
                    'amg_ng_id'  => $ngId,
                    'amg_amount' => $amount,
                ]);
            }
    
            // ✅ ส่ง Response สำเร็จ
            return response()->json(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // กรณี Validation ผิดพลาด
            return response()->json(['status' => 'error', 'message' => 'Validation Error: ' . implode(', ', $e->errors())], 422);
        } catch (\Exception $e) {
            // กรณีข้อผิดพลาดทั่วไป
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
    
    

    
    
    


    
    
    public function editwipamg(Request $request, $id)
{
    $edit = Wipbarcode::find($id);

    if ($edit) {
        // ✅ รับค่าจำนวนที่ต้องการแก้ไข
        $wipAmount = $request->input('wip_amount');

        // ✅ จัดรูปแบบให้ wip_amount เป็นเลข 3 หลัก (เช่น 76 => 076)
        $formattedAmount = str_pad($wipAmount, 3, '0', STR_PAD_LEFT);

        // ✅ แก้ไขสามตัวท้ายของ wip_barcode ให้ตรงกับ wip_amount
        $wipBarcode = substr($edit->wip_barcode, 0, -3) . $formattedAmount;

        // ✅ อัปเดตข้อมูล
        $edit->wip_amount = $wipAmount;
        $edit->wip_barcode = $wipBarcode;
        $edit->save();

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
    } else {
        return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการอัปเดต'], 404);
    }
}

public function deleteWipLine1($work_id, $id)
{
    try {
        $checkWip = Wipbarcode::where('wip_id', $id)->first();

        if (!$checkWip) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลบาร์โค้ด'], 404);
        }

        $empGroup = $checkWip->wip_empgroup_id;
        $amount = $checkWip->wip_amount;

        // ✅ กำหนดค่าเริ่มต้นให้ $eioOutput = 0
        $eioOutput = 0;

        // ค้นหาข้อมูลเข้า-ออกของพนักงาน
        $eio = EmpInOut::where('eio_working_id', $work_id)
                       ->where('eio_emp_group', $empGroup)
                       ->first();

        if ($eio) {
            $eioId = $eio->id;
            $eioInput = $eio->eio_input_amount;
            $eioOutput = $eio->eio_output_amount;
        }

        // ลบข้อมูลบาร์โค้ด
        $checkWip->delete();

        // เช็คข้อมูลในกลุ่มพนักงาน
        $checkEmpGroup = Wipbarcode::where('wip_working_id', $work_id)
                                   ->where('wip_empgroup_id', $empGroup)
                                   ->get();

        if ($checkEmpGroup->isEmpty() && $eioOutput <= 0) {
            // ถ้าไม่มีข้อมูลบาร์โค้ดในกลุ่มนี้ และไม่มีการเบิกจ่าย ให้ลบข้อมูลการเข้า-ออก
            if ($eio) {
                $eio->delete();
            }
        } else {
            // ถ้ามีข้อมูล ให้ลดจำนวนสินค้าใน eio_input_amount
            if ($eio) {
                $eio->update(['eio_input_amount' => $eioInput - $amount]);
            }
        }

        return response()->json(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ'], 200);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
    }
}
public function outfgcode(Request $request, $line, $work_id)
{
    // กำหนดสีตามเงื่อนไข (สมมติว่า function conditioncolor ยังคงใช้ได้)
    $colorpd = $this->conditioncolor($work_id_con = $work_id, $line_con = $line);

    // ตรวจสอบ lot ซ้ำในตาราง workprocess_qc
    $checklot = WorkProcessQC::where('line', $line)
        ->whereHas('wipBarcodes', function ($query) use ($request) {
            $query->where('lot', $request->input('brd_lot'));
        });

    // ค้นหาข้อมูล EmpInOut ตาม working_id และ group_emp
    $eio = EmpInOut::where('eio_working_id', $work_id)
        ->where('eio_emp_group', $request->get('brd_eg_id'));

    $eioid = $eio->value('eio_id');
    $eiooutput = $eio->value('eio_output_amount');

    // คำนวณจำนวน index สำหรับ empdate_index_key
    $index = Brands::where('brd_working_id', $work_id)
        ->where('brd_eg_id', $request->input('brd_eg_id'))
        ->count();
    $countindex = $index + 1;

    // Validation
    $this->validate($request, [
        'brd_lot' => 'required',
        'brd_eg_id' => 'required',
        'brd_brandlist_id' => 'required'
    ]);

    // เงื่อนไขแบรนด์
    $white_brandlist = ["32", "33", "36", "37", "38", "49"];
    $white_manufacture = "44";
    $white_qc = "31";

    // เพิ่มข้อมูลลงตาราง Brands
    if (!$checklot->exists()) {
        $brands = new Brands;
        $brands->brd_working_id = $work_id;
        $brands->brd_brandlist_id = $request->input('brd_brandlist_id');
        $brands->brd_lot = $request->input('brd_lot');
        $brands->brd_eg_id = $request->input('brd_eg_id');
        $brands->brd_amount = $request->input('brd_amount');
        $brands->brd_outfg_date = Carbon::now();
        $brands->brd_empdate_index_key = $countindex;
        $brands->brd_remark = $request->input('brd_remark');
        $brands->brd_backboard_no = $request->input('brd_backboard_no');
        $brands->brd_checker = $request->input('brd_checker');
        $brands->brd_color = $colorpd;

        // กำหนดสถานะ
        if (in_array($request->input('brd_brandlist_id'), $white_brandlist) ||
            $request->input('brd_brandlist_id') == $white_manufacture ||
            $request->input('brd_brandlist_id') == $white_qc) {
            $brands->brd_status = '2';
        } else {
            $brands->brd_status = '1';
        }

        $brands->save();

        // อัปเดต EmpInOut
        $eioout = EmpInOut::find($eioid);
        $eioout->eio_output_amount = $eiooutput + $request->input('brd_amount');
        $eioout->update();

        // ดึงข้อมูลล่าสุด
        $outfg = Brands::leftJoin('brandlist', 'bl_id', '=', 'brd_brandlist_id')
            ->orderBy('brd_outfg_date', 'DESC')
            ->where('brd_status', $brands->brd_status)
            ->limit(1)
            ->get();

        // ส่งข้อมูลแจ้งเตือน LINE
        foreach ($outfg as $linefg) {
            $sToken = "YOUR_LINE_TOKEN_HERE";
            $sMessage = "คัดบอร์ดออก tag FG ";
            $sMessage2 = "| Line: $line | Lot: {$linefg->brd_lot} | BX" . $linefg->bl_code;

            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage . "\n" . $sMessage2);
            curl_setopt($chOne, CURLOPT_HTTPHEADER, [
                'Content-type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $sToken,
            ]);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);
            curl_close($chOne);
        }

        return response()->json(['success' => true, 'brd_id' => $brands->brd_id]);
    } else {
        return response()->json(['success' => false, 'message' => 'Lot already exists.']);
    }
}


}


