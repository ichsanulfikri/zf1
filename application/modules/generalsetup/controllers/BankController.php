<?php
class GeneralSetup_BankController extends Base_Base { 
	private $lobjBank;
	private $lobjBankForm;
	private $lobjStaffmaster;
	private $lobjinitialconfigModel;
	private $_gobjlog;
		
	public function init() { //initialization function
		
		
		$this->locale = Zend_Registry::get('Zend_Locale');
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->_gobjlog = Zend_Registry::get ( 'log' );
   	    $this->fnsetObj();
		
	}
 	public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search (); //searchform
		$this->lobjBank = new GeneralSetup_Model_DbTable_Bank();
		$this->lobjBankForm = new GeneralSetup_Form_Bank();
		$this->lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->lobjinitialconfigModel = new GeneralSetup_Model_DbTable_Initialconfiguration();
		$this->gobjsessionsis = Zend_Registry::get('sis'); 
		
	}
	
   	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult =$this->lobjBank->fnGetBankDetails(); 
		  if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionstudent->bankpaginatorresult); 
   	    
   	    $lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		
   		if(isset($this->gobjsessionstudent->bankpaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->bankpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjBank->fnSearchbank( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->gobjsessionstudent = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/bank/index');
		}
	}
        	
	/*
	 * create new bank
	 */
  	public function newbankAction() {  				
		$this->view->lobjBankForm = $this->lobjBankForm; 
  		
  		
		$larrInitialSettings = $this->lobjinitialconfigModel ->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
 		
	  	$this->view->BKCode1 = $larrInitialSettings['BKCode1'];
	  	$this->view->BKCode2 = $larrInitialSettings['BKCode2'];		

	  	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBankForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBankForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$this->view->lobjBankForm->Country->addMultiOptions($lobjcountry);
		$this->view->lobjBankForm->Country->setValue ($this->view->DefaultCountry);
		
		$lobjLevelList = $this->lobjStaffmaster->fnGetLevelList();
		$this->view->lobjBankForm->Desgination->addMultiOptions($lobjLevelList);
		
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->lobjBankForm->isValid ( $larrformData )) {
				$result = $this->lobjBank->fnAddBank($larrformData); //instance for adding the lobjuserForm values to DB
				
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Bank Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/bank/index');
			}
		}
		
	}
        
	/*
	 * edit bank or update bank values
	 */
  	public function banklistAction() {		
  		
  		$this->view->lobjBankForm = $this->lobjBankForm; 
		
		$lintIdBank = ( int ) $this->_getParam ( 'id' );
		$this->view->IdBank = $lintIdBank;
		$larrInitialSettings = $this->lobjinitialconfigModel ->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
 		
	  	$this->view->BKCode1 = $larrInitialSettings['BKCode1'];
	  	$this->view->BKCode2 = $larrInitialSettings['BKCode2'];		
		
		$larrresult = $this->lobjBank->fnViewBank($lintIdBank);
		$lobjCommonModel = new App_Model_Common();
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$this->view->lobjBankForm->Country->addMultiOptions($lobjcountry);
		
		$lobjLevelList = $this->lobjStaffmaster->fnGetLevelList();
		$this->view->lobjBankForm->Desgination->addMultiOptions($lobjLevelList);	
		
		$larrCountryStateList = $lobjCommonModel->fnGetCountryStateList($larrresult['Country']);
		$this->view->lobjBankForm->State->addMultiOptions($larrCountryStateList);	
		
		$larrStateCityList = $lobjCommonModel->fnGetCityList($larrresult['State']);
		$this->view->lobjBankForm->City->addMultiOptions($larrStateCityList);	
		
		
		
		
		$this->lobjBankForm->populate($larrresult);	
		if($larrresult ['Phone']){
		$arrPhone = explode("-",$larrresult ['Phone']);
		$this->view->lobjBankForm->Phonecountrycode->setValue ( $arrPhone[0] );
		$this->view->lobjBankForm->Phonestatecode->setValue ( $arrPhone[1] );
		$this->view->lobjBankForm->Phone->setValue ( $arrPhone[2] );
		}
		if($larrresult ['Fax']){
		$arrFax = explode("-",$larrresult ['Fax']);
		$this->view->lobjBankForm->faxcountrycode->setValue ( $arrFax[0] );
		$this->view->lobjBankForm->faxstatecode->setValue ( $arrFax[1] );
		$this->view->lobjBankForm->Fax->setValue ( $arrFax[2] );
		}
		if($larrresult ['Phone']){
		$arrPhone = explode("-",$larrresult ['Phone']);
		$this->view->lobjBankForm->faxcountrycode->setValue ( $arrPhone[0] );
		$this->view->lobjBankForm->Phonestatecode->setValue ( $arrPhone[1] );
		$this->view->lobjBankForm->Phone->setValue ( $arrPhone[2] );
		}
		if($larrresult ['ContactPhone']){
		$arrContactPhone = explode("-",$larrresult ['ContactPhone']);
		$this->view->lobjBankForm->ContactPhonecountrycode->setValue ( $arrContactPhone[0] );
		$this->view->lobjBankForm->ContactPhonestatecode->setValue ( $arrContactPhone[1] );
		$this->view->lobjBankForm->ContactPhone->setValue ( $arrContactPhone[2] );
		}
		if($larrresult ['ContactCell']){
		$arrContactCell = explode("-",$larrresult ['ContactCell']);
		$this->view->lobjBankForm->countrycode->setValue ( $arrContactCell[0] );
		$this->view->lobjBankForm->ContactCell->setValue ( $arrContactCell[1] );
		}

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBankForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBankForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if ($this->lobjBankForm->isValid ( $larrformData )) {
						
					$this->lobjBank->fnUpdateBank($lintIdBank, $larrformData );
					
					$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Bank Edit Id=' . $lintIdBank,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/bank/index');
				}
			}
		}
		$this->view->lobjBankForm = $this->lobjBankForm;
		
  	}
}