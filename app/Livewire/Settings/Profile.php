<?php

namespace App\Livewire\Settings;

use App\Services\AvatarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $username = '';
    public $avatar = null;
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
    }

    public function updateProfile(AvatarService $avatar): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore(Auth::id())],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->username = $this->username;

        if ($this->avatar) {
            $user->avatar = $avatar->upload($this->avatar, $user->avatar);
        }

        $user->save();

        $this->dispatch('toast', message: 'Profile updated successfully', type: 'success');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => $this->new_password,
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('toast', message: 'Password changed successfully', type: 'success');
    }

    public function removeAvatar(AvatarService $avatar): void
    {
        $user = Auth::user();

        if ($avatar->delete($user->avatar)) {
            $user->avatar = null;
            $user->save();
            $this->dispatch('toast', message: 'Avatar removed', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.settings.profile')
            ->layout('components.layouts.app')
            ->title('Settings');
    }
}
