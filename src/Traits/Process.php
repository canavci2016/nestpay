<?php

namespace Nestpay\Traits;

use FormBuilder\FormBuilder as Form;

trait Process
{
    protected $card = [
        'number' => null,
        'cv2' => null,
        'exp_year' => null,
        'exp_month' => null,
        'card_type' => null,
    ];

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
            ->inputText('pan', $card['number'])
            ->inputText('cv2', $card['cv2'])
            ->inputText('Ecom_Payment_Card_ExpDate_Year', $card['exp_year'])
            ->inputText('Ecom_Payment_Card_ExpDate_Month', $card['exp_month'])
            ->inputText('cardType', $card['type'])
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
        $cardType = parent::creditCardType($number);
        switch ($cardType) {
            case 'visa':
                $cardType = 1;
                break;
            case 'mastercard':
                $cardType = 2;
                break;
            default:
                $cardType = 1;
        }

        $this->card = [
            'number' => $number,
            'cv2' => $cv2,
            'exp_month' => $exp_month,
            'exp_year' => $exp_year,
            'type' => $cardType,
        ];

        return $this;
    }

    public function getCard(): array
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
