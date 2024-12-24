{{-- <input type="text" class="form-control"  name="row_name"> --}}
{{-- <select name="row_name"  required>
    <option value="เลือกไลน์"> เลือกไลน์&nbsp;&nbsp;&nbsp;</option>
    <option value="1">1 </option>
    <option value="2">2 </option>
    <option value="3">3 </option>
    <option value="4">4 </option>
    <option value="5">5 </option>
    <option value="6">6 </option>
    <option value="7">7 </option>
    <option value="8">8 </option>
    <option value="9">9 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="21">21 </option>
    <option value="22">22 </option>
    <option value="23">23 </option>
    <option value="24">24 </option>
    <option value="25">25 </option>
    <option value="26">26 </option>
    <option value="27"> </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    <option value="10">10 </option>
    <option value="11">11 </option>
    <option value="12">12 </option>
    <option value="13">13 </option>
    <option value="14">14 </option>
    <option value="15">15 </option>
    <option value="16">16 </option>
    <option value="17">17 </option>
    <option value="18">18 </option>
    <option value="19">19 </option>
    <option value="20">20 </option>
    

    
</select> --}}
<select id="selectgroup"  aria-required="true" data-size="9" data-dropup-auto="false" data-style="btn-info btn-md text-white" data-width="fit">
    <option value="เลือกกลุ่ม"><h4>เลือกกลุ่ม  &nbsp;&nbsp;</h4></option>
        @foreach (range('1', '90') as $char)
            <option data-tokens="{{ $char }}" value="{{ $char }}">{{ $char }}</option>
        @endforeach
</select>
