<?php

namespace App\Livewire\Songs;

use App\Enums\Difficulty;
use App\Enums\LinkType;
use App\Models\Song;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SongForm extends Component
{
    public ?Song $song = null;
    public bool $isEditing = false;

    // Form fields
    public string $title = '';
    public string $artist = '';
    public string $genre = '';
    public string $key = '';
    public ?int $bpm = null;
    public ?int $duration = null;
    public ?string $difficulty = null;
    public string $tuning = 'Standard';
    public ?int $capo = null;
    public string $notes = '';
    public bool $is_favorite = false;
    
    // Arrays for dynamic fields
    public array $selectedTags = [];
    public array $links = [];

    // Helper data
    public array $allTags = [];
    public array $difficulties = [];
    public array $linkTypes = [];

    public function mount(?Song $song = null)
    {
        $this->allTags = Tag::orderBy('name')->get()->toArray();
        $this->difficulties = Difficulty::cases();
        $this->linkTypes = LinkType::cases();

        if ($song && $song->exists) {
            $this->authorize('update', $song);
            $this->song = $song;
            $this->isEditing = true;
            $this->fillForm();
        } else {
            // Default link empty slot
            $this->addLink();
        }
    }

    private function fillForm()
    {
        $this->title = $this->song->title;
        $this->artist = $this->song->artist ?? '';
        $this->genre = $this->song->genre ?? '';
        $this->key = $this->song->key ?? '';
        $this->bpm = $this->song->bpm;
        $this->duration = $this->song->duration;
        $this->difficulty = $this->song->difficulty?->value;
        $this->tuning = $this->song->tuning ?? 'Standard';
        $this->capo = $this->song->capo;
        $this->notes = $this->song->notes ?? '';
        $this->is_favorite = $this->song->is_favorite;

        $this->selectedTags = $this->song->tags->pluck('id')->toArray();
        
        foreach ($this->song->links as $link) {
            $this->links[] = [
                'id' => $link->id,
                'type' => $link->type->value,
                'url' => $link->url,
                'label' => $link->label,
            ];
        }
        
        if (empty($this->links)) {
            $this->addLink();
        }
    }

    public function addLink()
    {
        $this->links[] = [
            'id' => null,
            'type' => LinkType::Spotify->value,
            'url' => '',
            'label' => '',
        ];
    }

    public function removeLink($index)
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:100'],
            'key' => ['nullable', 'string', 'max:10'],
            'bpm' => ['nullable', 'integer', 'min:20', 'max:300'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'difficulty' => ['nullable', Rule::enum(Difficulty::class)],
            'tuning' => ['nullable', 'string', 'max:50'],
            'capo' => ['nullable', 'integer', 'min:0', 'max:12'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'is_favorite' => ['boolean'],
            'selectedTags' => ['array'],
            'selectedTags.*' => ['exists:tags,id'],
            'links' => ['array'],
            'links.*.type' => ['required_with:links.*.url', Rule::enum(LinkType::class)],
            'links.*.url' => ['nullable', 'url', 'max:2048'],
            'links.*.label' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        $songData = collect($validatedData)
            ->except(['selectedTags', 'links'])
            ->toArray();
            
        // Convert empty strings to null for nullable db fields
        foreach (['artist', 'genre', 'key', 'difficulty', 'tuning', 'notes'] as $field) {
            if (empty($songData[$field])) {
                $songData[$field] = null;
            }
        }

        if ($this->isEditing) {
            $this->song->update($songData);
        } else {
            $songData['created_by'] = auth()->id();
            $this->song = Song::create($songData);
        }

        // Sync tags
        $this->song->tags()->sync($this->selectedTags);

        // Sync links (delete old ones not in array, update existing, create new)
        $validLinks = collect($this->links)->filter(fn($l) => !empty($l['url']));
        
        $this->song->links()->whereNotIn('id', $validLinks->pluck('id')->filter())->delete();
        
        foreach ($validLinks as $linkData) {
            if (!empty($linkData['id'])) {
                $this->song->links()->where('id', $linkData['id'])->update([
                    'type' => $linkData['type'],
                    'url' => $linkData['url'],
                    'label' => $linkData['label'],
                ]);
            } else {
                $this->song->links()->create([
                    'type' => $linkData['type'],
                    'url' => $linkData['url'],
                    'label' => $linkData['label'],
                ]);
            }
        }

        return redirect()->route('songs.show', $this->song);
    }

    public function render()
    {
        return view('livewire.songs.song-form')
            ->layout('components.layouts.app')
            ->title($this->isEditing ? 'Edit Song' : 'Add New Song');
    }
}
