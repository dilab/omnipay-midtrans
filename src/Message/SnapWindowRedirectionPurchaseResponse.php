<?php

namespace Omnipay\Midtrans\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class SnapWindowRedirectionPurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (!is_array($data)) {
            $this->data = json_decode(trim($data), true);
        }
    }

    public function isPending()
    {
        return !isset($this->data['token']);
    }

    public function isSuccessful()
    {
        return isset($this->data['token']);
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return isset($this->data['redirect_url']) ? $this->data['redirect_url'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data['token'];
        }

        return array_pop($this->data['error_messages']);
    }
}