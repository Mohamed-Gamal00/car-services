<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminLogoutComponent extends Component
{
    public function logout()
    {
        Auth::guard('admin')->logout();

        session()->invalidate('guard.admin');
        session()->regenerateToken();

        return to_route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.auth.admin-logout-component');
    }
}
