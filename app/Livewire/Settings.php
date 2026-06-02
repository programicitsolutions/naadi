<?php
// ============================================================
// PATH: app/Livewire/Settings.php
// ============================================================

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Settings extends Component
{
    public $name;
    public $email;
    public $age;
    public $mode;
    public $saved = false;

    public function mount()
    {
        $user        = Auth::user();
        $this->name  = $user->name;
        $this->email = $user->email;
        $this->age   = $user->age;
        $this->mode  = $user->mode ?? 'focus';
    }

    public function save()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'age'   => 'nullable|integer|min:5|max:100',
            'mode'  => 'required|in:focus,moving_on,sleep,meditation',
        ]);

        Auth::user()->update([
            'name'  => $this->name,
            'email' => $this->email,
            'age'   => $this->age,
            'mode'  => $this->mode,
        ]);

        $this->saved = true;
        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.settings')
            ->layout('layouts.app');
    }
}