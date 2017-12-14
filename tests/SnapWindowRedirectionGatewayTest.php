<?php
namespace Omnipay\Midtrans\Message;

use Omnipay\Midtrans\SnapWindowRedirectionGateway;
use Omnipay\Tests\TestCase;

class SnapWindowRedirectionGatewayTest extends TestCase
{
    /** @var SnapWindowRedirectionGateway */
    protected $gateway;

    /** @var array */
    private $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new SnapWindowRedirectionGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setServerKey('askvnoibnosifnboseofinbofinfgbiufglnbfg');

        $this->options = [
            'amount' => '1000000.00',
            'transactionId' => 'ORDER-1',
            'testMode' => true
        ];
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('SnapSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('SnapFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertNotEmpty($response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->request->replace([
            'status_code' => '200',
            'status_message' => 'Success, transaction found',
            'transaction_id' => '249fc620-6017-4540-af7c-5a1c25788f46',
            'masked_card' => '481111-1114',
            'order_id' => '1111',
            'payment_type' => 'credit_card',
            'transaction_time' => '2015-02-26 14:39:33',
            'transaction_status' => 'capture',
            'fraud_status' => 'accept',
            'approval_code' => '1424936374393',
            'signature_key' => 'edc076b21793ebe3e17926350f5b8ae67d902fe657b3d0aa31b932d5c127e2375d308a2bc94f3265ac2d80a1f181a79b997ac178a236fcff35af263fc4d4c231',
            'bank' => 'bni',
            'gross_amount' => '100000.00',
        ]);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('249fc620-6017-4540-af7c-5a1c25788f46', $response->getTransactionReference());
    }

    public function testCompletePurchaseFailure()
    {
        $this->getHttpRequest()->request->replace([
            'status_code' => '400',
            'status_message' => 'Success, transaction found',
            'transaction_id' => '249fc620-6017-4540-af7c-5a1c25788f46',
            'masked_card' => '481111-1114',
            'order_id' => '1111',
            'payment_type' => 'credit_card',
            'transaction_time' => '2015-02-26 14:39:33',
            'transaction_status' => 'capture',
            'fraud_status' => 'accept',
            'approval_code' => '1424936374393',
            'signature_key' => 'edc076b21793ebe3e17926350f5b8ae67d902fe657b3d0aa31b932d5c127e2375d308a2bc94f3265ac2d80a1f181a79b997ac178a236fcff35af263fc4d4c231',
            'bank' => 'bni',
            'gross_amount' => '100000.00',
        ]);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

}
