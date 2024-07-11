<?php

namespace App\Livewire\Appeal;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportAppeal;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ProductAppealForm extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $product = null;
    public $images;
    public $content;

    protected $listeners = [
        'clearReportAppealFormModalData' => 'clearData',
        'for-product-appeal' => 'getData'
    ];

    public function getData($id){
        $this->product = Product::findOrFail($id);
    }

    public function clearData(){
        $this->reset([
            'images',
            'content'
        ]);

        $this->product = null;
    }

    public function store(){
        $validated = $this->validate([
            'content' => 'required',
            'images.*' => 'image|mimes:png,jpg,jpeg',
        ]);

        if($this->product){
            $conversation = Conversation::create([
                'user_1' => Auth::id(),
                'user_2' => 1, //admin acc
                'status' => 'active'
            ]);

            if($conversation){
                $message = Message::create([
                    'user_id' => Auth::id(),
                    'conversation_id' => $conversation->id,
                    'content' => $validated['content'],
                    'images' => json_encode($this->storeImages($this->images)),
                ]);

                if($message){
                    $report = ReportAppeal::create([
                        'product_id' => $this->product->id,
                        'conversation_id' => $conversation->id
                    ]);

                    $conversation->last_message_id = $message->id;
                    $conversation->save();

                    if($report){
                        $this->dispatch('refresh-product-table');
                        $this->dispatch('close-modal', ['modal' => 'productAppealFormModal']);

                        $this->notification()->send([
                            'icon' => 'success',
                            'title' => 'Success!',
                            'description' => 'Your appeal has been successfully sent.',
                        ]);
                    }else{
                        $this->notification()->send([
                            'icon' => 'error',
                            'title' => 'Error!',
                            'description' => 'Woops, its an error. There seem to be a problem sending your appeal.',
                        ]);
                    }
                }
            }
        }
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function storeImages($images){
        $imagePaths = [];

        if($images){
            foreach ($images as $key => $image) {
                // New image, store and get path
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('messages', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function render()
    {
        return view('livewire.Appeal.product-appeal-form');
    }
}
