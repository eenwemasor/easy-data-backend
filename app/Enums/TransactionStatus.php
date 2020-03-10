<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class TransactionStatus extends Enum
{
    const SENT =   "SENT";
    const PROCESSING =   "PROCESSING";
    const COMPLETED = "COMPLETED";
    const FAILED = "FAILED";
}
