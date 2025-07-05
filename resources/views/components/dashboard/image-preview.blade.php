<div class="row mb-3 mt-5">
    <label for="example-text-input" class="col-sm-2 col-form-label fw-bold">{{$title}}</label>
    <div class="col-sm-10">
        <x-form.input id="imageUpload" type="file" name="{{$fileName}}" accept="image/*"/>
        @if($fileName)
            <img src="{{$image}}" id="imagePreview" class="rounded mt-2" alt="Preview" width="{{$width}}"
                 height="{{$heigh}}">
        @endif
    </div>
</div>
