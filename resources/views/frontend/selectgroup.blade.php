<select id="selectgroup" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="false" data-style="btn-info btn-md text-white" data-width="fit">
    <option value="เลือกกลุ่ม"><h4>เลือกกลุ่ม</h4></option>
        @foreach (range('A', 'B') as $char)
            <option data-tokens="{{ $char }}" value="{{ $char }}">{{ $char }}</option>
        @endforeach
</select>
