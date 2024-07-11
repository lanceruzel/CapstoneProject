<x-modal-card name="reportAppealConversationModal" title="Report Appeal Conversation" align='center' x-cloak x-on:close="$dispatch('clearReportAppealModalData')" blurless wire:ignore.self>  
    <livewire:Messaging.conversation-container :selectedID='null' />
</x-modal-card>