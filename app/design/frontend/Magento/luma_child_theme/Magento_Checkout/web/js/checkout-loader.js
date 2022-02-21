/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'rjsResolver'
], function ($, resolver) {
    'use strict';

    /**
     * Removes provided loader element from DOM.
     *
     * @param {HTMLElement} $loader - Loader DOM element.
     */
    function hideLoader($loader) {
        $loader.parentNode.removeChild($loader);
        
        isInvoice();
        var el = this;
        $("input[name='custom_attributes[is_invoice]']").click(function(el) {
            isInvoice();
        });                
    }

    /**
     * Initializes assets loading process listener.
     *
     * @param {Object} config - Optional configuration
     * @param {HTMLElement} $loader - Loader DOM element.
     */
    function init(config, $loader) {
        resolver(hideLoader.bind(null, $loader));
    }
    
    function isInvoice() {
        if ($("input[name='custom_attributes[is_invoice]']").is(':checked')) {
            $('div[name="shippingAddress.custom_attributes.rfc"]').show();
            $('div[name="shippingAddress.custom_attributes.cfdi"]').show();
        } else {
            $('div[name="shippingAddress.custom_attributes.rfc"]').hide();
            $('div[name="shippingAddress.custom_attributes.cfdi"]').hide();
            
            $("input[name='custom_attributes[rfc]']").val('');
            $("select[name='custom_attributes[cfdi]']").val('');
        }
    }        

    return init;
});
