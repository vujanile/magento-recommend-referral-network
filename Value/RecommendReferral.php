<?php
declare(strict_types=1);

namespace Recommend\ReferralNetwork\Value;

class RecommendReferral
{
    private $orderId;
    private $code;
    private $orderNumber;
    private $orderTotal;
    private $currencyCode;
    private $ssnid;
    private $email;

    public function __construct(
        $orderId,
        $orderNumber,
        $orderTotal,
        $currencyCode,
        $code,
        $ssnid,
        $email
    ) {
        $this->orderId = $orderId;
        $this->orderNumber = $orderNumber;
        $this->orderTotal = $orderTotal;
        $this->currencyCode = $currencyCode;
        $this->code = $code;
        $this->ssnid = $ssnid;
        $this->email = $email;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getOrderTotal()
    {
        return $this->orderTotal;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getSsnid()
    {
        return $this->ssnid;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
