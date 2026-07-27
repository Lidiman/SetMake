<?php

namespace App\Livewire\Schedules;

use App\Enums\LinkType;
use App\Enums\ScheduleStatus;
use App\Enums\ScheduleType;
use App\Enums\SetlistType;
use App\Models\Schedule;
use App\Models\Setlist;
use App\Models\Song;
use App\Models\User;
use App\Services\YouTubeMusicService;
use Livewire\Component;

class ScheduleForm extends Component
{
    public ?Schedule $schedule = null;
    public bool $isEditing = false;

    // Schedule Fields
    public string $type = '';
    public string $title = '';
    public string $date = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $status = 'planned';
    public string $description = '';
    
    // Rehearsal Fields
    public string $location = '';
    
    // Gig Fields
    public string $venue = '';
    public string $address = '';
    public string $contact_person = '';
    public string $phone = '';
    public ?float $payment = 0;
    public ?float $tips = 0;
    public ?float $transport = 0;
    public ?float $parking = 0;
    public ?float $food = 0;
    public ?float $equipment_rental = 0;
    public ?float $other_expenses = 0;
    
    public array $selectedMembers = [];

    // Setlist Fields
    public bool $includeSetlist = true; // By default we want to make a setlist
    public string $setlist_title = '';
    public string $setlist_description = '';
    
    // Setlist Songs State
    public array $setlistSongs = [];
    
    // Search State
    public string $searchQuery = '';
    public bool $ytmSearching = false;
    public array $ytmResults = [];
    public ?string $ytmError = null;

    public function mount(?Schedule $schedule = null)
    {
        if ($schedule && $schedule->exists) {
            $this->schedule = $schedule;
            $this->isEditing = true;
            $this->fillForm();
        } else {
            $this->type = ScheduleType::Rehearsal->value;
        }
    }

    private function fillForm()
    {
        $this->type = $this->schedule->type->value;
        $this->title = $this->schedule->title;
        $this->date = $this->schedule->date->format('Y-m-d');
        $this->start_time = $this->schedule->start_time?->format('H:i') ?? '';
        $this->end_time = $this->schedule->end_time?->format('H:i') ?? '';
        $this->status = $this->schedule->status->value;
        $this->description = $this->schedule->description ?? '';
        
        $this->location = $this->schedule->location ?? '';
        
        $this->venue = $this->schedule->venue ?? '';
        $this->address = $this->schedule->address ?? '';
        $this->contact_person = $this->schedule->contact_person ?? '';
        $this->phone = $this->schedule->phone ?? '';
        
        $this->payment = (float) $this->schedule->payment;
        $this->tips = (float) $this->schedule->tips;
        $this->transport = (float) $this->schedule->transport;
        $this->parking = (float) $this->schedule->parking;
        $this->food = (float) $this->schedule->food;
        $this->equipment_rental = (float) $this->schedule->equipment_rental;
        $this->other_expenses = (float) $this->schedule->other_expenses;

        $this->selectedMembers = $this->schedule->members->pluck('id')->toArray();
        
        if ($this->schedule->setlist) {
            $this->includeSetlist = true;
            $this->setlist_title = $this->schedule->setlist->title;
            $this->setlist_description = $this->schedule->setlist->description ?? '';
            
            $this->schedule->setlist->load(['songs' => function ($q) {
                $q->orderBy('setlist_song.position');
            }]);
            
            foreach ($this->schedule->setlist->songs as $song) {
                $this->setlistSongs[] = [
                    'id' => $song->id,
                    'title' => $song->title,
                    'artist' => $song->artist,
                    'duration' => $song->formatted_duration,
                    'duration_seconds' => $song->duration ?? 0,
                    'notes' => $song->pivot->notes ?? '',
                ];
            }
        } else {
            $this->includeSetlist = false;
        }
    }

    public function updatedSearchQuery()
    {
        $this->ytmResults = [];
        $this->ytmError = null;

        if (strlen($this->searchQuery) >= 2) {
            $this->searchYtm();
        }
    }

    public function searchYtm()
    {
        if (strlen($this->searchQuery) < 2) return;

        $this->ytmSearching = true;
        $this->ytmError = null;
        $this->ytmResults = [];

        try {
            $service = app(YouTubeMusicService::class);
            $result = $service->search($this->searchQuery);

            if (isset($result['error'])) {
                $this->ytmError = $result['error'];
            } else {
                $this->ytmResults = array_slice($result['results'] ?? [], 0, 5);
            }
        } catch (\Exception $e) {
            $this->ytmError = $e->getMessage();
        }

        $this->ytmSearching = false;
    }

    public function addSong($songId)
    {
        if (collect($this->setlistSongs)->contains('id', $songId)) {
            $this->dispatch('toast', message: 'Song is already in the setlist.', type: 'error');
            return;
        }
        
        $song = Song::find($songId);
        if (!$song) return;
        
        $this->setlistSongs[] = [
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,
            'duration' => $song->formatted_duration,
            'duration_seconds' => $song->duration ?? 0,
            'notes' => '',
        ];
        
        $this->searchQuery = '';
        $this->ytmResults = [];
    }

    public function addSongFromYtm(int $index)
    {
        $data = $this->ytmResults[$index] ?? null;
        if (!$data) return;

        $title = $data['title'];
        $artist = implode(', ', $data['artists']);
        $duration = $data['duration_seconds'];
        $videoId = $data['videoId'];

        $existing = Song::where('title', $title)
            ->where('artist', $artist)
            ->first();

        if ($existing) {
            $song = $existing;
        } else {
            $song = Song::create([
                'title' => $title,
                'artist' => $artist ?: null,
                'duration' => $duration ?: null,
                'created_by' => auth()->id(),
            ]);

            if ($videoId) {
                $song->links()->create([
                    'type' => LinkType::YouTube,
                    'url' => "https://music.youtube.com/watch?v={$videoId}",
                    'label' => 'YouTube Music',
                ]);
            }
        }

        if (collect($this->setlistSongs)->contains('id', $song->id)) {
            $this->dispatch('toast', message: 'Song is already in the setlist.', type: 'error');
            return;
        }

        $this->setlistSongs[] = [
            'id' => $song->id,
            'title' => $song->title,
            'artist' => $song->artist,
            'duration' => $song->formatted_duration,
            'duration_seconds' => $song->duration ?? 0,
            'notes' => '',
        ];

        $this->searchQuery = '';
        $this->ytmResults = [];
    }

    public function removeSong($index)
    {
        unset($this->setlistSongs[$index]);
        $this->setlistSongs = array_values($this->setlistSongs);
    }

    public function updateSongOrder($list)
    {
        $newOrder = [];
        foreach ($list as $item) {
            $newOrder[] = $this->setlistSongs[(int) $item['value']];
        }
        $this->setlistSongs = $newOrder;
    }

    public function getTotalDurationProperty()
    {
        $totalSeconds = collect($this->setlistSongs)->sum('duration_seconds');
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
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
        $rules = [
            'type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:5000'],
            'selectedMembers' => ['array'],
            'selectedMembers.*' => ['exists:users,id'],
        ];

        if ($this->type === ScheduleType::Gig->value) {
            $rules['venue'] = ['required', 'string', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['contact_person'] = ['nullable', 'string', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:50'];
            $rules['payment'] = ['nullable', 'numeric', 'min:0'];
            $rules['tips'] = ['nullable', 'numeric', 'min:0'];
            $rules['transport'] = ['nullable', 'numeric', 'min:0'];
            $rules['parking'] = ['nullable', 'numeric', 'min:0'];
            $rules['food'] = ['nullable', 'numeric', 'min:0'];
            $rules['equipment_rental'] = ['nullable', 'numeric', 'min:0'];
            $rules['other_expenses'] = ['nullable', 'numeric', 'min:0'];
        } else {
            $rules['location'] = ['nullable', 'string', 'max:255'];
        }
        
        if ($this->includeSetlist) {
            $rules['setlist_title'] = ['required', 'string', 'max:255'];
            $rules['setlist_description'] = ['nullable', 'string', 'max:5000'];
        }

        return $rules;
    }

    public function save()
    {
        $validated = $this->validate();

        $scheduleData = [
            'type' => $validated['type'],
            'title' => $validated['title'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'] ?: null,
            'end_time' => $validated['end_time'] ?: null,
            'status' => $validated['status'],
            'description' => $validated['description'] ?: null,
        ];

        if ($this->type === ScheduleType::Gig->value) {
            $scheduleData['venue'] = $validated['venue'];
            $scheduleData['address'] = $validated['address'] ?: null;
            $scheduleData['contact_person'] = $validated['contact_person'] ?: null;
            $scheduleData['phone'] = $validated['phone'] ?: null;
            $scheduleData['payment'] = $validated['payment'] ?? 0;
            $scheduleData['tips'] = $validated['tips'] ?? 0;
            $scheduleData['transport'] = $validated['transport'] ?? 0;
            $scheduleData['parking'] = $validated['parking'] ?? 0;
            $scheduleData['food'] = $validated['food'] ?? 0;
            $scheduleData['equipment_rental'] = $validated['equipment_rental'] ?? 0;
            $scheduleData['other_expenses'] = $validated['other_expenses'] ?? 0;
            
            // clear rehearsal fields
            $scheduleData['location'] = null;
        } else {
            $scheduleData['location'] = $validated['location'] ?: null;
            
            // clear gig fields
            $scheduleData['venue'] = null;
            $scheduleData['address'] = null;
            $scheduleData['contact_person'] = null;
            $scheduleData['phone'] = null;
            $scheduleData['payment'] = 0;
            $scheduleData['tips'] = 0;
            $scheduleData['transport'] = 0;
            $scheduleData['parking'] = 0;
            $scheduleData['food'] = 0;
            $scheduleData['equipment_rental'] = 0;
            $scheduleData['other_expenses'] = 0;
        }

        // Handle Setlist creation/update
        $setlistId = null;
        if ($this->includeSetlist) {
            $setlistData = [
                'title' => $validated['setlist_title'],
                'description' => $validated['setlist_description'] ?: null,
                'type' => $this->type === ScheduleType::Rehearsal->value ? SetlistType::Rehearsal : SetlistType::Performance,
                'scheduled_at' => $validated['date'] . ' ' . ($validated['start_time'] ?: '00:00:00'),
                'venue' => $this->type === ScheduleType::Gig->value ? $validated['venue'] : ($validated['location'] ?? null),
            ];
            
            if ($this->isEditing && $this->schedule->setlist) {
                $setlist = $this->schedule->setlist;
                $setlist->update($setlistData);
                $setlistId = $setlist->id;
            } else {
                $setlistData['created_by'] = auth()->id();
                $setlist = Setlist::create($setlistData);
                $setlistId = $setlist->id;
            }
            
            $syncData = [];
            foreach ($this->setlistSongs as $index => $song) {
                $syncData[$song['id']] = [
                    'position' => $index + 1,
                    'notes' => $song['notes'] ?? null,
                ];
            }
            
            $setlist->songs()->sync($syncData);
        } else if ($this->isEditing && $this->schedule->setlist) {
            // Unlink setlist if user chose not to include it anymore
            $setlistId = null;
        }
        
        $scheduleData['setlist_id'] = $setlistId;

        if ($this->isEditing) {
            $this->schedule->update($scheduleData);
            $this->schedule->members()->sync($this->selectedMembers);
        } else {
            $scheduleData['created_by'] = auth()->id();
            $this->schedule = Schedule::create($scheduleData);
            $this->schedule->members()->attach($this->selectedMembers);
        }

        return redirect()->route('schedules.show', $this->schedule);
    }

    public function render()
    {
        $members = User::whereIn('role', ['admin', 'member'])->orderBy('name')->get();
        $types = ScheduleType::cases();
        $statuses = ScheduleStatus::cases();
        
        $searchResults = collect();
        if (strlen($this->searchQuery) >= 2) {
            $searchResults = Song::search($this->searchQuery)
                ->limit(5)
                ->get();
        }

        return view('livewire.schedules.schedule-form', [
            'members' => $members,
            'types' => $types,
            'statuses' => $statuses,
            'searchResults' => $searchResults,
        ])->layout('components.layouts.app')->title($this->isEditing ? 'Edit Schedule' : 'Create Schedule');
    }
}
