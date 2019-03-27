<?php
class GeneralSetup_SubjectwithdrawalpolicyController extends Base_Base {
	
	private $locale;
	private $registry;
	private $Subjectwithdrawalpolicyform;
	private $Subjectwithdrawalpolicy;
	private $_gobjlog;
	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');		
	}
	
	
	public function fnsetObj(){
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();		
		$this->Subjectwithdrawalpolicyform = new GeneralSetup_Form_Subjectwithdrawalpolicy();
		$this->Subjectwithdrawalpolicy = new GeneralSetup_Model_DbTable_Subjectwithdrawalpolicy();			
	}
	
	//Index Action
	public function indexAction() {
		
		$this->view->lobjform = $this->lobjform; 
		
		$larrresult = $this->Subjectwithdrawalpolicy->fnGetSubjectDetails();
		
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
				
				$larrResult = $this->Subjectwithdrawalpolicy->fnSearchSubject($this->lobjform->getValues ());
		    	$this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->Checklistpaginatorresult = $larrResult;	
			
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectwithdrawalpolicy/index');
		
		}
	}
	
	
	public function subjectwithdrawalpolicyAction(){
		
		$this->view->lobjSubjectwithdrawalpolicyform= $this->Subjectwithdrawalpolicyform;
		
		$lintIdSubject = ( int ) $this->_getParam ( 'id' );
		
		$this->view->Idsubject = $lintIdSubject;
		$this->view->lobjSubjectwithdrawalpolicyform->IdSubject->setValue($lintIdSubject);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSubjectwithdrawalpolicyform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjSubjectwithdrawalpolicyform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrSemesterlist=$this->Subjectwithdrawalpolicy->fnGetSemesterList($lintIdSubject);
		$this->Subjectwithdrawalpolicyform->IdSemester->addMultiOptions($larrSemesterlist);
		
		$larrSubjectlist=$this->Subjectwithdrawalpolicy->fnGetSubjectList($lintIdSubject);
		
		$this->view->SubName	= $larrSubjectlist['SubjectName'] ;
		$this->view->SubCode	= $larrSubjectlist['SubCode'] ;
		
		$larrresult = $this->Subjectwithdrawalpolicy->fnViewSubjectwithdrawalpolicylist($lintIdSubject);
		
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Subjectwithdrawalpolicyform->isValid($larrFormData)) {		
					unset($larrFormData['Save']);	
					
				$this->Subjectwithdrawalpolicy->fnDeleteSubjectwithdrawalpolicy($larrFormData['IdSubject']);		
				$lastinsertId=$this->Subjectwithdrawalpolicy->fnaddSubjectwithdrawalpolicy($larrFormData);	
				
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Subject withdrawal policy Add id =' .$lintIdSubject ,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
			   $this->_redirect( $this->baseUrl . '/generalsetup/subjectwithdrawalpolicy/subjectwithdrawalpolicy/id/'.$larrFormData['IdSubject']);
			}
			
		}
		
	}
	
	
	public function subjectwithdrawalpolicyeditAction() { //Action for the updation and view of the  details
		
		$this->view->lobjSubjectwithdrawalpolicyform= $this->Subjectwithdrawalpolicyform;
		
		$lintSubjectwithdrawalpolicy = ( int ) $this->_getParam ( 'id' );
		
		$this->view->lobjSubjectwithdrawalpolicyform->IdSubjectWithdrawalPolicy->setValue( $lintSubjectwithdrawalpolicy );	
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSubjectwithdrawalpolicyform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjSubjectwithdrawalpolicyform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
	
		
		$larrSemesterlist=$this->Subjectwithdrawalpolicy->fnGetSemesterList();
		$this->Subjectwithdrawalpolicyform->IdSemester->addMultiOptions($larrSemesterlist);
		
		$larrresult = $this->Subjectwithdrawalpolicy->fnGetSubjectwithdrawalpolicylist($lintSubjectwithdrawalpolicy);
		
		$this->view->lobjSubjectwithdrawalpolicyform->IdSemester->setValue( $larrresult['IdSemester'] );
		$this->view->lobjSubjectwithdrawalpolicyform->Days->setValue( $larrresult['Days'] );
		$this->view->lobjSubjectwithdrawalpolicyform->Percentage->setValue( $larrresult['Percentage'] );
		
		if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->view->lobjSubjectwithdrawalpolicyform->isValid($formData)) {
	    		unset($formData['Save']);
	   			$lintSubjectWithdrawalPolicy = $formData ['IdSubjectWithdrawalPolicy'];	   				   			
				$this->Subjectwithdrawalpolicy->fnupdateSubjectWithdrawalPolicy($formData,$lintSubjectWithdrawalPolicy);//update university
				
				
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semestermaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectwithdrawalpolicy/index');
			}
    	}
	
	}
	
	
	
}

?>