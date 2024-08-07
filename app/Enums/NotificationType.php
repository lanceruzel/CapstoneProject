<?php

namespace App\Enums;

enum NotificationType: string
{
    const Status = 'status';
    const Order = 'order';
    const Product = 'product';
    const ProductRegistration = 'product-registration';
    const StoreRegistration = 'store-registration';
    const Stock = 'stock';
    const Invitation = 'invitation';
    const Affiliate = 'affiliate';
    const Appeal = 'appeal';
    const ReturnRequest = 'return-request';
}
