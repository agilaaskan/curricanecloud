<?php
/**
 * Created by PhpStorm.
 * User: thomasnordkvist
 * Date: 17-01-30
 * Time: 08:15
 */

namespace Andering\ConfigurableDynamic\Plugin\Magento\Swatches\Block\Product\Renderer;

class Configurable
{
    public function afterGetJsonConfig(\Magento\Swatches\Block\Product\Renderer\Configurable $subject, $result) {

        $jsonResult = json_decode($result, true);

        foreach ($subject->getAllowProducts() as $simpleProduct) {
        	$id = $simpleProduct->getId();
        	foreach($simpleProduct->getAttributes() as $attribute) {
				if(($attribute->getIsVisible() && $attribute->getIsVisibleOnFront()) || in_array($attribute->getAttributeCode(), ['sku','description']) ) {
					$code = $attribute->getAttributeCode();
					$value = (string)$attribute->getFrontend()->getValue($simpleProduct);
					$jsonResult['dynamic'][$code][$id] = [
						'value' => $value
					];
				}
        	}
        }

        $result = json_encode($jsonResult);
        return $result;

    }
}
