<?php
class GeneralSetup_ProgramchecklistController extends Base_Base {
	private $locale;
	private $registry;
	private $Programchecklistform;
	private $Programchecklist;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');		
	}
	
	public function fnsetObj(){
		$this->Programchecklistform = new GeneralSetup_Form_Programchecklist();
		$this->Programchecklist = new GeneralSetup_Model_DbTable_Programchecklist();		
	}
  	
  	//Index Action
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		
		$larrresult = $this->Programchecklist->fnGetProgramDetails();
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->Checklistpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->Checklistpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->Checklistpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		//Maintenance Search 
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrFormData = $this->_request->getPost();
			if ($this->lobjform->isValid($larrFormData)) {
				$larrResult = $this->Programchecklist->fnSearchProgram($this->lobjform->getValues ());
		    	$this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->Checklistpaginatorresult = $larrResult;	
			
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/programchecklist/index');
		
		}
	}
	
	public function programchecklistAction() { //Action for the updation and view of the  details
		
		$this->view->lobjProgramchecklistform= $this->Programchecklistform;

		$lintIdProgram = ( int ) $this->_getParam ( 'id' );
		$this->view->Idprogram = $lintIdProgram;
		$this->view->lobjProgramchecklistform->IdProgram->setValue($lintIdProgram);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjProgramchecklistform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjProgramchecklistform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrresult = $this->Programchecklist->fnViewProgramchecklist($lintIdProgram);
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Programchecklistform->isValid($larrFormData)) {
				unset($larrFormData['Save']);
				$this->Programchecklist->fnaddChecklistname($larrFormData);		

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Program Check List add Id=' . $lintIdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/programchecklist/programchecklist/id/'.$larrFormData['IdProgram']);
			}
			
		}

	}
	
	public function programchecklisteditAction() { //Action for the updation and view of the  details
		
		$this->view->lobjProgramchecklistform= $this->Programchecklistform;

		$lintIdProgram = ( int ) $this->_getParam ( 'id' );
		$this->view->Idprogram = $lintIdProgram;
		
		$lintIdCheckList = ( int ) $this->_getParam ( 'IdCheckList' );
		$this->view->IdCheckList = $lintIdCheckList;
        $auth = Zend_Auth::getInstance();
		$larrEditResult = $this->Programchecklist->fnviewChecklistDtls($lintIdCheckList);
		$this->Programchecklistform->populate($larrEditResult);
		
		//$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		//$this->view->lobjProgramchecklistform->UpdDate->setValue( $ldtsystemDate );
		//$auth = Zend_Auth::getInstance();
		//$this->view->lobjProgramchecklistform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrresult = $this->Programchecklist->fnViewProgramchecklist($lintIdProgram);
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Programchecklistform->isValid($larrFormData)) {
				unset($larrFormData['Save']);
				$result=$this->Programchecklist->fnupdateChecklistDtls($lintIdCheckList,$larrFormData );

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Program Check List edit Id=' . $lintIdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/programchecklist/programchecklist/id/'.$larrFormData['IdProgram']);
			}
			
		}

	}
	
}