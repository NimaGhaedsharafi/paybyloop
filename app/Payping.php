<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payping extends Model
{
    const FailedVerification = -2;
    const FailedInitiation = -1;
    const Initiated = 0;
    const Requested = 1;
    const Verifying = 2;
    const Verified = 3;
}
