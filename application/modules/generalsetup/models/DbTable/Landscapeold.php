<?php 
class GeneralSetup_Model_DbTable_Landscape extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_landscape';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function fnSearchProgram($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_program'),array('IdProgram'))
			   ->where('a.ProgramName  like "%" ? "%"',$post['field3'])
			   ->where('a.ArabicName   like "%" ? "%"',$post['field4'])
			   ->where($field7);
		$select  ->order("a.ProgramName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
    public function fnaddLandscape($formData) { //Function for adding the University details to the table
    	echo "<pre>";
    	print_r($formData);die();
    		unset($formData['Save']);
    		unset($formData['Back']);	
    		unset($formData['block']);
    		unset($formData['semesterid']);
    		unset($formData['blocknamegrid']);
    		unset($formData['semesteridnamegrid']);
    		unset($formData['Semester']);
			$this->insert($formData);
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
	}
	

   public function fneditLandscape($idlandscape) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
			->join(array('prg'=>'tbl_programrequirement'),'lan.IdLandscape = prg.IdLandscape')
			->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
            ->where('lan.IdLandscape = ?',$idlandscape);	
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
   public function fneditLandblock($idlandscape) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
			->join(array('blc'=>'tbl_landscapeblock'),'lan.IdLandscape = blc.idlandscape')
			->join(array('sem'=>'tbl_semester'),'blc.semesterid = sem.IdSemester')
			->join(array('sma'=>'tbl_semestermaster'),'sem.IdSemester = sma.IdSemesterMaster')
            ->where('lan.IdLandscape = ?',$idlandscape);	
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
   public function fnLandscapeList($IdProgram) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))
			->join(array('prg'=>'tbl_program'),'lan.IdProgram = prg.IdProgram',array('prg.IdProgram'))
			->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
            ->where('lan.IdProgram = ?',$IdProgram);	
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
	public function fnLandscapeTypeList($IdProgram) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active'))
			->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
			->join(array('sem'=>'tbl_semester'),'lan.IdStartSemester = sem.IdSemester')
			->join(array('sma'=>'tbl_semestermaster'),'sem.IdSemester = sma.IdSemesterMaster')
            ->where('lan.IdProgram = ?',$IdProgram);
    $result = $this->fetchRow($select);
	return $result;
    }
    
	public function fnGetLandScapeBlockDtls($idlandscape) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active'))
			->join(array('lb'=>'tbl_landscapeblock'),'lan.IdLandscape = lb.idlandscape')
            ->where('lan.IdLandscape  = ?',$idlandscape);

    $result = $this->fetchAll($select);
	return $result->toArray();
    }
    
	public function fnGetLandScapeBlockSubjectDtls($idlandscape) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lbs'=>'tbl_landscapeblocksubject'),'lbs.blockid','lbs.IdLandscape')
			->join(array('lb'=>'tbl_landscapeblock'),'lbs.blockid = lb.block')
			->join(array('sm'=>'tbl_subjectmaster'),'lbs.subjectid = sm.IdSubject')
            ->where('lbs.IdLandscape = ?',$idlandscape)
            ->where('lb.idlandscape = ?',$idlandscape)
            ->group('lbs.subjectid');

    $result = $this->fetchAll($select);
	return $result->toArray();
    }
    
    
	public function fnGetLandScapeBlockSemesterDtls($idlandscape) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lbs'=>'tbl_landscapeblocksemester'),'lb.*')
			->join(array('lb'=>'tbl_landscapeblock'),'lbs.blockid=lb.block')
		    ->where('lbs.IdLandscape = ?',$idlandscape)
		    ->where('lb.idlandscape = ?',$idlandscape);
    $result = $this->fetchAll($select);
		return $result->toArray();
    }
    public function fnupdateLandscapeActive($formData,$lintIdProgram) { //Function for updating the university
    	unset ( $formData ['Save'] );
    	unset($formData['block']);
    	unset($formData['semesterid']);
    	unset($formData['blocknamegrid']);
    	unset($formData['semesteridnamegrid']);
		$where = 'IdProgram = '.$lintIdProgram;
		$formData['Active'] = 0;
		$this->update($formData,$where);
    }
    
	public function fnGetProgramRequrimentEditDetails($lintIdLandscape) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_programrequirement'),array('a.*'))
							->where("a.IdLandscape = '$lintIdLandscape'");
			$result = $this->fetchAll($select);
			return $result;
	}
	
	public function fninserttempprogramentryrequriments($larrmainprogramresult,$idlandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_tempprogramrequirement";
		$sessionID = Zend_Session::getId();
			
		foreach($larrmainprogramresult as $formData) {
				$larrtepprogrmentryreq = array('IdProgram'=>$formData['IdProgram'],
									'SubjectType'=>$formData['SubjectType'],
									'CreditHours'=>$formData['CreditHours'],
									'UpdDate'=>$formData['UpdDate'],
									'UpdUser'=>$formData['UpdUser'],
									'unicode'=>$idlandscape,
									'Date'=>date("Y-m-d"),
									'sessionId'=>$sessionID,
									'idExists'=>$formData['IdProgramReq'],
									'deleteFlag'=>'1'
						);
						
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	


   public function fnGetTemproryProgReqDetails($idlandscape) { //Function for the view University 
   	$sessionID = Zend_Session::getId();
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
			->join(array('prg'=>'tbl_tempprogramrequirement'),'lan.IdLandscape = prg.unicode')
			->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
            ->where('lan.IdLandscape = ?',$idlandscape)
            ->where('prg.deleteFlag = 1')
            ->where('prg.sessionId = ?',$sessionID);
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
	public function fnGetLandscapeEditDetails($lintIdLandscape) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_landscapesubject'),array('a.*'))
							->where("a.IdLandscape = '$lintIdLandscape'");
			$result = $this->fetchAll($select);
			return $result;
	}
	
	public function fninserttemplanscapesubject($larrmainlandscapesubesult,$idlandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapesubject";
		$sessionID = Zend_Session::getId();
			
		foreach($larrmainlandscapesubesult as $formData) {
				$larrteplandscapesubresult = array('IdProgram'=>$formData['IdProgram'],
									'IdSubject'=>$formData['IdSubject'],
									'SubjectType'=>$formData['SubjectType'],
									'IdSemester'=>$formData['IdSemester'],
									'CreditHours'=>$formData['CreditHours'],
									'Active'=>$formData['Active'],
									'UpdDate'=>$formData['UpdDate'],
									'UpdUser'=>$formData['UpdUser'],
									'unicode'=>$idlandscape,
									'Date'=>date("Y-m-d"),
									'sessionId'=>$sessionID,
									'compulsory' => $formData['Compulsory'],
									'idExists'=>$formData['IdLandscapeSub'],
									'IDProgramMajoring'=>$formData['IDProgramMajoring'],
									'deleteFlag'=>'1'
						);
						
			$db->insert($table,$larrteplandscapesubresult);	
		}

	}
	
   public function fnGetTemproryLandscapesubResult($idlandscape,$landscapetype) { //Function for the view University 
	   	$sessionID = Zend_Session::getId();
			$select = $this->select()
					->setIntegrityCheck(false)  
					->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
					->join(array('prg'=>'tbl_templandscapesubject'),'lan.IdLandscape = prg.unicode')
					->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
					->join(array('sub'=>'tbl_subjectmaster'),'prg.IdSubject = sub.IdSubject')
		            ->where('lan.IdLandscape = ?',$idlandscape)
		            ->where('prg.deleteFlag = 1')
		            ->where('prg.sessionId = ?',$sessionID);
		            //echo $select;
		            //die();
		$result = $this->fetchAll($select);
		return $result->toArray();
    }
    
	public function fnUpdateTempProgramRequriments($IdTempProgReq) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempprogramrequirement";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdTempProgramReq = '".$IdTempProgReq."'";
			$db->update($table,$larramounts,$where);	
	}
	
	public function fnUpdateTempLandscapesubject($IdTempLandscapesub) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapesubject";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdTempLandscapeSub = '".$IdTempLandscapesub."'";
			$db->update($table,$larramounts,$where);	
	}
	
	
	public function fnUpdateMainLandscape($IdLandscape,$idstartsemester,$description,$AddDrop) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscape";
			$larrupdate = array('IdStartSemester'=>$idstartsemester);
			$larrupdate = array('ProgramDescription'=>$description);
			$larrupdate = array('AddDrop'=>$AddDrop);
			$where = "IdLandscape = '".$IdLandscape."'";
			$db->update($table,$larrupdate,$where);	
	}
	
	
	public function fnGetTempProgramrequrimentsDetails($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_tempprogramrequirement'),array('a.IdTempProgramReq'))
							->where("a.unicode = '$Idlandscape'")
							->where("a.sessionId = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}	
		
	public function fnGetTempProgramrequrimentsDetailslevel($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_tempprogramrequirement'),array('a.IdTempProgramReq'))
							->where("a.unicode = '$Idlandscape'")
							->where("a.deleteFlag = '1'")
							->where("a.sessionID = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result->toArray();
		}
		
	public function fnGetTempLandscapeSubject($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapesubject'),array('a.IdTempProgramReq'))
							->where("a.unicode  = '$Idlandscape'")
							->where("a.deleteFlag = '1'")
							->where("a.sessionID = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result->toArray();
		}
		
		
		
		public function fnGetTempLandscapeBlockLevel($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapeblock'),array('a.IdTempProgramReq'))
							->where("a.IdLandscape = '$Idlandscape'")
							->where("a.deleteFlag = '1'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result->toArray();
		}
		
		
		
		public function fnGetTempLandscapeSemesterLevel($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapeblocksemester'),array('a.IdTempProgramReq'))
							->where("a.IdLandscape = '$Idlandscape'")
							->where("a.deleteFlag = '1'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result->toArray();
		}
		
		
		
		public function fnDeleteFromProgramRequirements($landscape){
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_programrequirement";
	    	$where = $db->quoteInto('IdLandscape = ?',$landscape);
			$db->delete($table, $where);
		}
		
		
		public function deleteLandscapeSubjectLevel($landscape){
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapesubject";
	    	$where = $db->quoteInto('IdLandscape = ?',$landscape);
			$db->delete($table, $where);
			
		}
		
		
       public function deleteLandscapeBlockLevel($landscape){
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblock";
	    	$where = $db->quoteInto('IdLandscape = ?',$landscape);
			$db->delete($table, $where);
			
		}
		
		
      public function deleteLandscapeSemesterLevel($landscape){
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblocksemester";
	    	$where = $db->quoteInto('IdLandscape = ?',$landscape);
			$db->delete($table, $where);
			
		}
		
	    public function fnDeleteProgramrequriments($programreq ) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_programrequirement";
	    	$where = $db->quoteInto('IdProgramReq = ?', $programreq);
			$db->delete($table, $where);
	    }
	    
	    
	public function fnGetTempLandscapeSubjectDetails($Idlandscape,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapesubject'),array('a.IdTempLandscapeSub'))
							->where("a.unicode = '$Idlandscape'")
							->where("a.sessionId = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}	
		
	    public function fnDeleteLandscapeSubject($IdLandscapeSub ) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapesubject";
	    	$where = $db->quoteInto('IdLandscapeSub = ?', $IdLandscapeSub);
			$db->delete($table, $where);
	    }
	    
	    public function fnDeleteTempProgramReq($Idlandscape,$sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempprogramrequirement";
	    	$where = $db->quoteInto('unicode = ?', $Idlandscape);
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
	    public function fnDeleteTemplandscapesub($Idlandscape,$sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapesubject";
	    	$where = $db->quoteInto('unicode = ?', $Idlandscape);
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
		public function fnDeleteTempProgramreqBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempprogramrequirement";
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
		public function fnDeleteTemplandsacpesubBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapesubject";
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
		public function fnDeleteTemplandsacpeblockBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblock";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
		public function fnDeleteTemplandsacpeblocksemesterBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksemester";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
		public function fnDeleteTemplandsacpeblocksubjectBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksubject";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
	public function fnUpdateTempblock($IdLandscapetempblock) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblock";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdLandscapetempblock = '".$IdLandscapetempblock."'";
			$db->update($table,$larramounts,$where);	
	}
	
	public function fnUpdateTempblocksub($IdLandscapetempblocksubject) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksubject";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdLandscapetempblocksubject = '".$IdLandscapetempblocksubject."'";
			$db->update($table,$larramounts,$where);	
	}
	
	public function fnUpdateTempblocksem($IdLandscapetempblocksemester) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksemester";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdLandscapetempblocksemester = '".$IdLandscapetempblocksemester."'";
			$db->update($table,$larramounts,$where);	
	}
	
	public function fninsertoldlandscapesemester($formData,$resultLandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_oldlandsacpesemester";
		$count = count($formData['Semester']);
			for($i=0;$i<$count;$i++){
				$larrtepprogrmentryreq = array('IdLandscape'=>$resultLandscape,
											   'semesterid'=>$formData['Semester'][$i]);	
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
   public function fninsertToProgramRequirements($formData,$idlandscpae) {  // function to insert po details
   		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_programrequirement";
				$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				                               'IdProgram'=>$formData['IdProgram'],
				 							   'SubjectType'=>$formData['SubjectType'],
												'CreditHours'=>$formData['CreditHours'],
												'UpdDate'=>$formData['UpdDate'],
												'UpdUser'=>$formData['UpdUser']);
			$db->insert($table,$larrtepprogrmentryreq);	
		
	}
	
	
	 public function fninsertToLandScapeSubjectLevel($formData,$idlandscpae) {  // function to insert po details
   		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapesubject";
				$larrtepprogrmentryreq = array('IdProgram'=>$formData['IdProgram'],
				                               'IdLandscape'=>$idlandscpae,
				                               'IdSubject'=>$formData['IdSubject'],
				 							   'SubjectType'=>$formData['SubjectType'],
												'IdSemester'=>$formData['IdSemester'],
												'CreditHours'=>$formData['CreditHours'],
												'IDProgramMajoring'=>$formData['IDProgramMajoring'],
												'Compulsory'=>$formData['Compulsory'],
												'IDProgramMajoring'=>$formData['IDProgramMajoring'],
												'Active'=>$formData['Active'],
												'UpdDate'=>$formData['UpdDate'],
												'UpdUser'=>$formData['UpdUser']);
			$db->insert($table,$larrtepprogrmentryreq);	
		
	}
	
	
	
	 public function fninsertToLandScapeBlockLevel($formData,$idlandscpae) {  // function to insert po details
   		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblock";
				$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				                               'block'=>$formData['blockid'],
				 							   'blockname'=>$formData['blockname'],
											   'CreditHours'=>$formData['CreditHours']);
			$db->insert($table,$larrtepprogrmentryreq);	
		
	}
	
	
	
     public function fninsertToLandScapeSemesterLevel($formData,$idlandscpae) {  // function to insert po details
   		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksemester";
				$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				                               'blockid'=>$formData['blockid'],
				 							   'semesterid'=>$formData['semesterid']);
			$db->insert($table,$larrtepprogrmentryreq);	
		
	}
	
	public function fnGetoldLandscapeDetails($idlandscape) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_oldlandsacpesemester'),array('a.*'))
							->where("a.IdLandscape = '$idlandscape'");
			$result = $this->fetchAll($select);
			return $result;
	}
	
	public function fnDeletelandscapeoldsemester($IdLandscape) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_oldlandsacpesemester";
	    	$where = $db->quoteInto('IdLandscape = ?', $IdLandscape);
			$db->delete($table, $where);
	}
	
    public function fnupdateLandscapeActiveaction($idlandscape) { //Function for updating the university
		$where = 'IdLandscape = '.$idlandscape;
		$formData['Active'] = 123;
		$this->update($formData,$where);
    }
    
    public function fnupdateLandscapeInActiveaction($idlandscape) { //Function for updating the university
		$where = 'IdLandscape = '.$idlandscape;
		$formData['Active'] = 124;
		$this->update($formData,$where);
    }
    
    public function fnGetMajoringList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_programmajoring"),array("key"=>"a.IDProgramMajoring","value"=>"a.EnglishDescription"))
		 				 ->order("a.EnglishDescription");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
    }
    
    
   
        
        
        
        
}
?>
