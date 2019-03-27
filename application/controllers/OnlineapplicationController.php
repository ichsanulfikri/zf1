<?php
class OnlineapplicationController extends Zend_Controller_Action {
	private $lobjUser;
	private $lobjStudentApplicationForm;
	private $lobjcollegemaster;
	private $lobjprogrambranch;
	private $lobjstudentapplication;
	private $lobjstudenteducation;
	private $lobjplacementtest;
	public $lobjCommon;
	private $lobjsponsor;
	private $lobjstaff;
	private $lobjloginform;
	private $applicantSession;
	private $applicantmodel;
	private $applicantpersonaldetailmodel;
	private $forgotpasswordForm;
	private $applicantpreferredForm;
	private $lobjqualificationform;
	private $lobjsubjectgradeform;
	private $lobjqualification;
	private $lobjsubjectdetail;
	private $lobjinstituion;
	private $lobjqualificationsub;
	private $lobjappconfigmodel;
	private $lobjuniversitymodel;
	private $lobjsubjectgrade;



	public function init() {
		$this->_helper->layout()->setLayout('/reg/usty1');
		$this->applicantSession = new Zend_Session_Namespace('applicant');
		$this->fnsetObj();
	}

	public function fnsetObj(){
		$this->lobjCommon = new App_Model_Common();
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();
		$this->lobjStudentApplicationForm = new Application_Form_Manualapplication();
		$this->lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster();
		$this->lobjprogrambranch = new GeneralSetup_Model_DbTable_Programbranch();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjstudentapplication = new Application_Model_DbTable_Studentapplication();
		$this->lobjstudenteducation = new Application_Model_DbTable_Studenteducation();
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjplacementtest = new Application_Model_DbTable_Placementtest();
		$this->lobjsponsor = new GeneralSetup_Model_DbTable_Sponsor();
		$this->lobjstaff = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->applicantmodel = new App_Model_Applicant();
		$this->applicantpersonaldetailmodel = new App_Model_Applicantpersonaldetail();
		$this->lobjloginform = new App_Form_Onlineapplogin();
		$this->applicantRegForm = new App_Form_Onlineregistration();
		$this->forgotpasswordForm = new App_Form_Forgotpasswordapplicant();
		$this->lobjqualificationform = new App_Form_OnlineapplicationQualification();
		$this->lobjsubjectgradeform = new App_Form_OnlineapplicationAddsubject();
		$this->applicantpreferredForm = new App_Form_Preferredapplicant();
		$this->lobjqualification = new App_Model_OnlineapplicationQualification();
		$this->lobjsubjectdetail = new App_Model_Onlineapplicationsubjectdetail();
		$this->lobjinstituion = new Application_Model_DbTable_Institutionsetup();
		$this->applicationconfig = new Application_Model_DbTable_ApplicationConfigStatusMessage();
		$this->lobjqualificationsub = new Application_Model_DbTable_QualificationsubjectMapping();
		$this->lobjappconfigmodel = new Application_Model_DbTable_ApplicationConfig();
		$this->lobjuniversitymodel = new GeneralSetup_Model_DbTable_University();
		$this->lobjsubjectgrade = new Application_Model_DbTable_Subjectgradetype();
	}

	public function applicationstatusAction(){
		$this->view->lobjStudentApplicationForm = $this->lobjStudentApplicationForm;
		$applicantId = $this->applicantSession->IdApplicant;
		$this->view->idapplicant= $applicantId = $this->applicantSession->IdApplicant;
		$status = $this->lobjstudentapplication->fngetapplicantstatus($applicantId);
		$msg = $this->applicationconfig->applicationstatusmsg($applicantId);
		if(isset($msg[0]['Message'])) {
			$this->view->msg    = $msg[0]['Message'];
		}
		else {
			$this->view->msg = "";
		}
		$this->view->status = $status['DefinitionDesc'];
		$email = $this->_getParam('email');
		if(isset($email)){
			$this->view->email = $email;
		}
		//$applicantdetails= $this->applicantmodel->fngetapplicantdetails($applicantId);

		// GET THE APPLICANT TAB VALUE
		$applicantGtTabValue = $this->applicantmodel->fngetapplicantTabDetails($applicantId);
		$this->view->applicantTabvalue = $applicantGtTabValue[0]['tabValue'];
		$this->view->selectedInactiveStyle = "style='display:none;'";
		$this->view->selectedActiveStyle = "style='display:block;'";
		// END



	}

	public function loginAction(){
		$this->_helper->layout()->setLayout('/login/usty');
		$this->view->lobjloginform = $this->lobjloginform;
		$this->view->lobjloginform->setMethod('Post');
		if ($this->getRequest()->isPost()) {
			$emaillogin = $this->getRequest()->getParam("Emaillogin");
			$password = $this->getRequest()->getParam("password");
			$auth = new App_Model_Applicant();
			$result = $auth->fnAgentauth($emaillogin, $password);
			if (!$result) {
				$this->view->alertError = 'Incorrect Email and Password, please try again';
				return $this->render('login');
			} else {
				$this->applicantSession->__set('IdApplicant',$result);
				$applicantId = $this->applicantSession->IdApplicant;
				$active = $auth->fnapplicantexistcheck1($emaillogin);
				if(isset($active)){
					$status = 198;
					$this->applicantmodel->fnupdateApplicantDetails($applicantId,$status);
				}
				else{
					$existing = $auth->fnapplicantexistcheck2($emaillogin);
					if($existing == 1){
						$registerdetail = $this->applicantmodel->fngetregisterdate($applicantId);
						if(isset($registerdetail['RegisteredDate'])){
							$registerdate = date('Y-m-d',strtotime($registerdetail['RegisteredDate']));
							if($registerdetail['ApplicationValidity'] == "year"){
								$validitymonths = 12*$registerdetail['ApplicationVal'];
								$validitydate = strtotime ( '+'.$validitymonths.'month' , strtotime ( $registerdate ) ) ;
								$validitydate = date ( 'Y-m-d' , $validitydate );
								$currentdate = date('Y-m-d');
								if($currentdate>=$validitydate){
									$status = 191;
									$this->applicantmodel->fnupdateApplicantDetails($applicantId,$status);
								}
								else{
									$this->_redirect( $this->baseUrl . '/onlineapplication/applicationstatus');

								}
							}
							else if($registerdetail['ApplicationValidity'] == "sem"){
								$validitymonths = 6*$registerdetail['ApplicationVal'];
								$validitydate = strtotime ( '+'.$validitymonths.'month' , strtotime ( $registerdate ) ) ;
								$validitydate = date ( 'Y-m-d' , $validitydate );
								$currentdate = date('Y-m-d');
								if($currentdate>=$validitydate){
									$status = 191;
									$this->applicantmodel->fnupdateApplicantDetails($applicantId,$status);
								}
								else{
									$this->_redirect( $this->baseUrl . '/onlineapplication/applicationstatus');

								}
							}
						}
					}
				}
				$this->_redirect( $this->baseUrl . '/onlineapplication/applicationstatus');
			}
		}
	}

	public function logoutAction(){
		Zend_Session:: namespaceUnset('applicant');
		Zend_Session::destroy();
		$this->_redirect( $this->baseUrl . '/onlineapplication/login');
	}


	public function registerapplicantAction(){
		$this->_helper->layout()->setLayout('/reg/usty1');
		$this->view->applicantRegForm = $this->applicantRegForm;
		$email = new Cms_EmailCheck();
		$this->view->applicantRegForm->Emaillogin->addValidator($email);
		$this->view->applicantRegForm->setMethod('Post');
		if($this->_request->getPost()){
			if (!$this->view->applicantRegForm->isValid($this->getRequest()->getPost())) {
				return $this->render("registerapplicant");
			}
			$larrformData = $this->_request->getPost();
			$ldtsystemDate = date ( 'Y-m-d H:i:s' );
			$larrformData['RegisteredDate'] = $ldtsystemDate;
			$idUniversity = 1;
			$lobjSubjectsetup = new Application_Model_DbTable_Subjectsetup();
			$configArray = $lobjSubjectsetup->getConfigDetail($idUniversity);
			$codegenObj = new Cms_CodeGeneration();
			$coderesult= $codegenObj->ApplicantCodeGenration($idUniversity, $configArray[0]);
			$larrformData['ApplicantCode'] = $coderesult['ApplicantCode'];
			$applicantId = $this->applicantmodel->fnAddNewApplicant($larrformData);
			$appDetailData = array();
			$appDetailData['status'] = 191;
			$appDetailData['IdApplicant'] = $applicantId;
			$appDetailData['ApplicantCodeFormat'] = $coderesult['ApplicantCodeFormat'];
			$appDetailData['ApplicantCode'] = $coderesult['ApplicantCode'];
			$appDetailData['IdFormat'] = $coderesult['IdFormat'];
			$result = $this->applicantpersonaldetailmodel->addNewapplicantdetail($appDetailData);
			$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
			$url = $baseUrl.'/onlineapplication/login';
			$this->view->msg = "Your registration is succesfully created <a href= '".$url."'>click here</a> for login";
			$message = "Congratulations you have been successfully registered.<br />Your account details are as follows:<br />
									<strong>UserName</strong>: ".$larrformData['Emaillogin']."<br />
									<strong>Password</strong>: ".$larrformData['password']."
									<br />
									Regards,<br />
									AUCMS";
			$lobjsendmail = new Cms_SendMail();
			$lobjsendmail->fnSendMail($larrformData['Emaillogin'],"Registration Successful",$message,$larrformData['Emaillogin']);
			return $this->render('registerapplicant');
		}
	}

	public function forgotpasswordapplicantAction(){
		$this->_helper->layout()->setLayout('/reg/usty1');
		$this->view->forgotpasswordForm = $this->forgotpasswordForm;
		$this->view->forgotpasswordForm->setMethod('Post');
		if($this->_request->getPost()){
			$larrformData = $this->_request->getPost();
			$ret = $this->applicantmodel->chekemailExist($larrformData['Emaillogin']);
			if(!$ret){
				$this->view->msg = "Email does not exist";
				return $this->render('forgotpasswordapplicant');
			}
			$newPass = $this->applicantmodel->changepassword($larrformData);
			$this->view->msg = "Your Password has been changed your new password is ".$newPass;
			return $this->render('forgotpasswordapplicant');
		}
	}

	public function summarystudentapplicantAction(){
		$this->_helper->layout()->setLayout('/reg/usty1');
		$id = $this->getRequest()->getParam("id");
		if(!isset($id)){
			$id = $this->applicantSession->IdApplicant;
		}
		$ret = $this->applicantmodel->getApplicantDetails($id);
		$this->view->applicantDetail = $ret;
	}

	public function addpersonalparticularsAction(){
		//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idapplicant=$this->applicantSession->IdApplicant;
		if ($this->_request->isPost()) { // save opeartion
			$lobjFormData = $this->_request->getPost();
			if(isset($lobjFormData['ExtraIdField1']) && $lobjFormData['ExtraIdField1'] != NULL){ 
				$extraidActive = $this->applicantmodel->fncheckextraid($lobjFormData['ExtraIdField1']);
				if(isset($extraidActive['ExtraIdField1']) && $extraidActive['ExtraIdField1'] != NULL){
					$status = 198;
					$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
					$data = array('key'=>'true','value'=>'redirect','email'=>$extraidActive['email']);
					echo Zend_Json_Encoder::encode($data);
					die;
				}
				else if(!(isset($extraidActive['ExtraIdField1']))){ 
					$extraidExist =  $this->applicantmodel->fncheckextraid1($idapplicant,$lobjFormData['ExtraIdField1']);
					if((isset($extraidExist['ExtraIdField1']) && $extraidExist['ExtraIdField1'] != NULL) || (isset($extraidExist['ExtraIdField']) && $extraidExist['ExtraIdField'] != NULL)){
						if(trim($extraidExist['ExtraIdField1']) != "") {
							$detail = $this->applicantmodel->fngetdetails($extraidExist['ExtraIdField1']);
						}
						else {
							$detail = $this->applicantmodel->fngetdetails($extraidExist['ExtraIdField']);
						}
						$registerdetail = $this->applicantmodel->fngetregisterdate($idapplicant);
						if(isset($registerdetail['RegisteredDate'])){
							$registerdate = date('Y-m-d',strtotime($registerdetail['RegisteredDate']));
							$registerdetail['ApplicationValidity'];
							if($registerdetail['ApplicationValidity'] == "year"){
								$validitymonths = 12*$registerdetail['ApplicationVal'];
								$validitydate = strtotime ( '+'.$validitymonths.'month' , strtotime ( $registerdate ) ) ;
								$validitydate = date ( 'Y-m-d' , $validitydate );
								$currentdate = date('Y-m-d');
								if($currentdate>=$validitydate){
									$status = 192;
									$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
									$this->applicantmodel->fnaddapplicant($idapplicant,$lobjFormData);
									$data = array('key'=>'false','value'=>'redirect');
									echo Zend_Json_Encoder::encode($data);
									die;
									
								}
								else{
									$status = 198;
									$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
									if($detail['olExtraIdField1'] == ''){
										if(!isset($detail['PEmail'])) $detail['PEmail'] = "";
										$data = array('key'=>'true','value'=>'redirect','email'=> $detail['PEmail']);
									}
									else if($detail['maExtraIdField1'] == ''){
										if(!isset($detail['email'])) $detail['email'] = "";
										$data = array('key'=>'true','value'=>'redirect','email'=> $detail['email']);
									}
									else{
										$data = array('key'=>'true','value'=>'redirect','email'=> '');
									}
									echo Zend_Json_Encoder::encode($data);
									die;
								}
							}
							else if($registerdetail['ApplicationValidity'] == "sem"){
								$validitymonths = 6*$registerdetail['ApplicationVal'];
								$validitydate = strtotime ( '+'.$validitymonths.'month' , strtotime ( $registerdate ) ) ;
								$validitydate = date ( 'Y-m-d' , $validitydate );
								$currentdate = date('Y-m-d');
								if($currentdate>=$validitydate){
									$status = 192;
									$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
									$this->applicantmodel->fnaddapplicant($idapplicant,$lobjFormData);
									$data = array('key'=>'false','value'=>'redirect');
									echo Zend_Json_Encoder::encode($data);
									die;
									
								}
								else{
									$status = 198;
									$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
									if($detail['olExtraIdField1'] == ''){
										$data = array('key'=>'true','value'=>'redirect','email'=> $detail['PEmail']);
									}
									else if($detail['maExtraIdField1'] == ''){
										$data = array('key'=>'true','value'=>'redirect','email'=> $detail['email']);
									}
									else{
										$data = array('key'=>'true','value'=>'redirect','email'=> '');
									}
									echo Zend_Json_Encoder::encode($data);
									die;	
								}

							}
						}
							
					}
				}
			}
			$status = 192;
			$this->applicantmodel->fnupdateApplicantDetails($idapplicant,$status);
			$this->applicantmodel->fnaddapplicant($idapplicant,$lobjFormData);
			$tabvalue = '2';
			$this->applicantmodel->fnupdapplicantTabDetails($idapplicant,$tabvalue);
			$data = array('key'=>'false','value'=>'redirect');
			echo Zend_Json_Encoder::encode($data);
			die;

		}
	}


	public function addpreferreddetailAction(){
		$awardLevelObj = new App_Model_Applicant();
		$this->_helper->viewRenderer->setNoRender();
		$formdata = $this->_request->getPost();
		$IntakeArray = $formdata['IdIntake'];
		$Idapplicant = $this->applicantSession->IdApplicant;
		$ret = $awardLevelObj->getapplicantpreferreddetail($Idapplicant);
		if(!empty($ret)){
			$awardLevelObj->deletepreffered($Idapplicant);
		}
		$i = 1;
		foreach($IntakeArray as $key => $val){
			$temp['IdApplicant'] = $Idapplicant;
			$temp['IdIntake'] = $IntakeArray[0];
			$temp['IdProgramLevel'] = $formdata['programlevel_'.$i];
			$temp['IdPriorityNo'] = $i;
			$temp['IdProgram'] = $formdata['program_'.$i];
			$temp['IdBranch'] = $formdata['branch_'.$i];
			$temp['IdScheme'] = $formdata['scheme_'.$i];
			if(isset($formdata['program_'.$i]) && !empty($formdata['program_'.$i])){
				$ret = $awardLevelObj->addPreferredApplicant($temp);
			}
			$i++;
		}
	}


	public function programlistajaxAction(){
		$IdIntake = $this->getRequest()->getParam('IntakeId');
		$IdProgramLevel = $this->getRequest()->getParam('programlevelId');
		$awardLevelObj = new App_Model_Applicant();
		$ret = $awardLevelObj->getProgram($IdIntake,$IdProgramLevel);
		$this->view->program = $ret;
		$this->view->intake = $IdIntake;
	}

	public function branchlistajaxAction(){
		$IdIntake = $this->getRequest()->getParam('IntakeId');
		$IdProgram = $this->getRequest()->getParam('programId');
		$awardLevelObj = new App_Model_Applicant();
		$ret = $awardLevelObj->getBranch($IdIntake,$IdProgram);
		$this->view->intake = $IdIntake;
		$this->view->branch = $ret;
	}

	public function admissionstatusAction(){
		$this->_helper->layout->disableLayout();
		$idapplicant=$this->applicantSession->IdApplicant;
		if ($this->_request->isPost()) { // save opeartion
			$lobjFormData = $this->_request->getPost();
			if(isset($lobjFormData['ConfirmDeclaration'])){
				$this->applicantmodel->fnupdateapplicantstatus($idapplicant);
				$status = $this->lobjstudentapplication->fngetapplicantstatus($idapplicant);
				$this->view->status = $status['DefinitionDesc'];
				$msg = $this->applicationconfig->applicationstatusmsg($idapplicant);
				$this->view->msg    = $msg[0]['Message'];
				$this->view->idapplicant = $idapplicant;
				$tabvalue = '5';
				$this->applicantmodel->fnupdapplicantTabDetails($idapplicant,$tabvalue);
				$this->render("admissionstatus");
			}
		}
	}

	public function schemelistajaxAction(){
		$IdIntake = $this->getRequest()->getParam('IntakeId');
		$IdProgram = $this->getRequest()->getParam('programId');
		$awardLevelObj = new App_Model_Applicant();
		$ret = $awardLevelObj->getScheme($IdProgram);
		$this->view->intake = $IdIntake;
		$this->view->scheme = $ret;

	}

	public function newstudentapplicationAction(){
		if(!isset($this->applicantSession->IdApplicant) && $this->applicantSession->IdApplicant == NULL){
			$this->_redirect( $this->baseUrl . '/onlineapplication/login');
		}
		$idapplicant=$this->applicantSession->IdApplicant;
		$this->view->applicantpreferredForm = $this->applicantpreferredForm;
		$this->view->applicantqualification = $this->lobjqualification->fngetapplicantQualification($idapplicant);
		//echo "<pre>";print_r($this->view->applicantqualification);die;
		//get active university
		$univedet = $this->lobjuniversitymodel->fngetActiveUniversity();
		$Iduniversity = $univedet['IdUniversity'];
		// Now get Intake setting from application configuration
		$noofpreferred = 3;
		$appconfigdet = $this->lobjappconfigmodel->fngetApplicationconfig($Iduniversity);
		if(!empty($appconfigdet) && $appconfigdet['NoofPreferred'] != 0){
			$noofpreferred = $appconfigdet['NoofPreferred'];
		}
		$this->view->noofpreferred = $noofpreferred;
		$programPreferred = array();
		// Get application detail
		$appdet = $this->applicantmodel->getAppdet($idapplicant);
		$tempIntakeList = $this->applicantmodel->getIntake($appdet['RegisteredDate']);
		$IntakeList = array();
		foreach($tempIntakeList as $intake){
			$IntakeList[] = $intake;
		}
		$this->view->IntakeList = $IntakeList;
		$preferredDet = $this->applicantmodel->getapplicantpreferreddetail($idapplicant);
		if(!empty($preferredDet)){
			$this->view->preferreddet = $preferredDet;
			 
		}
		$this->view->idapplicant = $idapplicant;
		// GET THE APPLICANT TAB VALUE
		$applicantGtTabValue = $this->applicantmodel->fngetapplicantTabDetails($idapplicant);
		$this->view->applicantTabvalue = $applicantGtTabValue[0]['tabValue'];
		$this->view->selectedInactiveStyle = "style='display:none;'";
		$this->view->selectedActiveStyle = "style='display:block;'";
		// END

		$applicantEmail =$this->applicantmodel->fngetemailaddress($idapplicant);
		$this->view->applicantEmail = $applicantEmail;
		$this->view->lobjqualificationform = $this->lobjqualificationform;
		$this->view->lobjsubjectgradeform = $this->lobjsubjectgradeform;
		$this->lobjconfig = new GeneralSetup_Model_DbTable_Initialconfiguration();
		$ExtarIdConfDetail = array();
		$idUniversity = 1;
		$confdetail = $this->lobjconfig->fnGetInitialConfigDetails($idUniversity);
		$lobjuniversitymodel = new GeneralSetup_Model_DbTable_University();
		$larrdefaultlanguage = $lobjuniversitymodel->fngetUniversityLanguage($idUniversity);
		$this->view->defaultLanguage = $larrdefaultlanguage[0]['UnivLang'];
		$confdetail = $this->lobjconfig->fnGetInitialConfigDetails($idUniversity);

		$ExtarIdConfDetail['count'] = $confdetail['ExtarIdDtlCount'];
		$temp1 = array();
		for($i=1 ; $i <= $ExtarIdConfDetail['count']; $i++){
			$field = 'ExtarIdDtl'.$i;
			$ExtarIdConfDetail['fields'][$i] = $confdetail[$field];
		}
		//		//echo "<pre>";
		//		$count=count($ExtarIdConfDetail['fields']);
		//		for($i=0;$i<$count;$i++){
		//			$extraid[$i]['key']=$i+1;
		//			$extraid[$i]['value']=$ExtarIdConfDetail['fields'][$i+1];
		//		}
		//		//print_r($extraid);die;
		$this->view->ExtraIdconfDetail = $ExtarIdConfDetail;

		$nameConfDetail = array();
		$nameConfDetail['count'] = $confdetail['NameDtlCount'];
		$temp = array();
		for($i=1 ; $i <= $nameConfDetail['count']; $i++){
			$field = 'NameDtl'.$i;
			$temp[$i] = $confdetail[$field];
		}
		$nameConfDetail['fields'] = $temp;
		$this->view->NameconfDetail = $nameConfDetail;
		$count = 0;
		$field = 1;
		for($i=0;$i<5;$i++){
			if($confdetail['PPField'.$field] != NULL){
				$count++;
				$field++;
			}
			else{
				break;
			}
		}
		$passportDtl =array();
		$passportDtl['count'] = $count;
		for($i=1 ; $i <= $passportDtl['count']; $i++){
			$field = 'PPField'.$i;
			$passportDtl['fields'][$i] = $confdetail[$field];
		}
		$this->view->passportDtl = $passportDtl;
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$this->view->upload_mb = min($max_upload, $max_post, $memory_limit);

		$status = $this->lobjstudentapplication->fngetapplicantstatus($idapplicant);
		$this->view->status = $status['DefinitionDesc'];
		$msg = $this->applicationconfig->applicationstatusmsg($idapplicant);
		$this->view->msg    = $msg[0]['Message'];
		//get applicant details

		$applicantdetails= $this->applicantmodel->fngetapplicantdetails($idapplicant);
		if(isset($applicantdetails['HomeNumber']) && $applicantdetails['HomeNumber']!=''){
			$arrPhone1 = explode("-",$applicantdetails ['HomeNumber']);
			$applicantdetails['HomeCountryCode']=$arrPhone1[0];
			$applicantdetails['HomeStateCode']=$arrPhone1[1];
			$applicantdetails['HomeNumber']=$arrPhone1[2];
		}
		if(isset($applicantdetails ['OfficeNumber']) && $applicantdetails['OfficeNumber']!=''){
			$arrPhone2 = explode("-",$applicantdetails ['OfficeNumber']);
			$applicantdetails['OfficeCountryCode']=$arrPhone2[0];
			$applicantdetails['OfficeStateCode']=$arrPhone2[1];
			$applicantdetails['OfficeNumber']=$arrPhone2[2];
		}
		if(isset($applicantdetails ['MobileNumber']) && $applicantdetails['MobileNumber']!=''){
			$arrPhone3 = explode("-",$applicantdetails ['MobileNumber']);
			$applicantdetails['MobileCountryCode']=$arrPhone3[0];
			$applicantdetails['MobileStateCode']=$arrPhone3[1];
			$applicantdetails['MobileNumber']=$arrPhone3[2];
		}
		$this->view->applicantdetails =$applicantdetails;
		if(isset($applicantdetails ['Nationality']) && $applicantdetails['Nationality']!='')
		{
			$countrycode =  $this->lobjUser->fngetcountrycode($applicantdetails['Nationality']);
			$this->view->countrycode = $countrycode['CountryCode'];
		}
		if(isset($applicantdetails ['CorrespondenceCountry']) && $applicantdetails['CorrespondenceCountry']!=''){
			$this->view->state = $this->lobjUser->fnGetStateListcountry($applicantdetails['CorrespondenceCountry']);
		}
		$lobjCommonModel = new App_Model_Common();

		if(isset($applicantdetails ['CorrespondenceProvince']) && $applicantdetails['CorrespondenceProvince']!=''){
			$this->view->city = $lobjCommonModel->fnGetCityList($applicantdetails['CorrespondenceProvince']);
		}

		$this->view->lobjStudentApplicationForm = $this->lobjStudentApplicationForm;   // send form to view
		$ldtsystemDate = date ('Y-m-d');
		$this->view->lobjStudentApplicationForm->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjStudentApplicationForm->ApplicationDate->setValue ( $ldtsystemDate );
		$this->view->lobjStudentApplicationForm->UpdUser->setValue (1);
		$this->view->lobjStudentApplicationForm->ApplicationType->setValue (1);
		$lobjcountry = $this->lobjUser->fnGetCountryList();
		$this->view->country = $lobjcountry;
		$this->view->maritalstatus = $this->lobjdeftype->fnGetDefinationMs('Marital Status');

		$lobjsponsor = $this->lobjsponsor->fnGetSponsorList();

		//$larrSchoollist= $this->lobjCommon->fnResetArrayFromValuesToNames();
		$initialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
		$larrInitialSettings = $initialconfig->fnGetInitialConfigDetails(1);
		$this->view->InternationalPlacementTest = $larrInitialSettings['InternationalPlacementTest'];
		$this->view->InternationalCertification = $larrInitialSettings['InternationalCertification'];
		$this->view->InternationalAndOr = $larrInitialSettings['InternationalAndOr'];

		$this->view->LocalPlacementTest = $larrInitialSettings['LocalPlacementTest'];
		$this->view->LocalCertification = $larrInitialSettings['LocalCertification'];
		$this->view->LocalAndOr = $larrInitialSettings['LocalAndOr'];
		$this->view->Completionofyears = empty($larrInitialSettings['Completionofyears']) ? "Completed Min Years(25)":$larrInitialSettings['Completionofyears'];


		$parentjob = $this->lobjdeftype->fnGetDefinationMs('Parent Job');
		$Reference = $this->lobjdeftype->fnGetDefinationMs('Reference');
		$jacketsize = $this->lobjdeftype->fnGetDefinationMs('Jacket Size');
		$awardlevel = $this->lobjdeftype->fnGetDefinationMs('Award');
		$this->view->award = $awardlevel;
		$grade = $this->lobjdeftype->fnGetDefinationMs('Grade');
		$TypeOfSchool = $this->lobjdeftype->fnGetDefinationMs('Type Of School');


		$larrSchoollist = $this->lobjCommon->fnGetSchoolList();

		$this->lobjStudentApplicationForm->ParentJob->addMultiOptions($parentjob);
		$this->lobjStudentApplicationForm->AwardLevel->addMultiOptions($awardlevel);
		$this->lobjStudentApplicationForm->Referrel->addMultiOptions($Reference);
		$this->lobjStudentApplicationForm->JacketSize->addMultiOptions($jacketsize);
		$this->lobjStudentApplicationForm->GradeOrCGPA->addMultiOptions($grade);
		$this->lobjStudentApplicationForm->City->addMultiOptions($this->lobjstaff->fnGetPOBList());
		$this->lobjStudentApplicationForm->TypeOfSchool->addMultiOptions($TypeOfSchool);

		$this->lobjStudentApplicationForm->HomeTownSchoolDD->addMultiOptions($larrSchoollist);
		$this->lobjStudentApplicationForm->CreditTransferFromDD->addMultiOptions($larrSchoollist);

		$this->lobjStudentApplicationForm->PermCountry->addMultiOptions($lobjcountry);
		$this->lobjStudentApplicationForm->Nationality->addMultiOptions($lobjcountry);
		$this->lobjStudentApplicationForm->CorrsCountry->addMultiOptions($lobjcountry);
		$this->lobjStudentApplicationForm->idsponsor->addMultiOptions($lobjsponsor);
		$lobjbranch = $this->lobjcollegemaster->fnGetCollegeList();
		$this->lobjStudentApplicationForm->idCollege->addMultiOptions($lobjbranch);

		$lobjReligionList = $this->lobjUser->fnGetReligionList();
		$this->lobjStudentApplicationForm->Religion->addMultiOptions($lobjReligionList);

		$lobjBloodGroupList = $this->lobjUser->fnGetBloodGroupList();
		$this->lobjStudentApplicationForm->BloodGroup->addMultiOptions($lobjBloodGroupList);

		$lobjSubjectList = $this->lobjUser->fnGetSubjectList();
		$this->lobjStudentApplicationForm->SubjectId->addMultiOptions($lobjSubjectList);

		$lobjProgramList = $this->lobjstudentapplication->fnGetProgramList();
		$this->lobjStudentApplicationForm->IDCourse->addMultiOptions($lobjProgramList);

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Type of Organization');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjStudentApplicationForm->currentjoborganizationtype->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjStudentApplicationForm->DegreeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		if ($this->_request->isPost() && $this->_request->getPost('submit')) { // save opeartion
			foreach($larrdefmsresultset as $larrdefmsresult) {
				$this->lobjStudentApplicationForm->DegreeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			}
			if ($this->_request->isPost()) { // save opeartion
				$lobjFormData = $this->_request->getPost();
				if(isset($lobjFormData['submit'])){
					$this->applicantmodel->fnupdateapplicantstatus($idapplicant);
				}
				//				$status = $this->lobjstudentapplication->fngetapplicantstatus($applicantId);
				//				$this->view->status = $status['DefinitionDesc'];

			}
		}
	}


	public function studentofferAction() {
		$id = $this->_getparam('id',0);
		$studentresult = $this->lobjstudentapplication->fnEducationdetaillist($id);
		foreach($studentresult as $studentresult) {
			$this->view->FName = $studentresult['FName'];
			$this->view->LName = $studentresult['LName'];
			//$this->view->ShortName = $studentresult['ShortName'];
			$this->view->ProgramName = $studentresult['ProgramName'];
			//$this->view->CollegeName = $studentresult['CollegeName'];
			//$this->view->Email = $studentresult['Email'];

		}
	}

	public function confirmationAction(){
	}

	public function confirmationlistAction() {
		if ($this->_request->isPost() && $this->_request->getPost('Save')) { // save opeartion
			$lobjFormData = $this->_request->getPost();
			$icnumber = $lobjFormData['ICNumber'];
			$this->view->studenteducationdetails = $this->lobjstudentapplication->fnEducationdetailviewlist($icnumber);
			$this->view->count = count($this->view->studenteducationdetails);
			if($this->view->count > 0) {
				foreach($this->view->studenteducationdetails as $studenteducationdetails) {
					$IdApplication	= $studenteducationdetails['IdApplication'];
					$Accepted 	= $studenteducationdetails['Accepted'];
				}
				if($Accepted == 1) {
					$this->view->accepted = $Accepted;
				}
				$this->view->IdApplication = $IdApplication;
				$this->view->educationdetails = $this->lobjstudenteducation->fnviewEducationdetaillist($IdApplication);
			}
		}
		if ($this->_request->isPost() && $this->_request->getPost('Accept')) {
			$lobjFormData = $this->_request->getPost();
			$IdApplication = $lobjFormData['IdApplication'];
			$this->lobjstudentapplication->fnupdateconfirmation($IdApplication);
			$this->_redirect( $this->baseUrl . '/index');
		}

	}

	public function getprogramlistAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintidCollege = $this->_getParam('idCollege');
		$programlist = $this->lobjprogrambranch->fnGetProgrambranchlist($lintidCollege);
		$larrProgramlist= $this->lobjCommon->fnResetArrayFromValuesToNames($programlist);
		echo Zend_Json_Encoder::encode($larrProgramlist);
	}

	public function getplacementlistAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idCourse = $this->_getParam('idCourse');
		$programtestlist = $this->lobjplacementtest->fnGetPlcaementList($idCourse);
		$larrProgramlist= $this->lobjCommon->fnResetArrayFromValuesToNames($programtestlist);
		echo Zend_Json_Encoder::encode($larrProgramlist);
	}

	public function getplacementlisttimeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdPlacementTest = $this->_getParam('IdPlacementTest');
		$programtestlist = $this->lobjplacementtest->fnGetPlacementtime($IdPlacementTest);
		$programtestlist ['PlacementTestDate'] = date ( "d-m-Y", strtotime ( $programtestlist['PlacementTestDate'] ) );
		echo Zend_Json_Encoder::encode($programtestlist);
	}

	public function deleteeducationdetailsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdTemp = $this->_getParam('IdTemp');
		$this->lobjstudenteducation->fnUpdateTempeducationdetails($IdTemp);
		echo "1";
	}

	public function getcountrystateslistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintIdCountry = $this->_getParam('idCountry');
		$larrCountryStatesDetails = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjCommon->fnGetCountryStateList($lintIdCountry));
		echo Zend_Json_Encoder::encode($larrCountryStatesDetails);
	}

	public function getstatecitylistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$lintidState = $this->_getParam('idState');

		$larrStateCityDetails = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjCommon->fnGetCityList($lintidState));
		echo Zend_Json_Encoder::encode($larrStateCityDetails);
	}

	public function getlocalorinternationalAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idCourse = $this->_getParam('idCourse');
		$programlocalinternational = $this->lobjstudentapplication->fnGetLocalorinternamtional($idCourse);
		echo Zend_Json_Encoder::encode($programlocalinternational);
	}

	public function getsubjectlistAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idCourse = $this->_getParam('idCourse');
		$subjectlist = $this->lobjstudentapplication->fnGetSubjectList($idCourse);
		$larrSubjectlist= $this->lobjCommon->fnResetArrayFromValuesToNames($subjectlist);
		echo Zend_Json_Encoder::encode($larrSubjectlist);
	}

	public function getprogramchecklistAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idCourse = $this->_getParam('idCourse');
		$programlist = $this->lobjstudentapplication->fnGetProgramcheckList($idCourse);
		$larrProgramChklist= $this->lobjCommon->fnResetArrayFromValuesToNames($programlist);
		echo Zend_Json_Encoder::encode($larrProgramChklist);
	}

	//Action To Get List Of States From Country Id
	public function getcountrystateslistonlineappAction(){

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
		echo Zend_Json_Encoder::encode($larrCountryStatesDetails);
	}
	public function getstatecitylistonlineappAction(){

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

	public function addqualificationAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdApplicant = $this->applicantSession->IdApplicant;
		$rowid = 1;
		$larrtemp = array();
		$value = "false";
		if ($this->_request->isPost()) { // save opeartion
			$larrFormData = $this->_request->getPost();
			//print_r($larrFormData);die;
			$rowcount = count($larrFormData['PlaceObtained']);
			for($i=0;$i<$rowcount;$i++) {
				$larrqualification = array();
				$larrqualification['IdApplicant'] = $IdApplicant;
				$larrqualification['IdPlaceObtained'] = $larrFormData['PlaceObtained'][$i];
				$larrqualification['yearobtained'] = $larrFormData['YearObtained'][$i];
				$larrqualification['IdEducationalLevel'] = $larrFormData['QualificationLevel'][$i];
				$check = $this->lobjqualification->fncheckspecialtreatment($larrqualification['IdEducationalLevel']);
				if($check == 1){
					$status = 184;
					$this->applicantmodel->fnupdateApplicantDetails($IdApplicant,$status);
					$value = "redirect";
				}
				
					$larrqualification['certificatename'] = $larrFormData['Certificate'][$i];
					$larrqualification['IdInstitute'] = $larrFormData['Institution'][$i];
					$larrqualification['instituteaddress1'] = $larrFormData['InstitutionAddress1'][$i];
					$larrqualification['instituteaddress2'] = $larrFormData['InstitutionAddress2'][$i];
					$larrqualification['country'] = $larrFormData['Country'][$i];
					$larrqualification['state'] = $larrFormData['Province'][$i];
					$larrqualification['city'] = $larrFormData['City'][$i];
					$larrqualification['zipcode'] = $larrFormData['PostCode'][$i];
					$larrqualification['phone'] = "NA";
					$larrqualification['phonecountrycode'] = "NA";
					$larrqualification['phonestatecode'] = "NA";
					$larrqualification['fax'] = "NA";
					$larrqualification['faxcountrycode'] = "NA";
					$larrqualification['faxstatecode'] = "NA";
					$larrqualification['IdResultItem'] = $larrFormData['ResultItem'][$i];
					$larrqualification['resulttotal'] = $larrFormData['Result'][$i];
						
						
					$IdApplicationQualification = $larrFormData['IdApplicationQualification'][$i];
					if($IdApplicationQualification == "new") {
						$IdApplicationQualification = $this->lobjqualification->fninsert($larrqualification);
					}
					else {
						$this->lobjqualification->fnupdate($larrqualification, $IdApplicationQualification);
					}
					$larrtemp[] = $IdApplicationQualification;
					$this->lobjsubjectdetail->fndeleteSubjectDetailMappings($IdApplicationQualification);
					if(isset($larrFormData['Subject']["subject".$rowid])) {
						$lintsubrowcount = count($larrFormData['Subject']["subject".$rowid]);
						for($j=0;$j < $lintsubrowcount;$j++) {
							$larrsubjectdetail = array();
							$larrsubjectdetail['IdApplicationQualification'] = $IdApplicationQualification;
							$larrsubjectdetail['IdSubject'] = $larrFormData['Subject']["subject".$rowid][$j];
							$larrsubjectdetail['IdSubjectGrade'] = $larrFormData['SubjectGrade']["subject".$rowid][$j];
							$this->lobjsubjectdetail->fninsert($larrsubjectdetail);
						}
					}
					$this->lobjqualification->fndeletequalification($larrtemp, $IdApplicant);
					$rowid++;
					$tabvalue = '4';
					$this->applicantmodel->fnupdapplicantTabDetails($IdApplicant,$tabvalue);
					$data = array('key'=>'1','value'=>$value);
					echo Zend_Json_Encoder::encode($data);
					//die;
				
			}
		}
	}

	public function getinstitutionaddressAction() {
		$lintidInstitution = $this->_getParam("idInstitute");
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$larrinstitutiondetails = $this->lobjinstituion->fetchAll('IdInstitution ='.$lintidInstitution);
		echo Zend_Json_Encoder::encode($larrinstitutiondetails->toArray());
	}

	public function getsubjectgradeAction() {
		$lintqualification = $this->_getParam("IdQualification");
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$larrinstitutiondetails = $this->lobjsubjectgrade->fngetGradePointForDropdown($lintqualification);
		echo Zend_Json_Encoder::encode($larrinstitutiondetails);
	}

	public function verifyqualificationAction() {
		$lintqualification = $this->_getParam("idQualification");
		$subjectcheck = array();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$larrtemp = $this->lobjqualificationsub->fetchAll("IdQualification =".$lintqualification);
		$qualificationname = $this->lobjqualificationsub->fngetqualification($lintqualification);
		$larrtemp = $larrtemp->toArray();
		if(count($larrtemp) > 0) {
			$subjectcheck = array('check'=>'1','qualification'=>$qualificationname[0]['QualificationLevel']);
			echo Zend_Json_Encoder::encode($subjectcheck);
		}
		else {
			echo "0";
		}
	}

	public function getcountrycodeAction(){
		$countryId = $this->_getParam("idCountry");
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$larrcountrycode = $this->lobjstudentapplication->fnGetCountryCode($countryId);
		echo Zend_Json_Encoder::encode($larrcountrycode);
	}
	
	public function getsubjectlistforqualificationAction(){
	    $lintqualification = $this->_getParam("IdQualification");
	    $this->_helper->layout->disableLayout();
	    $this->_helper->viewRenderer->setNoRender();
	    $larrinstitutiondetails = $this->lobjsubjectgrade->fngetSubjectList($lintqualification);
	    echo Zend_Json_Encoder::encode($larrinstitutiondetails);
  	}
  	
  	public function matchapplicantcoutryAction(){
  		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
  		$countryId = $this->_getParam("idCountry");
		$countrymatch = $this->lobjsubjectgrade->fnmatchcountry($countryId);
		if(!empty($countrymatch)){
			echo "1";die;
		}else{
			echo "0";die;
		}
  	}

}