<?php

namespace Nestpay\Traits;

use FormBuilder\FormBuilder as Form;
use Nestpay\Objects\BankCard;

trait Process
{
    protected $card = null;

    public function processByCurrencyCode($orderId, $amount, $curCode, $installment = 1)
    {
        return $this->process($orderId, $amount, $this->getCurrencyNumber($curCode), $installment);
    }

    public function process($orderId, $amount, $currencyCode, $installment = '')
    {
        $installment = intval($installment) > 1 ? $installment : '';
        $this->setRandomNumber(microtime());
        $card = $this->getCard();

        (new Form($this->getUrl(), 'POST'))
            ->inputText('pan', $card->getNumber())
            ->inputText('cv2', $card->getCvv())
            ->inputText('Ecom_Payment_Card_ExpDate_Year', $card->getExpYear())
            ->inputText('Ecom_Payment_Card_ExpDate_Month', $card->getExpMonth())
            ->inputText('cardType', $card->scheme() == 'mastercard' ? 2 : 1)
            ->inputHidden('clientid', $this->getClientId())
            ->inputHidden('amount', $amount)
            ->inputHidden('oid', $orderId)
            ->inputHidden('okUrl', $this->getSuccessUrl())
            ->inputHidden('failUrl', $this->getFailUrl())
            ->inputHidden('rnd', $this->getRandomNumber())
            ->inputHidden('hash', $this->hashGenerate($orderId, $amount, $installment))
            ->inputHidden('islemtipi', $this->getProcessType())
            ->inputHidden('taksit', $installment)
            ->inputHidden('storetype', '3d_pay')
            ->inputHidden('lang', 'tr')
            ->inputHidden('currency', $currencyCode)
            ->submit();
    }

    protected function hashGenerate($order_id, $amount, $installment = '')
    {
        $hashstr = $this->getClientId();
        $hashstr .= $order_id;
        $hashstr .= $amount;
        $hashstr .= $this->getSuccessUrl();
        $hashstr .= $this->getFailUrl();
        $hashstr .= $this->getProcessType();
        $hashstr .= $installment;
        $hashstr .= $this->getRandomNumber();
        $hashstr .= $this->getStoreKey();

        $hash = base64_encode(pack('H*', sha1($hashstr)));
        return $hash;
    }

    public function setCard($holder_name, $number, $cv2, $exp_month, $exp_year)
    {
        $this->card = new BankCard($holder_name, $number, $cv2, $exp_month, $exp_year);
        return $this;
    }

    public function getCard(): BankCard
    {
        return $this->card;
    }

    public function setSuccessUrl($successUrl)
    {
        $this->successUrl = $successUrl;
        return $this;
    }

    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    public function setRandomNumber($randomNumber)
    {
        $this->randomNumber = $randomNumber;
    }

    public function getRandomNumber()
    {
        return $this->randomNumber;
    }

    public function setFailUrl($url)
    {
        $this->failUrl = $url;
        return $this;
    }

    public function getFailUrl()
    {
        return $this->failUrl;
    }
}
