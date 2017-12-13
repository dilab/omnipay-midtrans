<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 13/12/17
 * Time: 12:24 PM
 */

namespace Omnipay\Midtrans\Message;


use Omnipay\Tests\TestCase;

class SnapWindowRedirectionCompletePurchaseRequestTest extends TestCase
{
    /**
     * @var  SnapWindowRedirectionCompletePurchaseRequest
     */
    public $request;

    public function testGetData()
    {
        $this->request = new SnapWindowRedirectionCompletePurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize([
            'serverKey' => 'askvnoibnosifnboseofinbofinfgbiufglnbfg'
        ]);

        $httpRequestData = [
            'status_code' => '200',
            'status_message' => 'Success, transaction found',
            'transaction_id' => '249fc620-6017-4540-af7c-5a1c25788f46',
            'masked_card' => '481111-1114',
            'order_id' => 'example-1424936368',
            'payment_type' => 'credit_card',
            'transaction_time' => '2015-02-26 14:39:33',
            'transaction_status' => 'capture',
            'fraud_status' => 'accept',
            'approval_code' => '1424936374393',
            'signature_key' => '2802a264cb978fbc59f631c68d120cbda8dc853f5dfdc52301c615cf4f14e7a0b09aa',
            'bank' => 'bni',
            'gross_amount' => '30000.0',
        ];

        $this->getHttpRequest()->request->replace($httpRequestData);

        $result = $this->request->getData();

        $this->assertSame($httpRequestData, $result);
    }

    public function testSendData()
    {
        $this->request = new SnapWindowRedirectionCompletePurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $data = [
            'order_id' => 'example-1424936368',
            'payment_type' => 'credit_card',
        ];

        $this->assertInstanceOf(
            SnapWindowRedirectionCompletePurchaseResponse::class,
            $this->request->sendData($data)
        );
    }

}