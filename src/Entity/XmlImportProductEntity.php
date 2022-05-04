<?php
namespace PrestaShop\Module\Ec_Xmlimport\Entity;

use Db;

use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class XmlImportProductEntity extends coreEntity
{



    public function __construct($data = array()) {
        $this->insert_columns=['code','id_supplier','name','short_description','long_description','manufacture_code','manufacture_name','category_id','warranty','price_without_tax','vat','available','pallet','smallpallet','percellocker','ean','manufacturer_part_number','id_xml_import_structure'];
        $this->table_name='ps_xml_import_products';
        $this->loadData($data);
        parent::__construct();
    }




    public function clearPriceAndQty(){
        $sql='UPDATE `ps_xml_import_products` SET `price_without_tax` = 0, available = 0;';
        Db::getInstance()->execute($sql);
    }


    public function markImported($code){
        $sql='UPDATE `ps_xml_import_products` SET `imported` = \'1\' WHERE `code` = \''.$code.'\'; ';
        Db::getInstance()->execute($sql);
    }

    function addValues(){
    $id_suppiler='0';
    $this->prepareSqlValues([$this->code,$id_suppiler,$this->name,'','',$this->manufacture_code,'manufacture_name',$this->category_id,$this->warranty, $this->price_without_tax, $this->vat, $this->available, $this->pallet, $this->smallpallet,'', $this->ean, $this->manufacturer_part_number,'1'  ]);
    }

   public static function getXmlImportProducts()
   {
       $XmlImportProducts=null;
       $sql='select `id_xml_import_products` as `id`, xp.`code`, `id_supplier`, xp.`name`, `short_description`, `long_description`, xm.`name` as manufacture_name, `category_id`, `warranty`, `price_without_tax`, `vat`, `available`, `pallet`, `smallpallet`, `percellocker`, `ean`, `manufacturer_part_number`, `id_xml_import_structure` FROM `ps_xml_import_products` xp left join ps_xml_import_manufacture xm on xm.code=xp.`manufacture_code` where xp.imported=0 ';

       $sql.='and xp.category_id in(SELECT category_code FROM `ps_xml_import_category_margin`)';
       $sql.

       $data= Db::getInstance()->executeS($sql);
       if (!is_array($data))
       die('Error etc.');

       foreach ($data as $row){
           $XmlImportProducts[]=new XmlImportProductEntity($row);
       }


return $XmlImportProducts;

   }




    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_import_product", type="integer")
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->short_description;
    }

    /**
     * @param string $short_description
     */
    public function setShortDescription(string $short_description): void
    {
        $this->short_description = $short_description;
    }

    /**
     * @return string
     */
    public function getLongDescription(): string
    {
        return $this->long_description;
    }

    /**
     * @param string $long_description
     */
    public function setLongDescription(string $long_description): void
    {
        $this->long_description = $long_description;
    }

    /**
     * @return string
     */
    public function getManufactureCode(): string
    {
        return $this->manufacture_code;
    }

    /**
     * @param string $manufacture_code
     */
    public function setManufactureCode(string $manufacture_code): void
    {
        $this->manufacture_code = $manufacture_code;
    }

    /**
     * @return string
     */
    public function getManufactureName(): string
    {
        return $this->manufacture_name;
    }

    /**
     * @param string $manufacture_name
     */
    public function setManufactureName(string $manufacture_name): void
    {
        $this->manufacture_name = $manufacture_name;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->category_id;
    }

    /**
     * @param string $category_id
     */
    public function setCategoryId(string $category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @return string
     */
    public function getWarranty(): string
    {
        return $this->warranty;
    }

    /**
     * @param string $warranty
     */
    public function setWarranty(string $warranty): void
    {
        $this->warranty = $warranty;
    }

    /**
     * @return float
     */
    public function getPriceWithoutTax(): float
    {
        return $this->price_without_tax;
    }

    /**
     * @param float $price_without_tax
     */
    public function setPriceWithoutTax(float $price_without_tax): void
    {
        $this->price_without_tax = $price_without_tax;
    }

    /**
     * @return string
     */
    public function getVat(): string
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat(string $vat): void
    {
        $this->vat = $vat;
    }

    /**
     * @return string
     */
    public function getAvailable(): string
    {
        return $this->available;
    }

    /**
     * @param string $available
     */
    public function setAvailable(string $available): void
    {
        $this->available = $available;
    }

    /**
     * @return int
     */
    public function getPallet(): int
    {
        return $this->pallet;
    }

    /**
     * @param int $pallet
     */
    public function setPallet(int $pallet): void
    {
        $this->pallet = $pallet;
    }

    /**
     * @return int
     */
    public function getSmallpallet(): int
    {
        return $this->smallpallet;
    }

    /**
     * @param int $smallpallet
     */
    public function setSmallpallet(int $smallpallet): void
    {
        $this->smallpallet = $smallpallet;
    }

    /**
     * @return int
     */
    public function getPercellocker(): int
    {
        return $this->percellocker;
    }

    /**
     * @param int $percellocker
     */
    public function setPercellocker(int $percellocker): void
    {
        $this->percellocker = $percellocker;
    }

    /**
     * @return string
     */
    public function getSuppilerReference(): string
    {
        return $this->suppiler_reference;
    }

    /**
     * @param string $suppiler_reference
     */
    public function setSuppilerReference(string $suppiler_reference): void
    {
        $this->suppiler_reference = $suppiler_reference;
    }

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     */
    public function setEan(string $ean): void
    {
        $this->ean = $ean;
    }

    /**
     * @return string
     */
    public function getManufacturerPartNumber(): string
    {
        return $this->manufacturer_part_number;
    }

    /**
     * @param string $manufacturer_part_number
     */
    public function setManufacturerPartNumber(string $manufacturer_part_number): void
    {
        $this->manufacturer_part_number = $manufacturer_part_number;
    }

    /**
     * @return string
     */
    public function getIdXmlImportStructure(): string
    {
        return $this->id_xml_import_structure;
    }

    /**
     * @param string $id_xml_import_structure
     */
    public function setIdXmlImportStructure(string $id_xml_import_structure): void
    {
        $this->id_xml_import_structure = $id_xml_import_structure;
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
     * @ORM\Column(name="short_description" , type="string", length=255)
     */
    protected $short_description;

    /**
     * @var string
     *
     * @ORM\Column(name="long_description" , type="string", length=255)
     */
    protected $long_description;


    /**
     * @var string
     *
     * @ORM\Column(name="manufacture_code" , type="string", length=255)
     */
    protected $manufacture_code;

    /**
     * @var string
     *
     * @ORM\Column(name="code" , type="string", length=255)
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="manufacture_name" , type="string", length=255)
     */
    protected $manufacture_name;

    /**
     * @var string
     *
     * @ORM\Column(name="category_id" , type="string", length=255)
     */
    protected $category_id;


    /**
     * @var string
     *
     * @ORM\Column(name="warranty" , type="string", length=255)
     */
    protected $warranty;

    /**
     * @var float
     *
     * @ORM\Column(name="price_without_tax" , type="decimal")
     */
    protected $price_without_tax;

    /**
     * @var string
     *
     * @ORM\Column(name="vat" , type="decimal")
     */
    protected $vat;


    /**
     * @var string
     *
     * @ORM\Column(name="available" , type="string", length=255)
     */
    protected $available;

    /**
     * @var integer
     *
     * @ORM\Column(name="pallet" , type="integer")
     */
    protected $pallet;

    /**
     * @var integer
     *
     * @ORM\Column(name="smallpallet" , type="integer")
     */
    protected $smallpallet;

    /**
     * @var integer
     *
     * @ORM\Column(name="percellocker" , type="integer")
     */
    protected $percellocker;

    /**
     * @var string
     *
     * @ORM\Column(name="suppiler_reference" ,type="string", length=255)
     */
    protected $suppiler_reference;

    /**
     * @var string
     *
     * @ORM\Column(name="ean" ,type="string", length=255)
     */
    protected $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="manufacturer_part_number" ,type="string", length=255)
     */
    protected $manufacturer_part_number;


    /**
     * @var string
     *
     * @ORM\Column(name="imported" ,type="integer", length=1)
     */
    protected $imported;

    /**
     * @return string
     */
    public function getImported(): string
    {
        return $this->imported;
    }

    /**
     * @param string $imported
     */
    public function setImported(string $imported): void
    {
        $this->imported = $imported;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="id_xml_import_structure" ,type="integer")
     */
    protected $id_xml_import_structure;


}