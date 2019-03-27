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
	private $lobjprogramblock;
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
		$this->lobjprogramblock = new GeneralSetup_Model_DbTable_Landscapeblock();
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
				$larrresult = $this->lobjprogram->fnSearchProgram ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				@$this->gobjsessionsis->landscapepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'coursemaster', 'action'=>'index'),'default',true));
		}
		
	}
	
	
	public function addlandscapeAction() {
		$this->view->lobjform = $this->lobjform;
		$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName']; 
		}

		$sessionID = Zend_Session::getId();
        $this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
        $this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
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

    
	public function editlandscapeAction(){
		
    	$this->view->title="Edit Course";  //title
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );		
    	$IdProgram = $this->_getParam('id', 0);
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
		
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
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
			
			$this->view->blocks = $lintsemestercount;

			for($i=1;$i<=$lintsemestercount;$i++) {
				$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
			}
			
			for($i=1;$i<=$lintsemestercount;$i++) {
				$this->lobjlandscapeform->block->addMultiOption($i,$i);
			}
			
		$larrsemesterresult = $this->lobjsemester->fngetSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->semesterid->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
		}
			
			$this->view->lobjlandscapeform->populate($landscaperesult);	
		}
		
		$this->view->Idblock = $this->_getParam('Idblock', 0);
		if($this->view->Idblock) {
			$this->view->blockreqresult = $this->lobjlandscape->fneditLandblock($this->view->idlandscape);
		}

	
		$this->view->IdProgramReq = $this->_getParam('IdProgramReq', 0);
		if($this->view->IdProgramReq) {
			$this->view->programreqresult = $this->lobjlandscape->fneditLandscape($this->view->idlandscape);
		}
		
		
		
		
		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		if(!$formData['IdLandscape']) {
	    			if($formData['Active'] == 1){	
	    				$IdProgram = $formData['IdProgram'];	
	    				$this->lobjlandscape->fnupdateLandscapeActive($formData,$IdProgram);
	    			}
	    		}

	    		
	    		if(!$formData['IdLandscape']) {
					$landscapeid = $this->lobjlandscape->fnaddLandscape($formData);
					$this->_redirect( $this->baseUrl . '/generalsetup/landscape/editlandscape/id/'.$IdProgram.'/idlandscape/'.$landscapeid);
	    		}
	    		
	    		
	    		$landscapeid = $formData['IdLandscape'];
	    		$LandscapeType = $formData['LandscapeType'];
	    		


	    		
	    		if($LandscapeType == 44 && !$formData['IdLandscape']) {
	    			$idblock = $this->lobjprogramblock->fnaddlandscapeblock($formData);
	    			$this->_redirect( $this->baseUrl . '/generalsetup/landscape/editlandscape/id/'.$IdProgram.'/idlandscape/'.$landscapeid.'/Idblock/'.$idblock);
	    			
	    		}

	    		
				if(!$formData['IdProgramReq']) {
	    		$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirement($formData);
	    		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/editlandscape/id/'.$IdProgram.'/idlandscape/'.$landscapeid.'/IdProgramReq/'.$IdProgramReq);
				}
				
				$this->lobjlandscapesubject->fnaddLandscapesubject($formData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Semester Landscape Add id='.$IdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');
				
			}
    	}
    }
    
    public function editlandscapelistAction (){		
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );		
    	$IdProgram = $this->_getParam('id', 0);
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
		
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMasterName'].'-'.$larrsemesterresult['year']);
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
		
		

		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlandscapeform->isValid($formData)) {
	    		$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester']);	
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
}