<?php

namespace Wagento\FixMercadoPago\Model;

/**
 * Core Model of MP plugin, used by all payment methods
 *
 * Class Core
 *
 * @package MercadoPago\Core\Model
 */
class Core extends \MercadoPago\Core\Model\Core
{
    /**
     * @var string
     */
    protected $_code = 'mercadopago';

    /**
     * {@inheritdoc}
     */
    protected $_isGateway = true;

    /**
     * {@inheritdoc}
     */
    protected $_canOrder = true;
    /**
     * {@inheritdoc}
     */
    protected $_canAuthorize = true;
    /**
     * {@inheritdoc}
     */
    protected $_canCapture = true;

    /**
     * {@inheritdoc}
     */
    protected $_canCapturePartial = true;

    /**
     * {@inheritdoc}
     */
    protected $_canRefund = true;
    /**
     * {@inheritdoc}
     */
    protected $_canRefundInvoicePartial = true;

    /**
     * {@inheritdoc}
     */
    protected $_canVoid = true;

    /**
     * {@inheritdoc}
     */
    protected $_canUseInternal = true;

    /**
     * {@inheritdoc}
     */
    protected $_canUseCheckout = true;

    /**
     * {@inheritdoc}
     */
    protected $_canFetchTransactionInfo = true;

    /**
     * {@inheritdoc}
     */
    protected $_canReviewPayment = true;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \MercadoPago\Core\Helper\
     */
    protected $_coreHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var
     */
    protected $_accessToken;
    /**
     * @var
     */
    protected $_clientId;
    /**
     * @var
     */
    protected $_clientSecret;

    /**
     * @var \MercadoPago\Core\Helper\Message\MessageInterface
     */
    protected $_statusMessage;
    /**
     * @var \MercadoPago\Core\Helper\Message\MessageInterface
     */
    protected $_statusDetailMessage;
    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $_transactionFactory;
    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $_invoiceSender;
    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $_orderSender;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_helperImage;
    
    /**
     * Retrieves Quote
     *
     * @param integer $quoteId
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function _getQuote()
    {
        return $this->_checkoutSession->getQuote();
    }

    /**
     * @return float
     */
    public function getAmount($quote = null)
    {
        if (!$quote) {
            $quote = $this->_getQuote();
        }
        
        //no use base totals
        $total = $quote->getSubtotalWithDiscount() + $quote->getShippingAddress()->getShippingAmount() + $quote->getShippingAddress()->getTaxAmount();
        
        return (float) $total;

    }
}
