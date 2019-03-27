<?php
class GeneralSetup_CoursemasterController extends Base_Base {
	private $lobjprogrammaster;
	private $lobjcoursemasterForm;
	private $_gobjlog;
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    
	}
	
	public function fnsetObj(){
		$this->lobjcoursemaster = new GeneralSetup_Model_DbTable_Coursemaster();
		$this->lobjcoursemasterForm = new GeneralSetup_Form_Coursemaster (); //intialize user lobjuniversityForm
		
	}
	
	public function indexAction() {
    	$this->view->title="Course Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$larrresult = $this->lobjcoursemaster->fngetCoursemasterDetails (); //get user details
		
		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->coursemasterpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->coursemasterpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->coursemasterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjcoursemaster->fnSearchCourse ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->coursemasterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'coursemaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/coursemaster/index');
		}
		
	}
	
	public function newcourseAction() { //title
    	$this->view->title="Add New Course";
		
		$this->view->lobjcoursemasterForm = $this->lobjcoursemasterForm;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjcoursemasterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjcoursemasterForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
				unset ( $formData ['Save'] );
				unset ( $formData ['Back'] );
				$this->lobjcoursemaster->fnaddCourse($formData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Course Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'coursemaster', 'action'=>'index'),'default',true));	//redirect
				$this->_redirect( $this->baseUrl . '/generalsetup/coursemaster/index');	
        }     
    }
    
	public function editcourseAction(){
    	$this->view->title="Edit Course";  //title
		$this->view->lobjcoursemasterForm = $this->lobjcoursemasterForm; //send the lobjuniversityForm object to the view
		$IdCourse = $this->_getParam('id', 0);
    	$result = $this->lobjcoursemaster->fetchAll('IdCoursemaster ='.$IdCourse);
    	$result = $result->toArray();
		foreach($result as $courseresult){
		}
    	$this->lobjcoursemasterForm->populate($courseresult);

    	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjcoursemasterForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjcoursemasterForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjcoursemasterForm->isValid($formData)) {
	   			$lintIdCoursemaster = $formData ['IdCoursemaster'];
				$this->lobjcoursemaster->fnupdateCourse($formData,$lintIdCoursemaster);//update university
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Course Edit Id=' . $IdCourse,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'coursemaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/coursemaster/index');
			}
    	}
    }
}