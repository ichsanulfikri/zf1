<?php
class GeneralSetup_LandscapeController extends Base_Base {
	private $lobjprogram;
	private $lobjlandscapeform;
	private $lobjdeftype;
	private $lobjsemester;
	private $lobjlandscape;
	private $lobjprogramrequirement;
	private $lobjsubjectmaster;
	private $lobjlandscapesubject;
	private $lobjlandscapetemp;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
	}
	
	public function fnsetObj(){
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjlandscapeform = new GeneralSetup_Form_Landscape();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$this->lobjlandscape = new GeneralSetup_Model_DbTable_Landscape();
		$this->lobjprogramrequirement = new GeneralSetup_Model_DbTable_Programrequirement();
		$this->lobjsubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster();
		$this->lobjlandscapesubject = new GeneralSetup_Model_DbTable_Landscapesubject();
		$this->lobjcoursemaster = new GeneralSetup_Model_DbTable_Coursemaster();
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjlandscapetemp = new GeneralSetup_Model_DbTable_Landscapetemp();
	}
	
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		$larrresult = $this->lobjprogram->fngetProgramDetails (); 
		
		$sessionID = Zend_Session::getId();
        $this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
         if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->landscapepaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->landscapepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->landscapepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjlandscape->fnSearchProgram ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				@$this->gobjsessionsis->landscapepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');
		}	
	}
	
	public function addlandscapeAction() {
		$this->view->lobjform = $this->lobjform;
		$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
			$this->view->TotalCreditHours = $programlistdetail['TotalCreditHours']; 
		}
		$sessionID = Zend_Session::getId();
        $this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpeblockBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpeblocksemesterBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpeblocksubjectBysession($sessionID);
        
		$larrresult = $this->lobjlandscape->fnLandscapeList($IdProgram);
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$this->view->id = $this->_getParam('id',0);
		
		if(isset($this->gobjsessionstudent->landscapeprgpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionstudent->landscapeprgpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
	}
	
		//add level landscape
    	public function levellandscapeAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$this->view->lobjlandscapeform->Active->setValue (125);
		//$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');			
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');	
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$this->view->lobjlandscapeform->TotalCreditHours->setValue ($larrprogramresult['TotalCreditHours']);
			
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$this->view->lobjlandscapeform->LandscapeType->setValue (42);	
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');	
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}
    	$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);
		
    	foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		$larrscheme = $schemeobject->fetchAll();
    	foreach($larrscheme as $larrschemearr) {
			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		
		$majoringlist = $this->lobjlandscape->fnGetMajoringList();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		

    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$resultLandscape=$this->lobjlandscapetemp->fninsertLandscape($formData);
	    		
	    	    $IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$resultLandscape);
	    	    $this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$resultLandscape);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$resultLandscape);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$resultLandscape);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Level Landscape Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
				$IdProgram = $formData['IdProgram'];
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
    	}
    }
    
   //edit level landscape 
   public function levellandscapelistAction(){
    	$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$this->view->lobjlandscapeform->Active->setValue (125);		
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->TotalCreditHours->setAttrib ('readonly','true');	
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
		$majoringlist = $this->lobjlandscape->fnGetMajoringList();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$this->view->lobjlandscapeform->LandscapeType->setValue (42);	
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');	
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//echo "<pre>";
		//print_r($larrsemesterresult);die();
		
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}
    	$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);
		
    	foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		$larrscheme = $schemeobject->fetchAll();
    	foreach($larrscheme as $larrschemearr) {
			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		
		if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
				$this->lobjlandscapetemp->fnDeleteTemplandsacpesubBysession($sessionID);
        	$this->lobjlandscapetemp->fnDeleteTemplandscapeblocksubsemBysession($sessionID);
        	$this->lobjlandscapetemp->fnDeleteTemplandscapeblockBysession($sessionID);
        	$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		}
		
		$idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
   		$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
		$result = $result->toArray();
		foreach($result as $landscaperesult) {	
			$this->view->LandscapeType = $landscaperesult['LandscapeType'];	
			$lintsemestercount = $this->view->SemsterCount = $landscaperesult['SemsterCount'];	
			$lintblockcount = $this->view->Blockcount = $landscaperesult['Blockcount'];
			$this->view->IdStartSemester = $landscaperesult['IdStartSemester'];
			$this->view->IdLandscape = $landscaperesult['IdLandscape'];
			$this->view->TotalCreditHours = $landscaperesult['TotalCreditHours'];
		}
		
		$this->view->lobjlandscapeform->IdStartSemester->setValue($this->view->IdStartSemester);
		$this->view->lobjlandscapeform->Blockcount->setValue($this->view->Blockcount);
		$this->view->lobjlandscapeform->Blockcount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->SemsterCount->setValue($this->view->SemsterCount);
		$this->view->lobjlandscapeform->SemsterCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->IdLandscape->setValue($this->view->IdLandscape);
		$this->view->lobjlandscapeform->TotalCreditHours->setValue($this->view->TotalCreditHours);
		
		$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape);
		$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape);
		if($this->_getParam('update')!= 'true') {
	  			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
	  			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
	  		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape,$sessionID);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
		
		$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
		$templandscapelist = $this->view->larrLandScapeBlock = $larrLandScapeBlock;
		$sessionID = Zend_Session::getId();	
		if($this->_getParam('update')!= 'true') {
		$this->lobjlandscapetemp->fninserttempblock($templandscapelist,$sessionID);
		}
		$this->view->larrtemplandscapeblock = $larrtemplandscapeblock = $this->lobjlandscapetemp->fnaddLandscapetempblocklist($idlandscape,$sessionID);
		
		
		$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
		$templandscapeblocksublistsem = $this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;
		if($this->_getParam('update')!= 'true') { 
			$this->lobjlandscapetemp->fninserttempblocksubjectsem($templandscapeblocksublistsem,$sessionID);
		}
		$this->view->tempblocksubsemlist = $tempblocksubsemlist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockSubjectSemDtls($idlandscape,$sessionID);


		foreach($this->view->larrLandScapeBlock as $larrLandScapeBlockSubject) {
			$this->lobjlandscapeform->BlockDtlsList->addMultiOption($larrLandScapeBlockSubject['block'], $larrLandScapeBlockSubject['blockname']);
		}
		for($i=1;$i<=$lintsemestercount;$i++) {
				$this->lobjlandscapeform->SemNameList->addMultiOption($i,$i);
				$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
		}
		
   		for($i=1;$i<=$lintblockcount;$i++) {
				$this->lobjlandscapeform->block->addMultiOption($i,$i);
		}
		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester']);

	    		$idlandscpae = $formData['IdLandscape'];
	    		$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetailslevel($formData['IdLandscape'],$sessionID);
	    		$this->lobjlandscape->fnDeleteFromProgramRequirements($idlandscpae);
	    		foreach($fetchtempprogramrequrimesntsdetails as $fetchtempprogramrequrimesntsdetailslevel)
	    		$this->lobjlandscape->fninsertToProgramRequirements($fetchtempprogramrequrimesntsdetailslevel,$idlandscpae);
	    		$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$idlandscpae);
	    	
	    		$templandsubject=$this->lobjlandscape->fnGetTempLandscapeSubject($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeSubject=$this->lobjlandscape->deleteLandscapeSubjectLevel($idlandscpae);
				foreach($templandsubject as $templandsubjectlevel)
	    		$this->lobjlandscape->fninsertToLandScapeSubjectLevel($templandsubjectlevel,$idlandscpae);
	    		$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$idlandscpae);
    		
	    		
	    		
	    		$templandScapeBlock=$this->lobjlandscape->fnGetTempLandscapeBlockLevel($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeBlock=$this->lobjlandscape->deleteLandscapeBlockLevel($idlandscpae);
	    		foreach($templandScapeBlock as $templandScapeBlock)
	    		$this->lobjlandscape->fninsertToLandScapeBlockLevel($templandScapeBlock,$idlandscpae);
	    		$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$idlandscpae);
	    		
	    		
	    		$templandScapeSemester=$this->lobjlandscape->fnGetTempLandscapeSemesterLevel($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeSemester=$this->lobjlandscape->deleteLandscapeSemesterLevel($idlandscpae);
	    		foreach($templandScapeSemester as $templandScapeSemester)
	    		$this->lobjlandscape->fninsertToLandScapeSemesterLevel($templandScapeSemester,$idlandscpae);
	    		$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$idlandscpae);
	    		
	    		
	    		
	    		// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Level Landscape Edit Id=' . $IdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				

				$IdProgram = $formData['IdProgram'];
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
    	}
    } 
    
    
    //blocklandscape add blocklandscapeAction
    	public function blocklandscapeAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$this->view->lobjlandscapeform->Active->setValue (125);
		//$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');			
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');	
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$this->view->lobjlandscapeform->TotalCreditHours->setValue ($larrprogramresult['TotalCreditHours']);
			
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$this->view->lobjlandscapeform->LandscapeType->setValue (44);	
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');	
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}
    	$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);
		
    	foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		$larrscheme = $schemeobject->fetchAll();
    	foreach($larrscheme as $larrschemearr) {
			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		
		$majoringlist = $this->lobjlandscape->fnGetMajoringList();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		

    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$resultLandscape=$this->lobjlandscapetemp->fninsertLandscape($formData);
	    		
	    	    $IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$resultLandscape);
	    	    $this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$resultLandscape);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$resultLandscape);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$resultLandscape);
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Block Landscape Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$IdProgram = $formData['IdProgram'];
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
    	}
    }
    
    //edit blockbased blocklandscapelistAction
   public function blocklandscapelistAction(){
    	$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$this->view->lobjlandscapeform->Active->setValue (125);		
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->TotalCreditHours->setAttrib ('readonly','true');	
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
		$majoringlist = $this->lobjlandscape->fnGetMajoringList();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$this->view->lobjlandscapeform->LandscapeType->setValue (44);	
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');	
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//echo "<pre>";
		//print_r($larrsemesterresult);die();
		
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}
    	$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		
		
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);
		
    	foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		$larrscheme = $schemeobject->fetchAll();
    	foreach($larrscheme as $larrschemearr) {
			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		
		if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
				$this->lobjlandscapetemp->fnDeleteTemplandsacpesubBysession($sessionID);
        	$this->lobjlandscapetemp->fnDeleteTemplandscapeblocksubsemBysession($sessionID);
        	$this->lobjlandscapetemp->fnDeleteTemplandscapeblockBysession($sessionID);
        	$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		}
		
		$idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
   		$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
		$result = $result->toArray();
		foreach($result as $landscaperesult) {	
			$this->view->LandscapeType = $landscaperesult['LandscapeType'];	
			$lintsemestercount = $this->view->SemsterCount = $landscaperesult['SemsterCount'];	
			$lintblockcount =$this->view->Blockcount = $landscaperesult['Blockcount'];
			$this->view->IdStartSemester = $landscaperesult['IdStartSemester'];
			$this->view->IdLandscape = $landscaperesult['IdLandscape'];
			$this->view->TotalCreditHours = $landscaperesult['TotalCreditHours'];
		}
		
		$this->view->lobjlandscapeform->IdStartSemester->setValue($this->view->IdStartSemester);
		$this->view->lobjlandscapeform->Blockcount->setValue($this->view->Blockcount);
		$this->view->lobjlandscapeform->Blockcount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->SemsterCount->setValue($this->view->SemsterCount);
		$this->view->lobjlandscapeform->SemsterCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->IdLandscape->setValue($this->view->IdLandscape);
		$this->view->lobjlandscapeform->TotalCreditHours->setValue($this->view->TotalCreditHours);
		
		$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape);
		$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape);
		if($this->_getParam('update')!= 'true') {
	  			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
	  			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
	  		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape,$sessionID);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
		
		$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
		$templandscapelist = $this->view->larrLandScapeBlock = $larrLandScapeBlock;
		$sessionID = Zend_Session::getId();	
		if($this->_getParam('update')!= 'true') {
		$this->lobjlandscapetemp->fninserttempblock($templandscapelist,$sessionID);
		}
		$this->view->larrtemplandscapeblock = $larrtemplandscapeblock = $this->lobjlandscapetemp->fnaddLandscapetempblocklist($idlandscape,$sessionID);
		
		
		$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
		$templandscapeblocksublistsem = $this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;
		if($this->_getParam('update')!= 'true') { 
			$this->lobjlandscapetemp->fninserttempblocksubjectsem($templandscapeblocksublistsem,$sessionID);
		}
		$this->view->tempblocksubsemlist = $tempblocksubsemlist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockSubjectSemDtls($idlandscape,$sessionID);


		foreach($this->view->larrLandScapeBlock as $larrLandScapeBlockSubject) {
			$this->lobjlandscapeform->BlockDtlsList->addMultiOption($larrLandScapeBlockSubject['block'], $larrLandScapeBlockSubject['blockname']);
		}
		for($i=1;$i<=$lintsemestercount;$i++) {
				$this->lobjlandscapeform->SemNameList->addMultiOption($i,$i);
				$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
		}
		
      		for($i=1;$i<=$lintblockcount;$i++) {
				$this->lobjlandscapeform->block->addMultiOption($i,$i);
		}
		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester']);

	    		$idlandscpae = $formData['IdLandscape'];
	    		$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetailslevel($formData['IdLandscape'],$sessionID);
	    		$this->lobjlandscape->fnDeleteFromProgramRequirements($idlandscpae);
	    		foreach($fetchtempprogramrequrimesntsdetails as $fetchtempprogramrequrimesntsdetailslevel)
	    		$this->lobjlandscape->fninsertToProgramRequirements($fetchtempprogramrequrimesntsdetailslevel,$idlandscpae);
	    		$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$idlandscpae);
	    	
	    		$templandsubject=$this->lobjlandscape->fnGetTempLandscapeSubject($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeSubject=$this->lobjlandscape->deleteLandscapeSubjectLevel($idlandscpae);
				foreach($templandsubject as $templandsubjectlevel)
	    		$this->lobjlandscape->fninsertToLandScapeSubjectLevel($templandsubjectlevel,$idlandscpae);
	    		$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$idlandscpae);
    		
	    		
	    		
	    		$templandScapeBlock=$this->lobjlandscape->fnGetTempLandscapeBlockLevel($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeBlock=$this->lobjlandscape->deleteLandscapeBlockLevel($idlandscpae);
	    		foreach($templandScapeBlock as $templandScapeBlock)
	    		$this->lobjlandscape->fninsertToLandScapeBlockLevel($templandScapeBlock,$idlandscpae);
	    		$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$idlandscpae);
	    		
	    		
	    		$templandScapeSemester=$this->lobjlandscape->fnGetTempLandscapeSemesterLevel($formData['IdLandscape'],$sessionID);
	    		$deleteLandscapeSemester=$this->lobjlandscape->deleteLandscapeSemesterLevel($idlandscpae);
	    		foreach($templandScapeSemester as $templandScapeSemester)
	    		$this->lobjlandscape->fninsertToLandScapeSemesterLevel($templandScapeSemester,$idlandscpae);
	    		$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$idlandscpae);
	    		
	    		
	    		// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Block Landscape Edit Id=' . $IdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				$IdProgram = $formData['IdProgram'];
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
    	}
    }
    
     

	public function editlandscapeAction(){		
    	$this->view->title="Edit Course";  //title
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->LandscapeType->setValue (43);	
		$this->view->lobjlandscapeform->Active->setValue (125);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');		
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');	
    	$this->view->IdProgram= $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$sessionID = Zend_Session::getId();
        $this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
    	$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
	    $this->view->idlandscape = $this->_getParam('idlandscape', 0);
		if($this->view->idlandscape) {
			$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
			$result = $result->toArray();
			foreach($result as $landscaperesult) {	
				   $this->view->LandscapeType = $landscaperesult['LandscapeType'];	
				   $lintsemestercount = $landscaperesult['SemsterCount'];	
			}
			for($i=1;$i<=$lintsemestercount;$i++) {
				$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
			}
			$this->view->lobjlandscapeform->populate($landscaperesult);	
		}
		
		$this->view->IdProgramReq = $this->_getParam('IdProgramReq', 0);
		if($this->view->IdProgramReq) {
			$this->view->programreqresult = $this->lobjlandscape->fneditLandscape($this->view->idlandscape);
		}
		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		if(!$formData['IdLandscape']) {
				$landscapeid = $this->lobjlandscape->fnaddLandscape($formData);
				$resultoldlandscaoesemester = $this->lobjlandscape->fninsertoldlandscapesemester($formData,$landscapeid);
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/editlandscape/id/'.$IdProgram.'/idlandscape/'.$landscapeid);
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'editlandscape','idlandscape'=>$landscapeid),'default',true));
	    		}
	    		$landscapeid = $formData['IdLandscape'];
				if(!$formData['IdProgramReq']) {
	    		$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirement($formData);
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/editlandscape/id/'.$IdProgram.'/idlandscape/'.$landscapeid.'/IdProgramReq/'.$IdProgramReq);
	    		//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'editlandscape','idlandscape'=>$landscapeid,'IdProgramReq'=>$IdProgramReq),'default',true));
				}
				$this->lobjlandscapesubject->fnaddLandscapesubject($formData);
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'index'),'default',true));
			}
    	}
    }
    

    
    
    public function editlandscapelistAction (){		
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');		
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');			
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
    	$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
    	$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
    		
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMasterName'].'-'.$larroldsemesterresult['year']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeprogSemesterDetails();
/*		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}*/
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
	    $idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
	    $larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape); // Get the Item Sales details
	    $larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape); // Get the Item Sales details
	    		//old semester
		$resoldsemester = $this->lobjlandscape->fnGetoldLandscapeDetails($idlandscape);
		foreach($resoldsemester as $resoldsemester) {
			$semesterid[] = $resoldsemester['semesterid'];
		}
		$this->view->lobjlandscapeform->Semester->setValue($semesterid);
	    if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
        	$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        	$this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
	    }
	   
		if($this->view->idlandscape) {
			$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
			//echo $result;die();
			$result = $result->toArray();
			
			foreach($result as $landscaperesult) {	
				$this->view->LandscapeType = $landscaperesult['LandscapeType'];	
				$this->view->SemsterCount = $landscaperesult['SemsterCount'];	
			}
			$SemsterCount =  $this->view->SemsterCount;
			for($i=1;$i<=$SemsterCount;$i++) {
				$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
			}
			$this->view->lobjlandscapeform->populate($landscaperesult);	
			
			if($this->_getParam('update')!= 'true') {
	  			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
	  			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
	  		}
		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);

    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester']);	
	    		$IdLandscape = $formData['IdLandscape'];
	    		$this->lobjlandscape->fnDeletelandscapeoldsemester($IdLandscape);
	    		
	    		$resultoldlandscaoesemester = $this->lobjlandscape->fninsertoldlandscapesemester($formData,$IdLandscape);
	    		
	    		$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirement($formData);
				$this->lobjlandscapesubject->fnaddLandscapesubject($formData);
				
				$sessionID = Zend_Session::getId();
				$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetails($formData['IdLandscape'],$sessionID);
				foreach($fetchtempprogramrequrimesntsdetails as $fetchdtempprogreq) {
					if($fetchdtempprogreq['deleteFlag'] =='0') {
							$this->lobjlandscape->fnDeleteProgramrequriments($fetchdtempprogreq['idExists']);
					}
				}
				
	    		$fetchtemplanscapesubjectdetails = $this->lobjlandscape->fnGetTempLandscapeSubjectDetails($formData['IdLandscape'],$sessionID);
				foreach($fetchtemplanscapesubjectdetails as $fetchdtemplandscapesub) {
					if($fetchdtemplandscapesub['deleteFlag'] =='0') {
							$this->lobjlandscape->fnDeleteLandscapeSubject($fetchdtemplandscapesub['idExists']);
					}
				}
				$this->lobjlandscape->fnDeleteTempProgramReq($formData['IdLandscape'],$sessionID);
				$this->lobjlandscape->fnDeleteTemplandscapesub($formData['IdLandscape'],$sessionID);
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');		
			}
    	}
    }
     
    public function viewlandscapelistAction (){		
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );		
    	$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
    	$idlandscape = $this->_getParam('idlandscape', 0);
    	$LandscapeType = $this->_getParam('LandscapeType');
    	$this->view->LandscapeType = $LandscapeType;
    	if($LandscapeType ==43 ) {
    	
    	$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
    	$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$larrLandscapeType=$this->lobjlandscape->fnLandscapeTypeList($IdProgram);
		$this->view->DefinitionDesc = $larrLandscapeType['DefinitionDesc'];
		$this->view->SemesterMasterName = $larrLandscapeType['SemesterMasterName'].'-'.$larrLandscapeType['year'];
		$this->view->SemsterCount = $larrLandscapeType['SemsterCount'];
		if( $larrLandscapeType['Active']== 1){
			$this->view->Active = "Active";
		}else {
			$this->view->Active = "In-Active";
		}
	
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeprogSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		
	    $this->view->idlandscape = $this->_getParam('idlandscape', 0);
	    $larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape); // Get the Item Sales details
	    $larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape); // Get the Item Sales details
	    
	    if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
        	$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        	$this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
	    }

		if($this->view->idlandscape) {
			$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
			$result = $result->toArray();
			foreach($result as $landscaperesult) {	
				$this->view->LandscapeType = $landscaperesult['LandscapeType'];	
			}
			$this->view->lobjlandscapeform->populate($landscaperesult);	
			if($this->_getParam('update')!= 'true') {
	  			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
	  			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
	  		}
		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
    	}else{//Block Based
 
    	$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
    	
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}
    	$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );	
    	$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
	
		$larrLandscapeType=$this->lobjlandscape->fnLandscapeTypeList($IdProgram);
		
		//print_r( $larrLandscapeType['SemesterMasterName'].'-'.$larrLandscapeType['year']);die();
		$this->view->DefinitionDesc = 'Block Based';
		$this->view->SemesterMasterName = $larrLandscapeType['SemesterMasterName'].'-'.$larrLandscapeType['year'];
		$this->view->SemsterCount = $larrLandscapeType['SemsterCount'];
		if( $larrLandscapeType['Active']== 1){
		$this->view->Active = "Active";
			
		}else{
			$this->view->Active = "In-Active";
			
		}
		
		//Get LandScapeBlock Details
		$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
		$this->view->larrLandScapeBlock = $larrLandScapeBlock;

		//Get LandScapeBlockSubject Details
		$larrLandScapeBlockSubject = $this->lobjlandscape->fnGetLandScapeBlockSubjectDtls($idlandscape);
		
		$this->view->larrLandScapeBlockSubject = $larrLandScapeBlockSubject;
		
		//Get LandScapeBlockSemester Details
		$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
		$this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;
		
		}		
    }
    

    

    
  
    
    
    
	public function deleteprogramrequrimentsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
						
		$IdTempProgReq = $this->_getParam('IdTempProgReq');
		
		$larrDelete = $this->lobjlandscape->fnUpdateTempProgramRequriments($IdTempProgReq);	
		echo "1";
	}

	
	public function deletesubjectprerequisitesAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
						
		$IdTempLandscapesub = $this->_getParam('IdTempLandscapesub');
		
		$larrDelete = $this->lobjlandscape->fnUpdateTempLandscapesubject($IdTempLandscapesub);	
		echo "1";
	}
	
	public function fngetsubjectcredithoursAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintidsubject = $this->_getParam('idsubject');
		$larrresult = $this->lobjsubjectmaster->fnviewSubject($lintidsubject);
		echo $larrresult['CreditHours'];	
	}
	
	public function getblocklistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$Blockcount = $this->_getParam('Blockcount');
		if($this->view->BlockType == 1){
		 $value = $this->view->BlockName.$this->view->BlockSeparator;
		}

		for($i=1;$i<=$Blockcount;$i++) {
		if($this->view->BlockType == 1){
					$block[] = array("name"=>"$value"."$i","key"=>"$i");
		}else
			$block[] = array("name"=>"$i","key"=>"$i");
		}
		echo Zend_Json_Encoder::encode($block);
	}
	
	public function semlistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$SemsterCount= $this->_getParam('SemsterCount');
		for($i=1;$i<=$SemsterCount;$i++) {
			$block[] = array("name"=>"$i","key"=>"$i");
		}
		echo Zend_Json_Encoder::encode($block);
	}
	
	public function deleteblockAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();				
		$IdLandscapetempblock = $this->_getParam('IdLandscapetempblock');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblock($IdLandscapetempblock);
		echo "1";
	}
	
	public function deleteblocksubAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();				
		$IdLandscapetempblocksubject = $this->_getParam('IdLandscapetempblocksubject');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblocksub($IdLandscapetempblocksubject);	
		echo "1";
	}
	
	public function deleteblocksemAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();				
		$IdLandscapetempblocksemester = $this->_getParam('IdLandscapetempblocksemester');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblocksem($IdLandscapetempblocksemester);	
		echo "1";
	}
	
	public function activeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();	
		$idlandscape = $this->_getParam('idlandscape');
		$IdProgram = $this->_getParam('idprogram');
		$this->lobjlandscape->fnupdateLandscapeActiveaction($idlandscape);
		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
		
	}
	
	public function inactiveAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();	
		$idlandscape = $this->_getParam('idlandscape');
		$IdProgram = $this->_getParam('idprogram');
		$this->lobjlandscape->fnupdateLandscapeInActiveaction($idlandscape);
		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
		
	}
	
	public function fngetsubjectpreAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();	
		$idsubject = $this->_getParam('idsubject');
		$subprereq = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$result = $subprereq->fnViewSubjectPrerequisitesDetails($idsubject);
		echo Zend_Json_Encoder::encode($result);
				
	}
}