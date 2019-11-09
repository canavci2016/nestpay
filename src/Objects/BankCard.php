<?php

namespace Nestpay\Objects;

class BankCard
{
    private $holder;
    private $number;
    private $cvv;
    private $expMonth;
    private $expYear;

    /**
     * BankCard constructor.
     * @param $holder
     * @param $number
     * @param $cvv
     * @param $expMonth
     * @param $expYear
     */
    public function __construct($holder, $number, $cvv, $expMonth, $expYear)
    {
        $this->holder = $holder;
        $this->number = $number;
        $this->cvv = $cvv;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
    }


    /**
     * @return mixed
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @param mixed $holder
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * @param mixed $cvv
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;
    }

    /**
     * @return mixed
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }

    /**
     * @param mixed $expMonth
     */
    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;
    }

    /**
     * @return mixed
     */
    public function getExpYear()
    {
        return $this->expYear;
    }

    /**
     * @param mixed $expYear
     */
    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;
    }


    public function scheme()
    {
        if ($this->numberSub(2) == 37) {
            $scheme = 'amex';
        } elseif ($this->numberSub() == 4) {
            $scheme = 'visa';
        } elseif (in_array($this->numberSub(2), range(51, 55))) {
            $scheme = 'mastercard';
        } elseif (in_array($this->numberSub(3), range(300, 305))) {
            $scheme = 'dinersclub';
        } elseif ($this->numberSub(2) == 36) {
            $scheme = 'dinersclub';
        } elseif ($this->numberSub(2) == 38) {
            $scheme = 'dinersclub';
        } elseif ($this->numberSub(4) == 6011) {
            $scheme = 'discover';
        } elseif ($this->numberSub(2) == 65) {
            $scheme = 'discover';
        } elseif ($this->numberSub(2) == 35) {
            $scheme = 'jcb';
        } else {
            $scheme = 'unknown';
        }
        return $scheme;
    }

    public function numberSub($length = 1)
    {
        $val = substr($this->getNumber(), 0, $length);
        return intval($val);
    }
}