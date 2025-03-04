<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST Data</title>
    
    <!-- เรียกใช้ SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 15px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        #responseMessage {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>ส่งข้อมูลไปยัง API</h2>

        <form id="weightbabyForm">
            <label>atwb_lot:</label>
            <input type="text" id="atwb_lot" name="atwb_lot" required>

            <label>atwb_weight_baby:</label>
            <input type="text" id="atwb_weight_baby" name="atwb_weight_baby" required>

            <label>atwb_sequence:</label>
            <input type="text" id="atwb_sequence" name="atwb_sequence" required>

            <label>atwb_weight_all:</label>
            <input type="text" id="atwb_weight_all" name="atwb_weight_all" required>

            <label>atwb_weight_10:</label>
            <input type="text" id="atwb_weight_10" name="atwb_weight_10">

            <button type="button" onclick="sendData()">🚀 ส่งข้อมูล</button>
        </form>

        <p id="responseMessage"></p>
    </div>

    <script>
        function sendData() {
            let data = {
                atwb_lot: document.getElementById("atwb_lot").value,
                atwb_weight_baby: document.getElementById("atwb_weight_baby").value,
                atwb_sequence: document.getElementById("atwb_sequence").value,
                atwb_weight_all: document.getElementById("atwb_weight_all").value,
                atwb_weight_10: document.getElementById("atwb_weight_10").value || null
            };

            axios.post("{{ url('/send-weightbaby') }}", data)
                .then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งข้อมูลสำเร็จ!',
                        text: 'ข้อมูลของคุณถูกส่งไปยัง API แล้ว 🎉',
                        confirmButtonColor: '#28a745'
                    });

                    document.getElementById("weightbabyForm").reset(); // ล้างค่าฟอร์ม
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: error.response?.data?.message || "ไม่สามารถเชื่อมต่อ API ได้ ❌",
                        confirmButtonColor: '#d33'
                    });
                });
        }
    </script>

</body>
</html>
