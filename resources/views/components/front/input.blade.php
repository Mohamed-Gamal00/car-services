<div class="col-md-6 col-sm-6 col-xs-6 fw-bold">
    <label>{{$name}}</label>
    <input type="{{ $type }}" name="{{ $column }}" value="{{ isset($value) ? $value : old($column) }}">
    @error($column)
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
