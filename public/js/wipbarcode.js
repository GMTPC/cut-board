
$('.codewip3').each(function(){
    var newText = ($(this).text()).substr(0,4);
    if (newText == 'W199' || newText == 'B199') {
        $(this).text('แผ่นรอคัด Line 1');
    }
    else if (newText == 'W299' || newText == 'B299') {
        $(this).text('แผ่นรอคัด Line 2');
    }
    else if (newText == 'W399' || newText == 'B399') {
        $(this).text('แผ่นรอคัด Line 3');
    }
    else {
        $(this).text('ไม่พบข้อมูลชนิดสินค้า');
    }
});

$(document).ready(function(){

    var itemDay = $('.wipline1code').text().substr(11,6);
    var n = 1;
    $('.outfg2').each(function(){
        var item = $(this).text().substr(4,7);
        var line = $(this).text().substr(0,2);
        var brand = $(this).text().substr(24,2);
        var amount = $(this).text().substr(26,17);
        if (brand == 'no') {
            $(this).text(' ');
        }
        else {
            if (line == 'W1') {
                $(this).text('BX' + brand + item + 'L1++++++++' + amount);
                n++;
            }
            else if (line == 'W2') {
                $(this).text('BX' + brand + item + 'L2++++++++' + amount);
                n++;
            }
            else if (line == 'W3') {
                $(this).text('BX' + brand + item + 'L3++++++++' + amount);
                n++;
            }
            else {
                $(this).text('BX' + brand + item + 'L4++++++++' + amount);
                n++;
            }
        }
    });
});

$('.amountline1').each(function(){
    var amount = $(this).text();
    if (amount == '0') {
        $(this).text(' ');
    }
    else {
        $(this).text(amount);
    }
});
