<?php

use Money\Money;

function money_to_string(Money $money): string
{
    $precision = 2;
    $amount    = bcdiv($money->getAmount(), 10 ** $precision, $precision);

    return sprintf("%.{$precision}F", $amount);
}