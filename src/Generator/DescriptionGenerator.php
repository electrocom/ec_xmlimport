<?php
namespace PrestaShop\Module\Ec_Xmlimport\Generator;

use Context;
use Db;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportProductEntity;
use PrestaShop\Module\Ec_Xmlimport\Import\ImportImageToShop;



class DescriptionGenerator{
    private  $db;
    private  $data;
    private  $smarty;
    private  $code;
    private $context;
    public function __construct($code)
    {
            $this->db = Db::getInstance();
            $this->smarty=$GLOBALS['smarty'];
            $this->context=Context::getContext() ;
            $this->loadData($code);

    }



public function getDescription(){
$path=dirname(__FILE__).'/';

$rows=array();
$last_section='';
foreach ($this->data as $row){

        $rows[$row['section']] []=array('name'=>$row['name'],'value'=>$row['value']);




}

$this->context->smarty->assign('rows',$rows);
$output= $this->context->smarty->fetch($path.'DescriptionGenerator.html');
return $output;

}


    /**
     * @throws \PrestaShopDatabaseException
     */
    private function loadData($code)
   {
        $sql='SELECT * FROM `ps_xml_import_attribute` WHERE `product_code` LIKE \''.$code.'\' ORDER BY `ps_xml_import_attribute`.`section` ASC';
      return  $this->data=$this->db->executeS($sql);
    }



}
