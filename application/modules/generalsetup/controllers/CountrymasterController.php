<?php
class GeneralSetup_CountrymasterController extends Base_Base { //Controller for the User Module	
	private $lobjCountrymaster;
	private $lobjCountrymasterForm;
	private $lobjdeftype;
	private $_gobjlog;
	public function init() 
	{
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis'); 
		Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->fnsetObj();
	}
	public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search (); //searchform
		$this->lobjCountrymaster = new GeneralSetup_Model_DbTable_Countrymaster();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjCountrymasterForm = new GeneralSetup_Form_Countrymaster(); 
	}
	public function indexAction() { // action for search and view
		$this->view->lobjform = $this->lobjform; //send the lobjuserForm object to the view
		
		$larrresult = $this->lobjCountrymaster->fngetCountryDetails (); //get user details
		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->countrymasterpaginatorresult);
		
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionsis->countrymasterpaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->countrymasterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjCountrymaster->fnSearchCountries( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->countrymasterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/countrymaster/index');
		}


	}

	public function newcountrymasterAction() { //Action for creating the new user
		$this->view->lobjCountrymasterForm = $this->lobjCountrymasterForm; 
		$this->view->lobjCountrymasterForm->CountryName->setAttrib('validator', 'validateUsername');
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Language');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjCountrymasterForm->DefaultLanguage->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjCountrymasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjCountrymasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->lobjCountrymasterForm->isValid ( $larrformData )) {
				$result = $this->lobjCountrymaster->fnaddCountrymaster($larrformData); //instance for adding the lobjuserForm values to DB
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New country Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/countrymaster/index');
			}
		}

	}

	public function countrymasterlistAction() { //Action for the updation and view of the user details
		$this->view->lobjCountrymasterForm = $this->lobjCountrymasterForm; 
		
		$lintidCountry = ( int ) $this->_getParam ( 'id' );
		$this->view->idCountry = $lintidCountry;
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Language');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjCountrymasterForm->DefaultLanguage->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$larrresult = $this->lobjCountrymaster->fnviewCountrymaster($lintidCountry); //getting user details based on userid
		$this->lobjCountrymasterForm->populate($larrresult);	
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjCountrymasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjCountrymasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$this->view->lobjCountrymasterForm->idCountry->setValue ( $lintidCountry );
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			$language = $this->lobjCountrymaster->fngetlanguage($larrformData['DefaultLanguage']);
			$countryId = $this->lobjCountrymaster->fngetCountryId($this->gobjsessionsis->idUniversity);
			if($countryId['Country'] == $larrformData ['idCountry']){
			$this->gobjsessionsis->UniversityLanguage = $language['DefinitionDesc'];
			}
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if ($this->lobjCountrymasterForm->isValid ( $larrformData )) {
						
					$lintidCountry = $larrformData ['idCountry'];
					$this->lobjCountrymaster->fnupdateCountrymaster($lintidCountry, $larrformData );
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Country Edit id=' . $lintidCountry,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/countrymaster/index');
				}
			}
		}
		$this->view->lobjCountrymasterForm = $this->lobjCountrymasterForm;
	}
	public function getcountrynameAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();		
		$ContryName = $this->_getParam('ContryName');	
		$larrDetails = $this->lobjCountrymaster->fngetcountryname($ContryName);
		echo $larrDetails['CountryName'];
		
	}
	
}