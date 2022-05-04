<?php

namespace PrestaShop\Module\Ec_Xmlimport\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OrderBy;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class XmlImportStructure
{

 public function __construct()
 {

 }

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
    public function getXmlPath(): string
    {
        return $this->xml_path;
    }

    /**
     * @param string $xml_path
     */
    public function setXmlPath(string $xml_path): void
    {
        $this->xml_path = $xml_path;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_xml_import_structure", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="xml_path" , type="string", length=255)
     */
    private $xml_path;


    /**
     * @var string
     *
     * @ORM\Column(name="field" , type="string", length=255)
     */
    private $field;


    /**
     * @var integer
     *
     * @ORM\Column(name="position" , type="integer")
     */
    private $position;

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="active" , type="integer")
     */
    private $active;
}