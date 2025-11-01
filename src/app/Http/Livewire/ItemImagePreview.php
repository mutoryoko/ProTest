<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ItemImagePreview extends Component
{
    use WithFileUploads;

    public $image;
    public $previewUrl;
    public $fileName = '選択されていません';

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image',
        ]);

        $this->fileName = $this->image->getClientOriginalName();

        $this->previewUrl = $this->image->temporaryUrl();
    }

    public function render()
    {
        return view('livewire.item-image-preview');
    }
}
