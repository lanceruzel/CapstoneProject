<?php

namespace App\Enums;

enum Status: string
{
    const Available = 'available';
    const Unavailable = 'unavailable';
    const Active = 'active';
    const Inactive = 'inactive';
    const Deleted = 'deleted';
}
