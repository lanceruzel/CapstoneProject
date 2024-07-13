<x-modal-card name="affiliateInvitationsModal" title="Affiliate Invites" align='center' x-cloak blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-center justify-center text-gray-600 overflow-auto">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">Store</th>
                    <th scope="col" class="px-6 py-3 text-center">Date Invited</th>
                    <th scope="col" class="px-6 py-3 text-center"></th>
                </tr>
            </thead>
    
            <tbody>
                @if(count($affiliates) > 0)
                    @foreach ($affiliates as $affiliate)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4 text-center">{{ $affiliate->store->storeInformation->name }}</td>
                            <td class="px-6 py-4 text-center">{{ date_format($affiliate->created_at, "M d, Y g:i a") }}</td>
                            <td class="px-6 py-4 flex items-center justify-center">
                                <x-button label="View Terms and Condition" onclick="$openModal('affiliateTermsAndConditionModal')" wire:click="$dispatch('view-terms-and-condition-info', { id: {{ $affiliate->id }} })" />
                            </td>
                        </tr>
                    @endforeach 
                @else
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4 bg-gray-50">
                            No invitation found.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
            
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Close" x-on:click="close" />
        </x-slot>
    </div>
</x-modal-card>
