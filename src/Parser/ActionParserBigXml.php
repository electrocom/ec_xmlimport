<?php
namespace PrestaShop\Module\Ec_Xmlimport\Parser;

use PrestaShop\Module\Ec_Xmlimport\Entity;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportProductEntity;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportImageEntity;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportCategoryEntity;

use XMLReader;
use function \FluidXml\fluidxml;
use function \FluidXml\fluidns;
use function \FluidXml\fluidify;
use \FluidXml\FluidXml;
use \FluidXml\FluidNamespace;
use  PrestaShop\PrestaShop\Adapter\Import;

class ActionParserBigXml{
    private  $modulename;
    private $sql_struct;
    private $big_xml_path;
    private  $image_copier;

    /**
     * @var XmlImportProductEntity
     */
    private  $import_product;

    public function __construct()
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '0');
        $this->modulename='ec_xmlimport';
        $this->sql_struct_manufacture='replace INTO `ps_xml_import_manufacture` ( `code`, `name`) VALUES ';
        $this->sql_struct_attribute='REPLACE INTO `ps_xml_import_attribute` (`name`, `value`, `section`, `product_code`) VALUES  ';
        $this->sql_struct_images='INSERT INTO `ps_xml_import_image` ( `name`, `suppiler_code`, `xml_id_suppiler`, `url`, `cover`) VALUES ';
        $this->big_xml_path=''._PS_MODULE_DIR_.$this->modulename.'/download/Cennik_Action_2021-01-10icecat.xml';
       // $this->big_xml_path=''._PS_MODULE_DIR_.$this->modulename.'/download/xml_big.xml';

        $this->import_product =  new XmlImportProductEntity();
        $this->import_image =  new XmlImportImageEntity() ;
        $this->import_category = new XmlImportCategoryEntity();

    }

    public  function loadData()
    {
       $this->Import_Products();
    //   $this->Import_Manufacture();


    }

    private function Import_Manufacture(){
        $count=0;
        $count_exec_sql=1;
        $reader = new XMLReader();
        $reader->open($this->big_xml_path);
        $sql='';
        while($reader->read())
        {
            if($reader->nodeType == XMLReader::ELEMENT) $nodeName = $reader->name;

                if ($nodeName == 'Producer' &&$reader->nodeType == XMLReader::ELEMENT){
                    $sql.=' (\''.$reader->getAttribute("id").'\'
                       ,\''.pSQL($reader->getAttribute("name")).'\'),';

                }



            if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == 'Producer')
            {
                $count++;
            }

            if($count==1000*$count_exec_sql){
                $this->execSql($this->sql_struct_manufacture.$sql);
                $sql='';
                $count_exec_sql++;
            }
        }
        $this->execSql($this->sql_struct_manufacture.$sql);
    }
   private function Import_Products(){



        $reader = new XMLReader();
        $reader->open($this->big_xml_path);
        $count=0;
        $count_exec_sql=1;
        $id_suppiler=1;
        $sql_values_manufacture='';
        $sql_values_attribute='';
        $product_code='';
        $attribute_name='';
        $attribute_value='';
        $attribute_section='';

        $suppiler_cat_id='';
       $suppiler_cat_parent_id='';

        while($reader->read())
        {
            if($reader->nodeType == XMLReader::ELEMENT) $nodeName = $reader->name;
           {

               if ($nodeName == 'MainCategory' &&$reader->nodeType == XMLReader::ELEMENT){
                   $suppiler_cat_parent_id=$suppiler_cat_id=$reader->getAttribute("id");
                   $name=$reader->getAttribute("name");
                   $this->import_category->setSuppilerCatId($suppiler_cat_id);
                   $this->import_category->setName($name);
                   $this->import_category->setSuppilerCatParentId('');
                   $this->import_category->add();
               }

               if ($nodeName == 'SubCategory' &&$reader->nodeType == XMLReader::ELEMENT){
                   $suppiler_cat_id=$reader->getAttribute("id");
                   $name=$reader->getAttribute("name");
                   $this->import_category->setSuppilerCatId($suppiler_cat_id);
                   $this->import_category->setName($name);
                   $this->import_category->setSuppilerCatParentId($suppiler_cat_parent_id);
                   $this->import_category->add();
               }



                if ($nodeName == 'Producer' &&$reader->nodeType == XMLReader::ELEMENT){
                    $sql_values_manufacture.=' (\''.$reader->getAttribute("id").'\'
                       ,\''.pSQL($reader->getAttribute("name")).'\'),';

                }
                if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == 'Producers')
                {
                    $this->execSql($this->sql_struct_manufacture.$sql_values_manufacture);
                }

                if ($nodeName == 'Product' &&$reader->nodeType == XMLReader::ELEMENT){

                    $smallPallet=$reader->getAttribute("smallPallet")=='T'?'1':'0';
                    $productIsLarge=$reader->getAttribute("productIsLarge")=='T'?'1':'0';
                    $this->import_product->setSmallpallet($smallPallet);
                    $this->import_product->setPallet($productIsLarge);
                    $this->import_product->setCode($reader->getAttribute("id"));
                    $this->import_product->setName($reader->getAttribute("name"));
                    $this->import_product->setManufactureCode($reader->getAttribute("producer"));
                    $this->import_product->setManufactureName('');
                    $this->import_product->setCategoryId($reader->getAttribute("categoryId"));
                    $this->import_product->setWarranty($reader->getAttribute("warranty"));
                    $this->import_product->setPriceWithoutTax($reader->getAttribute("priceNet"));
                    $this->import_product->setVat($reader->getAttribute("vat"));
                    $this->import_product->setAvailable($reader->getAttribute("available"));
                    $this->import_product->setEan($reader->getAttribute("EAN"));
                    $this->import_product->setManufacturerPartNumber($reader->getAttribute("manufacturerPartNumber"));
                    $this->import_product->add();
                    $product_code=$reader->getAttribute("id");
                }

            }



            if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == 'Product')
            {
                $count++;
            }

            if ($nodeName == 'Image' &&$reader->nodeType == XMLReader::ELEMENT){
                $image_url=pSQL($reader->getAttribute("url"));
                $image_is_main=pSQL($reader->getAttribute("isMain"));
                $this->import_image->setName($product_code);
                $this->import_image->setSuppilerCode($product_code);
                $this->import_image->setCover($image_is_main);
                $this->import_image->setUrl($image_url);
                $this->import_image->setXmlIdSuppiler($product_code);
                $this->import_image->add();
            }


            if ($nodeName == 'Section' &&$reader->nodeType == XMLReader::ELEMENT){
                $attribute_section=pSQL($reader->getAttribute("name"));
            }

            if ($nodeName == 'Attribute' &&$reader->nodeType == XMLReader::ELEMENT){
                    $attribute_name=pSQL($reader->getAttribute("name"));
                }

            if ($nodeName == 'Value' &&$reader->nodeType == XMLReader::ELEMENT){
                    $attribute_value=pSQL($reader->getAttribute("Name"));
            }

            if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == 'Attribute')
            {          $sql_values_attribute.=' (\''.$attribute_name.'\'
                       ,\''.pSQL($attribute_value).'\'
                       ,\''.pSQL($attribute_section).'\'
                       ,\''.pSQL($product_code).'\'),';
            }



            if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name == 'TechnicalSpecification')
            {

            }

            if($count==500*$count_exec_sql){

                $this->execSql($this->sql_struct_attribute.$sql_values_attribute);
                $sql_values_attribute='';



                $count_exec_sql++;
            }


        }
       $this->import_category->saveAll();
       $this->import_product->saveAll();
       $this->import_image->saveAll();
      // $this->execSql($this->sql_struct_attribute.$sql_values_attribute);

        $reader->close();


}

    private function execSql($sql){


    if (!\Db::getInstance()->execute(substr($sql, 0, -1)))
        die('Erreur etc.');
}

}