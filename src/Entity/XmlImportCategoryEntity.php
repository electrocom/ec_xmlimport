<?php


namespace PrestaShop\Module\Ec_Xmlimport\Entity;

use Db;

use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class XmlImportCategoryEntity extends coreEntity
{

    public function __construct($data = array())
    {
        $this->insert_columns = ['name', 'suppiler_cat_id', 'suppiler_cat_parent_id','imported'];
        $this->table_name = 'ps_xml_import_category';

        $this->loadData($data);
        parent::__construct();
    }

    function addValues()
    {
        $id_suppiler = '0';
        $this->prepareSqlValues([$this->getName(), $this->getSuppilerCatId(), $this->getSuppilerCatParentId(), 0]);

    }

    public function installTable()
    {
        $sql = 'CREATE TABLE  IF NOT EXISTS `ps_xml_import_category` ( `id_xml_import_category` INT NOT NULL AUTO_INCREMENT ,  `name` VARCHAR(255) NOT NULL ,  `suppiler_cat_id` VARCHAR(255) NOT NULL ,  `suppiler_cat_parent_id` VARCHAR(255) NOT NULL, `imported` INT,    PRIMARY KEY  (`id_xml_import_category`)) ENGINE = InnoDB;';
        $this->execSql($sql, 1);
        return true;
    }


    public static function getXmlImportSubCategoriesByParentId($suppiler_parent_id, $onlyNotImported = 1)
    {

        $XmlImportCategory = [];
        $imported = $onlyNotImported ? 1 : 0;

        $sql = 'SELECT `id_xml_import_category` as id, `name`,`suppiler_id`,`suppiler_parent_id`,`imported` FROM `ps_xml_import_category` WHERE `suppiler_cat_parent_id` =   '.$suppiler_parent_id.' ';
        $data = Db::getInstance()->executeS($sql);
        if (!is_array($data))
            die('Error etc.');

        foreach ($data as $row) {
            $XmlImportCategory[] = new XmlImportCategoryEntity($row);
        }


        return $XmlImportCategory;
    }


    public function markImported()
    {
        $sql = 'UPDATE `' . $this->table_name . '` SET `imported` = \'1\' WHERE `id_xml_import_category` = \'' . $this->id . '\'; ';
        $this->execSql($sql, 1);
    }


    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_xml_import_category", type="integer")
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

    public function setName(string $name): XmlImportCategoryEntity
    {
        $this->name = $name;
        return $this;
    }



    /**
     * @return string
     */
    public function getSuppilerCatId(): string
    {
        return $this->suppiler_cat_id;
    }

    /**
     * @param string suppiler_id
     * @return XmlImportCategoryEntity
     */
    public function setSuppilerCatId(string $suppiler_cat_id): XmlImportCategoryEntity
    {
        $this->suppiler_cat_id = $suppiler_cat_id;
        return $this;
    }





    /**
     * @return string
     */
    public function getSuppilerCatParentId(): string
    {
        return $this->suppiler_cat_parent_id;
    }

    /**
     * @param string $suppiler_cat_parent_id
     * @return XmlImportCategoryEntity
     */
    public function setSuppilerCatParentId(string $suppiler_cat_parent_id): XmlImportCategoryEntity
    {
        $this->suppiler_cat_parent_id = $suppiler_cat_parent_id;
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
     * @ORM\Column(name="suppiler_cat_id" , type="string", length=255)
     */
    protected $suppiler_cat_id;

    /**
     * @var string
     *
     * @ORM\Column(name="suppiler_cat_parent_id" , type="string", length=255)
     */
    protected $suppiler_cat_parent_id;




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
