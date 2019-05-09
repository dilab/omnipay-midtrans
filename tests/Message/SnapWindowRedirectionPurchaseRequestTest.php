<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 12/12/17
 * Time: 5:13 PM
 */

namespace Omnipay\Midtrans\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Common\Exception\InvalidRequestException;

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
            'card' => [
                'firstName' => 'Xu',
                'lastName' => 'Ding',
                'email' => 'xuding@spacebib.com',
                'number' => '93804194'
            ],
            'description' => 'Marina Run 2016',
            'amount' => '10000.00',
            'transactionId' => 'ORDER-101',
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
        ]);

        $result = $this->request->getData();

        $expected = [
            'transaction_details' => [
                'order_id' => 'ORDER-101',
                'gross_amount' => 10000
            ],
            'item_details' => [
                [
                    'id' => 'ORDER-101',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Marina Run 2016',
                    'brand' => 'Marina Run 2016',
                ]
            ],
            'credit_card' => [
                'secure' => true
            ],
            'customer_details' => [
                'first_name' => 'Xu',
                'last_name' => 'Ding',
                'email' => 'xuding@spacebib.com',
                'phone' => '93804194'
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testNoAmount()
    {
        $baseData = array(// nothing here - should cause a certain exception
        );

        $this->request->initialize($baseData);

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The amount parameter is required');

        $this->request->getData();
    }

    public function testMinAmount()
    {
        $baseData = array(
            'amount' => 100.00,
            'transactionId' => 'ORDER-101'
        );

        $this->request->initialize($baseData);

        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }

    public function testNoTransactionId()
    {
        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00'
        ]);

        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }

    public function testInvalidTransactionIdFormat()
    {
        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00',
            'transactionId' => '@123'
        ]);

        $this->expectException(InvalidRequestException::class);

        $this->request->getData();

        $this->request->initialize([
            'serverKey' => 'VT-server-Cpo03kYDOc0cNUKgt6hnLkKg',
            'amount' => '10000.00',
            'transactionId' => 'order-1~9_.'
        ]);

        $this->assertTrue(is_array($this->request->getData()));
    }


}
