<?php

namespace App\Livewire\Posting;

use App\Events\CommentCreated;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
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
            "echo:update-post.{$this->post->id},PostUpdated" => '$refresh',
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
        if ($this->checkPostStatus()) {
            return; 
        }

        $validated = $this->validate([
            'commentContent' => 'required',
        ]);

        $comment = PostComment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $validated['commentContent'],
        ]);

        if($comment){
            CommentCreated::dispatch($comment, $this->post->id);

            $this->reset('commentContent');

            if(!$this->showAllComments){
                $this->showAllComments = true;
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
