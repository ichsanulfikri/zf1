<?php
class GeneralSetup_SemestermasterController extends Base_Base {
	private $lobjsemestermaster;
	private $lobjsemestermasterForm;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
	}
	
	public function fnsetObj(){
		$this->lobjsemestermaster = new GeneralSetup_Model_DbTable_Semestermaster();
		$this->lobjsemestermasterForm = new GeneralSetup_Form_Semestermaster(); //intialize user lobjuniversityForm
		
	}
	
	public function indexAction() {
    	$this->view->title="Semester Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$larrresult = $this->lobjsemestermaster->fngetSemestermainDetails (); //get user details
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->semetsermasterpaginatorresult);
		$lintpagecount =$this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		if(isset($this->gobjsessionsis->semetsermasterpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->semetsermasterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjsemestermaster->fnSearchSemester ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->semetsermasterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'semestermaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/semestermaster/index');
		}
		
	}
	
	public function newsemesterAction() { //title
    	$this->view->title="Add New Semester";
		
		$this->view->lobjsemestermasterForm = $this->lobjsemestermasterForm;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsemestermasterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsemestermasterForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
				unset ( $formData ['Save'] );
				unset ( $formData ['Back'] );
				$this->lobjsemestermaster->fnaddSemester($formData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Semester master Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semestermaster', 'action'=>'index'),'default',true));	//redirect
				$this->_redirect( $this->baseUrl . '/generalsetup/semestermaster/index');	
        }     
    }
    
	public function editsemesterAction(){
    	$this->view->title="Edit Semester";  //title
		$this->view->lobjsemestermasterForm = $this->lobjsemestermasterForm; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsemestermasterForm->UpdDate->setValue ( $ldtsystemDate );		
    	$IdSemesterMaster = $this->_getParam('id', 0);
    	$result = $this->lobjsemestermaster->fetchAll('IdSemesterMaster ='.$IdSemesterMaster);
    	$result = $result->toArray();
		foreach($result as $courseresult){
		}
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsemestermasterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsemestermasterForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		
    	$this->lobjsemestermasterForm->populate($courseresult);	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjsemestermasterForm->isValid($formData)) {
	   			$lintIdSemesterMaster = $formData ['IdSemesterMaster'];
				$this->lobjsemestermaster->fnupdateSemester($formData,$lintIdSemesterMaster);//update university
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Semester master Edit Id=' . $IdSemesterMaster,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semestermaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/semestermaster/index');
			}
    	}
    }
}