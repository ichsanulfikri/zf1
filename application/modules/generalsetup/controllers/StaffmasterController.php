<?php
class GeneralSetup_StaffmasterController extends Base_Base {
	private $_gobjlog;


	
	public function init() { //initialization function
		$this->gobjsessionsis = Zend_Registry::get('sis'); //initialize session variable
		$lobjinitialconfigModel = new GeneralSetup_Model_DbTable_Initialconfiguration(); //user model object
        $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$larrInitialSettings = $lobjinitialconfigModel->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
		
		$this->gintPageCount = isset($larrInitialSettings['noofrowsingrid'])?$larrInitialSettings['noofrowsingrid']:"5";
		$this->view->College = isset($larrInitialSettings['CollegeAliasName']) ? $larrInitialSettings['CollegeAliasName']:"College";
		$this->view->Department = isset($larrInitialSettings['DepartmentAliasName']) ? $larrInitialSettings['DepartmentAliasName']:"Department";
		$this->view->Subject = isset($larrInitialSettings['SubjectAliasName']) ? $larrInitialSettings['SubjectAliasName']:"Subject";		
		$this->view->StaffIdType = $larrInitialSettings['StaffIdType'];
		
		$this->view->RefCodeField1=$larrInitialSettings['RefCodeField1'];
		$this->view->RefCodeField2=$larrInitialSettings['RefCodeField2'];
		$this->view->RefCodeField3=$larrInitialSettings['RefCodeField3'];
		$this->view->RefCodeField4=$larrInitialSettings['RefCodeField4'];
		
		
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    	
   	   
	}
	
	public function indexAction() {
    	$this->view->title="Staff Master"; 	//title
    	$lobjform = new App_Form_Search (); //intialize search lobjstaffmasterForm
		$this->view->lobjform = $lobjform; //send the lobjstaffmasterForm object to the view
		
		$lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster(); //staffmaster model object
		
		/*$lobjsession = Zend_Registry::get ( 'sis' );
		$RoleText=$lobjsession->collegeId;
		$Utype=$lobjsession->collegeId; */
		
		$lobjLevelList = $lobjStaffmaster->fnGetLevelList();
		$lobjform->field5->addMultiOptions($lobjLevelList);		
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $lobjStaffmaster->fngetStaffDetails(); //get staffmaster details
		} else {
			$larrresult = $lobjStaffmaster->fngetUserStaffDetails($this->gobjsessionsis->UserCollegeId); //get staffmaster details
		}
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->staffpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$sessionID = Zend_Session::getId();
        $lobjStaffmaster->fnDeleteTempStaffSubjectsDetailsBysession($sessionID);

		if(isset($this->gobjsessionsis->staffpaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->staffpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ($larrformData)) {
			if($this->gobjsessionsis->UserCollegeId == '0') {
				$larrresult = $lobjStaffmaster->fnSearchStaff($lobjform->getValues ());
			} else {
				$larrresult = $lobjStaffmaster->fnSearchUserStaff($lobjform->getValues (),$this->gobjsessionsis->UserCollegeId); //get staffmaster details
			}
				 
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->staffpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'staffmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/staffmaster/index');
		}
		
	}
	
	public function newstaffmasterAction() { //title
		$this->lobjconfig = new GeneralSetup_Model_DbTable_Initialconfiguration();  		
		$nameConfDetail = array();
                $idUniversity =$this->gobjsessionsis->idUniversity;
                //$this->view->confDetail = ;
                $nameConfDetail = array();
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
               
                
                $nameConfDetail['count'] = $confdetail['ExtarIdDtlCount'];
                $temp = array();
                for($i=1 ; $i <= $nameConfDetail['count']; $i++){
                  $field = 'ExtarIdDtl'.$i;
                  $temp[$i] = $confdetail[$field];
                }
                $nameConfDetail['fields'] = $temp;
                $this->view->ExtraIdconfDetail = $nameConfDetail;
    	$this->view->title="Add New Staff";
		$lobjstaffmasterForm = new GeneralSetup_Form_Staffmaster(); //intialize user lobjstaffmasterForm
		$this->view->lobjstaffmasterForm = $lobjstaffmasterForm; //send the lobjstaffmasterForm object to the view
		$lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		
	    
	   //0 = manual  and 1=auto generate		
	   if($this->view->StaffIdType == 1 ){
			$this->view->lobjstaffmasterForm->StaffId->setAttrib('readonly','true');
			$this->view->lobjstaffmasterForm->StaffId->setValue('xxx-xxx-xxx');
		}else{
			$this->view->lobjstaffmasterForm->StaffId->setAttrib('required','true');
		}
		
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$lobjstaffmasterForm->Country->addMultiOptions($lobjcountry);
		$lobjstaffmasterForm->Country->setValue ($this->view->DefaultCountry);
		
		/*$lobjCollegeList = $lobjStaffmaster->fnGetCollegeList();
		$lobjstaffmasterForm->IdCollege->addMultiOptions($lobjCollegeList);*/
		
		
		if($this->gobjsessionsis->UserCollegeId == '0') {
				$lobjDepartmentList = $lobjStaffmaster->fnGetDepartmentList();
		} else {
				$lobjDepartmentList = $lobjStaffmaster->fnGetUserDepartmentList($this->gobjsessionsis->UserCollegeId);
		}
		$lobjstaffmasterForm->IdDepartment->addMultiOptions($lobjDepartmentList);
	
		
		$lobjLevelList = $lobjStaffmaster->fnGetLevelList();
		$lobjstaffmasterForm->IdLevel->addMultiOptions($lobjLevelList);
		
		$lobjSalutationList = $lobjStaffmaster->fnGetSalutationList();
		$lobjstaffmasterForm->FrontSalutation->addMultiOptions($lobjSalutationList);
		
		$lobjdeftype = new App_Model_Definitiontype();
		$backsalutation = $lobjdeftype->fnGetDefinationMs('Back Salutation');
		$lobjstaffmasterForm->BackSalutation->addMultiOptions($backsalutation);
		
		//$lobjStaffJobtypeList = $lobjStaffmaster->fnGetSalutationList();
		//$lobjstaffmasterForm->StaffJobType->addMultiOptions($lobjStaffJobtypeList);
		
	    $lobjReligionList = $lobjStaffmaster->fnGetReligionList();
	    $lobjstaffmasterForm->Religion->addMultiOptions($lobjReligionList);
	    $this->view->lobjstaffmasterForm->Religion->setValue(135);
		
	    $lobjPOBList = $lobjStaffmaster->fnGetPOBList();
	    $lobjstaffmasterForm->PlaceOfBirth->addMultiOptions($lobjPOBList);
	    
	    $lobjBankList = $lobjStaffmaster->fnGetBankList();
	    $lobjstaffmasterForm->BankId->addMultiOptions($lobjBankList);
	    
	    
		$lobjLevelsList = $lobjStaffmaster->fnGetLevelsList();
		
		$lobjSubjectList = $lobjStaffmaster->fnGetSubjectList();
		$lobjstaffmasterForm->IdSubject->addMultiOptions($lobjSubjectList);
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjstaffmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjstaffmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$larrformData = $this->getRequest()->getPost();		
			if ($lobjstaffmasterForm->isValid($larrformData)) {//process form 
				$lArrIdSubject = $larrformData ['IdSubject'];
				if($larrformData ['FrontSalutation'] == "") {
					unset ( $larrformData ['FrontSalutation'] );
				}
				
				unset ( $larrformData ['IdSubject'] );
				unset ( $larrformData ['Save'] );
				unset ( $larrformData ['Back'] );			
				
				//$lvarstaffId = $lobjStaffmaster->fnaddStaff($larrformData);				
				$lvarstaffId = $lobjStaffmaster->fnaddStaff($larrformData,$this->gobjsessionsis->idUniversity,$this->view->StaffIdType);		
				
				$lobjStaffmaster->fnaddStaffSubject($larrformData,$lvarstaffId);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Staff Master Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'staffmaster', 'action'=>'index'),'default',true));	//redirect
				$this->_redirect( $this->baseUrl . '/generalsetup/staffmaster/index');	
			} 	
        }     
    }
    
	public function staffmasterlistAction(){
			$this->lobjconfig = new GeneralSetup_Model_DbTable_Initialconfiguration();  		
				$nameConfDetail = array();
                $idUniversity =$this->gobjsessionsis->idUniversity;

                $nameConfDetail = array();
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
               
                $nameConfDetail['count'] = $confdetail['ExtarIdDtlCount'];
                $temp = array();
                for($i=1 ; $i <= $nameConfDetail['count']; $i++){
                  $field = 'ExtarIdDtl'.$i;
                  $temp[$i] = $confdetail[$field];
                }
                $nameConfDetail['fields'] = $temp;
                $this->view->ExtraIdconfDetail = $nameConfDetail;
    	$lobjstaffmasterForm = new GeneralSetup_Form_Staffmaster(); //intialize user lobjstaffmasterForm
		$this->view->lobjstaffmasterForm = $lobjstaffmasterForm; //send the lobjuserForm object to the view
		$lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		
	if($this->view->StaffIdType == 1 ){
			$this->view->lobjstaffmasterForm->StaffId->setAttrib('readonly','true');
			//$this->view->lobjstaffmasterForm->StaffId->setValue('xxx-xxx-xxx');
		}else{
			$this->view->lobjstaffmasterForm->StaffId->setAttrib('required','true');
		}
		
		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$lobjcountry = $lobjUser->fnGetCountryList();
		$lobjstaffmasterForm->Country->addMultiOptions($lobjcountry);
		
		
		/*$lobjstate = $lobjUser->fnGetStateList();
		$lobjstaffmasterForm->State->addMultiOptions($lobjstate);*/
		
		if($this->gobjsessionsis->UserCollegeId == '0') {
				$lobjDepartmentList = $lobjStaffmaster->fnGetDepartmentList();
		} else {
				$lobjDepartmentList = $lobjStaffmaster->fnGetUserDepartmentList($this->gobjsessionsis->UserCollegeId);
		}
		$lobjstaffmasterForm->IdDepartment->addMultiOptions($lobjDepartmentList);
		
		
		$lobjLevelsList = $lobjStaffmaster->fnGetLevelsList();
		
		$lobjLevelList = $lobjStaffmaster->fnGetLevelList();		
		$lobjstaffmasterForm->IdLevel->addMultiOptions($lobjLevelList);
		
		$lobjSubjectList = $lobjStaffmaster->fnGetSubjectList();
		$lobjstaffmasterForm->IdSubject->addMultiOptions($lobjSubjectList);
		
		$lobjSalutationList = $lobjStaffmaster->fnGetSalutationList();
		$lobjstaffmasterForm->FrontSalutation->addMultiOptions($lobjSalutationList);
		
		$lobjdeftype = new App_Model_Definitiontype();
		$backsalutation = $lobjdeftype->fnGetDefinationMs('Back Salutation');
		$lobjstaffmasterForm->BackSalutation->addMultiOptions($backsalutation);
		//Load job type
		$lobjStaffJobtypeList = $lobjStaffmaster->fnGetSalutationList();
		$lobjstaffmasterForm->StaffJobType->addMultiOptions($lobjStaffJobtypeList);
		
		
		
		$lobjReligionList = $lobjStaffmaster->fnGetReligionList();
	    $lobjstaffmasterForm->Religion->addMultiOptions($lobjReligionList);
		
	    $lobjPOBList = $lobjStaffmaster->fnGetPOBList();
	    $lobjstaffmasterForm->PlaceOfBirth->addMultiOptions($lobjPOBList);
	    
	    $lobjBankList = $lobjStaffmaster->fnGetBankList();
	    $lobjstaffmasterForm->BankId->addMultiOptions($lobjBankList);
		
		$lintidstafft = ( int ) $this->_getParam ( 'id' );
		$this->view->idstaff = $lintidstafft;
		
		
		$larrresult = $lobjStaffmaster->fnviewStaffDetails($lintidstafft); //getting user details based on userid
		$lobjCollegeList = $lobjStaffmaster->fnGetCollegeList();
		$lobjstaffmasterForm->IdCollege->addMultiOptions($lobjCollegeList);
		
		$lobjDepartmentModel = new GeneralSetup_Model_DbTable_Departmentmaster();
		
		if($larrresult['StaffType'] == '0') {
			$lobjUniversityList = $lobjDepartmentModel->fnGetUniversityList();
			$lobjstaffmasterForm->IdCollege->addMultiOptions($lobjUniversityList);
		} else{
			if($this->gobjsessionsis->UserCollegeId == '0') {
					$lobjCollegeList = $lobjDepartmentModel->fnGetCollegeList();	
				}
			else {
					$lobjCollegeList = $lobjDepartmentModel->fnGetUserCollegeList($this->gobjsessionsis->UserCollegeId);
			
				}
			
			$lobjstaffmasterForm->IdCollege->addMultiOptions($lobjCollegeList);
		}
		
		
		$this->view->FrontSalutation = $larrresult['FrontSalutation'];
		
		$lobjCommonModel = new App_Model_Common();
		$larrStateCityList = $lobjCommonModel->fnGetCityList($larrresult['State']);
		$lobjstaffmasterForm->City->addMultiOptions($larrStateCityList);	
		
		$lobjUser = new GeneralSetup_Model_DbTable_User();
		$lobjstate = $lobjUser->fnGetStateListcountry($larrresult['Country']);
		$lobjstaffmasterForm->State->addMultiOptions($lobjstate);
		
    	$lobjstaffmasterForm->populate($larrresult);	
    	
    	$larrStaffSubject = $lobjStaffmaster->fnviewStaffSubjectDetails($lintidstafft); 
    	
    	if($lintidstafft) {
			if($this->_getParam('update') != 'true') {
			$temparrayreult=$lobjStaffmaster->fninserttemptempstaffsubject($larrStaffSubject,$lintidstafft);	
			}
	   	}
    		
    	$this->view->resultTempstaffsubject=$resultTempstaffsubject =$lobjStaffmaster->fnViewTempstaffsubject($lintidstafft);
    	
    	
		
    	$arrPhone = explode("-",$larrresult ['Phone']);
		$this->view->lobjstaffmasterForm->Phonecountrycode->setValue ( $arrPhone [0] );
		$this->view->lobjstaffmasterForm->Phonestatecode->setValue ( $arrPhone [1] );
		$this->view->lobjstaffmasterForm->Phone->setValue ( $arrPhone [2] );

		$arrMobile = explode("-",$larrresult ['Mobile']);
		$this->view->lobjstaffmasterForm->Mobilecountrycode->setValue ( $arrMobile [0] );
		$this->view->lobjstaffmasterForm->Mobile->setValue ( $arrMobile [1] );	

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjstaffmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjstaffmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);

		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if($larrformData ['salutation'] == "") {
					unset ( $larrformData ['salutation'] );
				}
				if ($lobjstaffmasterForm->isValid ( $larrformData )) {						
					$lintIdStaff = $larrformData ['IdStaff'];
					$lArrIdSubject = $larrformData ['IdSubject'];
					unset ( $larrformData ['IdSubject'] );				
					$lobjStaffmaster->fnupdateStaf($lintIdStaff, $larrformData );
					$lobjStaffmaster->fnupdateStafSubject($larrformData, $lintIdStaff );
					
					
				$sessionID = Zend_Session::getId();
				$fetchtempStafSubjectdetails = $lobjStaffmaster->fnGetStafSubjectDetails($lintIdStaff,$sessionID);
				foreach($fetchtempStafSubjectdetails as $fetchtempStafSubjectdtls) {
					if($fetchtempStafSubjectdtls['deleteFlag'] =='0') {
							$lobjStaffmaster->fnDeletestaffsubject($fetchtempStafSubjectdtls['idExists']);
					}
				}
				$lobjStaffmaster->fnDeleteTempStafSubjectdtls($lArrIdSubject,$sessionID);
					
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Staff Master Edit Id=' . $lintidstafft,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log	
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'staffmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/staffmaster/index');
				}
			}
		}
		$this->view->lobjstaffmasterForm = $lobjstaffmasterForm;
	}
	
	public function deletestaffsubjectAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
						
		$lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		
		$idTempStaffSubject = $this->_getParam('idTempStaffSubject');
		
		$larrDelete = $lobjStaffmaster->fnUpdateTempstaffsubject($idTempStaffSubject);	
		echo "1";
	}
}