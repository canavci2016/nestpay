<?php

// ##v.1.0.0  notes

use Nestpay\Nestpay;

class  A extends Nestpay
{

}

$nestpay = new A(CLIENT_ID,STORE_KEY,FORM_POST_URL);

##  Payment Request

$nestpay
<p> ->setSuccessUrl(SUCCESS_URL)</p>
<p> ->setFailUrl(FAIL_URL)</p>
<p> ->setCard(CARD_HOLDER_NAME,CARD_NUMBER,CARD_CVC,CARD_EXP_MONTH,CARD_EXP_YEAR)</p>
<p> ->process(ORDER_ID,TOTAL_AMOUNT,CURRENCY_CODE,INSTALLMENT='');</p>


##  Payment Response

print_r($nestpay->response(PAYMENT_REDIRECT_PARAMETERS));

result:
['order_id' => 122323, 'result' => [....], 'text' => digital_sign_verification_failed];

// payment response  (end)



//Parameter list
CURRENCY_CODE= supported currency codes by your bank account.  international currency codes. taken from https://www.iban.com/currency-codes
INSTALLMENT: installment count default is empty



