<?php
class GeneralSetup_Model_DbTable_Subjectprogram extends Zend_Db_Table {
	
	protected $_name = 'tbl_subjectprogram';

	//Function to Get Maintenance Details
	public function fnGetProgramDetails() {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_program"),array("pc.*"))
       								->where("pc.Active = 1")
       								->order("pc.ProgramName");
      					
		    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
	}
	
	public function fnSearchProgram($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_program'),array('IdProgram'))
			   ->where('a.ProgramName  like "%" ? "%"',$post['field2'])
			   ->where('a.ShortName  like "%" ? "%"',$post['field3'])
			   ->where($field7)
			   ->group("a.ProgramName")
		   	   ->order("a.ProgramName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	

    //Function To View Maintenace Type Ms
	public function fnViewSubjectlist($lintIdProgram) {
		$lstrSelect = $this	->select()
						->setIntegrityCheck(false)  
						->join(array('a' => 'tbl_subjectprogram'),array('a.*'))
						->join(array('b'=>'tbl_subjectmaster'),'a.IdSubject = b.IdSubject')
						->join(array('c'=>'tbl_program'),'a.IdProgram = c.IdProgram')
						->where('a.IdProgram  = ?',$lintIdProgram)
						->where("a.Active = 1")
						->order('b.SubjectName');                   
		$larrResult = $this->fetchAll($lstrSelect);
		return $larrResult->toArray();
	}
	
    public function fnaddSubjectprogramname($larrformData) { //Function for adding the University details to the table    	   	
   		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    	$this->insert($larrformData);
    	return $lobjDbAdpt->lastInsertId();
	}
	
	
	public function fnviewSubjectprogramDtls($lintIdChecklistType) { //Function for the view user 
		
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
								 ->from(array("pc"=>"tbl_subjectprogram"),array("pc.*"))
								 ->where("pc.IdSubjectProgram = ?",$lintIdChecklistType);
						 	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
	
 	public function fnupdateChecklistDtls($lintIdChecklistType,$larrformData) { //Function for updating the user
 		$where = 'IdSubjectProgram  = '.$lintIdChecklistType; 
		$this->update($larrformData,$where);
	}
	
	
	
	


}
