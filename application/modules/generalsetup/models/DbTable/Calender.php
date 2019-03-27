<?php
class GeneralSetup_Model_DbTable_Calender extends Zend_Db_Table_Abstract
{
  protected $_name = 'tbl_activity_calender';
  private $lobjDbAdpt;

  public function init(){
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  }
}
?>