<?php

namespace Meetanshi\Paymulti\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Meetanshi\Paymulti\Helper
 */
class Data extends AbstractHelper
{
    /**
     *
     */
    const MEETANSHI_MODULE_ENABLE = 'paymulti/general/active';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var
     */
    protected $extraPrice;

    /**
     * @var
     */
    protected $itemPrice;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param OrderInterface $order
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        OrderInterface $order
    )
    {
        $this->storeManager = $storeManager;
        $this->order = $order;
        parent::__construct($context);
    }

    /**
     * @return array
     */
    public static function getSupportedCurrency()
    {
        return ['AUD', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN',
            'NOK', 'NZD', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'USD', 'TWD', 'THB', 'INR'];
    }

    /**
     * @return bool
     */
    public static function shouldConvert()
    {
        return !self::isActive();
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->scopeConfig->getValue(self::MEETANSHI_MODULE_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $quote
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConvertedGrandTotal($quote)
    {
        $toCurrency = $this->getCurrentCurrency();
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        if ($toCurrency == $currentCurrency) {
            return $quote->getGrandTotal();
        } else {
            return $this->getConvertedBaseAmount($quote->getBaseGrandTotal());
        }
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * @param $value
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConvertedBaseAmount($value)
    {
        $baseCurrency = $this->storeManager->getStore()->getBaseCurrencyCode();
        $currentCurrency = $this->getCurrentCurrency();
        $amount = $this->convertCurrency($value, $baseCurrency, $currentCurrency);
        return $amount;
    }

    /**
     * @param $amountValue
     * @param null $currencyCodeFrom
     * @param null $currencyCodeTo
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function convertCurrency($amountValue, $currencyCodeFrom = null, $currencyCodeTo = null)
    {
        return $this->storeManager->getStore()->getBaseCurrency()->convert($amountValue, $currencyCodeTo);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencyArray()
    {
        return [$this->storeManager->getStore()->getBaseCurrencyCode()];
    }

    /**
     * @param $orderID
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPaymentOrderCurrency($orderID)
    {
        $order = $this->order->load($orderID);
        if ($order) {
            $payment = $order->getPayment();
            return $payment->getAdditionalInformation('payment_currency');
        }
        return $this->getCurrentCurrency();
    }

    /**
     * @param $identifier
     * @return mixed
     */
    public function getConfig($identifier)
    {
        return $this->scopeConfig->getValue(
            $identifier,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $key
     * @param $value
     */
    public function addExtraPrice($key, $value)
    {
        $this->extraPrice[$key] = $value;
    }

    /**
     * @param $i
     * @param $key
     * @param $value
     */
    public function addItemPrice($i, $key, $value)
    {
        $this->itemPrice[$i][$key] = $value;
    }

    /**
     * @param array $request
     * @return array
     */
    public function convertRequest(array &$request)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/payMulti.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('convertRequest CALL');

        $itemAmount = 0;
        $extraprice = 0;

        foreach ($this->itemPrice as $item) {
            $amt = str_replace(",", "", $item['amount']);
            $itemAmount = $itemAmount + ((int)$item['qty'] * (float)$amt);

            $logger->info('foreach AMT:-' . $amt);
            $logger->info('foreach qty:-' . $item['qty']);

        }

        foreach ($this->extraPrice as $key => $value) {
            $extraprice = (float)$extraprice + (float)$value;
        }
        
        $baseprice = $extraprice + $itemAmount;

        $logger->info('AMT:-' . $baseprice);
        $logger->info('ITEMAMT:-' . $itemAmount);

        $request['AMT'] = round($baseprice, 2);
        $request['ITEMAMT'] = $itemAmount;

        return $request;
    }
}
