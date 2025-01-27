<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC, GroupEmp, Skumaster, AmountNg, Brand};
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
        $wipAmount = (int) ltrim(substr($input['wip_barcode'], -3), '0');
        $insertwip = Wipbarcode::create([
            'wip_barcode'    => $input['wip_barcode'],
            'wip_amount'     => $wipAmount,
            'wip_working_id' => $input['wp_working_id'],
            'wip_empgroup_id'=> $input['wip_empgroup_id'],
            'wip_sku_name'   => $skuName,
            'wip_index'      => $peIndex,
        ]);

        // คำนวณ indexcount
        $index = WipProductDate::where('wp_working_id', $input['wp_working_id'])
            ->where('wp_empgroup_id', $input['wip_empgroup_id'])
            ->max('wp_empdate_index_id');
        $indexcount = $index + 1;

        // บันทึกข้อมูลลง WipProductDate
        $dmy = now(); // ใช้วันที่ปัจจุบัน
        $dateproduct = new WipProductDate;
        $dateproduct->wp_working_id = $input['wp_working_id'];
        $dateproduct->wp_wip_id = $insertwip->wip_id;
        $dateproduct->wp_empdate_index_id = $indexcount;
        $dateproduct->wp_empgroup_id = $input['wip_empgroup_id'];
        $dateproduct->wp_date_product = Carbon::parse($dmy)->toDateTimeString();
        $dateproduct->save();

        // บันทึกข้อมูลลง ProductTypeEmp
        ProductTypeEmp::create([
            'pe_working_id' => $work_id,
            'pe_type_code'  => $typeCode,
            'pe_type_name'  => $skuName,
            'pe_index'      => $peIndex,
        ]);

        // บันทึกข้อมูลลง EmpInOut
        EmpInOut::create([
            'eio_emp_group'    => $input['wip_empgroup_id'], // ค่าเดียวกับ wip_empgroup_id
            'eio_working_id'   => $input['wp_working_id'],  // ค่าเดียวกับ wp_working_id
            'eio_input_amount' => $wipAmount,              // ค่าเดียวกับ wip_amount
            'eio_line'         => $line,                  // แปลง L2 เป็น 2 หรือ L1 เป็น 1
            'eio_division'     => 'QC',                   // กำหนดเป็น QC
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
    // ตรวจสอบและแปลง $line ให้มี "L" ข้างหน้าถ้าเป็นตัวเลขล้วน
    $line = preg_match('/^\d+$/', $line) ? "L$line" : $line;

    // ดึงสีตามเงื่อนไข
    $colorpd = $this->conditioncolor($work_id, $line);

    // ตรวจสอบ lot โดยใช้ group_emp
    $checklot = Brand::leftJoin('group_emp', 'group_emp.id', '=', 'brands.brd_eg_id')
        ->where('group_emp.line', '=', $line)
        ->where('brands.brd_lot', '=', $request->input('brd_lot'));

    // ตรวจสอบ EmpInOut
    $eio = EmpInOut::where('eio_working_id', '=', $work_id)
        ->where('eio_emp_group', '=', $request->get('brd_eg_id'));

    $eioid = $eio->value('eio_id');
    $eiooutput = $eio->value('eio_output_amount') ?? 0;

    // ตรวจสอบลำดับข้อมูล
    $index = Brand::where('brd_working_id', '=', $work_id)
        ->where('brd_eg_id', '=', $request->input('brd_eg_id'))
        ->count();
    $countindex = $index + 1;

    // Validate Input
    $request->validate([
        'brd_lot' => 'required|string',
        'brd_eg_id' => 'required|numeric',
        'brd_brandlist_id' => 'required|numeric',
        'brd_amount' => 'required|numeric|min:1',
        'brd_checker' => 'required|string',
    ]);

    // กำหนดค่าคงที่สำหรับแบรนด์
    $white_brandlist = ["32", "33", "36", "37", "38", "49"];
    $white_manufacture = "44";
    $white_qc = "31";

    $brd_eg_id = $request->input('brd_eg_id');
    $brd_brandlist_id = $request->input('brd_brandlist_id');

    if ($brd_eg_id != "0" && $brd_brandlist_id != "0") {
        if (!$checklot->exists()) {
            $brands = new Brand();
            $brands->brd_working_id = $work_id;
            $brands->brd_brandlist_id = $brd_brandlist_id;
            $brands->brd_lot = $request->input('brd_lot');
            $brands->brd_eg_id = $brd_eg_id;
            $brands->brd_amount = $request->input('brd_amount');
            $brands->brd_outfg_date = now();
            $brands->brd_empdate_index_key = $countindex;
            $brands->brd_remark = $request->input('brd_remark');
            $brands->brd_backboard_no = $request->input('brd_backboard_no');
            $brands->brd_checker = $request->input('brd_checker');
            $brands->brd_color = $colorpd;

            // ตรวจสอบสถานะแบรนด์
            $brands->brd_status = in_array($brd_brandlist_id, $white_brandlist) || 
                                  $brd_brandlist_id == $white_manufacture || 
                                  $brd_brandlist_id == $white_qc ? '2' : '1';

            $brands->save();

            // อัปเดตข้อมูล EmpInOut
            if ($eioid) {
                $eioout = EmpInOut::find($eioid);
                $eioout->eio_output_amount = $eiooutput + $request->input('brd_amount');
                $eioout->update();
            }

            return response()->json([
                'message' => 'บันทึกข้อมูลสำเร็จ', // ข้อความภาษาไทย
                'brd_id' => $brands->brd_id,
                'brd_brandlist_id' => $brands->brd_brandlist_id,
            ], 200, [], JSON_UNESCAPED_UNICODE); // เพิ่ม JSON_UNESCAPED_UNICODE
        } else {
            return response()->json(['error' => 'Duplicate lot detected'], 400);
        }
    } else {
        return response()->json(['error' => 'Invalid input'], 400);
    }
}


public function conditioncolor($work_id_con,$line_con){

    $colorpd = "";

    $pdtype = ProductTypeEmp::leftJoin('wip_working','ww_id','=','pe_working_id')
    ->leftJoin('brands','brd_working_id','=','ww_id')
    ->where('ww_id','=',$work_id_con);

    $pdcode = $pdtype->value('pe_type_code');
    $remark = $pdtype->value('brd_remark');

    if (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '10' && substr($pdcode,4,2) == '18'){
        $colorpd = "#FFFFFF";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '4' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        $colorpd = "#00b0f0";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' &&  substr($pdcode,1,1) == '01' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff99cc";
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '01' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff99cc";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '07' && substr($pdcode,4,2) == '12') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '08' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ccccff";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '11' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        elseif ($line_con == 'L3') {
            $colorpd = "#00b0f0";
        }
        else {
            $colorpd = "#FFFFFF";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '12' && substr($pdcode,4,2) == '12') {
        $colorpd = "#00b0f0";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '08' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12' && $line_con == 'L2') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12' && $line_con == 'L2') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '15' && $line_con == 'L3') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '15' && $line_con == 'L3') {
        $colorpd = "#ff9966";
    }
    else {
        $colorpd = "#FFFFFF";
    }

    return $colorpd;

    #92d050 //green line 1
    #ffff00 //yellow
    #00b0f0 //sky
    #ff9966 //orange
    #ff99cc //pink
    #ccccff //pureple
    #a9d08e //green
    #FFFFFF //white
}
public function tagwipqc($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('group_emp as g')
        ->select(
            'g.emp1 as name1',
            'g.emp2 as name2',
            'g.id as eg_id',
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('brands', 'brands.brd_eg_id', '=', 'g.id')
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $checkdatpd = WipProductDate::select('brands.brd_eg_id', 'wp_date_product')
        ->leftJoin('brands', function ($query) {
            $query->on('brands.brd_empdate_index_key', '=', 'wip_product_date.wp_empdate_index_id')
                  ->on(DB::raw('cast(wip_product_date.wp_empgroup_id as nvarchar)'), '=', 'brands.brd_eg_id');
        })
        ->leftJoin('wipbarcode', 'wipbarcode.wip_empgroup_id', '=', 'brands.brd_eg_id');

    $getegid = $checkdatpd->where('brands.brd_id', '=', $brd_id)->value('brands.brd_eg_id');

    $dateproduct = $checkdatpd->where('brands.brd_id', '=', $brd_id)
        ->where('wip_product_date.wp_empgroup_id', '=', $getegid)
        ->where('wip_product_date.wp_working_id', '=', $work_id)
        ->value('wip_product_date.wp_date_product');

    $pcs = ProductColors::all();

    $colorbyid = Brands::select('brd_color')->where('brd_id', '=', $brd_id)->value('brd_color');

    $thmonth = $this->thaimonth();

    $typearr = $this->typeofproduct();

    $sizearr = $this->productsize();

    $thicknessarr = $this->thickness();

    $boarderarr = $this->boarder();

    $colordate = $this->wipcolordatecon($dateproduct);

    $view = view('template.tagwipqc', [
        'tag'               => $tag,
        'id'                => $id,
        'typearr'           => $typearr,
        'colorbyid'         => $colorbyid,
        'sizearr'           => $sizearr,
        'thicknessarr'      => $thicknessarr,
        'pcs'               => $pcs,
        'boarderarr'        => $boarderarr,
        'thmonth'           => $thmonth,
        'colordate'         => $colordate,
        'dateproduct'       => $dateproduct,
    ]);
    return $view;
}
public function tagwipnn($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('brands')
        ->select(
            DB::raw("'{$groupEmp->emp1}' as name1"),
            DB::raw("'{$groupEmp->emp2}' as name2"),
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $view = view('template.tagwipnn', [
        'tag' => $tag,
        'id' => $id,
    ]);
    return $view;
}
public function tagfn($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('brands')
        ->select(
            DB::raw("'{$groupEmp->emp1}' as name1"),
            DB::raw("'{$groupEmp->emp2}' as name2"),
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $view = view('template.tagfn', [
        'tag' => $tag,
        'id' => $id,
    ]);
    return $view;
}
public function tagfg($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('brands')
        ->select(
            DB::raw("'{$groupEmp->emp1}' as name1"),
            DB::raw("'{$groupEmp->emp2}' as name2"),
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color',
            'brands.brd_remark'
        )
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $view = view('template.tagfg', [
        'tag' => $tag,
        'id' => $id,
    ]);
    return $view;
}

}


