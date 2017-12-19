<?php

namespace Omnipay\Midtrans\Message;


class SnapWindowRedirectionCompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        if (0 === strpos($this->httpRequest->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($this->httpRequest->getContent(), true);
            $this->httpRequest->request->replace(is_array($data) ? $data : array());
        }

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return new SnapWindowRedirectionCompletePurchaseResponse(
            $this, $data
        );
    }

}