<?php
namespace Omnipay\Midtrans\Message;

use Omnipay\Common\Message\AbstractResponse;

class SnapWindowRedirectionPurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return false;
    }

}