<?php
class GeneralSetup_UserController extends Base_Base { //Controller for the User Module
    
	private $locale;
	private $registry;
	private $lobjuser;
	private $lobjuserForm;
	private $_gobjlog;
    private $_lobjconfig;
	
	public function init() { //initialization function
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		Zend_Form::setDefaultTranslator($this->view->translate);
		$this->fnsetObj();
		
	}

	public function fnsetObj() {
		$this->lobjuser = new GeneralSetup_Model_DbTable_User(); //user model object
		$this->lobjuserForm = new GeneralSetup_Form_User (); //intialize user lobjuserForm
		$this->registry = Zend_Registry::getInstance();
		$this->view->locale = $this->locale = $this->registry->get('Zend_Locale');
        $this->lobjconfig = new GeneralSetup_Model_DbTable_Initialconfiguration();
	}
	

	public function indexAction() { // action for search and view
		$lobjform=$this->view->lobjform = $this->lobjform; //send the lobjuserForm object to the view
		$larrresult = $this->lobjuser->fngetUserDetails (); //get user details
		
		//echo "<pre>";print_r($larrresult);die();
		 if(!$this->_getParam('search')) 
			unset($this->gobjsessionsis->userpaginatorresult);
			
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionsis->userpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->userpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjuser->fnSearchUser ( $lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->userpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'user', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/user/index');
		}

	}

	public function newuserAction() { //Action for creating the new user

                $nameConfDetail = array();
                $idUniversity =$this->gobjsessionsis->idUniversity;
                //$this->view->confDetail = ;
                $confdetail = $this->lobjconfig->fnGetInitialConfigDetails($idUniversity);
                $nameConfDetail['count'] = $confdetail['NameDtlCount'];
                $temp = array();
                for($i=1 ; $i <= $nameConfDetail['count']; $i++){
                  $field = 'NameDtl'.$i;
                  $temp[$i] = $confdetail[$field];
                }
                $nameConfDetail['fields'] = $temp;
                $this->view->NameconfDetail = $nameConfDetail;

		$this->view->lobjuserForm = $this->lobjuserForm; //send the lobjuserForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuserForm->loginName->setAttrib('validator', 'validateUsername');
		
		$this->view->lobjuserForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjuserForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
			$ldtsystemDate1 = date ( 'Y-m-d' );
		 $this->lobjuserForm->DOB->setValue ( $ldtsystemDate1 );
		
		//echo $this->view->DefaultCountry ;die();

		$lobjcountry = $this->lobjuser->fnGetCountryList();
		$this->lobjuserForm->country->addMultiOptions($lobjcountry);
		$this->view->lobjuserForm->country->setValue ($this->view->DefaultCountry);
		if($this->locale == 'ar_YE')  {
			$this->view->lobjuserForm->DOB->setAttrib('datePackage',"dojox.date.islamic");
		}
		
  		$lobjStaff = $this->lobjuser->fnGetStaffList();
		//echo "<pre>";print_r($lobjStaff);die();
		$this->lobjuserForm->IdStaff->addMultiOptions($lobjStaff);

		$lobjIdRole = new App_Model_Common (); //dropdown for roles
		$lobjIdRole = $lobjIdRole->fnGetRoleDetails();
		foreach ( $lobjIdRole as $lobjIdRole ) {
			if($this->view->DefaultDropDownLanguage==0){
				$this->lobjuserForm->IdRole->addMultiOption ( $lobjIdRole ['idDefinition'], $lobjIdRole ['DefinitionDesc'] );
			}else{
				if($lobjIdRole ['BahasaIndonesia'] == NULL ) {
					$lobjIdRole ['BahasaIndonesia'] = $lobjIdRole ['DefinitionDesc'];
				}
				$this->lobjuserForm->IdRole->addMultiOption ( $lobjIdRole ['idDefinition'], $lobjIdRole ['BahasaIndonesia'] );	
				
				
			}
		}


		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			unset ( $larrformData ['Close'] );
			if ($this->lobjuserForm->isValid ( $larrformData )) {
				$lstrpassword = $larrformData ['passwd'];
				$larrformData ['passwd'] = md5 ( $lstrpassword );
				$result = $this->lobjuser->fnaddUser ( $larrformData ); //instance for adding the lobjuserForm values to DB
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New User Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'user', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/user/index');
			}
		}

	}

	public function userlistAction() { //Action for the updation and view of the user details

    	$nameConfDetail = array();
        $idUniversity =$this->gobjsessionsis->idUniversity;
        
        //$this->view->confDetail = ;
        $confdetail = $this->lobjconfig->fnGetInitialConfigDetails($idUniversity);
        $nameConfDetail['count'] = $confdetail['NameDtlCount'];
        $temp = array();
        for($i=1 ; $i <= $nameConfDetail['count']; $i++){
        	$field = 'NameDtl'.$i;
            $temp[$i] = $confdetail[$field];
        }
        $nameConfDetail['fields'] = $temp;
        $this->view->NameconfDetail = $nameConfDetail;
                
		$this->view->lobjuserForm = $this->lobjuserForm; //send the lobjuserForm object to the view
		
		
		$lobjcountry = $this->lobjuser->fnGetCountryList();
		$this->lobjuserForm->country->addMultiOptions($lobjcountry);
		
		if($this->locale == 'ar_YE')  {
			$this->view->lobjuserForm->DOB->setAttrib('datePackage',"dojox.date.islamic");
		}
		
		$this->view->lobjuserForm->loginName->setAttrib ( 'readonly', true );
		$this->view->lobjuserForm->loginName->clearValidators();
		//$this->view->lobjuserForm->loginName->setAttrib ( 'required', 'false' );
		//$this->view->lobjuserForm->loginName->removeValidator ('Db_NoRecordExists' );
		
		$this->view->lobjuserForm->passwd->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->email->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->DOB->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->gender->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->addr1->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->addr2->setAttrib ( 'readonly', true );
		//$this->view->lobjuserForm->state->setAttrib ( 'readonly', true );
		
		$lintiduser = ( int ) $this->_getParam ( 'id' );
		$this->view->idusers	= $lintiduser;
		$larrresult = $this->lobjuser->fnviewUser ( $lintiduser ); //getting user details based on userid
		
		$lobjIdRole = new App_Model_Common (); //dropdown for roles
		$lobjIdRole = $lobjIdRole->fngetRoleDetails ();
		foreach ( $lobjIdRole as $lobjIdRole ) {
			$this->lobjuserForm->IdRole->addMultiOption ( $lobjIdRole ['idDefinition'], $lobjIdRole ['DefinitionDesc'] );
		}

  		//$lobjStaff = $this->lobjuser->fnGetStaffList();
  		$lobjStaff = $this->lobjCommon->fnGetEmpList();
		$this->lobjuserForm->IdStaff->addMultiOptions($lobjStaff);
		
		//Initialize SAM Common Model
		$lobjCommonModel = new App_Model_Common();                
		foreach ( $larrresult as $larruser ) {
			
			$this->view->lobjuserForm->iduser->setValue ( $larruser ['iduser'] );
			$this->view->lobjuserForm->IdStaff->setValue ( $larruser ['IdStaff'] );
			$this->view->lobjuserForm->loginName->setValue ( $larruser ['loginName'] );
			$this->view->lobjuserForm->UpdDate->setValue ( $larruser ['UpdDate'] );
			$this->view->lobjuserForm->UpdUser->setValue ( $larruser ['UpdUser'] );
			$this->view->lobjuserForm->fName->setValue ( $larruser ['fName'] );
			$this->view->lobjuserForm->mName->setValue ( $larruser ['mName'] );
			$this->view->lobjuserForm->lName->setValue ( $larruser ['lName'] );
			$this->view->lobjuserForm->email->setValue ( $larruser ['email'] );
			$this->view->lobjuserForm->NameField1->setValue ( $larruser ['NameField1'] );
            $this->view->lobjuserForm->NameField2->setValue ( $larruser ['NameField2'] );
            $this->view->lobjuserForm->NameField3->setValue ( $larruser ['NameField3'] );
            $this->view->lobjuserForm->NameField4->setValue ( $larruser ['NameField4'] );
            $this->view->lobjuserForm->NameField5->setValue ( $larruser ['NameField5'] );

			//Get States List
			$larrCountyrStatesList = $lobjCommonModel->fnGetCountryStateList($larruser['country']);
			$this->lobjuserForm->state->addMultiOptions($larrCountyrStatesList);
			$larrStateCityList = $lobjCommonModel->fnGetCityList($larruser['state']);
			$this->lobjuserForm->city->addMultiOptions($larrStateCityList);

			$this->view->lobjuserForm->DOB->setValue ( $larruser['DOB'] );
			$this->view->lobjuserForm->gender->setValue ( $larruser ['gender'] );
			$this->view->lobjuserForm->addr1->setValue ( $larruser ['addr1'] );
			$this->view->lobjuserForm->addr2->setValue ( $larruser ['addr2'] );
			//$this->view->lobjuserForm->city->setValue ( $larruser ['city'] );
			$this->view->lobjuserForm->state->setValue ( $larruser ['state'] );
			$this->view->lobjuserForm->country->setValue ( $larruser ['country'] );
			$this->view->lobjuserForm->zipCode->setValue ( $larruser ['zipCode'] );
			$this->view->lobjuserForm->userArabicName->setValue ( $larruser ['userArabicName'] );
			
			$arrhomephone = explode("-",$larruser ['homePhone']);
			$this->view->lobjuserForm->homecountrycode->setValue ( $arrhomephone [0] );
			$this->view->lobjuserForm->homestatecode->setValue ( $arrhomephone [1] );
			$this->view->lobjuserForm->homePhone->setValue ( $arrhomephone [2] );
			
			$arrworkphone = explode("-",$larruser ['workPhone']);
			$this->view->lobjuserForm->workcountrycode->setValue ( $arrworkphone [0] );
			$this->view->lobjuserForm->workstatecode->setValue ( $arrworkphone [1] );
			$this->view->lobjuserForm->workPhone->setValue ( $arrworkphone [2] );
			
			$arrcellphone = explode("-",$larruser ['cellPhone']);
			$this->view->lobjuserForm->countrycode->setValue ($arrcellphone[0]);
			$this->view->lobjuserForm->cellPhone->setValue ($arrcellphone[1]);
			$this->view->lobjuserForm->IdRole->setValue ( $larruser ['IdRole'] );
			
			$arrfax = explode("-",$larruser ['fax']);
			$this->view->lobjuserForm->faxcountrycode->setValue ( $arrfax[0] );
			$this->view->lobjuserForm->faxstatecode->setValue ( $arrfax[1] );
			$this->view->lobjuserForm->fax->setValue ( $arrfax[2] );
			
			$this->view->lobjuserForm->passwd->setValue ( $larruser ['passwd'] );
			$this->view->lobjuserForm->UserStatus->setValue ( $larruser ['UserStatus'] );
			$this->view->lobjuserForm->passwd->renderPassword = true;
		}
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuserForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjuserForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		

		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$this->view->lobjuserForm->passwd->renderPassword = true;
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				unset ( $larrformData ['Close'] );
				if ($this->lobjuserForm->isValid ( $larrformData )) {
						
					$lintiduser = $larrformData ['iduser'];
					
					$this->lobjuser->fnupdateUser($lintiduser, $larrformData );
					
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'User Edit Id=' . $lintiduser,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'user', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/user/index');
				}
			}
		}
		$this->view->lobjuserForm = $this->lobjuserForm;
	}

	//Action To Get List Of States From Country Id
	public function getcountrystateslistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		//Get Country Id
		$lintIdCountry = $this->_getParam('idCountry');
		//echo $lintIdCountry;die();
//		$language = $this->lobjCommon->fngetLanguage($lintIdCountry);
//		$this->gobjsessionsis->UniversityLanguage = $language['DefinitionDesc'];
		$larrCountryStatesDetails = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjCommon->fnGetCountryStateList($lintIdCountry));
		if($lintIdCountry != 96) {
		$larrCountryStatesDetails[]=array('key'=>'99','name'=>'Others');
		}
		//var_dump($larrCountryStatesDetails);
		echo Zend_Json_Encoder::encode($larrCountryStatesDetails);
	}
	public function getstatecitylistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$lintidState = $this->_getParam('idState');
	
		$larrStateCityDetails = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjCommon->fnGetCityList($lintidState));
		$larrStateCityDetails[]=array('key'=>'12','name'=>'Others');
		/*echo "<pre>";
		print_r($larrStateCityDetails);
		die();*/
		echo Zend_Json_Encoder::encode($larrStateCityDetails);
	}
	//Action To Get List Of States From Country Id
	public function getstafflistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		//Get Country Id
		$idstaff =$this->_getParam('idStaff');
		$larrStaffDetails = $this->lobjuser->getstaffdetails($idstaff);
		echo Zend_Json_Encoder::encode($larrStaffDetails);
		

		
	}
	
	public function getagentstafflistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		//Get Staff Id
		$idstaff =$this->_getParam('idAgent');
		$larrStaffDetails = $this->lobjuser->getagentstaffdetails();
		echo Zend_Json_Encoder::encode($larrStaffDetails);
		

		
	}	
	public function getvalidusernameAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$UserName = $this->_getParam('UserName');	
		$larrDetails = $this->lobjuser->fngetusername($UserName);
		echo $larrDetails['loginName'];
		
	}
	
	public function getagentdetailsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$AgentId = $this->_getParam('idAgent');	
		$larrDetails = $this->lobjuser->fngetagentdetails($AgentId);
		echo Zend_Json_Encoder::encode($larrDetails);
	}
	

}