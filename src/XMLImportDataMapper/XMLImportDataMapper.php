<?php

namespace PrestaShop\Module\Ec_Xmlimport\XMLImportDataMapper;
class XMLImportDataMapper{

    private  $product;
    private $id_lang;
    private $context;
    public function __construct()
    {
        $this->context = \Context::getContext();
        $this->id_lang=$this->context->language->id;
    }

public function setValue($name,$value){

                switch ($name){

                    case 'products':
                        $this->product = new \Product();
echo 'products';
                        break;

                    case 'product.reference' :

                        if($id_product=\Product::getIdByReference($value))
                            $this->product = new \Product($id_product);
                        else
                        $this->product->reference=$value;

                        echo 'product.reference';
                        break;


                    case 'product.name':
                        $this->product->name[$this->id_lang]=$value;

                        break;

                }
}


    public function save(){
        $this->product->save();
        echo 'save()';
    }




}