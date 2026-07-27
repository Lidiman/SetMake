<?php

namespace App\Livewire\Gigs;

use App\Enums\GigStatus;
use App\Models\Gig;
use App\Models\Setlist;
use App\Models\User;
use Livewire\Component;

class GigForm extends Component
{
    public ?Gig $gig = null;
    public bool $isEditing = false;

    public string $title = '';
    public string $venue = '';
    public string $address = '';
    public string $date = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $contact_person = '';
    public string $phone = '';
    public string $description = '';
    public ?float $payment = 0;
    public ?float $tips = 0;
    public ?float $transport = 0;
    public ?float $parking = 0;
    public ?float $food = 0;
    public ?float $equipment_rental = 0;
    public ?float $other_expenses = 0;
    public string $status = 'planned';
    public ?int $setlist_id = null;
    public array $selectedMembers = [];

    public function mount(?Gig $gig = null)
    {
        if ($gig && $gig->exists) {
            $this->gig = $gig;
            $this->isEditing = true;
            $this->fillForm();
        }
    }

    private function fillForm()
    {
        $this->title = $this->gig->title;
        $this->venue = $this->gig->venue;
        $this->address = $this->gig->address ?? '';
        $this->date = $this->gig->date->format('Y-m-d');
        $this->start_time = $this->gig->start_time?->format('H:i') ?? '';
        $this->end_time = $this->gig->end_time?->format('H:i') ?? '';
        $this->contact_person = $this->gig->contact_person ?? '';
        $this->phone = $this->gig->phone ?? '';
        $this->description = $this->gig->description ?? '';
        $this->payment = (float) $this->gig->payment;
        $this->tips = (float) $this->gig->tips;
        $this->transport = (float) $this->gig->transport;
        $this->parking = (float) $this->gig->parking;
        $this->food = (float) $this->gig->food;
        $this->equipment_rental = (float) $this->gig->equipment_rental;
        $this->other_expenses = (float) $this->gig->other_expenses;
        $this->status = $this->gig->status->value;
        $this->setlist_id = $this->gig->setlist_id;
        $this->selectedMembers = $this->gig->members->pluck('id')->toArray();
    }

    public function getNetIncomeProperty(): float
    {
        return ($this->payment ?? 0) + ($this->tips ?? 0)
            - ($this->transport ?? 0) - ($this->parking ?? 0)
            - ($this->food ?? 0) - ($this->equipment_rental ?? 0)
            - ($this->other_expenses ?? 0);
    }

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
            'payment' => ['nullable', 'numeric', 'min:0'],
            'tips' => ['nullable', 'numeric', 'min:0'],
            'transport' => ['nullable', 'numeric', 'min:0'],
            'parking' => ['nullable', 'numeric', 'min:0'],
            'food' => ['nullable', 'numeric', 'min:0'],
            'equipment_rental' => ['nullable', 'numeric', 'min:0'],
            'other_expenses' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string'],
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
            'venue' => $validated['venue'],
            'address' => $validated['address'] ?: null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'] ?: null,
            'end_time' => $validated['end_time'] ?: null,
            'contact_person' => $validated['contact_person'] ?: null,
            'phone' => $validated['phone'] ?: null,
            'description' => $validated['description'] ?: null,
            'payment' => $validated['payment'] ?? 0,
            'tips' => $validated['tips'] ?? 0,
            'transport' => $validated['transport'] ?? 0,
            'parking' => $validated['parking'] ?? 0,
            'food' => $validated['food'] ?? 0,
            'equipment_rental' => $validated['equipment_rental'] ?? 0,
            'other_expenses' => $validated['other_expenses'] ?? 0,
            'status' => $validated['status'],
            'setlist_id' => $validated['setlist_id'] ?: null,
        ];

        if ($this->isEditing) {
            $this->gig->update($data);
            $this->gig->members()->sync($this->selectedMembers);
        } else {
            $data['created_by'] = auth()->id();
            $this->gig = Gig::create($data);
            $this->gig->members()->attach($this->selectedMembers);
        }

        return redirect()->route('gigs.show', $this->gig);
    }

    public function render()
    {
        $setlists = Setlist::orderBy('title')->get();
        $members = User::whereIn('role', ['admin', 'member'])->orderBy('name')->get();
        $statuses = GigStatus::cases();

        return view('livewire.gigs.gig-form', [
            'setlists' => $setlists,
            'members' => $members,
            'statuses' => $statuses,
        ])->layout('components.layouts.app')->title($this->isEditing ? 'Edit Gig' : 'Create Gig');
    }
}
