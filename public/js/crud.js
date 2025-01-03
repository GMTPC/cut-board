/*

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function(){

    $('#insertbarcode').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
           url: path+"/cutstockstore",
          data: $('#insertbarcode').serialize(),
           success: function(){
               Swal.fire({
                   icon: 'success',
                   title: 'สแกนบาร์โค้ดสำเร็จ',
                   html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                   showConfirmButton: false,
                   timer: 1000
                })
                 $("#fg_code").val('');
               window.setTimeout(function(){
                    $.get({url:path+'/cutstocktable/'+getdi_key_se,
                    beforeSend: function(){
                      $('#tablecuts').html('<td></td><td><h3>กำลังโหลดข้อมูล...</h3></td><td></td>');
                       },
                      success:function( data ) {
                            $('#tablecuts').html(data);
                       },
                       complete:function(data){
                      },
                  });
                    $.get({url:path+'/cutstockhidden/'+getdi_key_se,
                 beforeSend: function(){
                        $('#btnsubfg').button('loading');
                   },
                   success:function( data ) {
                        $('#hiddenfginput').html(data);
                       $('#btnsubfg').button('reset');
                   },
                    complete:function(data){

                   },
                });
               } ,1350);
           },error:function(){
                Swal.fire({
                   icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
               })
                
            }
        });
    });
 });

$(document).ready(function(){
    $('#insertbarcode').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: path+"/cutstockstore",
            data: $('#insertbarcode').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1500);
                console.log("ok"+result)
            },error:function(){

                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#insertbarcode_ref').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/cutstocks_input/"+getdi_key_se,
            data: $('#insertbarcode_ref').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    text: "กดปุ่ม บันทึก CSV เพื่อดูข้อมูล",
                    html: '',
                    html: '<h4 style="color:green;">กดปุ่ม บันทึก CSV เพื่อดูข้อมูล</h4><br><small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,3000);
            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });
    });
});



$(document).ready(function(){

    $('#inserttransfer').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/transfer_input",
            data: $('#inserttransfer').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#inserttransferbp').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/transferbangphli_input",
            data: $('#inserttransferbp').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#inserttransfernwtotoa').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/transferbangphli_input",
            data: $('#inserttransfernwtotoa').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#inserttransferwh').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/warehouse_input",
            data: $('#inserttransferwh').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#inserttransfernwtotoa').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/transfernwtotoa_input",
            data: $('#inserttransfernwtotoa').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#itemdecodeform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/fetchitemcode",
            data: $('#itemdecodeform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#formlading').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/billoflading_input/"+redirectVar+"/"+dikeyVar,
            data: $('#formlading').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#insertwipline1').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertwip/"+line+"/"+workid,
            data: $('#insertwipline1').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">สาเหตุอาจจะมาจากชนิดที่ไม่เหมือนกัน บาร์โค้ดซ้ำ  ยังไม่เลือกผู้คัด หรือ รูปแบบไม่ถูกต้อง</small><br><small style="color:red;">กด OK เพื่อดำเนินการต่อ</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$('.deleteline1').on('click', function(){

    $('#notideleteline1').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_line1id').val(data[0]);
    $('#barcodetarget').text(data[3]);
});

$(document).ready(function(){

    $('#deletfieldline1').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_line1id').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deleteline1wip/"+workid+"/"+id,
            data: $('#deletfieldline1').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#deletfieldfg').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_idfg').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deletecodefg/"+id,
            data: $('#deletfieldfg').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลเสร็จสิ้น',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                $("#fg_code").val('');
                $('#notideletefg').modal('hide');
                $('#fg_code').focus();
                window.setTimeout(function(){
                    $.get({url:path+'/cutstocktable/'+getdi_key_se,
                    beforeSend: function(){
                        $('#tablecuts').html('<td></td><td><h3>กำลังโหลดข้อมูล...</h3></td><td></td>');
                        },
                        success:function( data ) {

                            $('#tablecuts').html(data);
                        },
                        complete:function(data){
                        },
                    });
                    $.get({url:path+'/cutstockhidden/'+getdi_key_se,
                    beforeSend: function(){
                        $('#btnsubfg').button('loading');
                    },
                    success:function( data ) {
                        $('#hiddenfginput').html(data);
                        $('#btnsubfg').button('reset');
                    },
                    complete:function(data){

                    },
                });
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });
    });
});

$('.deletefgtransfernwtoa').on('click', function(){

    $('#notideletefgallnwbp').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_idfg').val(data[0]);
});

$('.deletefgtransfernwtoa').on('click', function(){

    $('#notideletefgnwtoa').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_idfg').val(data[0]);
});

$(document).ready(function(){

    $('#deletfieldfgnwtoa').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_idfg').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deletefg_transnwtoa/"+id,
            data: $('#deletfieldfgnwtoa').serialize(),
            success: "Success."
        });
        location.reload();
    });
});

$('.deletefgtransfernwtobp').on('click', function(){

    $('#notideletefgtransfernwbp').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_idfg').val(data[0]);
});

$(document).ready(function(){

    $('#deletefgtransfernwbp').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_idfg').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deletefg_transnwtoa/"+id,
            data: $('#deletefgtransfernwbp').serialize(),
            success: "Success."
        });
        location.reload();
    });
});

$('.deletefgtransferwh').on('click', function(){

    $('#notideletefgwh').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_idfg').val(data[0]);
});

$(document).ready(function(){

    $('#deletfieldfgwh').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_idfg').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deletefg_transwh/"+id,
            data: $('#deletfieldfgwh').serialize(),
            success: "Success."
        });
        location.reload();
    });
});


$(document).ready(function(){
    var white_list = ["32","33","36","37","38"]; //fn
    var white_manu = "44"; //98
    var white_qc = "31"; //99


    $('#outfgform').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: path+"/outfgcode/"+line+"/"+workid,
            data: $('#outfgform').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                })
                // if(white_list.indexOf(result.brd_brandlist_id) == -1) {
                //     window.setTimeout(function(){
                //         window.open(path+'/tagfg/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                //     } ,1000);
                // }
                // else{
                //     window.setTimeout(function(){
                //         window.open(path+'/tagfn/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                //     } ,1000);
                // }


                // console.log(result.brd_brandlist_id)
                if (result.brd_brandlist_id == white_qc) {
                    window.setTimeout(function(){
                        window.open(path+'/tagwipqc/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                    } ,1000);
                }
                else if(result.brd_brandlist_id == white_manu) { //99
                    window.setTimeout(function(){
                        window.open(path+'/tagwipnn/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                    } ,1000);
                }
                else if(white_list.indexOf(result.brd_brandlist_id) == -1) { //fn
                    window.setTimeout(function(){
                        window.open(path+'/tagfg/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                    } ,1000);
                }

                else {
                    window.setTimeout(function(){ //98
                        window.open(path+'/tagfn/'+line+"/"+workid+"/"+result.brd_id,'name','width=800,height=600');
                    } ,1000);
                }

                window.setTimeout(function(){
                    location.reload();
                } ,1500);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$('.inputng').on('click', function(){

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#inputng_id').val(data[0]);
    $('#showbarcodewip').text(data[3]);

});

$(document).ready(function(){

    $('#inputngform').on('submit',function(e){
        e.preventDefault();
        var id = $('#inputng_id').val()
        $.ajax({
            type: "POST",
            url: path+"/addng",
            data: $('#inputngform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#formemployee').on('submit',function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/inputem/"+line,
            data: $('#formemployee').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1300);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$('.deleteemp').on('click', function(){

    $('#notideleteemp').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_empid').val(data[4]);
});

$(document).ready(function(){

    $('#deletempform').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_empid').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deleteemp/"+id,
            data: $('#deletempform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#addbrandlistform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/inputbrandslist",
            data: $('#addbrandlistform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#addnglistform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/inputlistng",
            data: $('#addnglistform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#forminputend').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/endprocess/"+line+"/"+workid,
            data: $('#forminputend').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                })
                if (result.ws_holding_amount > 0) {
                    window.setTimeout(function(){
                        window.open(path+'/taghd/'+line+"/"+workid+"/"+result.wh_id,'name','width=800,height=600');
                    } ,1000);
                }
                window.setTimeout(function(){
                    location.reload();
                } ,1500);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#endworktimeform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/endworktime/"+line,
            data: $('#endworktimeform').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    window.open(path+'/endtimeinterface/'+line+'/'+result.wwt_index,'name','width=800,height=600');
                } ,1000);
                window.setTimeout(function(){
                    location.reload();
                } ,1500);

            },
            // error:function(){
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'บันทึกข้อมูลไม่สำเร็จ',
            //         html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
            //         showConfirmButton: true,
            //     })
            // }
        });
    });
});

$('.deleteoutfg').on('click', function(){

    $('#notideleteoutfg').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_outfgid').val(data[0]);
    $('#showoutfg').text(data[2]);
});

$(document).ready(function(){

    $('#deletoutfg').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_outfgid').val()
        $.ajax({
            type: "DELETE",
            url: path+"/deleteoutfg/"+workid+"/"+id,
            data: $('#deletoutfg').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });
    });
});

$('.deletwork').on('click', function(){

    $('#notideletework').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_workid').val(data[5]);
});

// pruksapo
$('.deletpo').on('click', function(){

    $('#podelete').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#delete_po').val(data[5]);
});

$(document).ready(function(){

    $('#deletworkform').on('submit',function(e){
        e.preventDefault();
        // var id = $('#delete_workid').val()
        let id = document.getElementById('deletworkform2').value
        // console.log(id)
        $.ajax({
            type: "DELETE",
            url: path+"/deletework/"+parseInt(id),
            data: $('#deletworkform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);
            }
        });
    });
});

// pruksapo
$(document).ready(function(){

    $('#deletformpo').on('submit',function(e){
        e.preventDefault();
        var id = $('#delete_po').val()
        $.ajax({
            type: "DELETE",
            url: path+"/pruksadelete/"+id,
            data: $('#deletformpo').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1200);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        });
    });
});

$('.clinereportupdate').on('click', function(){

    $('#clineupdate').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#editclineid').val(data[0]);
    $('#showcline').text(data[2]);
});

$(document).ready(function(){

    $('#editclineform').on('submit', function(e){
        e.preventDefault();
        var id = $('#editclineid').val();
        $.ajax({
            type: "POST",
            url: path+"/clinereportupdate/"+id,
            data: $('#editclineform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$('.editbrand').on('click', function(){

    $('#notieditbrand').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#editbrandid').val(data[0]);
    $('#showoutlot').text(data[2]);
});

$(document).ready(function(){

    $('#editbrandform').on('submit', function(e){
        e.preventDefault();
        var id = $('#editbrandid').val();
        $.ajax({
            type: "POST",
            url: path+"/editbrand/"+id,
            data: $('#editbrandform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#formworking').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/inputworking/"+line,
            data: $('#formworking').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">โปรดรอสักครู่ ระบบกำลังนำพาท่านเข้าสู่งาน</small>',
                    showConfirmButton: false,
                })
                window.setTimeout(function(){
                    location.href=path+'/wip/'+line+'/'+result.ww_id;
                } ,2500);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#formgroupemp').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/appempgroup/"+line,
            data: $('#formgroupemp').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){
    $('#empgrouptable').on('change','.toggle-egstatus',function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        console.log(status);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: path+"/egstatus/"+line,
            data: {
                'eg_status': status,
                'eg_id': id
            },
            success: function(data){
                if (data.eg_status == 1) {
                    notif({
                        msg: "<b>เปิดการใช้งาน "+data.name1+" - "+data.name2+" แล้ว</b>",
                        type: "success"
                    });
                }
                else {
                    notif({
                        msg: "<b>ปิดการใช้งาน "+data.name1+" - "+data.name2+" แล้ว</b>",
                        type: "warning"
                    });
                }
            },error: function(){
                notif({
                    msg: "<b>เกิดข้อผิดพลาด</b>",
                    type: "error"
                });
            }
        });
    });
});

$(document).ready(function(){
    $('#brnadslisttable').on('change','.toggle-blstatus',function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        console.log(status);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: path+"/blstatus",
            data: {
                'bl_status': status,
                'bl_id': id
            },
            success: function(data){
                if (data.bl_status == 1) {
                    notif({
                        msg: "<b>เปิดการใช้งาน "+data.bl_code+" - "+data.bl_name+" แล้ว</b>",
                        type: "success"
                    });
                }
                else {
                    notif({
                        msg: "<b>ปิดการใช้งาน "+data.bl_code+" - "+data.bl_name+" แล้ว</b>",
                        type: "warning"
                    });
                }
            },error: function(){
                notif({
                    msg: "<b>เกิดข้อผิดพลาด</b>",
                    type: "error"
                });
            }
        });
    });
});

$(document).ready(function(){
    $('#nglisttable').on('change','.toggle-lngstatus',function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        console.log(status);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: path+"/lngstatus",
            data: {
                'lng_status': status,
                'lng_id': id
            },
            success: function(data){
                if (data.lng_status == 1) {
                    notif({
                        msg: "<b>เปิดการใช้งาน "+data.lng_name+" แล้ว</b>",
                        type: "success"
                    });
                }
                else {
                    notif({
                        msg: "<b>ปิดการใช้งาน "+data.lng_name+" แล้ว</b>",
                        type: "warning"
                    });
                }
            },error: function(){
                notif({
                    msg: "<b>เกิดข้อผิดพลาด</b>",
                    type: "error"
                });
            }
        });
    });
});


$('.inputwipamount').on('click', function(){

    $('#notiamount').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();

    $('#wipnewamount').val(data[1]);
    $('#wipbarcodechange').val(data[3]);
    $('#wipidamount').val(data[0]);
    $('#showwipbarcode2').text(data[3]);


    $('#wipnewamount').change(function(){
        var zero = '0';
        var amount = $(this).val();
        var barcode = $('#wipbarcodechange').val().substr(0,21);
        if (amount >= 100){
            $('#wipbarcodechange').val(barcode+amount);
        }
        else if (amount > 9) {
            $('#wipbarcodechange').val(barcode+zero+amount);
        }
        else if (amount <= 9) {
            $('#wipbarcodechange').val(barcode+zero+zero+amount);
        }
        else {
            $('#wipbarcodechange').val(barcode+amount);
        }
    });
});

$(document).ready(function(){
    $('#editamountform').on('submit',function(e){
        e.preventDefault();
        var id = $('#wipidamount').val();
        $.ajax({
            type: "POST",
            url: path+"/editwipamg/"+id,
            data: $('#editamountform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1300);

            },error:function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        });
    });

});

$(document).ready(function(){

    $('#edittypecolorform').on('submit', function(e){
        e.preventDefault();
        var id = $('#brdid').val();

        $.ajax({
            type: "POST",
            url: path+"/edittypecolor/"+id,
            data: $('#edittypecolorform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#homeplanform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/inputhomeplan",
            data: $('#homeplanform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#uploadpk').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pkupload",
            data: $('#uploadpk').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#pkoutform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pkoutinsert",
            data: $('#pkoutform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){


    $('#qrcodeform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertcheckcsvqrcode",
            data: $('#qrcodeform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#pkclineform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pkoutcline",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'กรุณารอสักครู่...',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#pkrimform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pkoutrim",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'กรุณารอสักครู่...',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#pkparcelform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pkoutparcel",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'กรุณารอสักครู่...',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


// $(document).ready(function(){

//     $('#simple').on('submit', function(e){
//         e.preventDefault();

//         $.ajax({
//             type: "POST",
//             url: path+"/pkoutrim",
//             data: new FormData(this).serialize(),
//             contentType: false,
//             processData: false,
//                 beforeSend: function(){
//                     Swal.fire({
//                         title: 'กรุณารอสักครู่...',
//                         html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
//                         showConfirmButton: false
//                     })
//                 },
//             success: function(){
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'บันทึกเรียบร้อย',
//                     html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
//                     showConfirmButton: false
//                 })
//                 window.setTimeout(function(){
//                     location.reload();
//                 } ,1350);

//             },error:function(){
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'บันทึกข้อมูลไม่สำเร็จ',
//                     html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
//                     showConfirmButton: true,
//                 })
//             }
//         });
//     });
// });

// $(document).ready(function(){

//     $('#simple').on('submit', function(e){
//         e.preventDefault();

//         $.ajax({
//             type: "POST",
//             url: path+"/pkoutparcel",
//             data: new FormData(this).serialize(),
//             contentType: false,
//             processData: false,
//                 beforeSend: function(){
//                     Swal.fire({
//                         title: 'กรุณารอสักครู่...',
//                         html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
//                         showConfirmButton: false
//                     })
//                 },
//             success: function(){
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'บันทึกเรียบร้อย',
//                     html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
//                     showConfirmButton: false
//                 })
//                 window.setTimeout(function(){
//                     location.reload();
//                 } ,1350);

//             },error:function(){
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'บันทึกข้อมูลไม่สำเร็จ',
//                     html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
//                     showConfirmButton: true,
//                 })
//             }
//         });
//     });
// });

$(document).ready(function(){

    $('#pkboqconform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertconboq",
            data: new FormData(this),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#checkcsvform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertcheckcsv",
            data: $('#checkcsvform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                $("#ccw_barcode").val('');
                window.setTimeout(function(){
                    $.get(path+"/csvdetailrealtime/", function(data) {
                        $('#csvdetailrealtime').html(data);
                    });
                } ,180000);
            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#csvindexform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertcheckcsvindex",
            data: $('#csvindexform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })

                window.setTimeout(function(){
                    window.open(path+'/outcheckcsvwh/'+result.indexno,'name','width=800,height=600');
                } ,1000);
                window.setTimeout(function(){
                    location.reload();
                } ,2000);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
                $('#noticsvindex').modal('hide');
            }
        });
    });
});

$(document).ready(function(){
    $('.editempwip').on('click',function(){
        var id = $(this).data('wipid');
        var wip = $(this).data('barcode');
        var egid = $(this).data('egid');
        $('#empwipid').val(id);
        $('#empwipbarcode').text(wip);
        $('#empgropidwip').val(egid);
        console.log(egid);
    });
    $('#editempwipform').on('submit', function(e){
        e.preventDefault();
        var wipid = $('#empwipid').val();
        $.ajax({
            type: "PUT",
            url: path+"/editempwip/"+line+"/"+workid+"/"+wipid,
            data: $('#editempwipform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){


    $('#deleteccwform').on('submit',function(e){
        e.preventDefault();
        var ccw_id = $('#ccw_id_hiden').val();
        $.ajax({
            type: "DELETE",
            url: path+"/deleteccw/"+ccw_id,
            data: $('#deleteccwform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#deleteccwbarcode').modal('hide');
                window.setTimeout(function(){
                    $.get(path+"/csvdetailrealtime/", function(data) {
                        $('#csvdetailrealtime').html(data);
                    });
                } ,180000);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


$(document).ready(function() {
    var basePath = "https://gypman-tech.com/";
    $("#wl_code_list").autocomplete({
      source:function(request, cb) {
        // console.log(request)
        $.ajax({
          url: path+'/autocomplete/'+request.term,
          method: 'GET',
          dataType: 'json',
          success: function(res) {
            var result;
            result = [
              {
                label: 'There is matching record found for '+request.term,
                value: ''
              }
            ];
            console.log(res)
            if(res.length) {
              result = $.map(res, function(obj) {
                return {
                    label: obj.WL_CODE,
                    value: obj.WLC0DE,
                    data: obj
                };
              });
            }
            cb(result);
          }
        })
      },
      select:function(e, selectData) {
        console.log(selectData);

        if(selectData && selectData.item && selectData.item.data) {
          var data = selectData.item.data;
          $('#wl_name').val(data.WL_NAME);
          $('#wh_name').val(data.WH_NAME);
        }
      }
    });
});

$(document).ready(function(){

    $('#pruksapo_post').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pruksacreate",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        // html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        // showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    // icon: 'success',
                    title: 'อัพเดทเรียบร้อย',
                    html: '<small style="color:green;">สามารถเพิ่มแบบบ้านในขั้นตอนต่อไป</small>',
                    // showConfirmButton: false
                })

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

// $(document).ready(function(){
//     $('#pruksapo_post').on('submit',function(e){
//         e.preventDefault();
//         $.ajax({
//             type: "POST",
//             url: path+"/pruksacreate",
//             data: $('#pruksapo_post').serialize(),
//             success: function(result){
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'บันทึกข้อมูลแล้ว',
//                     // html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
//                     // showConfirmButton: false,
//                     // timer: 1600
//                 })
//             //     window.setTimeout(function(){
//             //         location.reload();
//             //     } ,1500);
//             //     console.log("ok"+result)
//             // },error:function(){

//                 Swal.fire({
//                     icon: 'error',
//                     title: 'บันทึกข้อมูลไม่สำเร็จ',
//                     html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
//                     showConfirmButton: true,
//                 })
//             }
//         });
//     });
// });
// $dd;
$(document).ready(function(){
    $('#wpszone_post').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: path+"/warehousezonecreate",
            data: $('#wpszone_post').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1500);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){
    $('#wpslocation_post').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: path+"/warehouselocationcreate",
            data: $('#wpslocation_post').serialize(),
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1500);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#wpslocationadd').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/forkliftinterface",
            data: $('#wpslocationadd').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 3000
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);
            }

        });
        location.reload();
    });
});

$(document).ready(function(){

    $('#pkimgform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/pruksauploadimg",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'กรุณารอสักครู่...',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#checkflform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertcheckfl",
            data: $('#checkflform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                $("#ccw_barcode").val('');
                window.setTimeout(function(){
                    $.get(path+"/csvdetailrealtime/", function(data) {
                        $('#csvdetailrealtime').html(data);
                    });
                } ,180000);
            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


$(document).ready(function(){


    $('#deleteflform').on('submit',function(e){
        e.preventDefault();
        var fl_id = $('#fl_id_hiden').val();
        $.ajax({
            type: "DELETE",
            url: path+"/deletefl/"+fl_id,
            data: $('#deleteflform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#deleteflbarcode').modal('hide');
                window.setTimeout(function(){
                    $.get(path+"/flrealtime/", function(data) {
                        $('#flrealtime').html(data);
                    });
                } ,180000);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});




$(document).ready(function(){

    $('#flindexform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/editwpszone",
            data: $('#flindexform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })

                window.setTimeout(function(){
                    window.open(path+'/outcheckcsvwh/'+result.indexno,'name','width=800,height=600');
                } ,1000);
                window.setTimeout(function(){
                    location.reload();
                } ,2000);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
                $('#noticsvindex').modal('hide');
            }
        });
    });
});



$(document).ready(function(){

    $('#flhistoryindexform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/savehistoryfl",
            data: $('#flhistoryindexform').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                $("#ccw_barcode").val('');
                window.setTimeout(function(){
                    $.get(path+"/csvdetailrealtime/", function(data) {
                        $('#csvdetailrealtime').html(data);
                    });
                } ,180000);
            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ fl',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
                
            }
        });
    });
});

$(document).ready(function(){

    $('#updatefl').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/cutstocks_update/"+getdi_key_se,
            data: $('#updatefl').serialize(),
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                })
            },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1000
                })
                $("#ccw_barcode").val('');
                window.setTimeout(function(){
                    $.get(path+"/csvdetailrealtime/", function(data) {
                        $('#csvdetailrealtime').html(data);
                    });
                } ,180000);
            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ fl',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
                
            }
        });
    });
});

$(document).ready(function(){

    $('#apiform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertapiwlhmoe",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function() {
    var basePath = "https://gypman-tech.com/";
    $("#bdc_ar_name_list").autocomplete({
      source:function(request, cb) {
        // console.log(request)
        $.ajax({
          url: path+'/autocompletedebtor/'+request.term,
          method: 'GET',
          dataType: 'json',
          success: function(res) {
            var result;
            result = [
              {
                label: 'There is matching record found for '+request.term,
                value: ''
              }
            ];
            console.log(res)
            if(res.length) {
              result = $.map(res, function(obj) {
                return {
                    label: obj.AR_NAME,
                    value: obj.ARNAME,
                    data: obj
                };
              });
            }
            cb(result);
          }
        })
      },
      select:function(e, selectData) {
        console.log(selectData);

        if(selectData && selectData.item && selectData.item.data) {
          var data = selectData.item.data;
          $('#bdc_wl').val(data.WL);
          $('#bdc_arc').val(data.ARC);
          $('#bdc_arn').val(data.ARN);
          $('#bdc_ar_code').val(data.AR_CODE);
          $('#bdc_arg_name').val(data.ARG_NAME);
          $('#bdc_condition').val(data.CONDITION);
          $('#bdc_conditionset').val(data.CONDITIONSET);
          $('#bdc_blcondition').val(data.BLCONDITION);
          $('#bdc_branch').val(data.BRANCH);
          $('#bdc_addb_tax_id').val(data.ADDB_TAX_ID);
          $('#bdc_adr').val(data.ADR);
          $('#bdc_phone').val(data.PHONE);
          $('#bdc_fax').val(data.FAX);
          $('#bdc_website').val(data.WEBSITE);
          $('#bdc_email').val(data.EMAIL);
          
        }
      }
    });
});

$(document).ready(function(){

    $('#insertdebtorinview').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertdebtor",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'กำลังตรวจสอบ',
                        // html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false
                    })
                },
            success: function(){
                Swal.fire({
                    // icon: 'success',
                    title: 'ตรวจสอบข้อมูลเรียบร้อย',
                    html: '<small style="color:green;">สามารถดำเนินการต่อได้ตามขั้นตอน</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,400);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#builderconfirmfrom').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/insertbuilderconfirm",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


// $(document).ready(function() {
//     var basePath = "https://gypman-tech.com/";
//     $("#bd_ar_name_list").autocomplete({
//       source:function(request, cb) {
//         // console.log(request)
//         $.ajax({
//           url: path+'/autocompleteproduct/'+request.term,
//           method: 'GET',
//           dataType: 'json',
//           success: function(res) {
//             var result;
//             result = [
//               {
//                 label: 'There is matching record found for '+request.term,
//                 value: ''
//               }
//             ];
//             console.log(res)
//             if(res.length) {
//               result = $.map(res, function(obj) {
//                 return {
//                     label: obj.WL_CODE,
//                     value: obj.WLCODE,
//                     data: obj
//                 };
//               });
//             }
//             cb(result);
//           }
//         })
//       },
//       select:function(e, selectData) {
//         console.log(selectData);

//         if(selectData && selectData.item && selectData.item.data) {
//           var data = selectData.item.data;
//           $('#bd_arn').val(data.WL_NAME);
//           $('#bd_wl').val(data.WL_CODE);
//         //   $('#wh_name').val(data.WH_NAME);
//         }
//       }
//     });
// });



$(document).ready(function(){

    $('#editbrandform').on('submit', function(e){
        e.preventDefault();
        var id = $('#editbrandid').val();
        $.ajax({
            type: "POST",
            url: path+"/editbrand/"+id,
            data: $('#editbrandform').serialize(),
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                })
                window.setTimeout(function(){
                    location.reload();
                } ,1350);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


$(document).ready(function(){

    $('#updatewl').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/updatebtorcontract",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#updatewlcode').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/buildereditdebtor",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});


$(document).ready(function(){

    $('#updatetest').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/updatebtorcontract",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});

$(document).ready(function(){

    $('#reactinput').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: path+"/actupdateslip",
            data: new FormData(this),
            contentType: false,
            processData: false,
                beforeSend: function(){
                    Swal.fire({
                        title: 'บันทึกสำเร็จ',
                        html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                        showConfirmButton: false 
                    })
                },
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                })
                window.setTimeout(function(){
                    location.reload();
                } ,800);

            },error:function(){
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                })
            }
        });
    });
});
*/
