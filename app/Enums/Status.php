<?php

namespace App\Enums;

use App\Models\ReturnRequest;

enum Status: string
{
    const Available = 'available';
    const Unavailable = 'unavailable';
    const Active = 'active';
    const Inactive = 'inactive';
    const Deleted = 'deleted';

    const ForReview = 'for-review';
    const Accepted = 'accepted';
    const Declined = 'declined';
    const Submitted = 'submitted';
    const Resubmit = 'resubmit';
    const ForSubmission = 'for-submission';
    const ForReSubmission = 'for-resubmission';
    const LowStockLevel = 'low-stock-level';
    const Suspended = 'suspended';

    const Delivered = 'delivered';

    const OrderSellerConfirmation = 'Waiting for seller\'s confirmation.';
    const OrderSellerPreparing = 'Seller is preparing your order for shipment.';
    const OrderSellerShipped = 'Your order has already been shipped out by the seller.';
    const OrderSellerCancel = 'Seller cancelled your order.';

    const OrderBuyerReceived = 'Order has been received.';
    const OrderBuyerCancel = 'Buyer cancelled they\'re order.';

    const Invitation = 'invitation';

    const Preparing = 'preparing';

    const ReturnRequestReview = 'seller-review';
    const ReturnRequestReceieved = 'seller-receieved';
    const ReturnRequestBuyerShipped = 'buyer-shipped';
    const ReturnRequestSellerOrderCreated = 'seller-created-order';
}
