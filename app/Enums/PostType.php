<?php

namespace App\Enums;

enum PostType: string
{
    const Status = 'status';
    const Promotion = 'promotion';
    const Product = 'product';
    const Livestream = 'livestream';
}
