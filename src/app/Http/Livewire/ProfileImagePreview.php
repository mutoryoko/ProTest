<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileImagePreview extends Component
{
    use WithFileUploads;

    public $image; // 新しく選んだ画像
    public $existingImage; // DBに保存された画像
    public $fileName = '選択されていません';

    public function mount($existingImagePath)
    {
        $this->existingImage = $existingImagePath;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image'
        ]);
        // ファイル名を更新
        $this->fileName = $this->image->getClientOriginalName();
        // 既存画像を非表示
        $this->existingImage = null;
    }

    public function render()
    {
        return view('livewire.profile-image-preview');
    }
}
