<?php
class GeneralSetup_SponsorController extends Base_Base {
	private $locale;
	private $registry;
	private $sponsorform;
	private $sponsor;
	private $lobjUser;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		
	}
	
	public function fnsetObj(){
		$this->sponsorform = new GeneralSetup_Form_Sponsor();
		$this->sponsor = new GeneralSetup_Model_DbTable_Sponsor();		
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();
	}
	
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		$this->view->lobjform->field5->addMultioption("0","Individual");
		$this->view->lobjform->field5->addMultioption("1","Organisation");
		$larrresult = $this->sponsor->fnGetSponsorDetails();
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->sponsorpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->sponsorpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->sponsorpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->sponsor->fnSearchSponsor( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->sponsorpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/sponsor/index');
		}
		
	}
	
	public function newsponsorAction() { //title
		$this->view->lobjsponsorform= $this->sponsorform;
		$this->view->typeSponsor = 0;
		$lobjcountry = $this->lobjUser->fnGetCountryList();
		$this->sponsorform->Country->addMultiOptions($lobjcountry);
		$this->sponsorform->Country->setValue ($this->view->DefaultCountry);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsponsorform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsponsorform->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {			
			$formData = $this->getRequest()->getPost();
			 if($formData['typeSponsor'] == 1 && $formData['Organisation'] =="") {
			 	$this->view->typeSponsor  = 1;
			 	echo "<script>alert('Enter Organisation');</script>";
			 	$this->sponsorform->populate($formData);	
			 }
			 else{
				unset ( $formData ['Save'] );
				unset ( $formData ['Back'] );
				if($formData['typeSponsor']=="1")
				$formData['DOB'] = '0000-00-00';

				$this->sponsor->fnaddSponsor($formData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Sponsor Master Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/sponsor/index');
			 }
        }     
    }
    
	public function sponsordetailAction(){
		$this->view->lobjsponsorform= $this->sponsorform;
		$lobjcountry = $this->lobjUser->fnGetCountryList();
		$this->sponsorform->Country->addMultiOptions($lobjcountry);
		
		/*$lobjstate = $this->lobjUser->fnGetStateList();
		$this->sponsorform->State->addMultiOptions($lobjstate);*/
		if($this->locale == 'ar_YE')  {
			$this->view->lobjsponsorform->dob->setAttrib('datePackage',"dojox.date.islamic");
		}
		
    	$idsponsor = $this->_getParam('id', 0);
    	$result = $this->sponsor->fetchAll('idsponsor  ='.$idsponsor);
    	$result = $result->toArray();    	
    	$this->view->typeSponsor = $result[0]['typeSponsor'];
		foreach($result as $sponsorresult){
		}
    	$this->sponsorform->populate($sponsorresult);	
    	if(isset($sponsorresult ['HomePhone']) && !empty($sponsorresult ['HomePhone']) ){
			$arrHomePhone = explode("-",$sponsorresult ['HomePhone']);
			$this->view->lobjsponsorform->HomePhonecountrycode->setValue ( $arrHomePhone[0] );
			$this->view->lobjsponsorform->HomePhonestatecode->setValue ( $arrHomePhone[1] );
			$this->view->lobjsponsorform->HomePhone->setValue ( $arrHomePhone[2] );
		}
		if(isset($sponsorresult ['WorkPhone']) && !empty($sponsorresult ['WorkPhone']) ){
			$arrWorkPhone = explode("-",$sponsorresult ['WorkPhone']);
			$this->view->lobjsponsorform->WorkPhonecountrycode->setValue ( $arrWorkPhone[0] );
			$this->view->lobjsponsorform->WorkPhonestatecode->setValue ( $arrWorkPhone[1] );
			$this->view->lobjsponsorform->WorkPhone->setValue ( $arrWorkPhone[2] );
		}
		if(isset($sponsorresult ['CellPhone']) && !empty($sponsorresult ['CellPhone']) ){
			$arrCellPhone = explode("-",$sponsorresult ['CellPhone']);
			$this->view->lobjsponsorform->CellPhonecountrycode->setValue ( $arrCellPhone[0] );
			$this->view->lobjsponsorform->CellPhone->setValue ( $arrCellPhone[1] );
		}
		if(isset($sponsorresult['Fax']) && !empty($sponsorresult ['Fax']) ){
			$arrFax = explode("-",$sponsorresult ['Fax']);
			$this->view->lobjsponsorform->Faxcountrycode->setValue ( $arrFax[0] );
			$this->view->lobjsponsorform->Faxstatecode->setValue ( $arrFax[1] );
			$this->view->lobjsponsorform->Fax->setValue ( $arrFax[2] );
		}
		$lobjCommonModel = new App_Model_Common();
		$larrStateCityList = $lobjCommonModel->fnGetCityList($sponsorresult['State']);
		$this->view->lobjsponsorform->City->addMultiOptions($larrStateCityList);
		
		$lobjstate = $this->lobjUser->fnGetStateListcountry($sponsorresult['Country']);
		$this->view->lobjsponsorform->State->addMultiOptions($lobjstate);
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsponsorform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsponsorform->UpdUser->setValue( $auth->getIdentity()->iduser);
    	if ($this->getRequest()->isPost()) {
	    		$formData = $this->getRequest()->getPost();    		
		    	if ($this->sponsorform->isValid($formData)) {
		   			$idsponsor = $formData ['idsponsor'];
		   			if($formData['typeSponsor'] == 1 && $formData['Organisation'] =="") {		   			
				 	echo "<script>alert('Enter Organisation');</script>";
				 }
				 else{
				 	if($formData['typeSponsor'] == 1) unset($formData['DOB']);
					$this->sponsor->fnupdateSponsor($formData,$idsponsor);//update university
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Sponsor Master Edit Id=' . $idsponsor,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					$this->_redirect( $this->baseUrl . '/generalsetup/sponsor/index');
				 }
			}
    	}
    }
}