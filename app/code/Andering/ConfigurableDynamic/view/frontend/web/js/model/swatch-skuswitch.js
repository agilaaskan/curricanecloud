/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var updatePrice = targetModule.prototype._UpdatePrice;
        targetModule.prototype.dynamic = {};

        $('[itemprop]').each(function(){
            var code = $(this).data('dynamic');
            var value = $(this).html();

            targetModule.prototype.dynamic[code] = value;
        });

        var updatePriceWrapper = wrapper.wrap(updatePrice, function(original){
            var dynamic = this.options.jsonConfig.dynamic;//this.options.spConfig.dynamic;
            console.log(dynamic);
            
            var code = 'sku';
            var value = "";
            var $placeholder = $('.product-info-stock-' + code + ' .attribute.' + code + ' .value');//$('[itemprod='+code+']');
            var allSelected = true;

            for(var i = 0; i<this.options.jsonConfig.attributes.length;i++){
                if (!$('div.product-info-main .product-options-wrapper .swatch-attribute.' + this.options.jsonConfig.attributes[i].code).attr('option-selected')){
                    allSelected = false;
                }
            }

            if(allSelected){
                var products = this._CalcProducts();
                value = this.options.jsonConfig.dynamic[code][products.slice().shift()].value;
            } else {
                value = this.dynamic[code];
            }
            
            $placeholder.html(value);

            return original();
        });

        targetModule.prototype._UpdatePrice = updatePriceWrapper;
        return targetModule;

















    };
});
