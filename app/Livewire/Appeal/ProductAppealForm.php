<?php

namespace App\Livewire\Appeal;

use App\Classes\WordFilter;
use App\Enums\Status;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportAppeal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Str;
use WireUi\Traits\WireUiActions;

class ProductAppealForm extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $product = null;
    public $media;
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
            'media',
            'content'
        ]);

        $this->product = null;
    }

    public function store(){
        $rules = [
            'content' => 'required',
            'media.*' => 'nullable|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm',
        ];

        if(empty($this->media) || !$this->media || $this->media == '[]'){
            $rules['media'] = 'required|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm';
        }

        $validated = $this->validate($rules);

        try{
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
                        'content' => WordFilter::filteredInput($validated['content']),
                        'media' => json_encode($this->storeMedia($this->media)),
                    ]);
    
                    if($message){
                        $report = ReportAppeal::create([
                            'product_id' => $this->product->id,
                            'conversation_id' => $conversation->id,
                            'status' => Status::Ongoing
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
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error product appeal: ' . $e->getMessage());
        }
    }

    public function deleteMedia($index){
        array_splice($this->media, $index, 1);
    }

    public function storeMedia($media){
        $mediaPaths = [];

        if($media){
            foreach ($media as $key => $item) {
                // New image, store and get path
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $item->getClientOriginalExtension();
                $item->storeAs('messages', $filename);
                array_push($mediaPaths, $filename);
            }
        }

        return $mediaPaths;
    }

    public function render()
    {
        return view('livewire.Appeal.product-appeal-form');
    }
}
