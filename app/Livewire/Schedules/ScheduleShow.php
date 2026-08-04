<?php

namespace App\Livewire\Schedules;

use App\Enums\ScheduleStatus;
use App\Enums\ScheduleType;
use App\Models\Schedule;
use Livewire\Component;

class ScheduleShow extends Component
{
    public Schedule $schedule;

    public bool $showCompleteForm = false;
    public bool $showFinancialForm = false;

    public ?float $payment = null;
    public ?float $tips = null;
    public ?float $transport = null;
    public ?float $parking = null;
    public ?float $food = null;
    public ?float $equipment_rental = null;
    public ?float $other_expenses = null;

    public string $scheduleType = '';
    public string $scheduleStatus = '';

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule->load(['creator', 'members', 'setlist.songs']);
        $this->scheduleType = $this->schedule->type->value;
        $this->scheduleStatus = $this->schedule->status->value;
    }

    public function toggleCompleteForm()
    {
        if ($this->showCompleteForm) {
            $this->showCompleteForm = false;
            $this->resetFinancialFields();
            return;
        }

        if ($this->scheduleType !== ScheduleType::Gig->value) {
            $this->completeSchedule();
            return;
        }

        $this->showCompleteForm = true;
        $this->loadFinancialFields();
    }

    public function toggleFinancialForm()
    {
        if ($this->showFinancialForm) {
            $this->showFinancialForm = false;
            $this->resetFinancialFields();
            return;
        }

        $this->showFinancialForm = true;
        $this->loadFinancialFields();
    }

    protected function loadFinancialFields(): void
    {
        $this->payment = $this->schedule->payment ? (float) $this->schedule->payment : 0;
        $this->tips = $this->schedule->tips ? (float) $this->schedule->tips : 0;
        $this->transport = $this->schedule->transport ? (float) $this->schedule->transport : 0;
        $this->parking = $this->schedule->parking ? (float) $this->schedule->parking : 0;
        $this->food = $this->schedule->food ? (float) $this->schedule->food : 0;
        $this->equipment_rental = $this->schedule->equipment_rental ? (float) $this->schedule->equipment_rental : 0;
        $this->other_expenses = $this->schedule->other_expenses ? (float) $this->schedule->other_expenses : 0;
    }

    public function cancelComplete()
    {
        $this->showCompleteForm = false;
        $this->resetFinancialFields();
    }

    public function saveFinancials()
    {
        $rules = $this->financialRules();

        if (!empty($rules)) {
            $validated = $this->validate($rules);
        } else {
            $validated = [];
        }

        $updateData = [];

        if ($this->scheduleType === ScheduleType::Gig->value) {
            $updateData['payment'] = $validated['payment'] ?? 0;
            $updateData['tips'] = $validated['tips'] ?? 0;
            $updateData['transport'] = $validated['transport'] ?? 0;
            $updateData['parking'] = $validated['parking'] ?? 0;
            $updateData['food'] = $validated['food'] ?? 0;
            $updateData['equipment_rental'] = $validated['equipment_rental'] ?? 0;
            $updateData['other_expenses'] = $validated['other_expenses'] ?? 0;
        }

        $this->schedule->update($updateData);
        $this->schedule->refresh();

        $this->showFinancialForm = false;
        $this->resetFinancialFields();

        $this->dispatch('toast', message: 'Financial data saved!', type: 'success');
    }

    public function completeSchedule()
    {
        $rules = $this->financialRules();

        if (!empty($rules)) {
            $validated = $this->validate($rules);
        } else {
            $validated = [];
        }

        $updateData = [
            'status' => ScheduleStatus::Completed->value,
        ];

        if ($this->scheduleType === ScheduleType::Gig->value) {
            $updateData['payment'] = $validated['payment'] ?? 0;
            $updateData['tips'] = $validated['tips'] ?? 0;
            $updateData['transport'] = $validated['transport'] ?? 0;
            $updateData['parking'] = $validated['parking'] ?? 0;
            $updateData['food'] = $validated['food'] ?? 0;
            $updateData['equipment_rental'] = $validated['equipment_rental'] ?? 0;
            $updateData['other_expenses'] = $validated['other_expenses'] ?? 0;
        }

        $this->schedule->update($updateData);
        $this->schedule->refresh();
        $this->scheduleStatus = $this->schedule->status->value;

        $this->showCompleteForm = false;
        $this->resetFinancialFields();

        $this->dispatch('toast', message: 'Schedule marked as completed!', type: 'success');
    }

    protected function financialRules(): array
    {
        if ($this->scheduleType !== ScheduleType::Gig->value) {
            return [];
        }

        return [
            'payment' => ['nullable', 'numeric', 'min:0'],
            'tips' => ['nullable', 'numeric', 'min:0'],
            'transport' => ['nullable', 'numeric', 'min:0'],
            'parking' => ['nullable', 'numeric', 'min:0'],
            'food' => ['nullable', 'numeric', 'min:0'],
            'equipment_rental' => ['nullable', 'numeric', 'min:0'],
            'other_expenses' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function resetFinancialFields(): void
    {
        $this->payment = null;
        $this->tips = null;
        $this->transport = null;
        $this->parking = null;
        $this->food = null;
        $this->equipment_rental = null;
        $this->other_expenses = null;
    }

    public function getNetIncomeProperty(): float
    {
        $payment = $this->payment ?? $this->schedule->payment ?? 0;
        $tips = $this->tips ?? $this->schedule->tips ?? 0;
        $transport = $this->transport ?? $this->schedule->transport ?? 0;
        $parking = $this->parking ?? $this->schedule->parking ?? 0;
        $food = $this->food ?? $this->schedule->food ?? 0;
        $equipmentRental = $this->equipment_rental ?? $this->schedule->equipment_rental ?? 0;
        $otherExpenses = $this->other_expenses ?? $this->schedule->other_expenses ?? 0;

        $gross = (float) $payment + (float) $tips;
        $expenses = (float) $transport + (float) $parking + (float) $food + (float) $equipmentRental + (float) $otherExpenses;

        return $gross - $expenses;
    }

    public function getTypeLabelProperty(): string
    {
        return ScheduleType::from($this->scheduleType)->label();
    }

    public function getTypeColorProperty(): string
    {
        return ScheduleType::from($this->scheduleType)->color();
    }

    public function getTypeIconProperty(): string
    {
        return ScheduleType::from($this->scheduleType)->icon();
    }

    public function getStatusLabelProperty(): string
    {
        return ScheduleStatus::from($this->scheduleStatus)->label();
    }

    public function getStatusColorProperty(): string
    {
        return ScheduleStatus::from($this->scheduleStatus)->color();
    }

    public function render()
    {
        return view('livewire.schedules.schedule-show')
            ->layout('components.layouts.app')
            ->title($this->schedule->title);
    }
}
