<?php

namespace App\Livewire\Posting;

use App\Classes\Location;
use App\Classes\WordFilter;
use App\Enums\PostType;
use App\Enums\Status;
use App\Events\PostUpdated;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Str;
use WireUi\Traits\WireUiActions;

class PostFormModal extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $content;
    public $media = [];
    public $isIncluded = true;

    public $postUpdate = null;

    protected $listeners = [
        'update-post' => 'getPostData',
        'clearPostFormModalData' => 'clearData',
    ];

    public function getPostData($id = null){
        if($id){
            $this->postUpdate = Post::findOrFail($id);

            if($this->postUpdate){
                $this->content = $this->postUpdate->content;
                $this->media = json_decode($this->postUpdate->media);
                $this->isIncluded = $this->postUpdate->include_compilation == 1 ? true : false;
            }
        }
    }

    public function store(){
        try{
            $postType = PostType::Status;

            $validated = $this->formValidate();

            $post = $this->storePost($postType, $validated);

            if($post){
                if($this->postUpdate == null){
                    $this->dispatch('post-create-delete');
                }else{
                    PostUpdated::dispatch($this->postUpdate->id);
                }
    
                $this->dialog()->show([
                    'title' => 'Success!',
                    'icon' => 'success',
                    'description' => $this->postUpdate != null ? 'Your post has been successfully updated.' : 'Your post has been successfully created.',

                    'onClose' => [
                        'method' => 'closeModal',
                    ],
                    'onDismiss' => [
                        'method' => 'closeModal',
                    ],
                    'onTimeout' => [
                        'method' => 'closeModal',
                    ],
                ]);
            }else{
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => $this->postUpdate != null ? 'There seem to be a problem creating your post.' : 'There seem to be a problem updating your post.',
                ]);
            }
        }catch (\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => 'Woops, its an error. '
            ]);

            Log::error('Error storing post: ' . $e->getMessage());
        }
    }

    public function clearData(){
        $this->postUpdate = null;
        $this->reset('content');
        $this->reset('media');
    }

    public function closeModal(){
        $this->dispatch('close-modal', ['modal' => 'postFormModal']);
        $this->clearData();
    }

    public function storePost($postType, $validated){
        return Post::updateOrCreate(
            [
                'id' => $this->postUpdate ? $this->postUpdate->id : null,
                'user_id' => Auth::id()
            ],
            [
                'type' => $postType,
                'content' => WordFilter::filteredInput($validated['content']),
                'media' => json_encode($this->storeMedia($this->media)),
                'status' => Status::Available,
                'country' => Location::getLocation(),
                'include_compilation' => $this->isIncluded
            ]
        );      
    }

    public function formValidate(){
        return $this->validate([
            'content' => 'required',
            'media.*' => $this->postUpdate ? '' : 'nullable|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm',
        ]);
    }

    public function storeMedia($media){
        $mediaPaths = [];
        $dbMedia = null;

        if($media){
            if($this->postUpdate){
                $dbMedia = json_decode($this->postUpdate->media, true); // Decode to array
            }

            foreach ($media as $key => $item) {
                if ($dbMedia !== null && in_array($item, $dbMedia)) {
                    array_push($mediaPaths, $item);
                } else {
                    $filename = $key . '_' . time() . '_' . uniqid() . '.' . $item->getClientOriginalExtension();
                    $item->storeAs('posts', $filename);
                    array_push($mediaPaths, $filename);
                }
            }
        }

        return $mediaPaths;
    }

    public function markAsUnvailable($id){
        Product::findOrFail($id);
    }

    public function deleteMedia($index){
        array_splice($this->media, $index, 1);
    }

    public function render()
    {
        return view('livewire.Posting.post-form-modal');
    }
}
