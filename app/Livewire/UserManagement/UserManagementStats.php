<?php

namespace App\Livewire\UserManagement;

use App\Enums\UserType;
use App\Models\User;
use Livewire\Component;

class UserManagementStats extends Component
{
    public function render(){
        $totalUsers = User::where('id', '<>', 1)->count();
        $roleCounts = User::selectRaw('role, COUNT(*) as count')
            ->whereIn('role', [UserType::Travelpreneur, UserType::Store, UserType::ContentCreator])
            ->groupBy('role')
            ->pluck('count', 'role');

        return view('livewire.UserManagement.user-management-stats', [
            'totalUsers' => $totalUsers,
            'totalTravelpreneurs' => $roleCounts[UserType::Travelpreneur] ?? 0,
            'totalStores' => $roleCounts[UserType::Store] ?? 0,
            'totalContentCreator' => $roleCounts[UserType::ContentCreator] ?? 0,
        ]);
    }
}
