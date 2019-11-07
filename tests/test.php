<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Nestpay\Nestpay;

class  A extends Nestpay
{

    public function response(array $response)
    {
        return parent::response($response);
    }
}

$request = \Illuminate\Http\Request::createFromGlobals();

$request->merge(['adaw' => 'awd']);
print_r($request->all());

$nestpay = new A('CLIENT_ID', 'STORE_KEY', 'https://sanalpos2.ziraatbank.com.tr/fim/est3Dgate');


$nestpay
    ->setSuccessUrl('canavci.com/success')
    ->setFailUrl('canavci.com/fail')
    ->setCard('Can Avcı', '5170414347189876', '152', '01', 26)
    ->processByCurrencyCode(rand(), 123, 'TL');

print_r($nestpay->response(['awd']));
print_r($nestpay);