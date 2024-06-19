<?php

namespace App\Livewire\Posting;

use App\Enums\PostType;
use App\Enums\Status;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class PostFormModal extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $content;
    public $images;

    public function store(){
        $postType = PostType::Status;

        $validated = $this->formValidate();

        $post = $this->storePost($postType, $validated);

        if($post){
            $this->reset('content');

            $this->dispatch('post-created');
            $this->dispatch('close-modal', ['modal' => 'postFormModal']);

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Your post has been successfully posted!',
            ]);
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'There seem to be a problem creating your post.',
            ]);
        }
    }

    public function storePost($postType, $validated){
        return Post::create([
            'type' => $postType,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'images' => json_encode($this->storeImages($this->images)),
            'status' => Status::Available
        ]);
    }

    public function formValidate(){
        return $this->validate([
            'content' => 'required',
            'images.*' => 'image|mimes:png,jpg,jpeg',
        ]);
    }

    public function storeImages($images){
        $imagePaths = [];

        if($images){
            foreach ($images as $key => $image) {
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('posts', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function render()
    {
        return view('livewire.Posting.post-form-modal');
    }
}
