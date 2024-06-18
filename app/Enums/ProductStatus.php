<?php

namespace App\Enums;

enum ProductStatus: string
{
    const Available = 'available';
    const Unavailable = 'unavailable';
    const Suspended = 'suspended';
    const Validating = 'validating';
    const Declined = 'declined';
}
