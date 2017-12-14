<?php

namespace Omnipay\Midtrans;

use Omnipay\Common\AbstractGateway;

/**
 * Midtrans Gateway Driver for Omnipay
 *
 * This driver is based on
 * Snap Widow Redirection API documentation
 * @link https://snap-docs.midtrans.com/#window-redirection
 *
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class SnapWindowRedirectionGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Midtrans Snap Window Redirection';
    }

    public function getDefaultParameters()
    {
        return [
            'serverKey' => ''
        ];
    }

    public function getServerKey()
    {
        return $this->getParameter('serverKey');
    }

    public function setServerKey($serverKey)
    {
        return $this->setParameter('serverKey', $serverKey);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Midtrans\Message\SnapWindowRedirectionPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Midtrans\Message\SnapWindowRedirectionCompletePurchaseRequest', $parameters);
    }

}