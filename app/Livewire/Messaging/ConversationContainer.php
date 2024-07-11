<?php

namespace App\Livewire\Messaging;

use App\Events\NewChatCreated;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ConversationContainer extends Component
{
    use WithFileUploads;

    public $userID;

    public $id;
    public $conversation = null;
    
    public $message;

    public $isSender = false;

    public $user_id;

    public $isAppeal = false;

    public $images;

    public function getListeners(){ 
        return [
            "echo:new-chat.{$this->userID},NewChatCreated" => '$refresh',
            'view-conversation' => 'retrieveMessages',
            'view-appeal-convo' => 'getAppealData',
        ];
    }
    
    public function mount($selectedID){
        if($selectedID){
            $this->conversation = $this->getConversation($selectedID);
        }
    }

    public function getConversation($id){
        return Conversation::where(function ($query) use ($id) {
            $query->where('user_1', Auth::id())
                  ->where('user_2', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('user_1', $id)
                  ->where('user_2', Auth::id());
        })->first();;
    }

    public function sendMessage(){
        if($this->message){
            $validated = $this->formValidate();

            $messageStore = $this->storeMessage($validated);

            if($messageStore){
                if($this->images){
                    foreach($this->images as $key=>$image){
                        $this->deleteImage($key);
                    }
                }
                
                $this->images = [];

                //Update Conversation last message
                $this->conversation->last_message_id = $messageStore->id;
                $this->conversation->save();

                //Notify users
                NewChatCreated::dispatch($this->conversation->user_1);
                NewChatCreated::dispatch($this->conversation->user_2);

                $this->dispatch('messagesUpdated');

                $this->reset('message');
            }
        }
    }

    public function storeMessage($validated){
        return Message::create([
            'user_id' => Auth::id(),
            'conversation_id' => $this->conversation->id,
            'content' => $validated['message'],
            'images' => json_encode($this->storeImages($this->images)),
        ]);
    }

    public function storeImages($images){
        $imagePaths = [];

        if($images){
            foreach ($images as $key => $image) {
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('messages', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function formValidate(){
        return $this->validate([
            'message' => 'required',
            'images.*' => 'image|mimes:png,jpg,jpeg',
        ]);
    }

    public function getAppealData($id){
        if($id != null){
            $this->conversation = Conversation::findOrFail($id)->first();
            $this->isAppeal = true;
        }
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function retrieveMessages($id){
        $this->conversation = Conversation::findOrFail($id);
    }

    public function render(){
        $this->userID = Auth::id();
        $this->dispatch('messagesUpdated');

        return view('livewire.Messaging.conversation-container', [
            'conversation' => $this->conversation,
            'isAppeal' => $this->isAppeal
        ]);
    }
}
