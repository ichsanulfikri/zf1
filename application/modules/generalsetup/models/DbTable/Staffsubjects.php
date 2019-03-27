<?php
class GeneralSetup_Model_DbTable_Staffsubjects extends Zend_Db_Table {
	
	protected $_name = 'tbl_staffsubjects';

	 public function fnGetStaffDetails() {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_staffmaster"),array("pc.*"))
       								->where("pc.Active = 1")
       								->order("pc.FirstName");
      					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
	    return $larrResult;
	}
	
	public function fnGetStaff($lintSubjectwithdrawalpolicy) {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_subjectwithdrawalpolicy"),array("pc.*"))
       								->where('pc.IdSubjectWithdrawalPolicy  = ?',$lintSubjectwithdrawalpolicy)       								
       								->order("pc.IdSubject");
      					
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
		
	public function fnSearchStaff($post = array()) { //Function for searching the university details
		$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_staffmaster'),array('IdStaff'))
			   ->where('a.FirstName  like "%" ? "%"',$post['field2'])			  
			   ->where($field7)
			   ->group("a.FirstName");
			   
			   if(isset($post['field3']) && !empty($post['field3']) ){
				$select = $select->where("a.ArabicName = ?",$post['field3']);
				}	
		
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetSemesterList() { //Function for searching the university details		
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sa"=>"tbl_semestermaster"),array("key"=>"b.IdSemester","value"=>"CONCAT_WS(' ',IFNULL(sa.SemesterMasterName,''),IFNULL(b.year,''))"))
		 				 ->join(array('b'=>'tbl_semester'),'sa.IdSemesterMaster = b.Semester')
		 				 ->where("sa.Active = 1")
		 				 ->order("b.year");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetSubjectList() { //Function for searching the university details
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sa"=>"tbl_subjectmaster"),array("key"=>"sa.IdSubject","value"=>"CONCAT_WS('-',IFNULL(sa.SubjectName,''),IFNULL(sa.SubCode,''))"))		 				 
		 				 ->where("sa.Active = 1");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
 	public function fnaddStaffsubjects($larrformData) { //Function for adding the University details to the table    	   	
   		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
   			
   		$count = count($larrformData['IdSemestergrid']);
   	
		for($i = 0 ;$i<$count ; $i++) {   		
				$larrFormdatainsert = array('IdSubject'=>$larrformData['IdSubjectgrid'][$i],
											'IdSemester'=>$larrformData['IdSemestergrid'][$i],
											'IdStaff'=>$larrformData['IdStaff'],
											'EffectiveDate'=>date ( "Y-m-d", strtotime ( $larrformData['EffectiveDategrid'][$i]) ),
											'UpdUser'=>$larrformData['UpdUser'],
											'UpdDate'=>$larrformData['UpdDate'],
											'Active'=>$larrformData['Active'],								
							);														
							
    	 $this->insert($larrFormdatainsert);    
   		}
	}
		
	public function fnViewStaffsubjectlist($lintIdStaff) {
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()							
						->from(array('a' => 'tbl_staffsubjects'),array('a.*'))	
						->join(array('sm'=>'tbl_staffmaster'),'a.IdStaff = sm.IdStaff')		
						->join(array('b'=>'tbl_subjectmaster'),'a.IdSubject = b.IdSubject')		
						->join(array('s'=>'tbl_semester'),'a.IdSemester = s.IdSemester')
						->join(array('c'=>'tbl_semestermaster'),'s.Semester = c.IdSemesterMaster')		
						->where('a.IdStaff  = ?',$lintIdStaff)
						->where("a.Active = 1");
						 
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnupdateSubjectWithdrawalPolicy($formData,$lintSubjectWithdrawalPolicy) { //Function for updating the user
 		$where = 'IdSubjectWithdrawalPolicy  = '.$lintSubjectWithdrawalPolicy; 
		$this->update($formData,$where);
	}
	
	public function fnDeleteStaffsubjects($idStaff){	
		
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_staffsubjects";
	    	$where = $db->quoteInto('IdStaff = ?', $idStaff);
			$db->delete($table, $where);
		
	}	
}

?>