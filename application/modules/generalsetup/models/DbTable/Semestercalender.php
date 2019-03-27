<?php
class GeneralSetup_Model_DbTable_Semestercalender extends Zend_Db_Table_Abstract{
  protected $_name = 'tbl_semester0';
  private $lobjDbAdpt;

  public function init(){
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  }
  
}


?>
