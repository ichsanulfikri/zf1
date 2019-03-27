<?php 

class GeneralSetup_Model_DbTable_CourseGroupProgram extends Zend_Db_Table_Abstract {
	
	protected $_name = 'course_group_program';
	protected $_primary = "id";
	
  public function getGroupData($group_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('cgp'=>$this->_name))
					  ->join(array('p'=>'tbl_program'), 'p.IdProgram = cgp.program_id')
					  ->where('group_id = ?',$group_id);
							  
		 $row = $db->fetchAll($select);	
		 
		 return $row;
	}
	
}