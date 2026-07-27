<?php

namespace App\Livewire\Attachments;

use App\Models\Song;
use App\Models\SongAttachment;
use Livewire\Component;
use Livewire\WithFileUploads;

class SongAttachments extends Component
{
    use WithFileUploads;

    public Song $song;
    public $file = null;
    public string $name = '';
    public string $type = 'other';
    public bool $showUploadForm = false;

    protected function rules()
    {
        return [
            'file' => ['required', 'file', 'max:102400'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
        ];
    }

    public function mount(Song $song)
    {
        $this->song = $song->load('attachments.uploader');
    }

    public function upload()
    {
        $this->validate();

        $path = $this->file->store('song-attachments/' . $this->song->id, 'public');

        $this->song->attachments()->create([
            'name' => $this->name,
            'type' => $this->type,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        $this->reset(['file', 'name', 'type', 'showUploadForm']);
        $this->song->refresh();
        $this->dispatch('toast', message: 'Attachment uploaded successfully', type: 'success');
    }

    public function delete($attachmentId)
    {
        $attachment = $this->song->attachments()->find($attachmentId);
        if ($attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
            $this->song->refresh();
            $this->dispatch('toast', message: 'Attachment deleted', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.attachments.song-attachments', [
            'attachments' => $this->song->attachments,
        ]);
    }
}
