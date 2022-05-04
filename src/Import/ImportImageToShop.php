<?php
namespace PrestaShop\Module\Ec_Xmlimport\Import;

use Configuration;
use Context;
use Image;
use ImageManager;
use ImageType;
use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;
use Tools;
use PrestaShop\Module\Ec_Xmlimport\Entity\XmlImportImageEntity;
class ImportImageToShop
{


    public function __construct()
    {
    }

    protected static function copyImg($id_entity, $id_image = null, $url = '', $entity = 'products', $regenerate = true)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

        switch ($entity) {
            default:
            case 'products':
                $image_obj = new Image($id_image);

           $path = $image_obj->getPathForCreation();

                break;
            case 'categories':
                $path = _PS_CAT_IMG_DIR_ . (int) $id_entity;

                break;
            case 'manufacturers':
                $path = _PS_MANU_IMG_DIR_ . (int) $id_entity;

                break;
            case 'suppliers':
                $path = _PS_SUPP_IMG_DIR_ . (int) $id_entity;

                break;
            case 'stores':
                $path = _PS_STORE_IMG_DIR_ . (int) $id_entity;

                break;
        }

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = array();
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once _PS_TOOL_DIR_ . 'http_build_url/http_build_url.php';
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);

                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path . '.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
            $images_types = ImageType::getImagesTypes($entity, true);

            if ($regenerate) {
                $previous_path = null;
                $path_infos = array();
                $path_infos[] = array($tgt_width, $tgt_height, $path . '.jpg');
                foreach ($images_types as $image_type) {
                    $tmpfile = self::get_best_path($image_type['width'], $image_type['height'], $path_infos);

                    if (ImageManager::resize(
                        $tmpfile,
                        $path . '-' . stripslashes($image_type['name']) . '.jpg',
                        $image_type['width'],
                        $image_type['height'],
                        'jpg',
                        false,
                        $error,
                        $tgt_width,
                        $tgt_height,
                        5,
                        $src_width,
                        $src_height
                    )) {
                        // the last image should not be added in the candidate list if it's bigger than the original image
                        if ($tgt_width <= $src_width && $tgt_height <= $src_height) {
                            $path_infos[] = array($tgt_width, $tgt_height, $path . '-' . stripslashes($image_type['name']) . '.jpg');
                        }
                        if ($entity == 'products') {
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '.jpg');
                            }
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '_' . (int) Context::getContext()->shop->id . '.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '_' . (int) Context::getContext()->shop->id . '.jpg');
                            }
                        }
                    }
                    if (in_array($image_type['id_image_type'], $watermark_types)) {
                        Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                    }
                }
            }
        } else {
            @unlink($orig_tmpfile);

            return false;
        }
        unlink($orig_tmpfile);

        return true;
    }

    protected static function get_best_path($tgt_width, $tgt_height, $path_infos)
    {
        $path_infos = array_reverse($path_infos);
        $path = '';
        foreach ($path_infos as $path_info) {
            list($width, $height, $path) = $path_info;
            if ($width >= $tgt_width && $height >= $tgt_height) {
                return $path;
            }
        }

        return $path;
    }


    public function generateActionUrl($cid,$login,$pid,$image){

        return 'https://cdn.action.pl/File.aspx?CID='.$cid.'&UID='.$login.'&PID='.$pid.'&P='.$image.'';

}


 public function importImages($ext_code,$id_product){


     XmlImportImageEntity::getXmlImportImagesbySuppilerCode($ext_code);
     $XmlImportImageEntity=XmlImportImageEntity::getXmlImportImagesbySuppilerCode($ext_code);
     $id_cover = Image::getCover($id_product);
  //die(print_r($XmlImportImageEntity));
   //  echo('1'.$id_product.','.$ext_code.print_r($XmlImportImageEntity,1));
     foreach (  $XmlImportImageEntity as $row){

       $imgurl=  $this->generateActionUrl('75132','malmaz1229','a6ccc72932c3399bf33c4dd699ec5b93',$row->getUrl());
       $image=null;
       //  echo('2'.$id_product.','.$ext_code);
         if($row->getCover())
         {

             if(empty($id_cover))
             {try {
                 $image = new Image();
                 $image->id_product = (int)$id_product;
                 $image->cover = 1;
                 $image->add();
                 // die( "Obrazek Cover");
                 self::copyImg($id_product, $image->id, $imgurl);
                  }catch (\Exception $e){

                 echo "Błąd dodawania okładki: ".$e->getMessage();
                 continue;
             }

             }else{
               //  self::copyImg($id_product,$id_cover['id_image'],$imgurl);

             }
            $row->markImported();
            // echo('3'.$id_product.','.$ext_code);
         }else
         {
             try {
                 $image = new Image();
                 $image->id_product = (int)$id_product;
                 $image->position = Image::getHighestPosition($id_product) + 1;

                 $image->add();
                 self::copyImg($id_product, $image->id, $imgurl);
                 $row->markImported();
             }catch (Exception $e){

                 echo "Błąd dodawania pbrazka: ".$e->getMessage();
                 continue;
             }
           //  echo('4'.$id_product.','.$ext_code);
         }

     }
     flush();
     ob_flush();

 }

}