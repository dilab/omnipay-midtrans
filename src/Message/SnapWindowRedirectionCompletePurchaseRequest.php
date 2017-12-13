<?php
namespace Omnipay\Midtrans\Message;


class SnapWindowRedirectionCompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return new SnapWindowRedirectionCompletePurchaseResponse(
            $this,$data
        );
    }

}