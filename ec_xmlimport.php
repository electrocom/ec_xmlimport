<?php
declare(strict_types=1);



use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\Module\Ec_Xmlfeed\Entity;
class Ec_Xmlimport extends Module
{
    public function __construct()
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
        return parent::install();
    }
}