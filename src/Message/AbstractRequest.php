<?php

namespace Omnipay\Midtrans\Message;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 1;

    protected $productionEndpoint = 'https://app.midtrans.com/snap/v1/transactions';

    protected $sandboxEndpoint = 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    protected function getEndPoint()
    {
        if ($this->getTestMode()) {
            return $this->sandboxEndpoint;
        }

        return $this->productionEndpoint;
    }

    public function getServerKey()
    {
        return $this->getParameter('serverKey');
    }

    public function setServerKey($serverKey)
    {
        return $this->setParameter('serverKey', $serverKey);
    }
}