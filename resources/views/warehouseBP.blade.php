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
        margin-bottom: 30px;
    }

    .main-menu-button {
        margin-bottom: 50px;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .card {
        border: 2px solid #000; /* เพิ่มขอบสีดำ */
        border-radius: 8px;
        background-color: #ffffff; /* พื้นหลังการ์ดสีขาว */
        color: #000; /* สีข้อความ */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
        text-align: center;
        height: 180px;
        position: relative;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .card h4 {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .card p {
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .btn {
        color: #000; /* ข้อความสีดำ */
        font-weight: bold;
        background-color: transparent; /* ไม่มีพื้นหลัง */
        border: 2px solid #000; /* ขอบสีดำ */
        padding: 8px 15px;
        font-size: 0.9rem;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
    }

    .btn:hover {
        background-color: #000; /* พื้นหลังเป็นสีดำเมื่อ hover */
        color: #fff; /* ข้อความเป็นสีขาวเมื่อ hover */
        text-decoration: none; /* ลบขีดเส้นใต้เมื่อ hover */
    }
</style>

<div class="container">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <div class="header-section text-center">
        <h1 class="my-4">การทำงานในส่วนบางคลังบางพลี</h1>
        <p class="text-danger mb-5">เรียนแจ้งผู้ใช้งานทุกท่าน : เพื่อการทำงานที่สมบูรณ์ กรุณาใช้ Google Chrome ในการใช้ระบบ</p>
    </div>

    <div class="text-center main-menu-button">
        <a href="#" class="btn btn-warning btn-lg">กลับไปยังเมนูหลัก</a>
    </div>

    <div class="card-container">
        <div class="card">
            <h4>แปรรหัสออกของในประเทศ</h4>
            <p>เลือกข้อมูลในออกรหัส SB</p>
            <a href="#" class="btn">More info →</a>
        </div>
        <div class="card">
            <h4>งานรับของ (บางพลี)</h4>
            <p>งานรับของบางพลี</p>
            <a href="#" class="btn">More info →</a>
        </div>
        <div class="card">
            <h4>โอนย้ายไปยัง TOA (บางพลี)</h4>
            <p>จากบางพลีไป TOA</p>
            <a href="#" class="btn">More info →</a>
        </div>
        <div class="card">
            <h4>โอนย้ายไปยังพฤกษา</h4>
            <p>โอนย้ายพฤกษา</p>
            <a href="#" class="btn">More info →</a>
        </div>
        <div class="card">
            <h4>โอนย้ายไปยังนครสวรรค์</h4>
            <p>โอนย้ายจากบางพลีไปนครสวรรค์</p>
            <a href="#" class="btn">More info →</a>
        </div>
    </div>
</div>

@endsection
