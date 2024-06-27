<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AddStockFormModal extends Component
{
    use WireUiActions;

    public $product = null;
    public $variations = null;

    public $stocks = null;

    protected $listeners = [
        'clearAddStockFormModalData' => 'clearData',
        'viewProductStocksInformation' => 'getData'
    ];

    public function getData($id){
        $this->product = Product::findOrFail($id);

        if($this->product){
            $this->variations = json_decode($this->product->variations);

            foreach($this->variations as $key => $i){
                $this->stocks[$key] = [
                    'stocks' => '',
                ];
            } 
        }
    }

    public function formValidate(){
        $rules = [];

        foreach($this->variations as $key => $i){
            $rules["stocks.$key.stocks"] = 'numeric|min:1|max:9999';
        }   

        return $this->validate($rules);
    }

    public function store(){
        $validated = $this->formValidate();

        foreach ($this->variations as $key => $variation){
            $stockToAdd = (int) $this->stocks[$key]['stocks'];

            if($stockToAdd > 0 || empty($stockToAdd)){
                $variation->stocks += $stockToAdd; // Add stock to each variation
            }
        }

        // Update product with new variations (consider using dedicated update function)
        $this->product->variations = json_encode($this->variations);
        
        if($this->product->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Product has been successfully restocked.',
            ]);

            $this->dispatch('refresh-product-table');
            $this->dispatch('close-modal', ['modal' => 'addStockFormModal']);
        }
    }

    public function clearData(){
        $this->stocks = null;
        $this->variations = null;
        $this->product = null;
    }

    public function render()
    {
        return view('livewire.Product.add-stock-form-modal');
    }
}
