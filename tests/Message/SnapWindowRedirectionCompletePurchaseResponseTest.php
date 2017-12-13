<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 13/12/17
 * Time: 4:01 PM
 */

namespace Omnipay\Midtrans\Message;


use Omnipay\Tests\TestCase;

class SnapWindowRedirectionCompletePurchaseResponseTest extends TestCase
{
    /**
     * @var SnapWindowRedirectionCompletePurchaseRequest
     */
    public $request;

    /**
     * @var SnapWindowRedirectionCompletePurchaseResponse
     */
    public $response;

    public $data;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockRequest();

        $this->request
            ->shouldReceive('getServerKey')
            ->andReturn('askvnoibnosifnboseofinbofinfgbiufglnbfg');

        $this->data = [
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
        ];
    }

    public function testResponseSuccess()
    {
        $this->response = new SnapWindowRedirectionCompletePurchaseResponse(
            $this->request,
            $this->data
        );

        $this->assertFalse($this->response->isPending());
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('249fc620-6017-4540-af7c-5a1c25788f46', $this->response->getTransactionReference());
    }

    public function testResponseInvalidSignatureKey()
    {
        $this->data['signature_key'] = '123';

        $this->response = new SnapWindowRedirectionCompletePurchaseResponse(
            $this->request,
            $this->data
        );

        $this->assertTrue($this->response->isPending());
        $this->assertFalse($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('249fc620-6017-4540-af7c-5a1c25788f46', $this->response->getTransactionReference());
    }

    public function responseInvalidFieldsData()
    {
        return [
            [['status_code' => 201]],
            [['fraud_status' => 'REJECTED']],
            [['transaction_status' => 'pending']],
        ];
    }

    /**
     * @dataProvider responseInvalidFieldsData
     */
    public function testResponseInvalidFields($data)
    {
        $data = array_merge($this->data, $data);

        $this->response = new SnapWindowRedirectionCompletePurchaseResponse(
            $this->request,
            $data
        );

        $this->assertTrue($this->response->isPending());
        $this->assertFalse($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('249fc620-6017-4540-af7c-5a1c25788f46', $this->response->getTransactionReference());
    }
}
