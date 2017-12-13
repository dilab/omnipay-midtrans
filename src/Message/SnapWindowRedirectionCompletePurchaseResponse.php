<?php

namespace Omnipay\Midtrans\Message;

use Omnipay\Common\Message\AbstractResponse;

class SnapWindowRedirectionCompletePurchaseResponse extends AbstractResponse
{
    protected $message;

    public function isPending()
    {
        return !$this->isSuccessful();
    }

    public function isSuccessful()
    {
        return $this->isValidSignatureKey() && $this->isValidFields();
    }

    public function getTransactionReference()
    {
        return $this->data('transaction_id');
    }

    private function isValidSignatureKey()
    {
        $orderId = $this->data('order_id');
        $statusCode = $this->data('status_code');
        $grossAmount = $this->data('gross_amount');
        $serverKey = $this->getRequest()->getServerKey();
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');
        $result = ($signature === $this->data('signature_key'));

        if (!$result) {
            $this->message = 'invalid signature key';
        }

        return $result;
    }

    private function isValidFields()
    {
        $result =
            intval($this->data('status_code')) == 200 &&
            strtolower($this->data('fraud_status')) == 'accept' &&
            in_array(strtolower($this->data('transaction_status')), ['settlement', 'capture']);

        if (!$result) {
            $this->message = 'invalid fields';
        }

        return $result;
    }

    private function data($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : '';
    }

    public function getMessage()
    {
        return $this->message;
    }
}