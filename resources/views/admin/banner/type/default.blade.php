<div class="col-12 my-3">
    <b>Nội dung link</b>
</div>
<div class="col-6">
    <span>Link</span>
    <input type="text" class="form-control" name="link" value="{{$oneItem->link ?? ''}}" required>
</div>
<div class="col-2">
    <span>Target</span>
    <input class="form-control" type="text" name="target" value="{{$oneItem->target ?? '_blank'}}">
</div>
<div class="col-2">
    <span>Rel</span>
    <input class="form-control" type="text" name="rel" value="{{$oneItem->rel ?? 'nofollow'}}">
</div>

<div class="col-12 my-3">
    <b>Nội dung ảnh banner</b>
</div>
<div class="col-6">
    <label>Image</label>
    <input class="form-control" type="text" name="image" value="{{$oneItem->image ?? ''}}">
</div>
<div class="col-2">
    <span>Alt</span>
    <input type="text" class="form-control" name="alt" value="{{$oneItem->alt ?? ''}}">
</div>
<div class="col-2">
    <span>Width</span>
    <input required type="number" min="1" class="form-control" name="width" value="{{$oneItem->width ?? ''}}">
</div>
<div class="col-2">
    <span>Height</span>
    <input required type="number" min="1" class="form-control" name="height" value="{{$oneItem->height ?? ''}}">
</div>
