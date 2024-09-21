<?php

namespace App\Livewire\Etc;

use App\Classes\CurrencyConverter;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SelectCurrencyModal extends Component
{
    use WireUiActions;

    public $currencies = null;

    public function mount(){
        CurrencyConverter::loadCurrencyData();
        $this->currencies = CurrencyConverter::$currencyData;
    }
    
    public function confirmation($currency){
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Change the currency view to ' . $currency . '? Take note that all products price are USD based.' ,
            'acceptLabel' => 'Yes, save it',
            'method' => 'setCurrency',
            'params' => $currency,
        ]);
    }

    public function setCurrency($currency){
        auth()->user()->currency = $currency;
        auth()->user()->save();

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Currency has been successfully changed. The website will will now reload.',
        ]);

        $this->dispatch('updatedCurrency');
    }

    public function render()
    {
        return view('livewire.Etc.select-currency-modal');
    }
}
