<?php
declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';


use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportImageEntity;
class Ec_Xmlimport extends Module
{

    protected $_hooks=array('actionFrontControllerSetMedia');
    public function __construct( )
    {
        $this->name = 'ec_xmlimport';
        $this->author = 'kmkm2';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('XML Import', array(), 'Modules.Ec_XmlImport.Admin');
        $this->description = $this->trans(
            'Moduł do importowania plików XML',
            array(),
            'Modules.Ec_Xmlfeed.Admin'
        );

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);


    }



public function install()
    {
$XmlImportImageEntity=new XmlImportImageEntity();
$XmlImportImageEntity->installTable();
        parent::install();
    $this->registerHook('actionFrontControllerSetMedia');
        return true;

    }


public function getContent(){

       $configModule = new \PrestaShop\Module\Ec_Xmlimport\ConfigFormPage($this);
      return $configModule->getContent();

}


    public function hookActionFrontControllerSetMedia($params)
    {
        // Only on product page
        if ('product' === $this->context->controller->php_self) {
            $this->context->controller->registerStylesheet(
                'ec_xmlimport-action-style',
                'modules/'.$this->name.'/views/css/ec_xmlimport.css',
                [
                    'media' => 'all',
                    'priority' => 200,
                ]
            );


        }
    }

}