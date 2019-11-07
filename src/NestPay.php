<?php

namespace Nestpay;

use Nestpay\Traits\Process;
use Nestpay\Traits\Response;
use Illuminate\Http\Request;

abstract class NestPay extends BasePaymentProvider
{
    use Process, Response;

    protected $url = null;
    protected $clientId = null;
    protected $storeKey = null;
    protected $processType = "Auth";
    protected $successUrl = null;
    protected $failUrl = null;
    protected $randomNumber = null;
    /**
     * international currency codes. taken from https://www.iban.com/currency-codes
     */
    protected $supportedCurrencies = [
        'TRY' => '949',
        'TL' => '949',
        'EUR' => '978',
        'USD' => '840',
    ];


    public function __construct($clientId, $storeKey, $url)
    {
        $this->setClientId($clientId);
        $this->setStoreKey($storeKey);
        $this->setUrl($url);
    }

    public function supportedCurrencies(): array
    {
        return $this->supportedCurrencies;
    }

    public function getCurrencyNumber($code)
    {
        return $this->supportedCurrencies[strtoupper($code)];
    }

    public function setUrl(string $url)
    {
        return $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setStoreKey($store_key)
    {
        $this->storeKey = $store_key;
    }

    public function getStoreKey()
    {
        return $this->storeKey;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientId($client_id)
    {
        $this->clientId = $client_id;
    }

    public function getProcessType()
    {
        return $this->processType;
    }

    private function request(array $array): Request
    {
        $request = Request::createFromGlobals();
        $request->merge($array);
        return $request;
    }
}
