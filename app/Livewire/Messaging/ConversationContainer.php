<?php

namespace App\Livewire\Messaging;

use App\Classes\WordFilter;
use App\Events\NewChatCreated;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Str;
use WireUi\Traits\WireUiActions;

class ConversationContainer extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $userID;

    public $id;
    public $conversation = null;
    
    public $message = null;

    public $isSender = false;

    public $user_id;

    public $isAppeal = false;

    public $media = null;

    public function getListeners(){ 
        return [
            "echo:new-chat.{$this->userID},NewChatCreated" => '$refresh',
            'view-conversation' => 'retrieveMessages',
            'view-appeal-convo' => 'getAppealData',
            'clear-appeal-convo-view' => 'clearConvoView'
        ];
    }
    
    public function mount($selectedID){
        if($selectedID){
            $this->conversation = Conversation::findOrFail($selectedID);
        }
    }

    public function sendMessage(){
        $validated = $this->formValidate();

        try{
            if($this->message != null || ($this->media != null || $this->media != [])){
    
                $messageStore = $this->storeMessage($validated);
    
                if($messageStore){
                    if($this->media){
                        foreach($this->media as $key => $item){
                            $this->deleteMedia($key);
                        }
                    }

                    //Update Conversation last message
                    $this->conversation->last_message_id = $messageStore->id;
                    $this->conversation->save();
    
                    //Notify users
                    NewChatCreated::dispatch($this->conversation->user_1);
                    NewChatCreated::dispatch($this->conversation->user_2);
    
                    $this->dispatch('messagesUpdated');
                    $this->reset(['message', 'media']);
                }
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error send message: ' . $e->getMessage());
        }
    }

    public function storeMessage($validated){
        return Message::create([
            'user_id' => Auth::id(),
            'conversation_id' => $this->conversation->id,
            'content' => $validated['message'],
            'media' => json_encode($this->storeMedia($this->media)),
        ]);
    }

    public function clearConvoView(){
        $this->conversation = null;
    }

    public function storeMedia($media){
        $mediaPaths = [];

        if($media){
            foreach ($media as $key => $item) {
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $item->getClientOriginalExtension();
                $item->storeAs('messages', $filename);
                array_push($mediaPaths, $filename);
            }
        }

        return $mediaPaths;
    }

    public function formValidate(){
        return $this->validate([
            'message' => 'nullable|blasp_check',
            'media.*' => 'nullable|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm',
        ]);
    }

    public function getAppealData($id){
        if($id != null){
            $this->conversation = Conversation::findOrFail($id)->first();
            $this->isAppeal = true;
        }
    }

    public function deleteMedia($index){
        array_splice($this->media, $index, 1);
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
