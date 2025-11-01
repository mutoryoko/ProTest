<div class="item-image__wrapper" style="max-height: 200px;">
    <div class="image__wrapper" style="margin-left: 20px;">
        @if($image)
            <img src="{{ $previewUrl }}" alt="プレビュー" style="max-height: 180px;" />
        @endif
    </div>

    <div class="upload-btn__wrapper">
        <label class="file__upload-btn">
            画像を選択する
            <input wire:model="image" type="file" name="item_image" style="display:none;" />
        </label>
        @if($fileName)
            <span class="file-name">{{ $fileName }}</span>
        @endif
    </div>
</div>
