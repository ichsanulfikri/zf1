<?php 
class GeneralSetup_Model_DbTable_Landscapetemp extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_templandscape';
    private $lobjDbAdpt;
    
	public function init() {
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function fninserttempblock($templandscapelist,$sessionID) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblock";
		$sessionID = Zend_Session::getId();	
		foreach($templandscapelist as $formData) {
			$larrtepprogrmentryreq = array('IdLandscape'=>$formData['IdLandscape'],
										   'blockid'=>$formData['block'],
										   'blockname'=>$formData['blockname'],
										   'CreditHours'=>$formData['CreditHours'],
										   'session_id'=>$sessionID,
										   'deleteFlag'=>'1');
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
    public function fnaddLandscapetempblocklist($idlandscape,$sessionID) { //Function for adding the University details to the table
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lanb' => 'tbl_templandscapeblock'),array('lanb.IdLandscape'))
            ->where('lanb.IdLandscape = ?',$idlandscape)
            ->where('lanb.session_id = ?',$sessionID)
            ->where('lanb.deleteFlag = 1')
            ->order('lanb.blockid');
	$result = $this->fetchAll($select);
	return $result->toArray();
	}
	
	public function fnDeleteTemplandscapeblockBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblock";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	 }
	 
	public function fninserttempblocksubject($templandscapeblocksublist,$sessionID) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksubject";
		$sessionID = Zend_Session::getId();	
		foreach($templandscapeblocksublist as $formData) {
			$larrtepprogrmentryreq = array('IdLandscape'=>$formData['IdLandscape'],
										   'blockid'=>$formData['block'],
										   'subjectid'=>$formData['IdSubject'],
										   'session_id'=>$sessionID,
										   'deleteFlag'=>'1');
						
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
	public function fnGetTempLandScapeBlockSubjectDtls($idlandscape,$sessionID) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lbs'=>'tbl_templandscapeblocksubject'),'lbs.IdLandscape')
			->join(array('lb'=>'tbl_templandscapeblock'),'lbs.blockid = lb.blockid')
			->join(array('sm'=>'tbl_subjectmaster'),'lbs.subjectid = sm.IdSubject')
            ->where('lbs.IdLandscape = ?',$idlandscape)
            ->where('lbs.session_id = ?',$sessionID)
            ->where('lb.session_id = ?',$sessionID)
            ->group('lbs.subjectid')
            ->where('lbs.deleteFlag = 1')
            ->order('lbs.blockid');
    $result = $this->fetchAll($select);
	return $result->toArray();
    }
		
	public function fnDeleteTemplandscapeblocksubBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksubject";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	 }
	 
	public function fnDeleteTemplandscapeblocksubsemBysession($sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_templandscapeblocksemester";
	    	$where = $db->quoteInto('session_id = ?', $sessionID);
			$db->delete($table, $where);
	 }
	 
	public function fninserttempblocksubjectsem($templandscapeblocksublistsem,$sessionID) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksemester";
		$sessionID = Zend_Session::getId();	

		foreach($templandscapeblocksublistsem as $formData) {
			$larrtepprogrmentryreq = array('IdLandscape'=>$formData['idlandscape'],
										   'blockid'=>$formData['block'],
										   'semesterid'=>$formData['semesterid'],
										   'session_id'=>$sessionID,
										   'deleteFlag'=>'1');
						
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	 
    
	public function fnGetTempLandScapeBlockSubjectSemDtls($idlandscape,$sessionID) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lb'=>'tbl_templandscapeblock'),'lb.*')
			->join(array('lbs'=>'tbl_templandscapeblocksemester'),'lbs.blockid = lb.blockid')
		    ->where('lbs.IdLandscape = ?',$idlandscape)
		    ->where('lbs.session_id = ?',$sessionID)
		    ->where('lbs.deleteFlag = 1')
		    ->order('lbs.semesterid');
    $result = $this->fetchAll($select);
    
	return $result->toArray();
    }
	
    public function fnaddLandscapetemp($formData) { //Function for adding the University details to the table
    		unset($formData['Save']);
    		unset($formData['Back']);
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
			->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active'))
			->join(array('prg'=>'tbl_program'),'lan.IdProgram = prg.IdProgram',array('prg.IdProgram'))
			->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
            ->where('lan.IdProgram = ?',$IdProgram);	
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
	
	public function fnaddtemplandscapeblock($larrformData,$sessionID) { //Function for adding the user details to the table
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		foreach($larrformData as $larrformData) {
		$lstrTable = "tbl_templandscapeblock";
		$larrInsertData = array('IdLandscapetemp' => $larrformData["idlandscapetemp"],
								'blockid' => $larrformData["blockgrid"][$i],
								'blockname' => $larrformData["blocknamegrid"][$i],
								'session_id' => $sessionID
							);
		$lobjDbAdpt->insert($lstrTable,$larrInsertData);
		}
	 	return $lobjDbAdpt->lastInsertId();
	}
	
	public function fnaddtemplandscapeblocksubject($larrformData,$sessionID) { //Function for adding the user details to the table
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		for($i = 0 ;$i<count($larrformData['BlockNameListgrid']); $i++) {
		$lstrTable = "tbl_templandscapeblocksubject";
		$larrInsertData = array('IdLandscapetemp' => $larrformData["idlandscapetemp"],
								'blockid' => $larrformData["BlockNameListgrid"][$i],
								'subjectid' => $larrformData["SubjectNameListgrid"][$i],
								'session_id' => $sessionID
							);
		$lobjDbAdpt->insert($lstrTable,$larrInsertData);
		}
	 	return $lobjDbAdpt->lastInsertId();
	}
	public function fnaddtemplandscapeblocksemestert($larrformData,$sessionID) { //Function for adding the user details to the table
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		for($i = 0 ;$i<count($larrformData['BlockDtlsListgrid']); $i++) {
		$lstrTable = "tbl_templandscapeblocksemester";
		$larrInsertData = array('IdLandscapetemp' => $larrformData["idlandscapetemp"],
								'blockid' => $larrformData["BlockDtlsListgrid"][$i],
								'semesterid' => $larrformData["SemNameListnamegrid"][$i],
								'session_id' => $sessionID
							);
		$lobjDbAdpt->insert($lstrTable,$larrInsertData);
		}
	 	return $lobjDbAdpt->lastInsertId();
	}
	public function fngetLandscapeblock($idlandscapetemp) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lanb' => 'tbl_templandscapeblock'),array('lan.IdLandscapetempblock'))
            ->where('lanb.IdLandscapetemp = ?',$idlandscapetemp);	
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
    public function fnGetLandscapetempDetails($idlandscapetemp,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscape'),array('a.IdLandscapetemp'))
							->where("a.IdLandscapetemp = '$idlandscapetemp'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}	
		
	public function fninsertLandscape($formData) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscape";
			$larrLandscapetempDetails = array('IdProgram'=>$formData['IdProgram'],
											  'LandscapeType'=>$formData['LandscapeType'],
											  'IdStartSemester'=>$formData['IdStartSemester'],
											  'SemsterCount'=>$formData['SemsterCount'],
			                                  'Blockcount'=>$formData['Blockcount'],
											  'TotalCreditHours'=>$formData['TotalCreditHours'],
											  'Scheme'=>$formData['Scheme'],
											  'Active'=>$formData['Active'],
											  'UpdDate'=>$formData['UpdDate'],
											  'UpdUser'=>$formData['UpdUser']
							);			
			$db->insert($table,$larrLandscapetempDetails);	
			return $db->lastInsertId();

		}	
	 public function fnGetLandscapeBlocktempDetails($idlandscapetemp,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapeblock'),array('a.IdLandscapetemp'))
							->where("a.IdLandscapetemp = '$idlandscapetemp'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}	
	public function fninsertLandscapeBlock($formData,$resultLandscape){  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblock";
			$gridcount = count($formData['blockgrid']);
			for($i=0;$i<$gridcount;$i++){
				$data = array('idlandscape' => $resultLandscape,
							  'block' => $formData['blockgrid'][$i],
							  'blockname' => $formData['blocknamegrid'][$i],
							  'CreditHours' => $formData['BlockCreditHoursgrid'][$i]);	

			$db->insert($table,$data);
			}
		}	

			public function fninsertLandscapeBlockLevel($formData,$resultLandscape){  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblock";
			$gridcount = count($formData['blockgrid']);
			for($i=0;$i<$gridcount;$i++){
				$data = array('idlandscape' => $resultLandscape,
							  'block' => $formData['blockgrid'][$i],
							  'blockname' => $formData['blocknamegrid'][$i],
							  'CreditHours' => '0');	

			$db->insert($table,$data);
			}
		}	
		
	public function fngetLandscapeblocksubject($idlandscapetemp) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lanb' => 'tbl_templandscapeblocksubject'),array('lan.IdLandscapetempblocksubject'))
			->join(array('sub'=>'tbl_subjectmaster'),'lanb.subjectid = sub.IdSubject')
			->join(array('tlb'=>'tbl_templandscapeblock'),'lanb.blockid = tlb.blockid')
            ->where('lanb.IdLandscapetemp = ?',$idlandscapetemp);	
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
	
     public function fnGetLandscapeBlockSubjectemptDetails($idlandscapetemp,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapeblocksubject'),array('a.IdLandscapetemp'))
							->where("a.IdLandscapetemp = '$idlandscapetemp'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}	
	public function fninsertLandscapeBlockSubject($formData,$resultLandscape){  // function to insert po details

			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblocksubject";

			$gridcount = count($formData['BlockNameListgrid']);
			for($i=0;$i<$gridcount;$i++){
				$data = array('idlandscape' => $resultLandscape,
							  'blockid' => $formData['BlockNameListgrid'][$i],
							  'subjectid' => $formData['SubjectNameListgrid'][$i]);	

			$db->insert($table,$data);
			}

		}
	 public function fnGetLandscapeBlockSemestertemptDetails($idlandscapetemp,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_templandscapeblocksemester'),array('a.IdLandscapetemp'))
							->where("a.IdLandscapetemp = '$idlandscapetemp'")
							->where("a.session_id = '$sessionID'");
			$result = $this->fetchAll($select);
			return $result;
		}			
	public function fninsertLandscapeBlockSemester($formData,$resultLandscape){  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblocksemester";
			$gridcount = count($formData['BlockDtlsListgrid']);
			for($i=0;$i<$gridcount;$i++){
				$data = array('idlandscape' => $resultLandscape,
							  'blockid' => $formData['BlockDtlsListgrid'][$i],
							  'semesterid' => $formData['SemNameListnamegrid'][$i]);	

			$db->insert($table,$data);
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
									'PromotionFlag'=>$formData['PromotionFlag'],
									'Active'=>$formData['Active'],
									'UpdDate'=>$formData['UpdDate'],
									'UpdUser'=>$formData['UpdUser'],
									'unicode'=>$idlandscape,
									'Date'=>date("Y-m-d"),
									'sessionId'=>$sessionID,
									'compulsory' => $formData['Compulsory'],
									'idExists'=>$formData['IdLandscapeSub'],
									'deleteFlag'=>'1'
						);
						
			$db->insert($table,$larrteplandscapesubresult);	
		}
		//print_r($larrcourse);die();
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
	
	
	public function fnUpdateMainLandscape($IdLandscape,$idstartsemester) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscape";
			$larrupdate = array('IdStartSemester'=>$idstartsemester);
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
	    
		public function fngettmpeditblock($idlandscape,$sessionID) { //Function for the view University 
			$select = $this->select()
				->setIntegrityCheck(false)  
				->join(array('lbs'=>'tbl_templandscapeblock'),'lbs.IdLandscape')
				->where('lbs.IdLandscape = ?',$idlandscape)
            	->where('lbs.session_id = ?',$sessionID)
            	->where('lbs.deleteFlag = 1');
    		$result = $this->fetchAll($select);
		return $result->toArray();
    }
    
	public function fngettmpeditblocksub($idlandscape,$sessionID) { //Function for the view University 
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lbs'=>'tbl_templandscapeblocksubject'),'lbs.IdLandscape')
			->where('lbs.IdLandscape = ?',$idlandscape)
            ->where('lbs.session_id = ?',$sessionID)
            ->where('lbs.deleteFlag = 1');
    	$result = $this->fetchAll($select);
		return $result->toArray();
    }
    
	public function fngettmpeditblocksubsem($idlandscape,$sessionID) { //Function for the view University 
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('lbs'=>'tbl_templandscapeblocksemester'),'lbs.IdLandscape')
			->where('lbs.IdLandscape = ?',$idlandscape)
            ->where('lbs.session_id = ?',$sessionID)
            ->where('lbs.deleteFlag = 1');
    	$result = $this->fetchAll($select);
		return $result->toArray();
    }
    
	public function fnDeletelandscapeblock($IdLandscape) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblock";
	    	$where = $db->quoteInto('IdLandscape = ?', $IdLandscape);
			$db->delete($table, $where);
	}
	
	public function fnDeletelandscapeblocksub($IdLandscape) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblocksubject";
	    	$where = $db->quoteInto('IdLandscape = ?', $IdLandscape);
			$db->delete($table, $where);
	}
	
	public function fnDeletelandscapeblocksubsem($IdLandscape) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_landscapeblocksemester";
	    	$where = $db->quoteInto('IdLandscape = ?', $IdLandscape);
			$db->delete($table, $where);
	}
	
	public function fninsertLandscapeBlockfromtemp($resblock) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblock";
		foreach($resblock as $formData) {
			$larrtepprogrmentryreq = array('idlandscape'=>$formData['IdLandscape'],
										   'block'=>$formData['blockid'],
										   'blockname'=>$formData['blockname'],
										   'CreditHours'=>$formData['CreditHours']);
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
	public function fninsertLandscapeBlockSubjectfromtemp($resblocksub) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksubject";
		foreach($resblocksub as $formData) {
			$larrtepprogrmentryreq = array('IdLandscape'=>$formData['IdLandscape'],
										   'blockid'=>$formData['blockid'],
										   'subjectid'=>$formData['subjectid']);
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
	public function fninsertLandscapeBlockSemesterfromtemp($resblocksubsem) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksemester";
		foreach($resblocksubsem as $formData) {
			$larrtepprogrmentryreq = array('IdLandscape'=>$formData['IdLandscape'],
										   'blockid'=>$formData['blockid'],
										   'semesterid'=>$formData['semesterid']);
			$db->insert($table,$larrtepprogrmentryreq);	
		}
	}
	
}
?>