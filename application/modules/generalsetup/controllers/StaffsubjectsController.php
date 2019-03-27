<?php
class GeneralSetup_StaffsubjectsController extends Base_Base {
	
	private $locale;
	private $registry;
	private $Staffsubjectsform;
	private $Staffsubjects;
	private $lobjsemestermaster;
	private $Subjectwithdrawalpolicy;
	private $_gobjlog;
	
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');		
	}
		
	public function fnsetObj(){
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();		
		$this->Staffsubjectsform = new GeneralSetup_Form_Staffsubjects();
		$this->Staffsubjects = new GeneralSetup_Model_DbTable_Staffsubjects();	
		$this->lobjsemestermaster = new GeneralSetup_Model_DbTable_Semestermaster();			
	}
	
	//Index Action
	public function indexAction() {
		
		$this->view->lobjform = $this->lobjform; 		
		$larrresult = $this->Staffsubjects->fnGetStaffDetails();		
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->Staffsubjectpaginatorresult);	   	
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if(isset($this->gobjsessionsis->Staffsubjectpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->Staffsubjectpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		//Maintenance Search 
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrFormData = $this->_request->getPost();
			if ($this->lobjform->isValid($larrFormData)) {				
				$larrResult = $this->Staffsubjects->fnSearchStaff($this->lobjform->getValues ());
		    	$this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->Staffsubjectpaginatorresult = $larrResult;				
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {			
			$this->_redirect( $this->baseUrl . '/generalsetup/staffsubjects/index');		
		}
	}
	
	public function staffsubjectslistAction(){
		
		$this->view->Staffsubjectsform = $this->Staffsubjectsform;
		
		$lintIdStaff = ( int ) $this->_getParam ( 'id' );
		$lvarstaffname = $this->_getParam ( 'name' );
		
		$this->view->IdStaff = $lintIdStaff;
		$this->view->stafname = $lvarstaffname;
		
		$this->view->Staffsubjectsform->IdStaff->setValue($lintIdStaff);
		$ldtsystemDate = date ( 'Y-m-d' );
		$this->view->Staffsubjectsform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->Staffsubjectsform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$lobjsemester = $this->Staffsubjects->fnGetSemesterList();
		$this->view->Staffsubjectsform->IdSemester->addMultiOptions($lobjsemester);
		
		$lobjsubjects = $this->Staffsubjects->fnGetSubjectList();
		$this->view->Staffsubjectsform->IdSubject->addMultiOptions($lobjsubjects);
		
		$larrresult = $this->Staffsubjects->fnViewStaffsubjectlist($lintIdStaff);
		
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Staffsubjectsform->isValid($larrFormData)) {		
				
					unset($larrFormData['Save']);						
				$this->Staffsubjects->fnDeleteStaffsubjects($larrFormData['IdStaff']);		
				$lastinsertId=$this->Staffsubjects->fnaddStaffsubjects($larrFormData);

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Staff Subject add Id=' . $lintIdStaff,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log				
				
			    $this->_redirect( $this->baseUrl . '/generalsetup/staffsubjects/');
			}
			
		}
		
	}
		
}

?>