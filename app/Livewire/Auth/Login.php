<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'username' => 'required',
        'password' => 'required|min:6',
    ];

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->redirect(route('dashboard'), navigate: true);
        } else {
            $this->addError('username', 'These credentials do not match our records.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.app')
            ->title('Login');
    }
}
