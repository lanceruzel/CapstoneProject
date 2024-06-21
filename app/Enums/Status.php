<?php

namespace App\Enums;

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
}
