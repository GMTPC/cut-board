@extends('dashboard')

@section('title', 'ระบบ QC')

@section('content')

<style>
    body {
        font-family: 'Prompt', sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .header-section {
        margin-bottom: 30px; /* เพิ่มช่องว่างระหว่างหัวข้อและส่วนถัดไป */
    }

    .main-menu-button {
        margin-bottom: 50px; /* เพิ่มระยะห่างระหว่างปุ่มและการ์ด */
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 40px; /* เพิ่มระยะห่างระหว่างการ์ด */
    }

    .card {
        border: none;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 220px; /* เพิ่มความสูงของการ์ดเล็กน้อย */
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .card h4 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 15px; /* เพิ่มช่องว่างด้านล่างของข้อความ */
    }

    .btn {
        color: #007bff;
        font-weight: bold;
        background-color: transparent;
        border: 1px solid #007bff;
        padding: 8px 15px;
        font-size: 0.85rem;
        text-align: center;
        align-self: center;
        transition: all 0.3s ease;
        margin-top: auto; /* ดันปุ่มลงล่างสุด */
    }

    .btn:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>

<div class="container">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <div class="header-section text-center">
        <h1 class="my-4">เลือกคลังสินค้า</h1>
        <p class="text-danger mb-5">เรียนแจ้งผู้ใช้งานทุกท่าน : เพื่อการทำงานที่สมบูรณ์ กรุณาใช้ Google Chrome ในการใช้ระบบ</p>
    </div>

    <div class="text-center main-menu-button">
        <a href="#" class="btn btn-warning btn-lg">กลับไปยังเมนูหลัก</a>
    </div>

    <div class="card-container">
        <div class="card">
            <h4>แปรรหัสออกของในประเทศ</h4>
            <p>เลือกข้อมูลในออกรหัส SO</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalWarehouseSupport">More info</a>
        </div>
        <div class="card">
            <h4>แปรรหัสออกของต่างประเทศ</h4>
            <p>เลือกข้อมูลในออกรหัส SE</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalForeignSupport">More info</a>
        </div>
        <div class="card">
            <h4>โอนย้ายไปยังบางพลี</h4>
            <p>จากนครสวรรค์ไปบางพลี</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalTransferBangplee">More info</a>
        </div>
        <div class="card">
            <h4>โอนย้ายไปยัง TOA</h4>
            <p>จากนครสวรรค์ไป TOA</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalTransferTOA">More info</a>
        </div>
        <div class="card">
            <h4>งานรับของ</h4>
            <p>งานรับของนครสวรรค์</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalReceiving">More info</a>
        </div>
        <div class="card">
            <h4>ตรวจสอบสินค้าเข้าสู่ระบบ</h4>
            <p>ตรวจสอบสินค้าเพื่อเข้าสู่ระบบ</p>
            <a href="#" class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalCheck">More info</a>
        </div>
    </div>
</div>

@endsection
