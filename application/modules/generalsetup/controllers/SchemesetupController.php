<?php
class GeneralSetup_SchemesetupController extends Base_Base {//controller for a schemesetup
	private $_gobjlog;

public function init() { //initialization function
		$this->locale = Zend_Registry::get('Zend_Locale');
		$this->view->translate =Zend_Registry::get('Zend_Translate');
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->lobjform = new App_Form_Search ();  //local object for Search Form
		$this->lobjschemesetupmodel = new GeneralSetup_Model_DbTable_Schemesetup();  //local object for Schemesetup Model
        $this->gobjsessionsis = Zend_Registry::get('sis');
        $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
	}

public function indexAction() { //Index Action
	    $this->view->lobjform = $this->lobjform;
	    $lobjPaginator = new App_Model_Common(); // Definitiontype model
	    $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
        $larrresult =$this->lobjschemesetupmodel->fnGetSchemeDetails(); //function to display all schemesetup details in list
		if(!$this->_getParam('search'))
   	    unset($this->gobjsessionstudent->schemedetailspaginationresult);
   	    $lintpagecount = $this->gintPageCount;
        $lintpage = $this->_getParam('page',1); // Paginator instance
        if(isset($this->gobjsessionstudent->schemedetailspaginationresult)) {
		$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->schemedetailspaginationresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
		$larrformData = $this->_request->getPost ();
		    if ($this->lobjform->isValid ( $larrformData )) {
		     $larrresult = $this->lobjschemesetupmodel->fnSearchscheme( $this->lobjform->getValues () ); // Function to Search schemesetup Details
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->schemedetailspaginationresult = $larrresult;
			 }
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/schemesetup/index');
		}
  }

public function newschemesetupAction(){// Action to add schemesetup details
		$lobjSchemesetupForm = new GeneralSetup_Form_Schemesetup();
		$this->view->lobjSchemesetupForm = $lobjSchemesetupForm;
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSchemesetupForm->UpdDate->setValue( $ldtsystemDate );
        $auth = Zend_Auth::getInstance();
		$this->view->lobjSchemesetupForm->UpdUser->setValue( $auth->getIdentity()->iduser);

        // start commented on 190712-sp4-10998
        //$mode=$this->lobjschemesetupmodel->fnGetMode();//function to get a type of mode
	    //$lobjSchemesetupForm->Mode->addMultiOptions($mode);
		//end

	   if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$formData = $this->_request->getPost ();
			if ($lobjSchemesetupForm->isValid ( $formData )) {
			 $this->lobjschemesetupmodel->fnInsertToDb($formData);//function to insert data to database

			 // Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Scheme Setup Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
			 $this->_redirect( $this->baseUrl . '/generalsetup/schemesetup/index');	//to redirect to index page
			}
		}
	}

public function schemesetuplistAction(){
	    $IdScheme = $this->_getParam('id');
		$lobjSchemesetupForm = new GeneralSetup_Form_Schemesetup();
		$this->view->lobjSchemesetupForm = $lobjSchemesetupForm;
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSchemesetupForm->UpdDate->setValue( $ldtsystemDate );
        $auth = Zend_Auth::getInstance();
		$this->view->lobjSchemesetupForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		// start commented on 190712-sp4-10998
        //$mode=$this->lobjschemesetupmodel->fnGetMode();//function to get a type of mode
	    //$lobjSchemesetupForm->Mode->addMultiOptions($mode);
	    //end
	    $larrresult = $this->lobjschemesetupmodel->fnViewSchemeDetails($IdScheme);//function to find the data to populate in a page of a selected english description to edit.
	    $this->view->lobjSchemesetupForm->populate($larrresult);
	    if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
    		 	$formData = $this->getRequest()->getPost();
    		 	if ($lobjSchemesetupForm->isValid ( $formData )) {
    		 	$IdScheme= $formData ['IdScheme'];
				$this->lobjschemesetupmodel->fnupdateSchemeDetails($formData,$IdScheme);//function to update data

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Scheme Setup Edit Id=' . $IdScheme,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				$this->_redirect( $this->baseUrl . '/generalsetup/schemesetup/index');
    		 	}
        }
	   if ($this->_request->getPost ( 'Back' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/schemesetup/index');
		}
	}
}