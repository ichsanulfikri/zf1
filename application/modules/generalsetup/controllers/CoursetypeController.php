<?php
class GeneralSetup_CoursetypeController extends Base_Base {
	private $locale;
	private $registry;
	private $Coursetypeform;
	private $Coursetype;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');	
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object	
	}
	
	public function fnsetObj(){
		$this->Coursetypeform = new GeneralSetup_Form_Coursetype();
		$this->Coursetype = new GeneralSetup_Model_DbTable_Coursetype();	
	}
	
	public function indexAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjform = $this->lobjform; 
		$larrresult = $this->Coursetype->fngetCourseTypeDetails();
		//echo "<pre>";print_r($larrresult);die();
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->Coursetypepaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->Coursetypepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->Coursetypepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		 //$this->view->lobjform->field14->addMultiOption(array('0' => 'No','1' => 'Yes'));
		 $this->view->lobjform->field21->addMultiOptions(array('0' => 'No','1' => 'Yes'));
		 
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->Coursetype->fnSearchCourseType( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->Coursetypepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/coursetype/index');
		}
		
	}
	
	public function newcoursetypeAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjCoursetypeform= $this->Coursetypeform;
		
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjCoursetypeform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjCoursetypeform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->Coursetypeform->isValid ( $larrformData )) {
				$this->Coursetype->fnaddCoursetype($larrformData);	

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Course Type Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/coursetype/index');
			}
		}

	}
    
	public function coursetypelistAction() { //Action for the updation and view of the  details
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjCoursetypeform= $this->Coursetypeform;
		 
		$lintIdCourseType = ( int ) $this->_getParam ( 'id' );
		$this->view->IdCourseType = $lintIdCourseType;
		

		
		$larrResult = $this->Coursetype->fnviewCourseTypeDtls($lintIdCourseType);
		$this->Coursetypeform->populate($larrResult);
		
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjCoursetypeform->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjCoursetypeform->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if ($this->Coursetypeform->isValid ( $larrformData )) {
					$lintIdCourseType = $larrformData ['IdCourseType'];
					$result=$this->Coursetype->fnupdateCourseTypeDtls($lintIdCourseType, $larrformData );
					
						// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Course Type Edit Id=' . $lintIdCourseType,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/coursetype/index');
				}
			}
		}
		$this->view->lobjCoursetypeform= $this->Coursetypeform;
	}
	
}