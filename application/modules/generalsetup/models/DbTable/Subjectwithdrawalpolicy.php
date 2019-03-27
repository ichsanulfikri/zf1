<?php
class GeneralSetup_Model_DbTable_Subjectwithdrawalpolicy extends Zend_Db_Table {
	
	protected $_name = 'tbl_subjectwithdrawalpolicy';

	 public function fnGetSubjectDetails() {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_subjectmaster"),array("pc.*"))
       								->where("pc.Active = 1")
       								->order("pc.SubjectName");
      					
		    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
	}
	
	public function fnGetSubjectwithdrawalpolicylist($lintSubjectwithdrawalpolicy) {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_subjectwithdrawalpolicy"),array("pc.*"))
       								->where('pc.IdSubjectWithdrawalPolicy  = ?',$lintSubjectwithdrawalpolicy)       								
       								->order("pc.IdSubject");
      					
		    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
	}
	
	public function fnSearchSubject($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_subjectmaster'),array('IdSubject'))
			   ->where('a.SubjectName  like "%" ? "%"',$post['field2'])
			   ->where('a.ShortName  like "%" ? "%"',$post['field3'])
			   ->where('a.SubCode like "%" ? "%"',$post['field4'])
			   ->where('a.BahasaIndonesia like "%" ? "%"',$post['field6'])
			   ->where($field7)
			   ->group("a.SubjectName")
		   	   ->order("a.SubjectName");
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
	
	public function fnGetSubjectList($lintIdSubject) { //Function for searching the university details
	
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sa"=>"tbl_subjectmaster"),array("sa.*"))		 				 
		 				 ->where("sa.IdSubject = ?",$lintIdSubject);
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
	
 	public function fnaddSubjectwithdrawalpolicy($larrformData) { //Function for adding the University details to the table    	   	
   		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
   		
   		$count = count($larrformData['IdSemestergrid']);
   		
		for($i = 0 ;$i<$count ; $i++) {
   		
				$larrFormdatainsert = array('IdSubject'=>$larrformData['IdSubject'],
											'IdSemester'=>$larrformData['IdSemestergrid'][$i],
											'Days'=>$larrformData['Daysgrid'][$i],
											'Percentage'=>$larrformData['Percentagegrid'][$i],
											'UpdUser'=>$larrformData['UpdUser'],
											'UpdDate'=>$larrformData['UpdDate'],
											'Active'=>1,								
							);														
							
    	$this->insert($larrFormdatainsert);    	
   		}
	}
	
	
	public function fnViewSubjectwithdrawalpolicylist($lintIdSubject) {
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()							
						->from(array('a' => 'tbl_subjectwithdrawalpolicy'),array('a.*'))	
						->join(array('b'=>'tbl_subjectmaster'),'a.IdSubject = b.IdSubject')		
						->join(array('c'=>'tbl_semester'),'a.IdSemester = c.IdSemester')
						->join(array('d'=>'tbl_semestermaster'),'c.Semester = d.IdSemesterMaster')			
						->where('a.IdSubject  = ?',$lintIdSubject)
						->where("a.Active = 1");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnupdateSubjectWithdrawalPolicy($formData,$lintSubjectWithdrawalPolicy) { //Function for updating the user
 		$where = 'IdSubjectWithdrawalPolicy  = '.$lintSubjectWithdrawalPolicy; 
		$this->update($formData,$where);
	}
	
	public function fnDeleteSubjectwithdrawalpolicy($idSubject){	
		
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectwithdrawalpolicy";
	    	$where = $db->quoteInto('IdSubject = ?', $idSubject);
			$db->delete($table, $where);
		
	}
	
}

?>