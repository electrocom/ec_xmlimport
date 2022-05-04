<?php


namespace PrestaShop\Module\Ec_Xmlimport\Import;


use Db;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportProductEntity;

class ImportProductPriceAndQty
{
public function __construct()
{
    $XmlImportProductEntity = new XmlImportProductEntity();
    $XmlImportProductEntity->clearPriceAndQty();
}

public  function updatePriceAndQtybyProductReference(){
$sql='
UPDATE `ps_product` 
inner join `ps_xml_import_products` on ps_product.reference=ps_xml_import_products.code 
inner join ps_product_shop on ps_product_shop.id_product=ps_product.id_product
inner join ps_stock_available on ps_product_shop.id_product=ps_stock_available.id_product and ps_product_shop.id_shop=ps_stock_available.id_shop

set ps_product.quantity=ps_xml_import_products.available
,   ps_product_shop.wholesale_price=ps_xml_import_products.price_without_tax
,   ps_stock_available.quantity=ps_xml_import_products.available;
   COMMIT;
';

        Db::getInstance()->execute($sql);
    }
public  function updatePriceAndQtybySuppilerReference(){
$sql='
UPDATE `ps_product`  inner join ps_product_shop on ps_product_shop.id_product=ps_product.id_product
inner join ps_product_supplier on ps_product_supplier.id_product=ps_product_shop.id_product 
inner join ps_stock_available on ps_product_shop.id_product=ps_stock_available.id_product and ps_product_shop.id_shop=ps_stock_available.id_shop
left join `ps_xml_import_products` on ps_product_supplier.product_supplier_reference=ps_xml_import_products.code

set ps_product.quantity=  if(ps_xml_import_products.code IS NOT NULL,ps_xml_import_products.available,0)  
,   ps_product_supplier.product_supplier_price_te= ifnull(ps_xml_import_products.price_without_tax,0)
,   ps_product_shop.wholesale_price=ifnull(ps_xml_import_products.price_without_tax,0)
,   ps_stock_available.quantity=ifnull(ps_xml_import_products.available,0);
COMMIT;
';

        Db::getInstance()->execute($sql);
    }

}