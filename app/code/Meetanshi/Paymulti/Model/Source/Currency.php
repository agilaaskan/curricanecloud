<?php

namespace Meetanshi\Paymulti\Model\Source;

/**
 * Class Currency
 * @package Meetanshi\Paymulti\Model\Source
 */
class Currency extends \Magento\Config\Model\Config\Source\Locale\Currency
{
    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $_localeLists;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_currencySymbol;
    /**
     * @var \Meetanshi\Paymulti\Helper\Data
     */
    protected $_helper;

    /**
     * Currency constructor.
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param \Magento\Store\Model\StoreManagerInterface $currencySymbol
     * @param \Meetanshi\Paymulti\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Locale\ListsInterface $localeLists,
        \Magento\Store\Model\StoreManagerInterface $currencySymbol,
        \Meetanshi\Paymulti\Helper\Data $helper
    ) {
        $this->_localeLists = $localeLists;
        $this->_currencySymbol = $currencySymbol;
        $this->_helper = $helper;
        parent::__construct($localeLists);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function toOptionArray()
    {
        $_supportedCurrencyCodes = $this->_helper->getSupportedCurrency();
        $_availableCurrencyCodes = $this->_currencySymbol->getStore()->getAvailableCurrencyCodes(true);
        ;
        if (!$this->_options) {
            $this->_options = $this->_localeLists->getOptionCurrencies();
        }
        $options = [];
        foreach ($this->_options as $option) {
            if (in_array($option['value'], $_supportedCurrencyCodes) && in_array($option['value'], $_availableCurrencyCodes)) {
                $options[] = $option;
            }
        }
        return $options;
    }
}
