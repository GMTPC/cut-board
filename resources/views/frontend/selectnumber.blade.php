<select id="selectnum" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="false" data-live-search="true" data-style="btn-info btn-sm text-white" data-width="fit">
    <option value="0">เลือกกลุ่ม</option>
        @foreach (range('1', '9') as $char)
            <option data-tokens="{{ $char }}" value="{{ $char }}">{{ $char }}</option>
        @endforeach
</select>
