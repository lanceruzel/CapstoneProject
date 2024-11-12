<?php

namespace App\Livewire\Posting;

use App\Classes\UserNotif;
use App\Classes\WordFilter;
use App\Enums\NotificationType;
use App\Events\CommentCreated;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Str;
use WireUi\Traits\WireUiActions;

class PostContainer extends Component
{
    use WireUiActions;

    public $post;

    public $totalLikes = 0;
    public $totalComments = 0;

    public $commentContent;

    public $showAllComments;
    public $hasLike;

    public function getListeners(){
        return [
            "echo:new-comment.{$this->post->id},CommentCreated" => '$refresh',
            "echo:post-updated.{$this->post->id},PostUpdated" => '$refresh',
            "delete-post" => 'postDeleteConfirmation',
        ];
    }

    public function mount($post = null){
        $this->post = $post;
    }

    public function toggleComments(){
        $this->showAllComments = !$this->showAllComments;
    }

    public function storeComment(){
        //Check post status first
        if($this->checkPostStatus()) {
            return; 
        }

        $validated = $this->validate([
            'commentContent' => 'required',
        ]);

        $comment = PostComment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => WordFilter::filteredInput($validated['commentContent']),
        ]);

        if($comment){
            CommentCreated::dispatch($comment, $this->post->id);

            $this->reset('commentContent');

            if(!$this->showAllComments){
                $this->showAllComments = true;
            }

            if($this->post->user_id != Auth::id()){
                UserNotif::sendNotif($this->post->user_id, auth()->user()->name() . ' have commented on your post.' , NotificationType::Status);
            }
        }
    }

    public function storeLike(){
        //Check post status first
        if ($this->checkPostStatus()) {
            return; 
        }

        //Delete if like is existing 
        if ($this->hasLike) {
            $this->hasLike->delete();
            return;
        }

        //If it is not existing then insert 
        $store_like = PostLike::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
        ]);

        if($store_like && ($this->post->user_id != Auth::id())){
            UserNotif::sendNotif($this->post->user_id, auth()->user()->name() . ' liked your post.' , NotificationType::Status);
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

    public function checkPostStatus(){
        //Check if post is existing
        $post = Post::find($this->post->id);

        if ($post && $post->is_deleted) {
            $this->dialog()->show([
                'icon' => 'info',
                'title' => 'Information!',
                'description' => 'Woops, this post has already been deleted.',
            ]);
            
            return true;
        }

        return false;
    }

    public function postDeleteConfirmation($id){
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Delete this post?',
            'icon' => 'question',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'deletePost',
                'params' => $id,
            ],
            'reject' => [
                'label' => 'No, cancel',
                'method' => '',
            ],
        ]);
    }

    public function deletePost($id){
        $post = Post::find($id);

        if($post){
            if(Post::destroy($id)){
                $this->dispatch('post-create-delete');

                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Your post has been removed.',
                ]);
            }
        }else{
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Error Dialog!',
                'description' => 'Your post cannot be found.',
            ]);
        }
    }

    public function getTotalLikes(){
        $likes = 0;

        if($this->post->postLikes){
            foreach ($this->post->postLikes as $like) {
                $likes++;
            }
        }

        $this->totalLikes = $likes;
    }

    public function getTotalComments(){
        $comments = 0;

        if($this->post->postComments){
            foreach ($this->post->postComments as $comment) {
                $comments++;
            }
        }

        $this->totalComments = $comments;
    }

    public function render()
    {
        $this->hasLike = PostLike::where('post_id', $this->post->id)->where('user_id', Auth::id())->first();

        if($this->post != null){
            $this->getTotalLikes();
            $this->getTotalComments();
        }

        return view('livewire.Posting.post-container', [
            'post' => $this->post,
            'totalLikes' => $this->totalLikes,
            'totalComments' => $this->totalComments,
            'comments' => $this->showAllComments ? $this->post->postComments : $this->post->postComments->take(3),
        ]);
    }
}
