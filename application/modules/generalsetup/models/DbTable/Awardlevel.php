<?php
class GeneralSetup_Model_DbTable_Awardlevel extends Zend_Db_Table {
	
	protected $_name = 'tbl_awardlevel';

	 public function fnGetSubjectDetails() {
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_subjectmaster"),array("pc.*"))
       								->where("pc.Active = 1")
       								->order("pc.SubjectName");
      					
		    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
	}
	
	public function fnGetDefinations($defms) { 
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
				->setIntegrityCheck(false) 
				->join(array('dtms' => 'tbl_definationtypems'),array())
                       		->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType')
                       		->where('dtms.defTypeDesc = ?', $defms)
                       		->where('dms.Status = 1')
                       		->where('dtms.Active = 1')
                       		->order('dms.idDefinition');               
		$larrResult = $lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}
	
	public function fnfetchAwardCode($lintawardlevel) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
				->setIntegrityCheck(false) 
				->join(array('dtms' => 'tbl_definationtypems'),array())
                       		->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType',array('dms.DefinitionCode'))
                       		->where('dtms.defTypeDesc = ?', 'Award')
                       		->where('dms.Status = 1')
                       		->where('dtms.Active = 1')
                       		->order('dms.idDefinition');               
		$larrResult = $lobjDbAdpt->fetchAll($select);
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
	
	public function fnSearchAward($post = array()) { //Function for searching the university details
              $defms = 'Award';
              $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
              if(empty($post['field2'])){
		$select = $this->select()
				->setIntegrityCheck(false)
				->join(array('dtms' => 'tbl_definationtypems'),array())
                       		->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType')
                       		->where('dtms.defTypeDesc = ?', $defms)
                       		->where('dms.Status = 1')
                       		->where('dtms.Active = 1')
                       		->order('dms.idDefinition');
		$larrResult = $this->fetchAll($select);
              }else{               
		$select = $this->select()
				->setIntegrityCheck(false)
				->join(array('dtms' => 'tbl_definationtypems'),array())
                       		->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType')
                       		->where('dtms.defTypeDesc = ?', $defms)
                                ->where('dms.DefinitionDesc like "%" ? "%"', $post['field2'])
                       		->where('dms.Status = 1')
                       		->where('dtms.Active = 1')
                       		->order('dms.idDefinition');
		$larrResult = $this->fetchAll($select);
              }
              return $larrResult;
		/*$field7 = "Status = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_definationms'),array('idDefinition'))
			   ->where('a.DefinitionCode  like "%" ? "%"',$post['field3'])
			   ->where('a.DefinitionDesc  like "%" ? "%"',$post['field2'])
                           ->where('a.defTypeDesc = ?', $defms)
			   ->group("a.DefinitionDesc")
		   	   ->order("a.DefinitionCode");
		$result = $this->fetchAll($select);
		return $result->toArray();*/
		
	}
	
	public function fnSearchAwardlevels($IdLevel){
		
		 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("pc"=>"tbl_awardlevel"),array("pc.*"))
       								->join(array('b'=>'tbl_definationms'),'pc.IdLevel = b.idDefinition',array("b.DefinitionDesc as awardlevel"))	
       								->join(array('c'=>'tbl_definationms'),'pc.IdAllowanceLevel = c.idDefinition',array("c.DefinitionDesc as allowdlevel"))		
       								->where('pc.IdLevel  = ?',$IdLevel)       								
       								->order("pc.IdLevel");
      					
		    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
	}

	public function fnGetSemesterList() { //Function for searching the university details
		/*$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()		   
			  // ->join(array('a' => 'tbl_semestermaster'),array("key"=>"a.IdSemesterMaster ","value"=>"a.SemesterMasterName"))
			   ->from(array("a"=>"tbl_semestermaster"),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMasterName"))
			   ->where("a.Active = 1")
       		   ->order("a.SemesterMasterName");	
		
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
		
		*/
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sa"=>"tbl_semestermaster"),array("key"=>"b.IdSemester","value"=>"CONCAT_WS(' ',IFNULL(sa.SemesterMainName,''),IFNULL(b.year,''))"))
		 				 ->join(array('b'=>'tbl_semester'),'sa.IdSemesterMaster = b.Semester')
		 				 ->where("sa.Active = 1");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	
 	public function fnaddAwardlevel($larrformData) { //Function for adding the University details to the table    	   	
   		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
 		$count = count($larrformData['IdLevelgrid']);
   		
		for($i = 0 ;$i<$count ; $i++) {
   		
				$larrFormdatainsert = array('IdLevel'=>$larrformData['IdLevel'],
											'IdAllowanceLevel'=>$larrformData['IdAllowanceLevelidgrid'][$i],																						
											'UpdUser'=>$larrformData['UpdUser'],
											'UpdDate'=>$larrformData['UpdDate'],
																		
							);														
							
    	$this->insert($larrFormdatainsert);    	
   		}
    	 	
	}
	
	
	public function fnviewAwardListDtls($lintIdAllowance) {
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()							
						->from(array('a' => 'tbl_awardlevel'),array('a.*'))									
						->where('a.IdAllowance  = ?',$lintIdAllowance);						    
		
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
	
	public function fnupdateAwardlistDtls($lintIdAllowance,$formData) { //Function for updating the user
 		$where = 'IdAllowance  = '.$lintIdAllowance; 
		$this->update($formData,$where);
	}
	
	public function fnDeleteAwardlevel($idAwardlevel){	
		
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_awardlevel";
	    	$where = $db->quoteInto('IdLevel = ?', $idAwardlevel);
			$db->delete($table, $where);
		
	}
	
}

?>