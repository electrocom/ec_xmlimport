<?php


namespace PrestaShop\Module\Ec_Xmlimport;


class ActionDownloaderXml
{
private $actionCustomerId;
private $actionUserName;
private $actionXmlAuthKey;
private $actionPassword;
private $actionImageKey;

const urlBigXml='http://xml.action.pl/Export_XMLIcecat.aspx';
const urlSmallXml='http://xml.action.pl/Export_ShortXMLIcecat.aspx';
const urlImgCdn= 'https://cdn.action.pl/File.aspx';
const xmlSmallPath=''._PS_MODULE_DIR_.'ec_xmlimport'.'/download/xml_small.xml';
const xmlBigPath=''._PS_MODULE_DIR_.'ec_xmlimport'.'/download/xml_big.xml';

private  $fileSaveTo='';

public function __construct($actionCustomerId,$actionUserName,$actionXmlAuthKey,$actionPassword,$actionImageKey){
    $this->actionCustomerId=$actionCustomerId;
    $this->actionUserName=$actionUserName;
    $this->actionXmlAuthKey=$actionXmlAuthKey;
    $this->actionPassword=$actionPassword;
    $this->actionImageKey=$actionImageKey;
}

private function downloadFile($endPoint){
    set_time_limit(0);
    $cred= http_build_query(array('ActionCustomerId'=>$this->actionCustomerId,'ActionUserName'=>$this->actionUserName,'ActionXmlAuthKey'=>$this->actionXmlAuthKey));
    $fp = fopen ($this->fileSaveTo, 'w+');
    $url = $endPoint.'?'.$cred;
    $ch = curl_init($url);
   // curl_setopt($ch, CURLOPT_USERPWD, $this->actionUserName . ":" . $this->actionPassword);
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);
}

public function downloadSmall(){
    $this->fileSaveTo=self::xmlSmallPath;
    $this->downloadFile(self::urlSmallXml);
}

public function downloadBig(){
        $this->fileSaveTo=self::xmlBigPath;
        $this->downloadFile(self::urlBigXml);
}

public function buildImageUrl($imgpath){

    $q= http_build_query(array('CID'=>$this->actionCustomerId,'UID'=>$this->actionUserName,'ZZZ'=>$this->actionImageKey,'P'=>$imgpath));
  return  self::urlImgCdn.'?'.$q;
}

}