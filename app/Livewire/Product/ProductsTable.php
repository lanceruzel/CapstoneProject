<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Str;

class ProductsTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    public $search = '';

    protected $listeners = [
        'refresh-product-table' => '$refresh'
    ];

    public function getProducts(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Product::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->where('seller_id', Auth::id())->paginate(10);
        }else{
            return Product::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', Auth::id())
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
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

    public function render()
    {
        return view('livewire.Product.products-table', [
            'products' => $this->getProducts()
        ]);
    }
}
