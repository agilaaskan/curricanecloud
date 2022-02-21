<?php

namespace Meetanshi\Paymulti\Model\Paypal;

use Magento\Paypal\Model\Config as PayPalConfig;

/**
 * Class Config
 * @package Meetanshi\Paymulti\Model\Paypal
 */
class Config extends PayPalConfig
{
    /**
     * @var \Meetanshi\Paymulti\Helper\Data
     */
    protected $helper;
    /**
     * @var array
     */
    protected $_supportedCurrencyCodes = [
        'AUD',
        'CAD',
        'CZK',
        'DKK',
        'EUR',
        'HKD',
        'HUF',
        'ILS',
        'JPY',
        'MXN',
        'NOK',
        'NZD',
        'PLN',
        'GBP',
        'RUB',
        'SGD',
        'SEK',
        'CHF',
        'TWD',
        'THB',
        'USD',
        'INR',
    ];

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Payment\Model\Source\CctypeFactory $cctypeFactory
     * @param \Magento\Paypal\Model\CertFactory $certFactory
     * @param \Meetanshi\Paymulti\Helper\Data $helper
     * @param array $params
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Payment\Model\Source\CctypeFactory $cctypeFactory,
        \Magento\Paypal\Model\CertFactory $certFactory,
        \Meetanshi\Paymulti\Helper\Data $helper,
        $params = []
    ) {
        parent::__construct($scopeConfig, $directoryHelper, $storeManager, $cctypeFactory, $certFactory, $params);
        $this->helper = $helper;
    }

    /**
     * @param string $code
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isCurrencyCodeSupported($code)
    {
        if ($this->helper->isActive()) {
            $this->_supportedCurrencyCodes =
                array_merge($this->_supportedCurrencyCodes, $this->helper->getCurrencyArray());
        }

        if (in_array($code, $this->_supportedCurrencyCodes)) {
            return true;
        }
        if ($this->getMerchantCountry() == 'BR' && $code == 'BRL') {
            return true;
        }
        if ($this->getMerchantCountry() == 'MY' && $code == 'MYR') {
            return true;
        }
        if ($this->getMerchantCountry() == 'TR' && $code == 'TRY') {
            return true;
        }
        return false;
    }
}
