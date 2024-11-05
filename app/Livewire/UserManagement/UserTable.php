<?php

namespace App\Livewire\UserManagement;

use App\Enums\UserType;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    public $search = '';

    protected $listeners = [
        'refresh-user-table' => '$refresh',
    ];

    public function getUsers() {
        $filter = $this->filterStatus;
        $search = $this->search;
    
        return User::query()
            ->where('id', '<>', 1)
            ->when(!empty($filter), function ($query) use ($filter) {
                $query->where(function ($q) use ($filter) {
                    foreach ($filter as $status) {
                        $q->orWhere('role', 'like', '%' . $status . '%');
                    }
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where(function ($q) use ($search) {
                        $q->whereHas('storeInformation', function ($storeQuery) use ($search) {
                            $storeQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('userInformation', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', '%' . $search . '%')
                                      ->orWhere('last_name', 'like', '%' . $search . '%');
                        });
                    });
                })
                ->orWhere(function ($subQuery) use ($search) {
                    $subQuery->where('role', UserType::Travelpreneur)
                             ->whereHas('userInformation', function ($userQuery) use ($search) {
                                 $userQuery->where('first_name', 'like', '%' . $search . '%')
                                           ->orWhere('last_name', 'like', '%' . $search . '%');
                             });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function render(){
        return view('livewire.UserManagement.user-table', [
            'users' => $this->getUsers()
        ]);
    }
}
