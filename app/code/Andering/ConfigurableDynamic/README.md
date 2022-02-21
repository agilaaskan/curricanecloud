# magento2-configurable-sku-switch

## Status

Tested by on magento 2.1.3 (blank theme), 2.1.8 Modified Snowdog Theme

Do not expect this module to be updated/bugg fixed/altered for any specific magento version

## Purpose
Change attributes for configurable on frontend to show the sku for selected product

### Credits
[stackexchange](http://magento.stackexchange.com/questions/130128/magento-2-why-do-sku-not-change-dynamically-in-configurable-product-view-page/130148)


## HowTo

The plugin is flexibel. Theme HTML has to be modified to contain data-dynamic attributes.

### Example: HowTo Make the SKU dynamic

in `layout/catalog_product_view.xml` add

		<referenceBlock name="product.info.sku">
			<arguments>
				<argument name="add_attribute" xsi:type="string">itemprop="sku" data-dynamic="sku"</argument>
			</arguments>
		</referenceBlock>
