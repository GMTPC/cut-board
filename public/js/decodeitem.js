$(document).ready(function(){

    $('#itemdecodeform').on('submit', function(a){
        a.preventDefault();

        $.ajax({
            type: "POST",
            url: "/fetchitemcode",
            data: $('#itemdecodeform').serialize(),
            success: "Success."

        });
        location.reload();
    });
});

$('.idc1').each(function(){
    var newText = $(this).text();
    if (newText == 'B100-A10109') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx9mm');
    }
    else if (newText == 'B100-A10112') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx12mm');
    }
    else if (newText == 'B100-A10116') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx15mm');
    }
    else if (newText == 'B100-A10209') {
        $(this).text('ยิปซั่ม ขอบลาด 1.22mx2.44mx9mm');
    }
    else if (newText == 'B100-A10909') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.2mx9mm');
    }
    else if (newText == 'B100-A10912') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.2mx12mm');
    }
    else if (newText == 'B100-A30209') {
        $(this).text('ยิปซั่ม ขอบลาด ทนชื้น 1.22mx2.44mx9mm');
    }
    else if (newText == 'B100-A30212') {
        $(this).text('ยิปซั่ม ขอบลาด ทนชื้น 1.22mx2.44mx12mm');
    }
    else if (newText == 'B100-A60116') {
        $(this).text('ยิปซั่ม ขอบลาด ทนไฟ 1.2mx2.4mx15mm');
    }
    else if (newText == 'B100-B10109') {
        $(this).text('ยิปซั่ม - ขอบเรียบ  1.2x2.4m.x9mm');
    }
    else if (newText == 'B100-B10112') {
        $(this).text('ยิปซั่ม - ขอบเรียบ  1.2x2.4m.x12mm');
    }
    else if (newText == 'B100-B30109') {
        $(this).text('ยิปซั่ม ขอบเรียบ ทนชื้น 1.2x2.4mx9mm');
    }
    else if (newText == 'B101-AZ0109') {
        $(this).text('ยิปซั่ม VIP ขอบลาด 1.2mx2.4mx9mm');
    }
    else if (newText == 'B101-A10112') {
        $(this).text('ยิปซั่ม VIP ขอบลาด (มอก.) 1.2x2.4m.x12mm');
    }
    else if (newText == 'B101-A30109') {
        $(this).text('ยิปซั่ม VIP ขอบลาด ทนชื้น 1.2mx2.4mx9mm');
    }
    else if (newText == 'B101-A10109') {
        $(this).text('ยิปซั่ม VIP ขอบลาด (มอก.) 1.2x2.4m.x9mm');
    }
    else if (newText == 'B101-B10109') {
        $(this).text('ยิปซั่ม VIP ขอบเรียบ (มอก.) 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-A10109') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-A10112') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.2x2.4m.x12mm');
    }
    else if (newText == 'B104-A10209') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-A10212') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.22x2.44m.x12mm');
    }
    else if (newText == 'B104-A30109') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-A30112') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น 1.2x2.4m.x12mm');
    }
    else if (newText == 'B104-A30209') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-A40109') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.2x 2.4m.x9mm');
    }
    else if (newText == 'B104-A40209') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-A40309') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.21x2.42m.x9mm');
    }
    else if (newText == 'B104-A70109') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น กันร้อน 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-AW0109') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น WAX 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-AW0209') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น WAX 1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-B10109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-B10112') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.2x2.4m.x12mm');
    }
    else if (newText == 'B104-B10209') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-B10212') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.22x2.44mx12mm');
    }
    else if (newText == 'B104-B10309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.21x2.42cmx9mm');
    }
    else if (newText == 'B104-B30109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ ทนชื้น 1.2 x 2.4 m.x9 mm');
    }
    else if (newText == 'B104-B30209') {
        $(this).text('ยิปซั่ม GMขอบเรียบ ทนชื้น 1.22x2.44m.x9mm');
    }
    else if (newText == 'B104-B30309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบทนชื้น1.21x2.42cmx9mm');
    }
    else if (newText == 'B104-B40109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน 1.2x2.4m.x9mm');
    }
    else if (newText == 'B104-B40209') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน1.22x2.44m.x9');
    }
    else if (newText == 'B104-B40309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน 1.21x2.42mx9');
    }
    else if (newText == 'B105-A10109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B105-A30109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B105-A40109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด กันร้อน1.2x2.4m.x 9');
    }
    else if (newText == 'B105-B10109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบเรียบ  1.2x2.4m.x9');
    }
    else if (newText == 'B106-A10109') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B106-A30109') {
        $(this).text('ยิปซั่ม เพชรห้าดาว ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B106-A30709') {
        $(this).text('ยิปซั่ม เพชรห้าดาว ขอบลาด ทนชื้น 1.0x2.4m.x9');
    }
    else if (newText == 'B106-B10109') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B106-B10209') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบเรียบ 1.22x2.44m.x9');
    }
    else if (newText == 'B109-A10109') {
        $(this).text('เกรด Cขอบลาด1.2x2.4m.x9');
    }
    else if (newText == 'B109-A10112') {
        $(this).text('เกรดC ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B109-A30109') {
        $(this).text('เกรดC ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B109-A40109') {
        $(this).text('เกรดC ขอบลาด กันร้อน 1.2 x 2.4 m.x 9 ');
    }
    else if (newText == 'B110-A10109') {
        $(this).text('ยิปซั่ม ต้นไม้ ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B110-A30109') {
        $(this).text('ยิปซั่มต้นไม้ ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B110-A40109') {
        $(this).text('ยิปซั่ม ต้นไม้ ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B111-A10109') {
        $(this).text('ยิปซั่ม SCL ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B111-A10112') {
        $(this).text('ยิปซั่ม SCL ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B111-A30109') {
        $(this).text('ยิปซั่ม SCL ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B111-A40109') {
        $(this).text('ยิปซั่ม SCL ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B112-A10109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B112-A30109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B112-A40109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B113-A10109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B113-A30109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B113-A40109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B114-A10109') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B114-A10112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B114-A10512') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x3m.x12');
    }
    else if (newText == 'B114-A10812') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x2.7m.x12');
    }
    else if (newText == 'B114-A30109') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B114-A30112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x2.4m.x12');
    }
    else if (newText == 'B114-A30512') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x3m.x12');
    }
    else if (newText == 'B114-A30615') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x2.5m.x9');
    }
    else if (newText == 'B114-B10109') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.4mx9mm');
    }
    else if (newText == 'B114-B10112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.4mx12mm');
    }
    else if (newText == 'B114-B10712') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.3mx12mm');
    }
    else if (newText == 'B115-A10109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B115-A30109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B115-A40109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B115-B10109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B116-A10109') {
        $(this).text('ยิปซั่ม ST ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B116-A30109') {
        $(this).text('ยิปซั่ม ST ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B116-A40109') {
        $(this).text('ยิปซั่ม ST ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B117-A30109') {
        $(this).text('ยิปซั่ม 3G ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B118-A10109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B118-A30109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B118-A40109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B118-A90109') {
        $(this).text('ยิปซั่ม Maxum.N ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B119-A10109') {
        $(this).text('ยิปซั่ม City bord ขบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B119-A30109') {
        $(this).text('ยิปซั่ม City bord ขอบลาด ทนชื้น1.2x2.4m.x9m');
    }
    else if (newText == 'B119-A40109') {
        $(this).text('ยิปซั่ม City bord ขอบลาด กันร้อน 1.2x2.4m.x9m');
    }
    else if (newText == 'B125-A10109') {
        $(this).text('ยิปซั่ม บิ๊ก-บอย ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B125-A30109') {
        $(this).text('ยิปซั่ม บิ๊ก-บอย ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B125-A40109') {
        $(this).text('ยิปซั่ม บิ๊ก-บอย ขอบลาด กันร้อน 1.2x2.4m.x9mm');
    }
    else if (newText == 'B126-A10209') {
        $(this).text('ยิปซั่ม ยิปแม๊ก ขอบลาด 1.22x2.44m.x9');
    }
    else if (newText == 'B127-A10195') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด 1.2x2.4m.x9.5');
    }
    else if (newText == 'B127-A10595') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด1.2x3.0mx9.5mm');
    }
    else if (newText == 'B127-A10895') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด 1.2x2.7m.x9.5');
    }
    else if (newText == 'B127-A30195') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด ทนชื้น 1.2x2.4m.x9.5');
    }
    else if (newText == 'B127-B10209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ 1.22x2.44m.x9');
    }
    else if (newText == 'B127-B10412') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ 1.22x3.05x12');
    }
    else if (newText == 'B127-B30209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ ทนชื้น 1.22x2.44m.x9m');
    }
    else if (newText == 'B127-B30412') {
        $(this).text('ยิปซั่มTRUSUS ขอบเรียบ ทนชื้น 1.22x3.05x12');
    }
    else if (newText == 'B127-B40209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ กันร้อน1.22x2.44m.x9mm');
    }
    else if (newText == 'B127-B60209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ ทนไฟ 1.22x2.44m.x9');
    }
    else if (newText == 'B128-A10595') {
        $(this).text('ยิปซั่ม OPSKY ขอบลาด 1.2x3.0m9.5mm');
    }
    else if (newText == 'B129-A10109') {
        $(this).text('ยิปซั่ม ขอบลาด ตรา DD board 1.2mx2.4mx9mm');
    }
    else if (newText == 'B129-B10109') {
        $(this).text('ยิปซั่ม DD bord ขอบเรียบ 1.2mx2.4mx9mm');
    }
    else if (newText == 'B131-A10109') {
        $(this).text('ยิปซั่ม GM กัมพูชา ขอบลาด 1.2mx2.4mx9mm');
    }
    else if (newText == 'B132-A10109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B132-A10112') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B132-A10209') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.22x2.44m.x9');
    }
    else if (newText == 'B132-A30109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B132-A40109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B132-AW0209') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น WAX 1.22x2.44m.x9');
    }
    else if (newText == 'B132-AW0212') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น WAX 1.22x2.44m.x12');
    }
    else if (newText == 'B132-B10109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B132-B10112') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ 1.2x2.4m.x12');
    }
    else if (newText == 'B132-B30109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B133-A10109') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B133-A10112') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B133-A10512') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x3m.x12');
    }
    else if (newText == 'B133-A10709') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.3m.x9');
    }
    else if (newText == 'B133-A10712') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.3m.x12');
    }
    else if (newText == 'B133-A10812') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.7m.x12');
    }
    else if (newText == 'B133-A30112') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชื้น 1.2x2.4m.x12');
    }
    else if (newText == 'B133-A30512') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชิ้น 1.2x3m.x12');
    }
    else if (newText == 'B133-A30812') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชิ้น 1.2x2.7m.x12');
    }
    else if (newText == 'B134-A10109') {
        $(this).text('ยิปซั่ม DIC ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B200-A10109') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx9mm');
    }
    else if (newText == 'B200-A10112') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx12mm');
    }
    else if (newText == 'B200-A10116') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.4mx15mm');
    }
    else if (newText == 'B200-A10209') {
        $(this).text('ยิปซั่ม ขอบลาด 1.22mx2.44mx9mm');
    }
    else if (newText == 'B200-A10909') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.2mx9mm');
    }
    else if (newText == 'B200-A10912') {
        $(this).text('ยิปซั่ม ขอบลาด 1.2mx2.2mx12mm');
    }
    else if (newText == 'B200-A30209') {
        $(this).text('ยิปซั่ม ขอบลาด ทนชื้น 1.22mx2.44mx9mm');
    }
    else if (newText == 'B200-A30212') {
        $(this).text('ยิปซั่ม ขอบลาด ทนชื้น 1.22mx2.44mx12mm');
    }
    else if (newText == 'B200-A60116') {
        $(this).text('ยิปซั่ม ขอบลาด ทนไฟ 1.2mx2.4mx15mm');
    }
    else if (newText == 'B200-B10109') {
        $(this).text('ยิปซั่ม - ขอบเรียบ  1.2x2.4m.x9');
    }
    else if (newText == 'B200-B10112') {
        $(this).text('ยิปซั่ม - ขอบเรียบ  1.2x2.4m.x12');
    }
    else if (newText == 'B200-B30109') {
        $(this).text('ยิปซั่ม ขอบเรียบ ทนชื้น 1.2mx2.4mx9mm');
    }
    else if (newText == 'B201-A10112') {
        $(this).text('ยิปซั่ม VIP ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B201-A30109') {
        $(this).text('ยิปซั่ม VIP ขอบลาด ทนชื้น 1.2mx2.4mx9mm');
    }
    else if (newText == 'B201-B10109') {
        $(this).text('ยิปซั่ม VIP ขอบเรียบ (มอก.) 1.2x2.4m.x9');
    }
    else if (newText == 'B204-A10109') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B204-A10112') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B204-A10209') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.22x2.44m.x9');
    }
    else if (newText == 'B204-A10212') {
        $(this).text('ยิปซั่ม GM ขอบลาด 1.22x2.44m.x12');
    }
    else if (newText == 'B204-A30112') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น 1.2x2.4m.x12');
    }
    else if (newText == 'B204-A30209') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น1.22x2.44m.x9');
    }
    else if (newText == 'B204-A40109') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B204-A40209') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.22x2.44m.x9');
    }
    else if (newText == 'B204-A40309') {
        $(this).text('ยิปซั่ม GM ขอบลาด กันร้อน 1.21x2.42m.x9');
    }
    else if (newText == 'B204-A70109') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B204-AW0109') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น WAX 1.2x2.4m.x9');
    }
    else if (newText == 'B204-AW0209') {
        $(this).text('ยิปซั่ม GM ขอบลาด ทนชื้น WAX 1.22x2.44m.x9');
    }
    else if (newText == 'B204-B10109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B204-B10112') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.2x2.4m.x12');
    }
    else if (newText == 'B204-B10209') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.22x2.44m.x9');
    }
    else if (newText == 'B204-B10212') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.22x2.44mx12');
    }
    else if (newText == 'B204-B10309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ 1.21x2.42cmx9');
    }
    else if (newText == 'B204-B30109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B204-B30209') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ ทนชื้น 1.22x2.44m.x9');
    }
    else if (newText == 'B204-B30309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ ทนชื้น1.21x2.42cmx9');
    }
    else if (newText == 'B204-B40109') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B204-B40209') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน1.22x2.44m.x9');
    }
    else if (newText == 'B204-B40309') {
        $(this).text('ยิปซั่ม GM ขอบเรียบ กันร้อน1.21x2.42cmx9');
    }
    else if (newText == 'B205-A10109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด1.2x2.4m.x9');
    }
    else if (newText == 'B205-A30109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B205-A40109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B205-B10109') {
        $(this).text('ยิปซั่ม SHOGUN ขอบเรียบ  1.2x2.4m.x9');
    }
    else if (newText == 'B206-A10109') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B206-A30109') {
        $(this).text('ยิปซั่ม เพชรห้าดาว ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B206-A30709') {
        $(this).text('ยิปซั่ม เพชรห้าดาว ขอบลาด ทนชื้น 1.0x2.4m.x9');
    }
    else if (newText == 'B206-B10109') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B206-B10209') {
        $(this).text('ยิปซั่ม เพชร5ดาว ขอบเรียบ 1.22x2.44m.x9');
    }
    else if (newText == 'B209-A10109') {
        $(this).text('เกรด C ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B209-A10112') {
        $(this).text('เกรด Cขอบลาด1.2x2.4m.x12');
    }
    else if (newText == 'B209-A30109') {
        $(this).text('เกรดCขอบลาดทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B209-A40109') {
        $(this).text('เกรดCขอบลาดกันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B210-A10109') {
        $(this).text('ยิปซั่ม ต้นไม้ ขอบลาด1.2x2.4m.x9');
    }
    else if (newText == 'B210-A30109') {
        $(this).text('ยิปซั่ม ต้นไม้ ขอบลาดทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B210-A40109') {
        $(this).text('ยิปซั่ม ต้นไม้ ขอบลาดกันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B211-A10109') {
        $(this).text('ยิปซั่ม SCLขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B211-A10112') {
        $(this).text('ยิปซั่ม SCL ขอบลาด1.2x2.4m.x12');
    }
    else if (newText == 'B211-A30109') {
        $(this).text('ยิปซั่ม SCL ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B211-A40109') {
        $(this).text('ยิปซั่ม SCL ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B212-A10109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B212-A30109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B212-A40109') {
        $(this).text('ยิปซั่ม RPG ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B213-A10109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B213-A30109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B213-A40109') {
        $(this).text('ยิปซั่ม SUPER ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B214-A10109') {
        $(this).text('ยิปซั่ม-ขอบลาด1.2x2.4m.x9');
    }
    else if (newText == 'B214-A10112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B214-A10512') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x3m.x12');
    }
    else if (newText == 'B214-A10812') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด 1.2x2.7m.x12');
    }
    else if (newText == 'B214-A30109') {
        $(this).text('ยิปซั่ม- ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B214-A30112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x2.4m.x12');
    }
    else if (newText == 'B214-A30512') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x3m.x12');
    }
    else if (newText == 'B214-A30615') {
        $(this).text('ยิปซั่ม NOLOGO ขอบลาด ทนชื้น 1.2x2.5m.x9');
    }
    else if (newText == 'B214-B10109') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.4mx9mm');
    }
    else if (newText == 'B214-B10112') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.4mx12mm');
    }
    else if (newText == 'B214-B10712') {
        $(this).text('ยิปซั่ม NOLOGO ขอบเรียบ 1.2mx2.3mx12mm');
    }
    else if (newText == 'B215-A10109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B215-A30109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B215-A40109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B215-B10109') {
        $(this).text('ยิปซั่ม ตราเพชร ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B216-A10109') {
        $(this).text('ยิปซั่ม ST ขอบลาด 1.2x2.4 m.x9');
    }
    else if (newText == 'B216-A30109') {
        $(this).text('ยิปซั่ม ST ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B216-A40109') {
        $(this).text('ยิปซั่ม ST ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B217-A30109') {
        $(this).text('ยิปซั่ม 3G ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B218-A10109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B218-A30109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด ทนชื้น1.2x2.4m.x9');
    }
    else if (newText == 'B218-A40109') {
        $(this).text('ยิปซั่ม Maxum ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B219-A10109') {
        $(this).text('ยิปซั่ม City bord ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B219-A30109') {
        $(this).text('ยิปซั่ม City bord ขอบลาด ทนชื้น1.2x2.4m.x9mm');
    }
    else if (newText == 'B219-A40109') {
        $(this).text('ยิปซั่ม City bord ขอบลาด กันร้อน1.2x2.4m.x9');
    }
    else if (newText == 'B225-A30109') {
        $(this).text('ยิปซั่ม บิ๊ก-บอย ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B225-A40109') {
        $(this).text('ยิปซั่ม บิ๊ก-บอย ขอบลาด กันร้อน1.2x2.4m.x9mm');
    }
    else if (newText == 'B226-A10209') {
        $(this).text('ยิปซั่ม ยิปแม๊ก ขอบลาด 1.22x2.44m.x9');
    }
    else if (newText == 'B227-A10195') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด 1.2x2.4m.x9.5');
    }
    else if (newText == 'B227-A10595') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด1.2x3.0mx9.5mm');
    }
    else if (newText == 'B227-A10895') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด 1.2x2.7m.x9.5');
    }
    else if (newText == 'B227-A30195') {
        $(this).text('ยิปซั่ม TRUSUS ขอบลาด ทนชื้น 1.2x2.4m.x9.5');
    }
    else if (newText == 'B227-B10209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ 1.22x2.44m.x9');
    }
    else if (newText == 'B227-B10512') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ 1.22x3.05x12');
    }
    else if (newText == 'B227-B30209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ ทนชื้น 1.22x2.44m.x9m');
    }
    else if (newText == 'B227-B30512') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ ทนชื้น1.22x3.05x12');
    }
    else if (newText == 'B227-B40209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ กันร้อน 1.22x2.44m.x9mm');
    }
    else if (newText == 'B227-B60209') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ ทนไฟ1.22x2.44m.x9');
    }
    else if (newText == 'B228-A10495') {
        $(this).text('ยิปซั่ม OPSKY ขอบลาด1.2x3.0mx9.5mm');
    }
    else if (newText == 'B229-A10109') {
        $(this).text('ยิปซั่ม ตรา DD board ขอบลาด  1.2mx2.4mx9mm');
    }
    else if (newText == 'B229-B10109') {
        $(this).text('ยิปซั่ม DD bord ขอบเรียบ 1.2mx2.4mx9mm');
    }
    else if (newText == 'B231-A10109') {
        $(this).text('ยิปซั่ม GM กัมพูชา ขอบลาด 1.2mx2.4mx9mm');
    }
    else if (newText == 'B232-A10109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B232-A10112') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B232-A10209') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด 1.22x2.44m.x9');
    }
    else if (newText == 'B232-A30109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B232-A40109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด กันร้อน 1.2x2.4m.x9');
    }
    else if (newText == 'B232-AW0209') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น WAX 1.22x2.44m.x9');
    }
    else if (newText == 'B232-AW0212') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบลาด ทนชื้น WAX 1.22x2.44m.x12');
    }
    else if (newText == 'B232-B10109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ 1.2x2.4m.x9');
    }
    else if (newText == 'B232-B10112') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ 1.2x2.4m.x12');
    }
    else if (newText == 'B232-B30109') {
        $(this).text('ยิปซั่ม ตรา TOA ขอบเรียบ ทนชื้น 1.2x2.4m.x9');
    }
    else if (newText == 'B233-A10109') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B233-A10112') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.4m.x12');
    }
    else if (newText == 'B233-A10512') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x3m.x12');
    }
    else if (newText == 'B233-A10709') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.3m.x9');
    }
    else if (newText == 'B233-A10712') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.3m.x12');
    }
    else if (newText == 'B233-A10812') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด 1.2x2.7m.x12');
    }
    else if (newText == 'B233-A30112') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชื้น 1.2x2.4m.x12');
    }
    else if (newText == 'B233-A30512') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชิ้น 1.2x3m.x12');
    }
    else if (newText == 'B233-A30812') {
        $(this).text('ยิปซั่ม ตรา HOFF GM ขอบลาด ทนชิ้น 1.2x2.7m.x12');
    }
    else if (newText == 'B234-A10109') {
        $(this).text('ยิปซั่ม DIC ขอบลาด 1.2x2.4m.x9');
    }
    else if (newText == 'B227-B10412') {
        $(this).text('ยิปซั่ม TRUSUS ขอบเรียบ กันร้อน 1.22x3.05m.x12mm');
    }
    else if (newText == 'B144-A10109') {
        $(this).text('ยิปซั่ม GM เบาๆ ขอบลาด 1.2x2.4m.x9');
    }
    else {
        $(this).text('ไม่พบข้อมูลชนิดสินค้า');
    }
});
