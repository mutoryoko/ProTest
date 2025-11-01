<div class="user-image__preview">
    <div class="user-image__wrapper">
        @if ($image)
            <img class="preview" src="{{ $image->temporaryUrl() }}" alt="プレビュー" />
        @elseif ($existingImage)
            <img class="preview" src="{{ asset('storage/'.$existingImage) }}" alt="現在の画像" />
        @endif
    </div>
    <div class="upload-btn__wrapper">
        <label class="file__upload-btn">
            画像を選択する
            <input wire:model="image" type="file" name="user_image" style="display:none;" />
        </label>
        @if($fileName)
            <span class="file-name">{{ $fileName }}</span>
        @endif
    </div>
</div>
