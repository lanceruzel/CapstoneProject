<x-modal-card name="affiliateTermsAndConditionModal" title="Invite Affiliate" align='center' x-cloak x-on:close="$dispatch('clearAffiliateTermsAndConditionModalData')" blurless wire:ignore.self>  
    @if($affiliate)
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-center">Affiliate Program Terms and Conditions</h1>

            <div class="prose">
                <p>Welcome! These terms and conditions ("Terms") govern your participation in the <span class="font-bold">{{ $affiliate->store->storeInformation->name }}</span> Affiliate Program ("Program"). By signing up for the Program, you ("Affiliate") agree to be bound by these Terms.</p>
                
                <br>
                
                <h2>1. Eligibility</h2>
                <ul class="list-disc pl-4">
                    <li>You must be at least 18 years old to participate in the Program.</li>
                    <li>You must have a website, blog, or other online presence that is appropriate for promoting our products.</li>
                    <li>We reserve the right to reject any affiliate application for any reason.</li>
                </ul>

                <br>

                <h2>2. Promotion and Linking</h2>
                <ul class="list-disc pl-4">
                    <li>You are permitted to promote our products using the unique affiliate links or coupon codes provided by us.</li>
                    <li>You may place these links or codes on your website, social media channels, or other online platforms.</li>
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
                <p>We will track sales generated through your affiliate links or coupon codes. You will earn a commission on qualified sales referred by you. The commission rate is <span class="font-bold">{{ $affiliate->rate }}%</span>. We reserve the right to withhold commission for any sales that are fraudulent, cancelled, or returned.</p>

                <br>

                <h2>5. Payment</h2>
                <p>We will pay out commissions at the end of each month, provided you have reached a minimum threshold of [Specify minimum payout threshold]. Payments will be made via [Specify payment method (e.g., PayPal, bank transfer)].</p>

                <br>

                <h2>6. Termination</h2>
                <p>We may terminate your participation in the Program at any time for any reason. You may terminate your participation in the Program at any time. Upon termination, all rights and obligations under these Terms will cease, but any accrued and unpaid commissions will be paid out.</p>

                <br>

                <h2>7. Disclaimer</h2>
                <p>We make no warranties or guarantees regarding the profitability of the Program. You are solely responsible for the content of your website or online presence.</p>

                <br>

                <h2>8. Governing Law</h2>
                <p>These Terms will be governed by and construed in accordance with the laws of [Specify jurisdiction].</p>

                <br>

                <h2>9. Changes to the Terms</h2>
                <p>We may update these Terms at any time. We will notify you of any changes by posting the updated Terms on our website. Your continued participation in the Program after the posting of any changes constitutes your acceptance of the updated Terms.</p>

                <br>

                <h2>10. Contact Us</h2>
                <p>If you have any questions about these Terms, please contact us at [Specify email address].</p>
            </div>
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button flat label="Close" x-on:click="close" />

            <div class="space-x-3">
                <x-button flat negative wire:loading.attr="disabled" wire:click="decline" spinner="decline" label="Decline" />
                <x-button wire:loading.attr="disabled" wire:click="accept" spinner="accept" label="Accept" />
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