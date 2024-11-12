<?php

namespace App\Livewire\Product;

use App\Classes\CurrencyConverter;
use App\Enums\Status;
use App\Models\CartItem;
use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Str;
use WireUi\Traits\WireUiActions;

class ProductViewModal extends Component
{
    use WireUiActions;

    public $product;
    public $media;
    public $name;

    public $quantity = 1;

    public $description;

    public $variations;
    public $origin;

    public $currencyData = [];
    public $totalStocks;

    protected $listeners = [
        'view-product-info' => 'getData',
        'clearProductViewModalData' => 'clearData',
    ];

    public function getData($id){
        $this->product = Product::findOrFail($id);
        $this->media = json_decode($this->product->media);

        $this->variations = json_decode($this->product->variations);

        foreach($this->variations as $variation){
            $this->totalStocks += (int) $variation->stocks;
        }
    }

    public function identifyFileType($fileName){
        // Trim any leading/trailing spaces
        $fileName = trim($fileName);

        // Find the position of the last dot
        $dotPosition = strrpos($fileName, '.');

        // If there is no dot, it's not a file with an extension
        if ($dotPosition === false) {
            return 'unknown';
        }

        // Find the position of the first question mark (if any) after the dot
        $questionMarkPosition = strpos($fileName, '?', $dotPosition);

        // If there is no question mark, the extension ends at the end of the string
        if ($questionMarkPosition === false) {
            $extension = substr($fileName, $dotPosition + 1);
        } else {
            // If there's a question mark, extract the part before it
            $extension = substr($fileName, $dotPosition + 1, $questionMarkPosition - $dotPosition - 1);
        }

        // Convert to lowercase
        $extension = Str::lower($extension);

        // List of common video extensions
        $videoExtensions = ['mp4', 'webm', 'avi', 'mov', 'mkv', 'flv'];
        // List of common image extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];

        // Check if the file extension matches any known video or image types
        if (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        }

        return 'unknown'; // Default return if it's neither video nor image
    }

    public function clearData(){
        $this->reset([
            'media',
            'name',
            'description',
            'variations',
            'origin',
            'totalStocks'
        ]);

        $this->product = null;
        $this->quantity = 1;
    }

    public function store_toCart(){
        $validated = $this->validate([
            'quantity' => 'required',
        ]);

        try{
            $existingCartItem = CartItem::where('user_id', Auth::id())->where('product_id', $this->product->id)->where('variation', 'Default')->first();

            if($existingCartItem){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Added to your cart.',
                ]);
    
                $existingCartItem->increment('quantity', 1);
                return;
            }
    
            $store = CartItem::create([
                'user_id' => Auth::id(),
                'seller_id' => $this->product->seller_id,
                'product_id' => $this->product->id,
                'variation' => 'Default',
                'quantity' => $validated['quantity'],
            ]);
    
            if($store){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Added to your cart.',
                ]);
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error Notification!',
                    'description' => 'Woops, its an error. There seems to be a problem inserting this product to your cart.',
                ]);
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error storing cart item: ' . $e->getMessage());
        }
    }

    public function render(){
        return view('livewire.Product.product-view-modal');
    }
}
