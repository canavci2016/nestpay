<?php

namespace Nestpay;

abstract class BasePaymentProvider
{
    protected function creditCardType($card_number)
    {
        $card_first_number = (int)substr($card_number, 0, 1);

        if ($card_first_number === 4) {
            return 'visa';
        } elseif ($card_first_number === 5) {
            return 'mastercard';
        }

        throw new \Exception('Unsupported cart type');
    }
}
