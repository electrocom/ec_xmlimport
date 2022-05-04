<?php


namespace PrestaShop\Module\Ec_Xmlimport;
use Configuration;

class Config
{

  public function __set($name, $value)
  {
      // TODO: Implement __set() method.
      \Configuration::updateValue($name,$value);
  }

  public function __get($name){

       $tmp=\Configuration::get($name);
     return $tmp;
  }

    /**
     * @return mixed
     */
    public function getActionUserName()
    {
        return $this->actionUserName;
    }

    /**
     * @param mixed $actionUserName
     * @return Config
     */
    public function setActionUserName($actionUserName)
    {
        $this->actionUserName = $actionUserName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionPassword()
    {
        return $this->actionPassword;
    }

    /**
     * @param mixed $actionPassword
     * @return Config
     */
    public function setActionPassword($actionPassword)
    {
        $this->actionPassword = $actionPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionCustomerId()
    {
        return $this->actionCustomerId;
    }

    /**
     * @param mixed $actionCustomerId
     * @return Config
     */
    public function setActionCustomerId($actionCustomerId)
    {
        $this->actionCustomerId = $actionCustomerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionApiKey()
    {
        return $this->actionApiKey;
    }

    /**
     * @param mixed $actionApiKey
     * @return Config
     */
    public function setActionApiKey($actionApiKey)
    {
        $this->actionApiKey = $actionApiKey;
        return $this;
    }

    public function setActionImageKey($actionImageKey){
         $this->actionImageKey=$actionImageKey;
    }

    public function getActionImageKey(){
        return $this->actionImageKey;
    }
}