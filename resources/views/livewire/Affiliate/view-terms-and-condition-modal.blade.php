<x-modal-card name="affiliateTermsAndConditionModal" title="Invite Affiliate" align='center' x-cloak x-on:close="$dispatch('clearAffiliateTermsAndConditionModalData')" blurless wire:ignore.self>  
    @if($affiliate)
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center">Affiliate Program Terms and Conditions</h1>

            <div class="prose">
                <p>Welcome! These terms and conditions ("Terms") govern your participation in the <span class="font-bold">{{ $affiliate->store->name() }}</span> Affiliate Program ("Program"). By signing up for the Program, you ("Affiliate") agree to be bound by these Terms.</p>
                
                <br>
                
                <h2>1. Eligibility</h2>
                <ul class="list-disc pl-4">
                    <li>You must be at least 18 years old to participate in the Program.</li>
                    <li>You must have a website, blog, or other online presence that is appropriate for promoting our products.</li>
                </ul>
            
                <br>
            
                <h2>2. Promotion and Linking</h2>
                <ul class="list-disc pl-4">
                    <li>You are permitted to promote our products using the unique coupon codes provided by us.</li>
                    <li>You may place these codes on your website, social media channels, or other online platforms.</li>
                    <li>Your promotions must be truthful and not misleading. You may not make any false or exaggerated claims about our products.</li>
                    <li>You may not use spam or other unethical marketing practices to promote our products.</li>
                </ul>
            
                <br>
            
                <h2>3. Prohibited Activities</h2>
                <ul class="list-disc pl-4">
                    <li>You may not promote our products on websites or platforms that contain illegal or offensive content.</li>
                    <li>You may not use our trademarks or logos without our permission.</li>
                    <li>You may not offer your own discounts or coupons in conjunction with our products.</li>
                    <li>You may not engage in any activity that could damage our reputation or brand.</li>
                </ul>
                
                <br>
            
                <h2>4. Tracking and Commissions</h2>
                <p>We will track sales generated through your coupon codes. You will earn a commission on qualified sales referred by you. The commission rate is <span class="font-bold">{{ $affiliate->rate }}%</span>. The discount rate provided to customers through your affiliate coupon code is <span class="font-bold">{{ $affiliate->discount }}%</span>. We reserve the right to withhold commission for any sales that are fraudulent, cancelled, or returned.</p>
            
                <br>
            
                <h2>5. Termination</h2>
                <p>We may deactivate your participation in the Program at any time for any reason. Upon termination, all rights and obligations under these Terms will cease.</p>
            
                <br>
            
                <h2>6. Disclaimer</h2>
                <p>We make no warranties or guarantees regarding the profitability of the Program. You are solely responsible for the content of your website or online presence.</p>

                <br>
            
                <h2>9. Contact Us</h2>
                <p>If you have any questions about these Terms, please contact us at our email {{ $affiliate->store->storeInformation->email }} or via phone number {{ $affiliate->store->storeInformation->contact }}.</p>
            </div>
            
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button flat label="Close" x-on:click="close" />

            <div class="space-x-3">
                @if($mode == 'promoter')
                    <x-button flat negative wire:loading.attr="disabled" wire:click="decline" spinner="decline" label="Decline" />
                    <x-button wire:loading.attr="disabled" wire:click="accept" spinner="accept" label="Accept" />
                @elseif($mode == 'seller')
                    <x-button label="View Payout History" wire:click="$dispatch('get-payout-info', { id: {{ $affiliate->promoter_id }} })" onclick="$openModal('affiliatePayoutHistoryModal')"/>

                    @if($affiliate->status == App\Enums\Status::Active)
                        <x-button negative wire:loading.attr="disabled" wire:click="inactiveConfirmation" spinner="inactive" label="Deactivate Affiliation" />
                    @elseif($affiliate->status == App\Enums\Status::Inactive)
                        <x-button positive wire:loading.attr="disabled" wire:click="activeConfirmation" onclick="$openModal('affiliateUpdateFormModal')" wire:click="$dispatch('get-affilaite-data', { id: {{ $affiliate->id }}, affiliateCode: '{{ $affiliate->affiliate_code }}' })" label="Update Affiliation" />
                    @endif
                @endif
            </div>
        </x-slot>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
 </x-modal-card>