<?php

class GeneralSetup_Model_DbTable_ProgramMajoring extends Zend_Db_Table_Abstract {

  protected $_name = 'tbl_programmajoring';
  protected $_primary   = 'IDProgramMajoring';
  
  public function getData($program_id){
  	
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	
	  	$lstrSelect = $db->select()
	                     ->from(array('m'=>$this->_name))	                   
	                     ->where("m.idProgram =?", $program_id);
	                    	  
	    $result = $db->fetchAll($lstrSelect);
	    	 
	  	if($result){
	  		return $result;	
	  	}else{
	  		return null;
	  	}
  }
  
  
  public function getInfo($idProgramMajoring){
  	
  		$db = Zend_Db_Table::getDefaultAdapter();
  		
	  	$select = $db->select()
	                 ->from(array('pm'=>'tbl_programmajoring'))
	                 ->where('pm.IDProgramMajoring = ?', $idProgramMajoring);
        $row = $db->fetchRow($select);
        return $row;
  }
  
  
  public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
  }
  
}
?>