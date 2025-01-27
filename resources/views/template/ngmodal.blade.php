    <div class="modal fade"  id="notiinputng" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="InputNg"><b>เพิ่มข้อมูลของเสีย</b></h3>
                    <h4><b>Barcode : <i id="showbarcodewip"></i></b></h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <h4><b>สรุปรายการของเสีย</b></h4>
                        <div class="table-responsive ">
                            <table class="table table-hover table-striped table-bordered" id="listresultng">
                                <thead>
                                    <tr>
                                        <th class="text-center">ของเสีย</th>
                                        <th class="text-center">จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ngamount as $ngamounts)
                                        <tr>
                                            <td>{{ $ngamounts->amg_amount }}</td>
                                            <td>{{ $ngamounts->lng_name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <input class="inputng_id" type="text" name="inputng_id" id="inputng_id">
                        <div id="panel-ng" class="panel panel-gmt">
                            <div class="panel-heading text-center" style="font-size:18px;">เพิ่มข้อมูลของเสีย</div>
                            <div class="panel-body" style="padding-top: 0px;padding-left: 0px;">
                                <br>
                                <div class="text-center">
                                    <a class="btn btn-default btn-sm" style="font-size:13px;" id="addl1a" href="#" role="button"><span class="glyphicon glyphicon-plus"></span>&nbsp;เพิ่มของที่เสีย</a>
                                </div>
                                <form id="inputngform" class="form-inline md-form form-sm mt-0">
                                    {{ csrf_field() }}
                                    <div class="container-fluid">
                                        <div class="table-responsive">
                                            <table class="table" id="wipline1awaste">
                                                <tr>
                                                    <th class="text-left">ของเสีย</th>
                                                    <th class="text-center">จำนวนที่เสีย</th>
                                                </tr>
                                            </table>
                                            <div class="text-right">
                                                <button id="removelistng" class="btn btn-warning btn-sm " type="button" name="button"><span class="fas fa-redo-alt"></span>&nbsp;ทำใหม่</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="fas fa-save btn btn-success" type="submit">  บันทึก</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
