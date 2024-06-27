<?php

namespace App\Livewire\Posting;

use App\Enums\PostType;
use App\Enums\Status;
use App\Events\PostUpdated;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class PostFormModal extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $content;
    public $images;

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
                $this->images = json_decode($this->postUpdate->images);
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
            Log::error('Error on store post: ' . $e->getMessage());
        }
    }

    public function clearData(){
        $this->postUpdate = null;
        $this->reset('content');
        $this->reset('images');
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
                'content' => $validated['content'],
                'images' => json_decode($this->postUpdate->images) == $this->images ? json_encode($this->images) : json_encode($this->storeImages($this->images)),
                'status' => Status::Available
            ]
        );      
    }

    public function formValidate(){
        return $this->validate([
            'content' => 'required',
            'images.*' => $this->postUpdate ? '' : 'image|mimes:png,jpg,jpeg',
        ]);
    }

    public function storeImages($images){
        $imagePaths = [];

        if ($images) {
            $dbImages = json_decode($this->postUpdate->images, true); // Decode to array

            foreach ($images as $key => $image) {
                if ($dbImages !== null && in_array($image, $dbImages)) {
                    // Existing image, keep the path
                    array_push($imagePaths, $image);
                } else {
                    // New image, store and get path
                    $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('posts', $filename);
                    array_push($imagePaths, $filename);
                }
            }
        }

        return $imagePaths;
    }

    public function markAsUnvailable($id){
        Product::findOrFail($id);
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function render()
    {
        return view('livewire.Posting.post-form-modal');
    }
}
