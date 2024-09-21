<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $selectedCurrency = 'USD';
    
    protected $listeners = [
        'currencySelectedUpdated' => 'getSelectedCurrency'
    ];

    public function getSelectedCurrency($currency){
        if($currency){
            $this->selectedCurrency = $currency;
        }
    }

    
    public function __construct()
    {

    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
