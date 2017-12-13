<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 12/12/17
 * Time: 5:13 PM
 */

namespace Omnipay\Midtrans\Message;

use Omnipay\Tests\TestCase;

class TestSnapWindowRedirectionPurchaseRequest extends SnapWindowRedirectionPurchaseRequest
{
    public function getSendDataHeader()
    {
        return parent::getSendDataHeader();
    }
}

class SnapWindowRedirectionPurchaseRequestTest extends TestCase
{
    /**
     * @var SnapWindowRedirectionPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new SnapWindowRedirectionPurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function testGetSendDataHeader()
    {
        $this->request = new TestSnapWindowRedirectionPurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg'
        ]);

        $expected = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic VlQtc2VydmVyLUNwbzAza1lET2MwY05VS2d0NmhuTGtLZzo=',
        ];

        $result = $this->request->getSendDataHeader();

        $this->assertEquals($expected, $result);
    }

    public function testGetData()
    {
        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00',
            'transactionId' => 'ORDER-101'
        ]);

        $result = $this->request->getData();

        $expected = [
            'transaction_details' => [
                'order_id' => 'ORDER-101',
                'gross_amount' => 10000
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testNoAmount()
    {
        $baseData = array(// nothing here - should cause a certain exception
        );

        $this->request->initialize($baseData);

        $this->setExpectedException(
            '\Omnipay\Common\Exception\InvalidRequestException',
            'The amount parameter is required'
        );

        $this->request->getData();
    }

    public function testMinAmount()
    {
        $baseData = array(
            'amount' => 100.00,
            'transactionId' => 'ORDER-101'
        );

        $this->request->initialize($baseData);

        $this->setExpectedException(
            '\Omnipay\Common\Exception\InvalidRequestException'
        );

        $this->request->getData();
    }

    public function testNoTransactionId()
    {
        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00'
        ]);

        $this->setExpectedException(
            '\Omnipay\Common\Exception\InvalidRequestException'
        );

        $this->request->getData();
    }

    public function testInvalidTransactionIdFormat()
    {
        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00',
            'transactionId' => '@123'
        ]);

        $this->setExpectedException(
            '\Omnipay\Common\Exception\InvalidRequestException'
        );

        $this->request->getData();

        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00',
            'transactionId' => 'order-1~9_.'
        ]);

        $this->assertTrue(is_array($this->request->getData()));
    }


}
