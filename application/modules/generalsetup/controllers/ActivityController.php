<?php
class GeneralSetup_ActivityController extends Base_Base {
	private $lobjActivity;
	private $_gobjlog;
	private $lobjActivityForm;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);		
	}
	public function fnsetObj(){	
		$this->lobjform = new App_Form_Search ();
		$this->lobjActivityForm = new GeneralSetup_Form_Activity();
		$this->lobjActivity = new GeneralSetup_Model_DbTable_Activity();			
    }

	//Index Action
	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult = $this->lobjActivity->fngetActivityDetails (); //get Activity Details
//		echo '<pre>';
//		var_dump($larrresult);die;
		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->activitypaginatorresult);
		$lintpagecount =$this->gintPageCount;// Definitiontype model
		
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		if(isset($this->gobjsessionsis->activitypaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->activitypaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}	
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjActivity->fnSearchActivity( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->activitypaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {			
			$this->_redirect( $this->baseUrl . '/generalsetup/activity/index');
		}
	}
	
	//Action to add new activity
	public function newactivityAction(){
		$this->view->lobjActivityForm = $this->lobjActivityForm;	
		//$this->view->lobjActivityForm->ActivityName->setAttrib('validator', 'validateUsername');
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjActivityForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjActivityForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjactivityForm data from post
			unset ( $larrformData ['Save'] );
			if ($this->lobjActivityForm->isValid ( $larrformData )) {
				$result = $this->lobjActivity->fnaddActivity($larrformData); //instance for adding the lobjactivityForm values to DB
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Activity Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/activity/index');
			}
		}
	}

	public function editactivityAction() {
		
		$this->view->lobjActivityForm = $this->lobjActivityForm; //send the lobjActivityForm object to the view	
    	$IdActivity = $this->_getParam('id', 0);
    	$result = $this->lobjActivity->fetchAll('IdActivity ='.$IdActivity);        
    	$result = $result->toArray();       
    	foreach($result as $activityresult){
    		$this->lobjActivityForm->populate($activityresult);
    	}
		//$this->lobjActivityForm->populate($activityresult);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjActivityForm->IdActivity->setValue( $result[0]['idActivity'] );
		$this->view->lobjActivityForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjActivityForm->UpdUser->setValue( $auth->getIdentity()->iduser);
	    if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjActivityForm->isValid($formData)) {
				$lintIdActivity = $formData ['IdActivity'];
				$this->lobjActivity->fnupdateActivity($formData,$lintIdActivity);//update Activity
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Activity Edit Id=' . $IdActivity,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semester', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/activity/index');
			}
	    }
	}

   public function deleteactivityAction() {
		$this->view->lobjActivityForm = $this->lobjActivityForm;	
   		//$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender();
		$idActivity = $this->_getParam('id', 0);
		$larrDelete = $this->lobjActivity->fnDeleteActivity($idActivity);	
		echo "1";
		$this->_redirect( $this->baseUrl . '/generalsetup/activity/index');
  }
   
}


	