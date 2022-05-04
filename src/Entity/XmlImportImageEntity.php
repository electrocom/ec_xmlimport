<?php

namespace PrestaShop\Module\Ec_Xmlimport\Entity;

use Db;

use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class XmlImportImageEntity extends coreEntity
{

    public function __construct($data = array())
    {
        $this->insert_columns=['name','suppiler_code','xml_id_suppiler','url','cover','imported'];
        $this->table_name='ps_xml_import_image';

        $this->loadData($data);
        parent::__construct();
    }
    function addValues()
    {
        $id_suppiler='0';
        $this->prepareSqlValues([$this->getName(),$this->getSuppilerCode(),$this->getXmlIdSuppiler(),$this->getUrl(),$this->getCover(),0 ]);

    }

    public  function installTable(){
        $sql='CREATE TABLE  IF NOT EXISTS `prestashop`.`ps_xml_import_image` ( `id_import_image` INT NOT NULL AUTO_INCREMENT ,  `name` VARCHAR(255) NOT NULL ,  `suppiler_code` VARCHAR(255) NOT NULL ,  `xml_id_suppiler` VARCHAR(255) NOT NULL ,  `url` VARCHAR(255) NOT NULL ,  `cover` INT NOT NULL ,`imported` INT NOT NULL,    PRIMARY KEY  (`id_import_image`)) ENGINE = InnoDB;';
        $this->execSql($sql,1);
        return true;
    }


    public static function getXmlImportImagesbySuppilerCode($suppiler_code,$onlyNotImported=1){

        $XmlImportImages=[];
        $imported= $onlyNotImported?1:0;

        $sql='SELECT `id_import_image` as id, `name`,`suppiler_code`,`xml_id_suppiler`,`url`,`cover`,`imported`  FROM `ps_xml_import_image` where suppiler_code = \''.$suppiler_code.'\' and imported!='.$imported.' ';
        $data= Db::getInstance()->executeS($sql);
        if (!is_array($data))
            die('Error etc.');

        foreach ($data as $row){
            $XmlImportImages[]=new XmlImportImageEntity($row);
        }


        return $XmlImportImages;
    }


    public function markImported(){
        $sql='UPDATE `'.$this->table_name.'` SET `imported` = \'1\' WHERE `id_import_image` = \''.$this->id.'\'; ';
        $this->execSql($sql,1);
    }



    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_import_image", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return XmlImportImageEntity
     */
    public function setId(int $id): XmlImportImageEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return XmlImportImageEntity
     */
    public function setName(string $name): XmlImportImageEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuppilerCode(): string
    {
        return $this->suppiler_code;
    }

    /**
     * @param string $suppiler_code
     * @return XmlImportImageEntity
     */
    public function setSuppilerCode(string $suppiler_code): XmlImportImageEntity
    {
        $this->suppiler_code = $suppiler_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getXmlIdSuppiler(): string
    {
        return $this->xml_id_suppiler;
    }

    /**
     * @param string $xml_id_suppiler
     * @return XmlImportImageEntity
     */
    public function setXmlIdSuppiler(string $xml_id_suppiler): XmlImportImageEntity
    {
        $this->xml_id_suppiler = $xml_id_suppiler;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return XmlImportImageEntity
     */
    public function setUrl(string $url): XmlImportImageEntity
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     * @return XmlImportImageEntity
     */
    public function setCover(string $cover): XmlImportImageEntity
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name" , type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="suppiler_code" , type="string", length=255)
     */
    protected $suppiler_code;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_id_suppiler" , type="string", length=255)
     */
    protected $xml_id_suppiler;

    /**
     * @var string
     *
     * @ORM\Column(name="url" , type="string", length=255)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="cover" , type="integer")
     */
    protected $cover;


    /**
     * @var int
     *

     * @ORM\Column(name="imported", type="integer")
     */
    protected $imported;

    /**
     * @return int
     */
    public function getImported(): int
    {
        return $this->imported;
    }

    /**
     * @param int $id
     * @return XmlImportImageEntity
     */
    public function setImported(int $imported): XmlImportImageEntity
    {
        $this->imported = $imported;
        return $this;
    }

}
