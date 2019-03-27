<?php
class GeneralSetup_AgentmasterController extends Base_Base { 
	private $lobjAgentmaster;
	private $lobjAgentmasterForm;
	private $lobjStaffmaster;
	private $lobjinitialconfigModel;
	private $_gobjlog;
		
	public function init() { //initialization function
		
		
		$this->locale = Zend_Registry::get('Zend_Locale');
		$this->view->translate =Zend_Registry::get('Zend_Translate');
		$this->_gobjlog = Zend_Registry::get ( 'log' ); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    
   	    $this->fnsetObj();
		
	}
 	public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search (); //searchform
		$this->lobjAgentmaster = new GeneralSetup_Model_DbTable_Agentmaster();
		$this->lobjAgentmasterForm = new GeneralSetup_Form_Agentmaster();
		$this->lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->lobjinitialconfigModel = new GeneralSetup_Model_DbTable_Initialconfiguration();
		$this->gobjsessionsis = Zend_Registry::get('sis'); 		
	}
	
   	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult =$this->lobjAgentmaster->fnGetAgentDetails(); 
		  if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionstudent->Agentmasterpaginatorresult); 
   	    
   	    $lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$ProgramList=$this->lobjAgentmaster->fnGetProgramList();
		$this->view->lobjform->field5->addMultiOptions($ProgramList);
		
   		if(isset($this->gobjsessionstudent->Agentmasterpaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->Agentmasterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjAgentmaster->fnSearchAgents( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->gobjsessionstudent = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/agentmaster/index');
		}
	}
        	
	/*
	 * create new bank
	 */
  	public function newagentmasterAction() {  				
		$this->view->lobjAgentmasterForm = $this->lobjAgentmasterForm; 
  		
  		
		$larrInitialSettings = $this->lobjinitialconfigModel ->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
 		
	  	$this->view->AgentField1 = $larrInitialSettings['AgentField1'];
	  	$this->view->AgentField2 = $larrInitialSettings['AgentField2'];
	  	$this->view->AgentField3 = $larrInitialSettings['AgentField3'];
	  	$this->view->AgentField4 = $larrInitialSettings['AgentField4'];
	  	$this->view->AgentField5 = $larrInitialSettings['AgentField5'];		

	  	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjAgentmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjAgentmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$this->view->lobjAgentmasterForm->Country->addMultiOptions($lobjcountry);
		$this->view->lobjAgentmasterForm->Country->setValue ($this->view->DefaultCountry);
		
		$lobjLevelList = $this->lobjStaffmaster->fnGetLevelList();
		$this->view->lobjAgentmasterForm->Desgination->addMultiOptions($lobjLevelList);
		
		$ProgramList=$this->lobjAgentmaster->fnGetProgramList();
		$this->view->lobjAgentmasterForm->IdProgram->addMultiOptions($ProgramList);
		
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->lobjAgentmasterForm->isValid ( $larrformData )) {
				$lstrpassword = $larrformData ['passwd'];
				$larrformData ['passwd'] = md5 ( $lstrpassword );
				$lintIdAgentMaster = $this->lobjAgentmaster->fnAddAgentMaster($larrformData);

				$lintIdAgentMaster = $this->lobjAgentmaster->fnaddAgentProgram($larrformData,$lintIdAgentMaster);
				
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Agent Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/agentmaster/index');
			}
		}
		
	}
        
	/*
	 * edit bank or update bank values
	 */
  	public function agentmasterlistAction() {		
  		
  		$this->view->lobjAgentmasterForm = $this->lobjAgentmasterForm; 
		
	    $lintIdAgentMaster = ( int ) $this->_getParam ( 'id' );
		$this->view->IdAgentMaster = $lintIdAgentMaster;
		$larrInitialSettings = $this->lobjinitialconfigModel ->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
 		
	  	$this->view->AgentField1 = $larrInitialSettings['AgentField1'];
	  	$this->view->AgentField2 = $larrInitialSettings['AgentField2'];
	  	$this->view->AgentField3 = $larrInitialSettings['AgentField3'];
	  	$this->view->AgentField4 = $larrInitialSettings['AgentField4'];
	  	$this->view->AgentField5 = $larrInitialSettings['AgentField5'];
	  	$lobjCommonModel = new App_Model_Common();
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$this->view->lobjAgentmasterForm->Country->addMultiOptions($lobjcountry);
		
		$lobjLevelList = $this->lobjStaffmaster->fnGetLevelList();
		$this->view->lobjAgentmasterForm->Desgination->addMultiOptions($lobjLevelList);	
				
		$larrresult = $this->lobjAgentmaster->fnViewAgetMaster($lintIdAgentMaster);

		$ProgramList=$this->lobjAgentmaster->fnGetProgramList();
		$this->view->lobjAgentmasterForm->IdProgram->addMultiOptions($ProgramList);
		
		foreach($larrresult as $larrresults){
			$larrCountryStateList = $lobjCommonModel->fnGetCountryStateList($larrresults['Country']);
			$this->view->lobjAgentmasterForm->State->addMultiOptions($larrCountryStateList);	
			$larrStateCityList = $lobjCommonModel->fnGetCityList($larrresults['State']);
			$this->view->lobjAgentmasterForm->City->addMultiOptions($larrStateCityList);	
			$arrIdProgram[]  = $larrresults['IdProgram'];			
		}
		$City = $larrresults['City'];
		$this->lobjAgentmasterForm->populate($larrresults);
		if($City == 12) {
	      $this->view->lobjAgentmasterForm->City->addMultiOption('12','Others');
		 }
		$this->view->lobjAgentmasterForm->IdProgram->setValue($arrIdProgram);
		
		
		
		
		
  		if($larrresults ['Phone']){
		$arrPhone = explode("-",$larrresults ['Phone']);
		$this->view->lobjAgentmasterForm->Phonecountrycode->setValue ( $arrPhone[0] );
		$this->view->lobjAgentmasterForm->Phonestatecode->setValue ( $arrPhone[1] );
		$this->view->lobjAgentmasterForm->Phone->setValue ( $arrPhone[2] );
		}
		if($larrresults ['Fax']){
		$arrFax = explode("-",$larrresults ['Fax']);
		$this->view->lobjAgentmasterForm->faxcountrycode->setValue ( $arrFax[0] );
		$this->view->lobjAgentmasterForm->faxstatecode->setValue ( $arrFax[1] );
		$this->view->lobjAgentmasterForm->Fax->setValue ( $arrFax[2] );
		}
		if($larrresults ['Phone']){
		$arrPhone = explode("-",$larrresults ['Phone']);
		$this->view->lobjAgentmasterForm->faxcountrycode->setValue ( $arrPhone[0] );
		$this->view->lobjAgentmasterForm->Phonestatecode->setValue ( $arrPhone[1] );
		$this->view->lobjAgentmasterForm->Phone->setValue ( $arrPhone[2] );
		}
		if($larrresults ['ContactPhone']){
		$arrContactPhone = explode("-",$larrresults ['ContactPhone']);
		$this->view->lobjAgentmasterForm->ContactPhonecountrycode->setValue ( $arrContactPhone[0] );
		$this->view->lobjAgentmasterForm->ContactPhonestatecode->setValue ( $arrContactPhone[1] );
		$this->view->lobjAgentmasterForm->ContactPhone->setValue ( $arrContactPhone[2] );
		}
		if($larrresults ['ContactCell']){
		$arrContactCell = explode("-",$larrresults ['ContactCell']);
		$this->view->lobjAgentmasterForm->countrycode->setValue ( $arrContactCell[0] );
		$this->view->lobjAgentmasterForm->ContactCell->setValue ( $arrContactCell[1] );
		}
		
		$this->view->lobjAgentmasterForm->EffectiveDate->setValue(date('Y-m-d',strtotime($larrresults['EffectiveDate'])));
			
			
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjAgentmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjAgentmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if ($this->lobjAgentmasterForm->isValid ( $larrformData )) {
					
					$this->lobjAgentmaster->fnUpdateAgentMaster($lintIdAgentMaster, $larrformData );
					$this->lobjAgentmaster->fndeleteAgentProgram($lintIdAgentMaster);
					
					$this->lobjAgentmaster->fnaddAgentProgram($larrformData,$lintIdAgentMaster);
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Agent Edit Id=' . $lintIdAgentMaster,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/agentmaster/index');
				}
			}
		}
		$this->view->lobjAgentmasterForm = $this->lobjAgentmasterForm;
		
  	}
}