<?php
namespace PrestaShop\Module\Ec_Xmlimport\Import;

use Db;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportProductEntity;
use PrestaShop\Module\Ec_Xmlimport\Import\ImportImageToShop;
use PrestaShop\Module\Ec_Xmlimport\Generator\DescriptionGenerator;
class ImportProductToShop{


    public function __construct()
    {


    }

function importOneProduct(XmlImportProductEntity $data){

    $id_manufacture=\Manufacturer::getIdByName($data->getManufactureName());
    $importImageToShop = new ImportImageToShop();
    $description_generator = new DescriptionGenerator($data->getCode());

    $dectiption=$description_generator->getDescription();

    if(!$id_manufacture){
        $manufacture = new \Manufacturer();
        $manufacture->name=$data->getManufactureName();
        $manufacture->active=1;
        $manufacture->save();
        $id_manufacture=$manufacture->id;
    }



    $id= \Product::getIdByReference($data->getCode());
    if($id) {
        echo "Produkt istnieje";
        return;
    }

    $prestashop_product= new \Product($id);
    $prestashop_product->id_manufacturer=$id_manufacture;
    $name=str_replace(array(';','#','[',']','=','>','<'),' ',$data->getName());
    $name=mb_substr($name ,0,125);

    echo "Dodaje product:".$data->getCode().", $name<br>";
    $prestashop_product->name=$name.PHP_EOL ;
    $prestashop_product->wholesale_price=$data->getPriceWithoutTax();
    $prestashop_product->price=$data->getPriceWithoutTax();
    $prestashop_product->reference=$data->getCode();
    $prestashop_product->description=$dectiption;
    $prestashop_product->id_category_default=76;
    $prestashop_product->link_rewrite=\Tools::str2url($name);




   $result= $prestashop_product->save();
    $prestashop_product->addToCategories(76);
   \StockAvailable::setQuantity($id, null, intval($data->getAvailable()));   $prestashop_product->active=true;


    $importImageToShop->importImages($data->getCode(), $prestashop_product->id);


   $data->markImported($data->getCode());
}


public function importProducts(){

    $xml_products=XmlImportProductEntity::getXmlImportProducts();
    foreach ($xml_products as $xml_product){
        $this->importOneProduct($xml_product);

    }

}




}