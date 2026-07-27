<?php

namespace App\Livewire\Rehearsals;

use App\Models\Rehearsal;
use App\Models\Setlist;
use App\Models\User;
use Livewire\Component;

class RehearsalForm extends Component
{
    public ?Rehearsal $rehearsal = null;
    public bool $isEditing = false;

    public string $title = '';
    public string $date = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $location = '';
    public string $description = '';
    public ?int $setlist_id = null;
    public array $selectedMembers = [];

    public function mount(?Rehearsal $rehearsal = null)
    {
        if ($rehearsal && $rehearsal->exists) {
            $this->rehearsal = $rehearsal;
            $this->isEditing = true;
            $this->fillForm();
        }
    }

    private function fillForm()
    {
        $this->title = $this->rehearsal->title;
        $this->date = $this->rehearsal->date->format('Y-m-d');
        $this->start_time = $this->rehearsal->start_time?->format('H:i') ?? '';
        $this->end_time = $this->rehearsal->end_time?->format('H:i') ?? '';
        $this->location = $this->rehearsal->location ?? '';
        $this->description = $this->rehearsal->description ?? '';
        $this->setlist_id = $this->rehearsal->setlist_id;
        $this->selectedMembers = $this->rehearsal->members->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'setlist_id' => ['nullable', 'exists:setlists,id'],
            'selectedMembers' => ['array'],
            'selectedMembers.*' => ['exists:users,id'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $data = [
            'title' => $validated['title'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'] ?: null,
            'end_time' => $validated['end_time'] ?: null,
            'location' => $validated['location'] ?: null,
            'description' => $validated['description'] ?: null,
            'setlist_id' => $validated['setlist_id'] ?: null,
        ];

        if ($this->isEditing) {
            $this->rehearsal->update($data);
            $this->rehearsal->members()->sync($this->selectedMembers);
        } else {
            $data['created_by'] = auth()->id();
            $this->rehearsal = Rehearsal::create($data);
            $this->rehearsal->members()->attach($this->selectedMembers);
        }

        return redirect()->route('rehearsals.show', $this->rehearsal);
    }

    public function render()
    {
        $setlists = Setlist::orderBy('title')->get();
        $members = User::whereIn('role', ['admin', 'member'])->orderBy('name')->get();

        return view('livewire.rehearsals.rehearsal-form', [
            'setlists' => $setlists,
            'members' => $members,
        ])->layout('components.layouts.app')->title($this->isEditing ? 'Edit Rehearsal' : 'Create Rehearsal');
    }
}
