<div class="w-full bg-white p-5 rounded-lg shadow space-y-3 flex flex-col items-end justify-center mt-3">
    <table class="border-separate border-spacing-3">
        <tbody>
            <tr>
                <td class="text-end">Merchandise Subtotal:</td>
                <td>${{ $totalMerchandiseTotal }}</td>
            </tr>

            <tr>
                <td class="text-end">Tax Total:</td>
                <td>₱3232 (2%)</td>
            </tr>

            <tr>
                <td class="text-end">Shipping Total:</td>
                <td>₱323232</td>
            </tr>

            <tr>
                <td class="text-end">Total Payment:</td>
                <td class="text-xl font-semibold">₱3232</td>
            </tr>
        </tbody>
    </table>

    <x-button class="!px-10" label="Place Order" />
</div> 