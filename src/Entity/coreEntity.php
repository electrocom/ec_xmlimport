<?php
namespace PrestaShop\Module\Ec_Xmlimport\Entity;
use Db;

use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;
abstract class coreEntity{
    private $sql_struct;
    private $sql_values;
    private $sql_duplicate_key;
    private $db;
    protected $insert_columns;
    protected $table_name;
    private $values_counter;
    /**
     *Maksymalna liczba rekordów dodawana w jednym zapytaniu
     */
const MAX_VALUES = 10000;

public function __construct()
{

    $this->db = Db::getInstance();
    $this->startTransaction();
    $this->prepareSqlStruct();
    $this->prepareSqlDuplicateKeyUpdate();

}
abstract function addValues();

protected function loadData($data){
        foreach($data as $k => $v) {
            if(property_exists(get_class($this) ,$k))
                $this->{$k} = $v;
        }
    }

protected function prepareSqlValues($values){

        $values_sql=implode(", ", array_map(function($col){
            return "'".pSQL($col)."'";
        },$values));
        $this->sql_values.="($values_sql),";

        $this->values_counter++;
    }
private  function prepareSqlStruct(){

        $columns = implode(", ",array_values($this->insert_columns));
        $this->sql_struct = "INSERT INTO $this->table_name ($columns) VALUES ";
    }

private  function prepareSqlDuplicateKeyUpdate(){
        $update_cols = array_map(function($col){
            return "$col= VALUES($col)";
        },$this->insert_columns);
        $update_cols_sql=implode(", ",$update_cols);
        $this->sql_duplicate_key = "  ON DUPLICATE KEY  UPDATE $update_cols_sql ";

    }
protected function execSql($sql,$autocommit=0){
        try{
            //$this->db->getBestEngine();
            if (!$this->db->query($sql)) {

                throw new Exception($sql);
            }
        }catch (\Exception $ex){
            $this->rollbackTransaction();
            die($ex->getMessage());
        }
if($autocommit)
    $this->commitTransaction();

    }
private function  execFinalSql(){

        $this->sql_final=substr($this->sql_struct.$this->sql_values, 0, -1).' '.$this->sql_duplicate_key;
        $this->execSql($this->sql_final);
        $this->sql_values='';
        $this->sql_final='';
    }
public function add(){
        $this->addValues();

        if($this->values_counter==self::MAX_VALUES){
            $this->execFinalSql();
            $this->values_counter=0;
        }

    }
public function saveAll(){

        $this->execFinalSql();
        $this->commitTransaction();

    }
private function startTransaction(){
    $this->db->execute('COMMIT;');
        if (!$this->db->execute('SET autocommit = 0; START TRANSACTION;'))
            die( "disableAutoCommit error");
    }


private function commitTransaction(){
        $this->db->execute('COMMIT;');
    if (!$this->db->execute('SET autocommit = 1; '))
        die( "enableAutoCommit error");
    }
private function rollbackTransaction(){
        $this->db->execute('ROLLBACK;');
    }

}