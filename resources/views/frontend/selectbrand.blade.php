<select id="brd_brandlist_id" name="brd_brandlist_id" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="false" data-live-search="true" data-style="btn-info btn-md text-white" data-width="fit" required>
    <option value="0">เลือกแบรนด์</option>
    @foreach ($brandLists as $brand)
        <option data-tokens="{{ $brand->bl_name }}" value="{{ $brand->bl_id }}">{{ $brand->bl_name }}</option>
    @endforeach
</select>
