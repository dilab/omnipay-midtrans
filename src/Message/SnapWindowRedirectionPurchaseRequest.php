<?php

namespace Omnipay\Midtrans\Message;


use Omnipay\Common\Exception\InvalidRequestException;

class SnapWindowRedirectionPurchaseRequest extends AbstractRequest
{
    const MIN_AMOUNT = 10000;
    const MAX_LENGTH_TRANSACTION_ID = 50;

    public function sendData($data)
    {
        $responseData = $this->httpClient
            ->post($this->getEndPoint(), $this->getSendDataHeader(), $data)
            ->send()
            ->getBody(true);

        return $this->response = new SnapWindowRedirectionPurchaseResponse($this, $responseData);
    }

    public function getData()
    {
        $this->validate('amount', 'transactionId');

        $this->guardMinAmount();

        $this->guardTransationId();

        return [
            'transaction_details' => [
                'order_id' => $this->getTransactionId(),
                'gross_amount' => intval($this->getAmount()),
            ]
        ];
    }

    protected function getSendDataHeader()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->getServerKey() . ':')
        ];
    }

    /**
     * @throws InvalidRequestException
     */
    private function guardMinAmount()
    {
        if (intval($this->getAmount()) < self::MIN_AMOUNT) {
            throw new InvalidRequestException(
                sprintf('Each transaction requires a minimum gross_amount of %s.', self::MIN_AMOUNT)
            );
        }
    }

    /**
     * @throws InvalidRequestException
     */
    private function guardTransationId()
    {
        if (!preg_match('/^[a-z0-9\-_\~.]+$/i', $this->getTransactionId())) {
            throw new InvalidRequestException(
                'Allowed symbols for transactionId are dash(-), underscore(_), tilde (~), and dot (.)'
            );
        }

        if (strlen($this->getTransactionId()) > self::MAX_LENGTH_TRANSACTION_ID) {
            throw new InvalidRequestException(
                'Max length for transactionId is ' . self::MAX_LENGTH_TRANSACTION_ID
            );
        }

    }

}