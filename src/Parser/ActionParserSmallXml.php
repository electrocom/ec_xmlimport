<?php
namespace PrestaShop\Module\Ec_Xmlimport\Parser;

use PrestaShop\Module\Ec_Xmlimport\Entity;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportProductEntity;
use XMLReader;
use PrestaShop\Module\Ec_Xmlimport;

class ActionParserSmallXml{
    private $modulename;
    private $downloader;
    private $config;

    /**
     * @var XmlImportProductEntity
     */
    private $product;

    public function __construct()
    {
        $this->modulename='ec_xmlimport';
        $this->xml_path=''._PS_MODULE_DIR_.$this->modulename.'/download/xml_small.xml';
        $this->config= new Ec_Xmlimport\Config();
        $this->product =  new XmlImportProductEntity();
        $this->downloader = new Ec_Xmlimport\ActionDownloaderXml($this->config->getActionCustomerId(),$this->config->getActionUserName(),$this->config->getActionApiKey(),$this->config->getActionPassword(),$this->config->getActionImageKey());
    }




    public function loadData(){
        $this->downloader->downloadSmall();
        $reader = new XMLReader();
        $reader->open($this->xml_path);
        $count=0;
        while($reader->read())
        {
            if($reader->nodeType == XMLReader::ELEMENT) $nodeName = $reader->name;
           {


                if ($nodeName == 'Product' &&$reader->nodeType == XMLReader::ELEMENT){
                    $reader->getAttribute("id");

                    $this->product->setCode($reader->getAttribute("id"));
                    $this->product->setPriceWithoutTax($reader->getAttribute("priceNet"));
                    $this->product->setVat($reader->getAttribute("vat"));
                    $this->product->setAvailable($reader->getAttribute("available"));
                    $this->product->setEan($reader->getAttribute("EAN"));
                    $this->product->setName($reader->getAttribute("name"));
                    $this->product->add();
                    $count++;
                }

            }
        }


        $this->product->saveAll();
        $reader->close();


                        }


}