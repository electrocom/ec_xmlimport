<?php

use Doctrine\DBAL\Connection;
use \FluidXml\FluidXml;
use \FluidXml\FluidNamespace;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportStructure;
use PrestaShop\Module\Ec_Xmlimport\Import\ImportProductToShop;
use PrestaShop\Module\Ec_Xmlimport\Parser\ActionParserSmallXml;
use PrestaShop\Module\Ec_Xmlimport\XMLImportDataMapper\XMLImportDataMapper;
use PrestaShop\Module\Ec_Xmlimport\Parser\ActionParserBigXml;
use PrestaShop\Module\Ec_Xmlimport\Import\ImportProductPriceAndQty;
use function \FluidXml\fluidxml;
use function \FluidXml\fluidns;
use function \FluidXml\fluidify;

/**
 * Class Ec_XmlfeedGenerateModuleFrontController
 */
class Ec_XmlimportXmlimportModuleFrontController extends ModuleFrontController
{
    /** @var bool If set to true, will be redirected to authentication page */
    public $auth = false;

    /** @var bool */
    public $ajax;
    private $xml=null;
    private $xmlDataMaps;
    private $import_product_to_shop;

    public function display()
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '0');
        set_time_limit(0);



//$this->updatePriceQuantity()
//$this->updateFullData();

  $this->importToShop();
  return '';

    }


    private function updatePriceQuantity(){
        $start = microtime(true);
        $ActionParserSmallXml= new  ActionParserSmallXml();
        $ActionParserSmallXml->loadData();
        $ImportProductPriceAndQty = new ImportProductPriceAndQty();
        $ImportProductPriceAndQty->updatePriceAndQtybyProductReference();
        $ImportProductPriceAndQty-> updatePriceAndQtybySuppilerReference();
        $time_elapsed_secs = microtime(true) - $start;
        echo 'Load data time: '.$time_elapsed_secs;


    }
    private function updateFullData(){
        $start = microtime(true);
        $actionParser = new ActionParserBigXml();
        $actionParser->loadData();

        $time_elapsed_secs = microtime(true) - $start;
        echo 'Load data time: '.$time_elapsed_secs;
    }

    private function importToShop(){
        $start = microtime(true);
        $import_product_to_shop =new ImportProductToShop();
        $import_product_to_shop->importProducts();
        $time_elapsed_secs = microtime(true) - $start;
        echo 'Load data time: '.$time_elapsed_secs;

    }



public function Xmlread($XmlImportStructure,$fxml,$xpath=''){

foreach ($XmlImportStructure as $item){
    $path=$item->getXmlPath();
    $is_arr=0;
    $path=str_replace('[]','',$path,$is_arr);

    if($xpath!=''&&!strstr($path,$xpath))
        continue;

    $q='//'.$path;


    if($is_arr)
    {
 $xmlarr= $fxml->query($q);

        foreach ($xmlarr as $key2=>$value)
        {
            $this->xmlDataMaps->setValue($item->getField(),'');

            $doc2=fluidxml($value);
            $this->Xmlread($XmlImportStructure,$doc2,$path.'/');
            $this->xmlDataMaps->save();
        }

        return;
    }
        else {
        $aa=    $fxml->query('/*[last()]');
        $value='';

           if (strstr($path, "@"))
            {
                $attr=substr($path, strpos($path, "@") + 1);
                $value= $aa[0]-> getAttribute($attr);
            }


            $this->xmlDataMaps->setValue($item->getField(),$value);
             }
    }



    }



}