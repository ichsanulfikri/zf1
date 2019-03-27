<?php
class GeneralSetup_SubjectmasterController extends Base_Base { //Controller for the User Module

	private $_gobjlog;
	private $_locale;
	
	public function init() { //initialization function
		$this->_locale = Zend_Registry::get('Zend_Locale');
		
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		$this->locale = Zend_Registry::get('Zend_Locale');
		if(!$this->_getParam('search'))
		unset($this->gobjsessionstudent->subjectmasterpaginatorresult);
	}

	public function indexAction() { // action for search and view
		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
				
		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster(); //user model object

		
		/*if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $lobjSubjectmaster->fngetSubjectDetails();
		} else {
			$larrresult = $lobjSubjectmaster->fngetUserSubjectDetails($this->gobjsessionsis->UserCollegeId); //get user details
		}*/
		
		if($this->gobjsessionsis->rolename == "Admin"){
			//subject list
			$larrresult = $lobjSubjectmaster->fngetSubjectDetails();

			//college list 
			$lobjCollegeList = $lobjSubjectmaster->fnGetCollegeList();
		}else{
			//subject list
			$larrresult = $lobjSubjectmaster->fngetUserSubjectDetails($this->gobjsessionsis->idCollege); //get user details
			
			//college list
			$lobjCollegeList = $lobjSubjectmaster->fnGetCollegeList($this->gobjsessionsis->idCollege);
			$this->view->idCollege = $this->gobjsessionsis->idCollege;
		}

		$lobjUniversityMasterList = $lobjSubjectmaster->fnGetUniversityMasterList();
		$lobjform->field1->addMultiOptions($lobjUniversityMasterList);

		
		$lobjform->field5->addMultiOptions($lobjCollegeList);
		$lobjform->field5->setAttrib('OnChange', 'fnGetColgDeptList');


		$lobjBranchList = $lobjSubjectmaster->fnGetBranchList();
		$lobjform->field8->addMultiOptions($lobjBranchList);
		
		$lobjDepartmentList = $lobjSubjectmaster->fnGetDepartmentList();
		$lobjform->field20->addMultiOptions($lobjDepartmentList);
		
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance

		/*
		 * Search
		 */
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ( $larrformData )) {


				if($this->gobjsessionsis->UserCollegeId == '0') {
					
					if($this->gobjsessionsis->rolename == "Admin"){
						$larrresult = $lobjSubjectmaster->fnSearchSubject($lobjform->getValues());
					}else{
						$larrresult = $lobjSubjectmaster->fnSearchUserSubject($lobjform->getValues(),$this->gobjsessionsis->idCollege); //get user details		
					}
				} else {
					$larrresult = $lobjSubjectmaster->fnSearchUserSubject($lobjform->getValues(),$this->gobjsessionsis->UserCollegeId); //get user details
				}
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionstudent->subjectmasterpaginatorresult = $larrresult;
			}
		}else 
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			/* 
			 * Clear 
			 */
			
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectmaster/index');
		}else{
			/*
			 * Normal
			 */
			
			if(isset($this->gobjsessionstudent->subjectmasterpaginatorresult)) {
				$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->subjectmasterpaginatorresult,$lintpage,$lintpagecount);
			} else {
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
			}			
		}


	}

	public function newsubjectmasterAction() { //Action for creating the new user

		if($this->gobjsessionsis->rolename == 'Admin'){
			$lobjSubjectmasterForm = new GeneralSetup_Form_Subjectmaster(); //intialize user lobjuserForm	
		}else{
			$lobjSubjectmasterForm = new GeneralSetup_Form_Subjectmaster( array('idCollege'=>$this->gobjsessionsis->idCollege) ); //intialize user lobjuserForm
			$this->view->idCollege = $this->gobjsessionsis->idCollege;
		}
		
		$this->view->lobjsubjectmasterForm = $lobjSubjectmasterForm; //send the lobjuserForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		//$this->view->lobjsubjectmasterForm->SubjectName->setAttrib('validator', 'validateSubjName');

		  
		$idUniversity =$this->gobjsessionsis->idUniversity;
		$lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
		$initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);

		if($initialConfig['SubjectCodeType'] == 1 ){
			//$this->view->lobjsubjectmasterForm->SubCode->setAttrib('readonly','true');
			//$this->view->lobjsubjectmasterForm->SubCode->setValue('xxx-xxx-xxx');
			$this->view->lobjsubjectmasterForm->SubCode->setAttrib('required','true');
			$this->view->lobjsubjectmasterForm->SubCode->setAttrib('maxlength','15');
			//$this->view->lobjsubjectmasterForm->SubCode->setAttrib('validator', 'validateSubjCode');

		}else{
			$this->view->lobjsubjectmasterForm->SubCode->setAttrib('required','true');
			$this->view->lobjsubjectmasterForm->SubCode->setAttrib('maxlength','15');
			//$this->view->lobjsubjectmasterForm->SubCode->setAttrib('validator', 'validateSubjCode');
		}

		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster(); //intialize user Model

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsubjectmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsubjectmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$this->view->lobjuniversityForm = new GeneralSetup_Form_University (); //intialize user lobjuniversityForm
		$this->lobjstaffmodel = new GeneralSetup_Model_DbTable_Staffmaster();


		$lobjdeftype = new App_Model_Definitiontype();
		$religionlist = $lobjdeftype->fnGetDefinationMs('Religion');
		$lobjSubjectmasterForm->IdReligion->addMultiOptions($religionlist);

		$componentlist = $lobjdeftype->fnGetDefinationMs('Subject Components');
		$lobjSubjectmasterForm->Idcomponents->addMultiOptions($componentlist);


		/*if($this->gobjsessionsis->UserCollegeId == '0') {
			$lobjDepartmentList = $lobjSubjectmaster->fnGetDepartmentList();
			} else {
			$lobjDepartmentList = $lobjSubjectmaster->fnGetUserDepartmentList($this->gobjsessionsis->UserCollegeId); //get user details
			}
			$lobjSubjectmasterForm->IdDepartment->addMultiOptions($lobjDepartmentList);*/


		$lobjCourseTypeList = $lobjSubjectmaster->fnGetCourseTypeList();
		$lobjSubjectmasterForm->CourseType->addMultiOptions($lobjCourseTypeList);
		if($this->locale == 'ar_YE')
		{
			$this->view->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );
		$this->view->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
		$this->view->errMSg = '';


		// check for SUBJECTCODE format. is it manual(0) or auto(1) from tbl_config
		$this->lobjconfig = new GeneralSetup_Model_DbTable_Initialconfiguration();
		$idUniversity = $this->gobjsessionsis->idUniversity;
		$confdetail = $this->lobjconfig->fnGetInitialConfigDetails($idUniversity);
		$CourseIdType = $confdetail['SubjectCodeType'];
		$this->view->lstrsubjectcodeconf = $confdetail['CourseIdType'];
		$CourseIdFormat = $confdetail['CourseIdFormat'];
		if($CourseIdType=='0') {
			$this->view->makeRegFieldHide = '0';
			$this->view->lobjsubjectmasterForm->SubCode->setValue('');
		} else {
			$this->view->makeRegFieldHide = '1';
			$codeGenrateObj = new Cms_CodeGeneration();
			$codeGenrateResult = $codeGenrateObj->CourseCodeGenration($idUniversity, $confdetail);
			$this->view->lobjsubjectmasterForm->SubCode->setValue($codeGenrateResult)->setAttribs(array('readonly' => 'true'));
		}




		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($lobjSubjectmasterForm->isValid ( $larrformData )) {

				//asd($larrformData);
				 
				if($CourseIdType=='1') {
					$coderesult = $codeGenrateObj->CourseCodeGenration($idUniversity, $confdetail);
					$SubCode = $coderesult['SubCode'];
					$larrformData['SubCodeFormat'] = $coderesult['SubCodeFormat'];
					$larrformData['IdFormat'] = $coderesult['IdFormat'];
				}
				else if($CourseIdType=='0') {
					$SubCode = $larrformData['SubCode'];
				}
				$checkCodeDuplicate = $lobjSubjectmaster->fngetsubjcode($SubCode);
				//asd($checkCodeDuplicate);
				if(count($checkCodeDuplicate)==0) {
					$this->view->errMSg = '';
					if($larrformData['ReligiousSubject']=='0') { $larrformData['IdReligion']=0;  }
					if(!$larrformData['IdReligion'])$larrformData['IdReligion']=0;
					 
					$larrformData['SubCode'] = $SubCode;
					$result = $lobjSubjectmaster->fnaddSubject($larrformData,$idUniversity,$initialConfig['SubjectCodeType']); //instance for adding the lobjuserForm values to DB
					//print_r($result);die;
					$lobjSubjectmaster->fninsertChiefofSubjectList($larrformData,$result);
					$lobjSubjectmaster->fninsertSubjectComponentcredithours($larrformData,$result);

					// Write Logs
					$priority=Zend_Log::INFO;
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New subject Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));

					$this->_redirect( $this->baseUrl . '/generalsetup/subjectmaster/index');
				}
				else {
					$this->view->errMSg = 'Course Code Already Exists. Please Try Different.';

				}

			}
		}

	}

	public function subjectmasterlistAction() { //Action for the updation and view of the user details

		$lobjSubjectmasterForm = new GeneralSetup_Form_Subjectmaster(); //intialize user lobjuserForm
		
		$this->view->lobjsubjectmasterForm = $lobjSubjectmasterForm; //send the lobjuserForm object to the view
		
		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster(); //intialize user Model
		$this->lobjstaffmodel = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lintisubject = ( int ) $this->_getParam ( 'id' );
		$this->view->idsubject = $lintisubject;

		$this->view->lobjuniversityForm = new GeneralSetup_Form_University (); //intialize user lobjuniversityForm

		if($this->locale == 'ar_YE')
		{
			$this->view->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->view->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}

		$lobjdeftype = new App_Model_Definitiontype();
		$religionlist = $lobjdeftype->fnGetDefinationMs('Religion');
		$lobjSubjectmasterForm->IdReligion->addMultiOptions($religionlist);

		$componentlist = $lobjdeftype->fnGetDefinationMs('Subject Components');
		$lobjSubjectmasterForm->Idcomponents->addMultiOptions($componentlist);

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );
		$this->view->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());

		$larrrstaffesult = $lobjSubjectmaster->fngetSubjectCoordinator($lintisubject);
		//asd($larrrstaffesult,false);
		$this->view->lobjuniversityForm->FromDate->setValue ($larrrstaffesult['FromDate'] );
		$this->view->lobjuniversityForm->IdStaff->setValue ($larrrstaffesult['IdStaff'] );

		$larrresult = $lobjSubjectmaster->fnviewSubject($lintisubject); //getting user details based on userid
		$this->view->displayResult =    $larrresult;
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$lobjDepartmentList = $lobjSubjectmaster->fnGetDepartmentList();
		} else {
			$lobjDepartmentList = $lobjSubjectmaster->fnGetUserDepartmentList($this->gobjsessionsis->UserCollegeId);
		}
		$lobjSubjectmasterForm->IdDepartment->addMultiOptions($lobjDepartmentList);

		$lobjCourseTypeList = $lobjSubjectmaster->fnGetCourseTypeList();
		$lobjSubjectmasterForm->CourseType->addMultiOptions($lobjCourseTypeList);

		if($larrresult['MandatoryCreditHrs'] == 0 ){
			$this->view->lobjsubjectmasterForm->CreditHours->setAttrib('readonly','true');
		}else{
			$this->view->lobjsubjectmasterForm->CreditHours->setAttrib('required','false');
		}
		if($larrresult['MandatoryAmount'] == 0 ){
			$this->view->lobjsubjectmasterForm->AmtPerHour->setAttrib('readonly','true');
		}else{
			$this->view->lobjsubjectmasterForm->AmtPerHour->setAttrib('required','false');
		}

		$larrrcomponenetesult = $lobjSubjectmaster->fngetSubjectComponentlist($lintisubject);

		$this->view->larrcomponent=$larrrcomponenetesult;

		$lobjSubjectmasterForm->populate($larrresult);

		$this->view->lobjsubjectmasterForm->SubCode->setAttrib('readonly','true');
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsubjectmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsubjectmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);

		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				if(!$larrformData['IdReligion'])$larrformData['IdReligion']=0;
				if($larrformData['ReligiousSubject']==0)$larrformData['IdReligion']=0;

				unset ( $larrformData ['Save'] );
				if ($lobjSubjectmasterForm->isValid ( $larrformData )) {

					$lintisubject = $larrformData ['IdSubject'];

					$lobjSubjectmaster->fnupdateChiefofSubjectList($larrformData,$lintisubject);
					
					$lobjSubjectmaster->fndeleteSubjectComponentcredithours($lintisubject);
					$lobjSubjectmaster->fninsertSubjectComponentcredithours($larrformData,$lintisubject);

					$lobjSubjectmaster->fnupdateSubject($lintisubject, $larrformData );
					// Write Logs
					$priority=Zend_Log::INFO;
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'subject Edit Id=' . $lintisubject,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log



					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/subjectmaster/index');
				}
			}
		}
		$this->view->lobjdepartmentmasterForm = $lobjSubjectmasterForm;
	}

	public function getmandatoryfieldsAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster(); //intialize user Model

		$lintIdCourseType = $this->_getParam('CourseType');
		$larrDetails = $lobjSubjectmaster->fngetMandatoryfields($lintIdCourseType);
		echo Zend_Json_Encoder::encode($larrDetails);
		//echo $larrDetails['MandatoryCreditHrs'];
	}

	public function getsubjnameAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$subjName = $this->_getParam('subjName');

		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster();
		$larrDetails = $lobjSubjectmaster->fngetsubjname($subjName);
		echo $larrDetails['SubjectName'];

	}


	public function getsubjcodeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$subjcode = $this->_getParam('subjcode');
		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster();
		$larrDetails = $lobjSubjectmaster->fngetsubjcode($subjcode);
		echo count($larrDetails);
		exit;
		/*if(!empty($larrDetails)){
			echo 1;
			}
			else{
			echo 0;
			}*/
		//echo $larrDetails['SubCode'];

	}

	public function getdepartmentAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$Idfaculty = $this->_getParam('idFaculty');
		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster();
		$larrdepartmentlist = $this->lobjCommon->fnResetArrayFromValuesToNames($lobjSubjectmaster->fnGetDepartmentList($Idfaculty));
		echo Zend_Json_Encoder::encode($larrdepartmentlist);
	}

}