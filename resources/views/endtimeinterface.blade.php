<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <title>Endtime Interface</title>
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css') }}">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <div class="text-right">
                <a href="{{ route('csvendtime', ['line' => $line, 'index' => $index, 'workprocess' => implode(',', $workprocess)]) }}" 
   class="btn btn-success btn-lg">
    บันทึก CSV
</a>


                </div>
            </div>
            <div class="col-xs-6">
                <div class="text-left">
                    <a href="" class="btn btn-warning btn-lg" name="button">พิมพ์ TAG แผ่นเสีย</a>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('AdminLTE-master/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</html>
