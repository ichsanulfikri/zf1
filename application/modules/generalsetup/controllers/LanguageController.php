<?php
class GeneralSetup_LanguageController extends Base_Base {
	private $lobjlanguage;
	private $lobjlonguageForm;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		   
	}
	
	public function fnsetObj(){
		$this->lobjlanguage = new GeneralSetup_Model_DbTable_Language();
		$this->lobjlonguageForm = new GeneralSetup_Form_Language(); //intialize user lobjuniversityForm
		
	}
	
	public function indexAction() {
    	$this->view->title="Language";
    	$languageid = $this->view->Languageid;
    	
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$larrresult = $this->lobjlanguage->fngetLanguageDetails($languageid); //get user details
		
		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->languagepaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->languagepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->languagepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				
				$larrresult = $this->lobjlanguage->fnSearchLang ( $this->lobjform->getValues (), $languageid ); //searching the values for the user
				
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->languagepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'Language', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/language/index');
		}
		
	}
	
	public function newlangAction() { //title
		     

    	$this->view->title="Add New Course";
		$this->view->lobjlonguageForm = $this->lobjlonguageForm;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlonguageForm->createddt->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlonguageForm->createdby->setValue( $auth->getIdentity()->iduser);
		$this->view->lobjlonguageForm->Language->setValue( $this->view->Languageid);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjlonguageForm->isValid ( $formData )) {
					unset ( $formData ['Save'] );
					unset ( $formData ['Back'] );
					$larrresult = $this->lobjlanguage->fnaddLang($formData);
					
					// Write Logs
					$priority=Zend_Log::INFO;
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
									  'level' => $priority,
									  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
					                  'time' => date ( 'Y-m-d H:i:s' ),
					   				  'message' => 'New Language Add',
									  'Description' =>  Zend_Log::DEBUG,
									  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'Landscape', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/language/index');
			}
		}
    }
    
	public function editlangAction(){
    	$this->view->title="Edit Language";  //title
		$this->view->lobjlonguageForm = $this->lobjlonguageForm; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlonguageForm->createddt->setValue ( $ldtsystemDate );		
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlonguageForm->createdby->setValue( $auth->getIdentity()->iduser);
    	$IdLang = $this->_getParam('id');
    	$result = $this->lobjlanguage->fnGetlang($IdLang);
    	$this->lobjlonguageForm->populate($result);	
    	$this->lobjlonguageForm->system->removeValidator ('Db_NoRecordExists' );
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjlonguageForm->isValid($formData)) {
	   			$lintIdLanguage = $formData ['id'];
				$this->lobjlanguage->fnupdateLanguage($formData,$lintIdLanguage);//update university
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Language Edit Id=' . $IdLang,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'language', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/language/index');
			}
    	}
    }
}