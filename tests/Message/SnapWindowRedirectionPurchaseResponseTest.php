<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 13/12/17
 * Time: 11:21 AM
 */

namespace Omnipay\Midtrans\Message;


use Omnipay\Tests\TestCase;

class SnapWindowRedirectionPurchaseResponseTest extends TestCase
{
    /**
     * @var SnapWindowRedirectionPurchaseResponse
     */
    private $response;

    public function testPurchaseSuccess()
    {
        $httpResponse = '
        {
          "token": "d379aa71-99eb-4dd1-b9bb-eefe813746e9",
          "redirect_url": "https://app.sandbox.veritrans.co.id/snap/v2/vtweb/d379aa71-99eb-4dd1-b9bb-eefe813746e9"
        }
        ';

        $this->response = new SnapWindowRedirectionPurchaseResponse($this->getMockRequest(), $httpResponse);

        $this->assertFalse($this->response->isPending());
        $this->assertTrue($this->response->isSuccessful());
        $this->assertSame('d379aa71-99eb-4dd1-b9bb-eefe813746e9', $this->response->getMessage());
        $this->assertTrue($this->response->isRedirect());
        $this->assertNull($this->response->getRedirectData());
        $this->assertNull($this->response->getTransactionReference());
        $this->assertSame('https://app.sandbox.veritrans.co.id/snap/v2/vtweb/d379aa71-99eb-4dd1-b9bb-eefe813746e9', $this->response->getRedirectUrl());
        $this->assertSame('GET', $this->response->getRedirectMethod());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = '
        {
          "error_messages": [
            "Access denied, please check client or server key"
          ]
        }
        ';

        $this->response = new SnapWindowRedirectionPurchaseResponse($this->getMockRequest(), $httpResponse);

        $this->assertTrue($this->response->isPending());
        $this->assertFalse($this->response->isSuccessful());
        $this->assertSame('Access denied, please check client or server key', $this->response->getMessage());
        $this->assertTrue($this->response->isRedirect());
        $this->assertNull($this->response->getRedirectData());
        $this->assertNull($this->response->getTransactionReference());
        $this->assertNull($this->response->getRedirectUrl());
        $this->assertSame('GET', $this->response->getRedirectMethod());
    }

}
