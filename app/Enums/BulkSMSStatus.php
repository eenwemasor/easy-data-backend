<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BulkSMSStatus extends Enum
{
    const FAILED = "FAILED";
    const DELIVERED = "DELIVERED";
}
