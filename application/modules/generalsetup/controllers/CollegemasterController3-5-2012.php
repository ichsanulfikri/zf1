<?php
class GeneralSetup_CollegemasterController extends Base_Base { //Controller for the User Module

	private $locale;
	private $registry;
	private $_gobjlog;

	public function init() { //initialization function
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');
	}

	public function indexAction() { // action for search and view

		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		$lobjCollegemaster = new GeneralSetup_Model_DbTable_Collegemaster(); //user model object
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $lobjCollegemaster->fngetCollegemasterDetails(); //get user details
		} else {
			$larrresult = $lobjCollegemaster->fngetCollegemasterDetailsById($this->gobjsessionsis->UserCollegeId); //get user details
		}

		$lobjUniversity = new GeneralSetup_Model_DbTable_University(); //intialize user Model
		$lobjUniversity = $lobjUniversity->fnGetUniversityList();
		$lobjform->field5->addMultiOptions($lobjUniversity);

		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->collegepaginatorresult);

		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionsis->collegepaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->collegepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ( $larrformData )) {
			if($this->gobjsessionsis->UserCollegeId == '0') {
				$larrresult = $lobjCollegemaster->fnSearchCollege( $lobjform->getValues ());
			} else {
				$larrresult = $lobjCollegemaster->fnSearchUserCollege($lobjform->getValues (),$this->gobjsessionsis->UserCollegeId); //get user details
			}
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
			$this->gobjsessionsis->collegepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'collegemaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/collegemaster/index');
		}
	}

	public function newcollegeAction() { //Action for creating the new University
		$idUniversity =$this->gobjsessionsis->idUniversity;


		$lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
		$initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);

		$this->view->title="Add New University";
		$lobjcollegeForm = new GeneralSetup_Form_Collegemaster (); //intialize user lobjuserForm
		$this->view->lobjcollegeForm = $lobjcollegeForm; //send the lobjuserForm object to the view
		$this->view->lobjcollegeForm->CollegeName->setAttrib('validator', 'validateCollegeName');

		if($initialConfig['CollegeCodeType'] == 1 ){
			$this->view->lobjcollegeForm->CollegeCode->setAttrib('readonly','true');
			$this->view->lobjcollegeForm->CollegeCode->setValue('xxx-xxx-xxx');
		}else{
			$this->view->lobjcollegeForm->CollegeCode->setAttrib('required','true');
			$this->view->lobjcollegeForm->CollegeCode->setAttrib('validator', 'validateCollegeCode');
		}

		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjcollegeForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjcollegeForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$this->view->lobjcollegeForm->ToDate->setValue ( $ldtsystemDate);

		$lobjcountry = $lobjUser->fnGetCountryList();
		$lobjcollegeForm->Country->addMultiOptions($lobjcountry);
		$lobjcollegeForm->Country->setValue ($this->view->DefaultCountry);
		$lobjcollegeForm->Countrystaff->addMultiOptions($lobjcountry);

		if($this->locale == 'ar_YE')  {
			$this->view->lobjcollegeForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjcollegeForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}

		$lobjUniversity = new GeneralSetup_Model_DbTable_University(); //intialize user Model
		$lobjUniversity = $lobjUniversity->fnGetUniversityList();
		$lobjcollegeForm->AffiliatedTo->addMultiOptions($lobjUniversity);

		$lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();

		//$lobjLevelList = $lobjStaffmaster->fnGetLevelList();
		//$lobjcollegeForm->IdLevel->addMultiOptions($lobjLevelList);

		$lobjStafflist = $lobjStaffmaster->fngetStaffMasterListforDD();
		$lobjcollegeForm->IdStaff->addMultiOptions($lobjStafflist);

		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			unset ( $larrformData ['Close'] );
			if ($lobjcollegeForm->isValid ( $larrformData )) {
				$lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster ();
				$result = $lobjcollegemaster->fnaddCollege ( $larrformData ,$idUniversity,$initialConfig['CollegeCodeType'],$lobjinitialconfig); //instance for adding the lobjuserForm values to DB


				$lobjdeanmaster = new GeneralSetup_Model_DbTable_Deanmaster ();
				$lobjdeanmaster->fnaddDean($result,$larrformData ); //instance for adding the lobjuserForm values to DB

				if($larrformData['IsDepartment'] == 1) {
					$larrDeptformData['DepartmentType'] ='0';
					$larrDeptformData['IdCollege'] =$result;
					$larrDeptformData['DepartmentName'] =$larrformData['CollegeName'];
					$larrDeptformData['ArabicName'] =$larrformData['ArabicName'];
					$larrDeptformData['ShortName'] =$larrformData['ShortName'];
					$larrDeptformData['DeptCode'] =$larrformData['ShortName'].'-'.$larrformData['UpdDate'];
					$larrDeptformData['Active'] =$larrformData['Active'];
					$larrDeptformData['UpdDate'] =$larrformData['UpdDate'];
					$larrDeptformData['UpdUser'] =$larrformData['UpdUser'];

					$cdepartmentmodel = new GeneralSetup_Model_DbTable_Departmentmaster();
					$resultstaff = $cdepartmentmodel->fnaddCollegeDepartment($larrDeptformData,$idUniversity,$initialConfig['DepartmentCodeType']); //instance for adding the lobjuserForm values to DB
				}

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Faculty Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				$this->_redirect( $this->baseUrl . '/generalsetup/collegemaster/index');
			}
		}

	}

	public function editcollegeAction(){

    	$this->view->title="Edit College";  //title
		$lobjcollegeForm = new GeneralSetup_Form_Collegemaster (); //intialize user lobjuserForm
		$this->view->lobjcollegeForm = $lobjcollegeForm; //send the lobjuserForm object to the view
		$lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster(); //user model object

		$lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
		$idUniversity =$this->gobjsessionsis->idUniversity;
		$initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);

		$lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model

		$lobjcountry = $lobjUser->fnGetCountryList();
		$lobjcollegeForm->Country->addMultiOptions($lobjcountry);

		/*$lobjstate = $lobjUser->fnGetStateList();
		$lobjcollegeForm->State->addMultiOptions($lobjstate);*/

		if($this->locale == 'ar_YE')  {
			$this->view->lobjcollegeForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjcollegeForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}

		$lobjUniversity = new GeneralSetup_Model_DbTable_University(); //intialize user Model
		$lobjUniversity = $lobjUniversity->fnGetUniversityList();
		$lobjcollegeForm->AffiliatedTo->addMultiOptions($lobjUniversity);

		$lobjstaffmaster = new GeneralSetup_Model_DbTable_Staffmaster(); //intialize user Model
		$lobjstaffmaster = $lobjstaffmaster->fngetStaffMasterListforDD();
		$lobjcollegeForm->IdStaff->addMultiOptions($lobjstaffmaster);
    	$IdCollege = $this->_getParam('id', 0);


	    	$larrDetails = $lobjcollegemaster->fnGetListofCollege();
	    	$lobjcollegeForm->Idhead->addMultiOptions($larrDetails);

	    $lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		$lobjLevelList = $lobjStaffmaster->fnGetLevelList();
		$lobjcollegeForm->IdLevel->addMultiOptions($lobjLevelList);

		//$this->view->lobjcollegeForm->CollegeCode->setAttrib('readonly','true');

		if($initialConfig['CollegeCodeType'] == 1 ){
			$this->view->lobjcollegeForm->CollegeCode->setAttrib('readonly','true');
			$this->view->lobjcollegeForm->CollegeCode->setValue('xxx-xxx-xxx');
		}else{
			$this->view->lobjcollegeForm->CollegeCode->setAttrib('required','true');
		}



		$larrdeanlist = $lobjcollegemaster->fneditDeanDetails($IdCollege);
		foreach($larrdeanlist as $larrdeanlistresult){
		}

		$lobjcollegeForm->IdStaff->setValue($larrdeanlistresult['IdStaff']);
		$lobjcollegeForm->FromDate->setValue($larrdeanlistresult['FromDate']);


    	$result = $lobjcollegemaster->fneditCollege($IdCollege);
		foreach($result as $colresult){
		}
		$this->view->Isdepartment = $colresult['IsDepartment'];
		$this->view->statid = $colresult['State'];
	    /*$this->view->collegetype=$colresult['CollegeType'];
    	if($colresult['CollegeType']== '0') {
    		$lobjcollegeForm->Idhead->addMultiOptions(array('0' => 'Select'));
    		$lobjcollegeForm->Idhead->setValue('0');

    	} else if($colresult['CollegeType']== '1') {
	    	$larrDetails = $lobjcollegemaster->fnGetListofCollege();
	    	$lobjcollegeForm->Idhead->addMultiOptions($larrDetails);
    	}*/

		$lobjCommonModel = new App_Model_Common();
		$larrStateCityList = $lobjCommonModel->fnGetCityList($colresult['State']);
		$this->view->lobjcollegeForm->City->addMultiOptions($larrStateCityList);


		$lobjUser = new GeneralSetup_Model_DbTable_User();
		$lobjstate = $lobjUser->fnGetStateListcountry($colresult['Country']);
		$this->view->lobjcollegeForm->State->addMultiOptions($lobjstate);

    	$lobjcollegeForm->populate($colresult);

    	$arrPhone1 = explode("-",$colresult ['Phone1']);
		$this->view->lobjcollegeForm->Phone1countrycode->setValue ( $arrPhone1 [0] );
		$this->view->lobjcollegeForm->Phone1statecode->setValue ( $arrPhone1 [1] );
		$this->view->lobjcollegeForm->Phone1->setValue ( $arrPhone1 [2] );

		$arrPhone2 = explode("-",$colresult ['Phone2']);
		$this->view->lobjcollegeForm->Phone2countrycode->setValue ( $arrPhone2 [0] );
		$this->view->lobjcollegeForm->Phone2statecode->setValue ( $arrPhone2 [1] );
		$this->view->lobjcollegeForm->Phone2->setValue ( $arrPhone2 [2] );

		$arrfax = explode("-",$colresult ['Fax']);
		$this->view->lobjcollegeForm->Faxcountrycode->setValue ( $arrfax[0] );
		$this->view->lobjcollegeForm->Faxstatecode->setValue ( $arrfax[1] );
		$this->view->lobjcollegeForm->Fax->setValue ( $arrfax[2] );


		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjcollegeForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjcollegeForm->UpdUser->setValue ( $auth->getIdentity()->iduser);

    	$lobjcollegeForm->CollegeName->removeValidator ('Db_NoRecordExists' );
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($lobjcollegeForm->isValid($formData)) {
	   			$lintIdCollege = $formData ['IdCollege'];
				$college= new GeneralSetup_Model_DbTable_Collegemaster();
				$college->fnupdateCollege($formData,$lintIdCollege);

				$lobjldeanistmodel = new GeneralSetup_Model_DbTable_Deanmaster();
				$lobjldeanistmodel->fnupdateDeanList($formData,$lintIdCollege);//update registrar

				//$lobjldeanistmodel->fninsertDeanList($formData,$lintIdCollege);//insert new registrar

				if($formData ['IsDepartment'] == "1") {
					$cdepartmentmodel = new GeneralSetup_Model_DbTable_Departmentmaster();
					$cdepartmentmodel->fnupdateDepartmentMaster($formData,$lintIdCollege);//update registrar
				}

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Faculty Edit Id=' . $IdCollege,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log


				$this->_redirect( $this->baseUrl . '/generalsetup/collegemaster/index');
			}
    	}
    }

	public function getcollegelistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$lobjCommonModel = new App_Model_Common();
    	$lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster(); //user model object
		//Get Country Id
		$lintidCollege = $this->_getParam('coltype');
		if($lintidCollege == '1') {
			$larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjcollegemaster->fnGetListofCollege());
			echo Zend_Json_Encoder::encode($larrDetails);
		}else if($lintidCollege == '0') {
			echo '[{"name":"Select","key":"0"}]';
		}
	}
	public function getcollegenameAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$college= new GeneralSetup_Model_DbTable_Collegemaster();
		$CollegeName = $this->_getParam('CollegeName');
		$larrDetails = $college->fnValidateCollegeName($CollegeName);
		echo $larrDetails['CollegeName'];

	}

	public function getcollegecodeAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$college= new GeneralSetup_Model_DbTable_Collegemaster();
		$CollegeCode = $this->_getParam('CollegeCode');
		$regex="[$^?+*()|\\]";
		$lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
		$initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
		$sep= $initialConfig['CollegeSeparator'];
		//$CollegeCode=preg_replace($regex,$sep,$CollegeCode);
		$larrDetails = $college->fnValidateCollegeCode($CollegeCode);
		echo $larrDetails['CollegeCode'];

	}
}