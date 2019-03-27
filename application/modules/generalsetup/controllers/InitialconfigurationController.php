<?php
class GeneralSetup_InitialconfigurationController extends Base_Base { //Controller for the initialconfiguration Module

  private $locale;
  private $registry;
  private $lobjinitialconfig;
  private $lobjuniversityModel;
  private $lobjinitialconfigForm;
  private $lobjUser;
  private $_gobjlog;
	
  public function init() { //initialization function
    $this->view->translate =Zend_Registry::get('Zend_Translate');
    Zend_Form::setDefaultTranslator($this->view->translate);
    $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
    $this->fnsetObj();
    if(!$this->_getParam('search'))
    unset($this->gobjsessionstudent->initialconfigpaginatorresult);
  }
	
  public function fnsetObj() {
    $this->lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration(); //user model object
    $this->lobjuniversityModel = new GeneralSetup_Model_DbTable_University(); //user model object
    $this->lobjinitialconfigForm = new GeneralSetup_Form_Initialconfiguration (); //intialize user lobjuserForm
    $this->lobjUser = new GeneralSetup_Model_DbTable_User();
    $this->registry = Zend_Registry::getInstance();
    $this->locale = $this->registry->get('Zend_Locale');
  }

  public function indexAction() { // action for search and view
    $lobjform=$this->view->lobjform = $this->lobjform; //send the lobjuserForm object to the view
    $larrresult = $this->lobjuniversityModel->fngetUniversityDetails (); //get user details
	$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
    $lintpagecount = $this->gintPageCount;
    $lintpage = $this->_getParam('page',1); // Paginator instance

    if(isset($this->gobjsessionstudent->initialconfigpaginatorresult)) {
      $this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionstudent->initialconfigpaginatorresult,$lintpage,$lintpagecount);
    }else{
      $this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
    }
    if($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
      $larrformData = $this->_request->getPost ();
      if ($lobjform->isValid ( $larrformData )) {
        $larrresult = $this->lobjinitialconfig->fnSearchUniversity ( $lobjform->getValues () ); //searching the values for the user
        $this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
        $this->gobjsessionstudent->initialconfigpaginatorresult = $larrresult;
      }
    }
    if($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
      //$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'initialconfiguration', 'action'=>'index'),'default',true));
      $this->_redirect( $this->baseUrl . '/generalsetup/initialconfiguration/index');
    }
  }

  public function initialconfiglistAction() { //Action for creating the new user  		
    $this->view->lobjInitialConfigForm = $this->lobjinitialconfigForm; //send the lobjuserForm object to the view
    $this->view->lobjInitialConfigModel = $this->lobjinitialconfig;
    $auth = Zend_Auth::getInstance();
    $lintiduniversity = ( int ) $this->_getParam ( 'idUniversity' );
    $this->lobjinitialconfigForm->UpdUser->setValue($auth->getIdentity()->iduser);
    $this->lobjinitialconfigForm->UpdDate->setValue(date('Y-m-d H:i:s'));
    $this->lobjinitialconfigForm->idUniversity->setValue($lintiduniversity);

    $larrReceiptFields[''] = 'Select';
    $larrReceiptFields['University'] = 'University';
    $larrReceiptFields['College'] = 'College';
    $larrReceiptFields['Uniqueid'] = 'Uniqueid';
    $larrReceiptFields['Year'] = 'Year';
    $larrReceiptFields['Text'] = 'Text';

    $larrReceiptField[''] = 'Select';
    $larrReceiptField['University'] = 'University';
    $larrReceiptField['Uniqueid'] = 'Uniqueid';
    $larrReceiptField['Year'] = 'Year';
    $larrReceiptField['Text'] = 'Text';

    $larrRegistrationFields[''] = 'Select';
    $larrRegistrationFields['Program'] = 'Program';
    $larrRegistrationFields['Year'] = 'Year';
    $larrRegistrationFields['Semester'] = 'Semester';
    $larrRegistrationFields['Uniqueid'] = 'Uniqueid';

    //$larrReceiptFields['Text'] = 'Text';

    /*$this->lobjinitialconfigForm->StudentCodeField1->addMultiOptions($larrReceiptFields);
    $this->lobjinitialconfigForm->StudentCodeField2->addMultiOptions($larrReceiptFields);
    $this->lobjinitialconfigForm->StudentCodeField3->addMultiOptions($larrReceiptFields);
    $this->lobjinitialconfigForm->StudentCodeField4->addMultiOptions($larrReceiptFields);*/

    $this->lobjinitialconfigForm->generalbaseField1->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseField2->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseField3->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseField4->addMultiOptions($larrReceiptField);

    $this->lobjinitialconfigForm->generalbaseitemField1->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseitemField2->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseitemField3->addMultiOptions($larrReceiptField);
    $this->lobjinitialconfigForm->generalbaseitemField4->addMultiOptions($larrReceiptField);

    /*$this->lobjinitialconfigForm->RegistrationCodeField1->addMultiOptions($larrRegistrationFields);
    $this->lobjinitialconfigForm->RegistrationCodeField2->addMultiOptions($larrRegistrationFields);
    $this->lobjinitialconfigForm->RegistrationCodeField3->addMultiOptions($larrRegistrationFields);
    $this->lobjinitialconfigForm->RegistrationCodeField4->addMultiOptions($larrRegistrationFields);*/


    $larrSemReceiptFields[''] = 'Select';
    $larrSemReceiptFields['ShortName'] = 'Short Name';
    $larrSemReceiptFields['Uniqueid'] = 'Uniqueid';
    //$larrSemReceiptFields['Others'] = 'Others';

    $this->lobjinitialconfigForm->CollegeCodeField1->addMultiOptions($larrSemReceiptFields);
    $this->lobjinitialconfigForm->CollegeCodeField2->addMultiOptions($larrSemReceiptFields);
    $this->lobjinitialconfigForm->CollegeCodeField3->addMultiOptions($larrSemReceiptFields);

    $larrSemReceiptFields['Text'] = 'Text';
    $this->lobjinitialconfigForm->SemesterCodeField1->addMultiOptions($larrSemReceiptFields);
    $this->lobjinitialconfigForm->SemesterCodeField2->addMultiOptions($larrSemReceiptFields);
    $this->lobjinitialconfigForm->SemesterCodeField3->addMultiOptions($larrSemReceiptFields);
    $this->lobjinitialconfigForm->SemesterCodeField4->addMultiOptions($larrSemReceiptFields);
    $larrDefaultCountry = $this->lobjUser->fnGetCountryList();
    $this->lobjinitialconfigForm->DefaultCountry->addMultiOptions($larrDefaultCountry);


    $larrSubReceiptFields[''] = 'Select';
    $larrSubReceiptFields['Uniqueid'] = 'Uniqueid';
    $larrSubReceiptFields['ShortName'] = 'Short Name';
    $larrSubReceiptFields['DepartmentShortName'] = 'Department';
    $larrSubReceiptFields['CollegeShortName'] = 'College';

    /*$this->lobjinitialconfigForm->SubjectCodeField1->addMultiOptions($larrSubReceiptFields);
    $this->lobjinitialconfigForm->SubjectCodeField2->addMultiOptions($larrSubReceiptFields);
    $this->lobjinitialconfigForm->SubjectCodeField3->addMultiOptions($larrSubReceiptFields);
    $this->lobjinitialconfigForm->SubjectCodeField4->addMultiOptions($larrSubReceiptFields);*/

    $larrDepReceiptFields[''] = 'Select';
    $larrDepReceiptFields['Year'] = 'Year';
    $larrDepReceiptFields['Uniqueid'] = 'Uniqueid';
    $larrDepReceiptFields['ShortName'] = 'Short Name';
    $larrDepReceiptFields['CollegeShortName'] = 'College';

    $this->lobjinitialconfigForm->DepartmentCodeField1->addMultiOptions($larrDepReceiptFields);
    $this->lobjinitialconfigForm->DepartmentCodeField2->addMultiOptions($larrDepReceiptFields);
    $this->lobjinitialconfigForm->DepartmentCodeField3->addMultiOptions($larrDepReceiptFields);
    $this->lobjinitialconfigForm->DepartmentCodeField4->addMultiOptions($larrDepReceiptFields);

    /*$StaffIdFields[''] = 'Select';
    $StaffIdFields['Uniqueid'] = 'Uniqueid';
    $StaffIdFields['University'] = 'University';
    $StaffIdFields['DepartmentShortName'] = 'Department';
    $StaffIdFields['CollegeShortName'] = 'College';


    $this->lobjinitialconfigForm->StaffIdField1->addMultiOptions($StaffIdFields);
    $this->lobjinitialconfigForm->StaffIdField2->addMultiOptions($StaffIdFields);
    $this->lobjinitialconfigForm->StaffIdField3->addMultiOptions($StaffIdFields);
    $this->lobjinitialconfigForm->StaffIdField4->addMultiOptions($StaffIdFields);*/


    $larrInitialConfigDetails = $this->lobjinitialconfig->fnGetInitialConfigDetails($lintiduniversity);
    if($larrInitialConfigDetails){
      $this->view->Blockcode = $larrInitialConfigDetails['BlockType'];

      $this->view->Regcode1=$larrInitialConfigDetails['ApplicantCodeType'];
      $this->view->Regcode2=$larrInitialConfigDetails['SemesterCodeType'];
      $this->view->Regcode3=$larrInitialConfigDetails['SubjectCodeType'];
      $this->view->Regcode4=$larrInitialConfigDetails['DepartmentCodeType'];
      $this->view->Regcode5=$larrInitialConfigDetails['CollegeCodeType'];
      $this->view->Regcode6=$larrInitialConfigDetails['StaffIdType'];
      $this->view->Regcode7=$larrInitialConfigDetails['RegistrationCodeType'];
      $this->view->Regcode8=$larrInitialConfigDetails['AccountCodeType'];
      $this->view->Regcode9=$larrInitialConfigDetails['AppealCodeType'];
      $this->view->Regcode10=$larrInitialConfigDetails['BillNoType'];
      $this->view->Regcode11=$larrInitialConfigDetails['ReceiptType'];
      $this->view->Regcode12=$larrInitialConfigDetails['StudentIdType'];
      $this->view->Regcode13=$larrInitialConfigDetails['CourseIdType'];
      $this->view->Regcode14=$larrInitialConfigDetails['AgentIdType'];
      $this->view->Regcode15=$larrInitialConfigDetails['inventoryIdType'];

      /*$this->view->CodeText1=$larrInitialConfigDetails['StudentCodeField1'];
      $this->view->CodeText2=$larrInitialConfigDetails['StudentCodeField2'];
      $this->view->CodeText3=$larrInitialConfigDetails['StudentCodeField3'];
      $this->view->CodeText4=$larrInitialConfigDetails['StudentCodeField4'];*/

      $this->view->SemCodeText1=$larrInitialConfigDetails['SemesterCodeField1'];
      $this->view->SemCodeText2=$larrInitialConfigDetails['SemesterCodeField2'];
      $this->view->SemCodeText3=$larrInitialConfigDetails['SemesterCodeField3'];
      $this->view->SemCodeText4=$larrInitialConfigDetails['SemesterCodeField4'];

      /*$this->view->RegistrationCodeText1=$larrInitialConfigDetails['RegistrationCodeField1'];
      $this->view->RegistrationCodeText2=$larrInitialConfigDetails['RegistrationCodeField2'];
      $this->view->RegistrationCodeText3=$larrInitialConfigDetails['RegistrationCodeField3'];
      $this->view->RegistrationCodeText4=$larrInitialConfigDetails['RegistrationCodeField4'];*/

      $this->view->AccountCodeText1=$larrInitialConfigDetails['AccountCodeField1'];
      $this->view->AccountCodeText2=$larrInitialConfigDetails['AccountCodeField2'];
      $this->view->AccountCodeText3=$larrInitialConfigDetails['AccountCodeField3'];
      $this->view->AccountCodeText4=$larrInitialConfigDetails['AccountCodeField4'];

      $this->view->AccountGroup=$larrInitialConfigDetails['AccountCode'];
      $this->view->BaseType=$larrInitialConfigDetails['base'];
      $this->view->LevelBased=$larrInitialConfigDetails['levelbase'];
      $this->view->generalbaseField1=$larrInitialConfigDetails['generalbaseField1'];
      $this->view->generalbaseField2=$larrInitialConfigDetails['generalbaseField2'];
      $this->view->generalbaseField3=$larrInitialConfigDetails['generalbaseField3'];
      $this->view->generalbaseField4=$larrInitialConfigDetails['generalbaseField4'];
      $this->view->generalbaseText1=$larrInitialConfigDetails['generalbaseText1'];
      $this->view->generalbaseText2=$larrInitialConfigDetails['generalbaseText2'];
      $this->view->generalbaseText3=$larrInitialConfigDetails['generalbaseText3'];
      $this->view->generalbaseText4=$larrInitialConfigDetails['generalbaseText4'];
      $this->view->FirstletterSeparator=$larrInitialConfigDetails['FirstletterSeparator'];
      $this->view->FixedSeparator=$larrInitialConfigDetails['FixedSeparator'];
      $this->view->FixedText=$larrInitialConfigDetails['FixedText'];

      $this->view->ItemCode=$larrInitialConfigDetails['ItemCode'];
      $this->view->Itembase=$larrInitialConfigDetails['Itembase'];
      $this->view->itemlevelbase=$larrInitialConfigDetails['itemlevelbase'];
      $this->view->generalbaseitemField1=$larrInitialConfigDetails['generalbaseitemField1'];
      $this->view->generalbaseitemField2=$larrInitialConfigDetails['generalbaseitemField2'];
      $this->view->generalbaseitemField3=$larrInitialConfigDetails['generalbaseitemField3'];
      $this->view->generalbaseitemField4=$larrInitialConfigDetails['generalbaseitemField4'];
      $this->view->generalbaseitemText1=$larrInitialConfigDetails['generalbaseitemText1'];
      $this->view->generalbaseitemText2=$larrInitialConfigDetails['generalbaseitemText2'];
      $this->view->generalbaseitemText3=$larrInitialConfigDetails['generalbaseitemText3'];
      $this->view->generalbaseitemText4=$larrInitialConfigDetails['generalbaseitemText4'];
      $this->view->FirstletteritemSeparator=$larrInitialConfigDetails['FirstletteritemSeparator'];
      $this->view->FixeditemSeparator=$larrInitialConfigDetails['FixeditemSeparator'];
      $this->view->FixeditemText=$larrInitialConfigDetails['FixeditemText'];

      $AccIdFields[''] = 'Select';
      $AccIdFields['Year'] = 'Year';
      $AccIdFields['Uniqueid'] = 'Uniqueid';
      $AccIdFields['ShortName'] = 'Short Name';
      $AccIdFields['Text'] = 'Text';

      $this->lobjinitialconfigForm->AccountCodeField1->addMultiOptions($AccIdFields);
      $this->lobjinitialconfigForm->AccountCodeField2->addMultiOptions($AccIdFields);
      $this->lobjinitialconfigForm->AccountCodeField3->addMultiOptions($AccIdFields);
      $this->lobjinitialconfigForm->AccountCodeField4->addMultiOptions($AccIdFields);

      $InvIdFields[''] = 'Select';
      $InvIdFields['Year'] = 'Year (2 Digits)';
      $InvIdFields['StudentIntake'] = 'Student Intake (2 Digits)';
      $InvIdFields['ProgramCode'] = 'Program Code (4 Digits)';
      $InvIdFields['NIM'] = 'NIM';
      $InvIdFields['RunningNumber'] = 'Running Number (4 Digits)';

      $this->lobjinitialconfigForm->InvoiceCodeField1->addMultiOptions($InvIdFields);
      $this->lobjinitialconfigForm->InvoiceCodeField2->addMultiOptions($InvIdFields);
      $this->lobjinitialconfigForm->InvoiceCodeField3->addMultiOptions($InvIdFields);
      $this->lobjinitialconfigForm->InvoiceCodeField4->addMultiOptions($InvIdFields);


      $AppealIdFields[''] = 'Select';
      $AppealIdFields['ProgramId'] = 'ProgramId';
      $AppealIdFields['SubjectId'] = 'SubjectId';
      $AppealIdFields['StudentRegId'] = 'StudentRegId';
      $AppealIdFields['UniquId'] = 'UniquId';

      $this->lobjinitialconfigForm->AppealCodeField1->addMultiOptions($AppealIdFields);
      $this->lobjinitialconfigForm->AppealCodeField2->addMultiOptions($AppealIdFields);
      $this->lobjinitialconfigForm->AppealCodeField3->addMultiOptions($AppealIdFields);
      $this->lobjinitialconfigForm->AppealCodeField4->addMultiOptions($AppealIdFields);


      $BillIdFields[''] = 'Select';
      $BillIdFields['UniversityShortName'] = 'UniversityShortName';
      $BillIdFields['ProgramShortName'] = 'ProgramShortName';
      $BillIdFields['Year'] = 'Year';
      $BillIdFields['Uniqueid'] = 'Uniqueid';

      $this->lobjinitialconfigForm->BillNoTypeField1->addMultiOptions($BillIdFields);
      $this->lobjinitialconfigForm->BillNoTypeField2->addMultiOptions($BillIdFields);
      $this->lobjinitialconfigForm->BillNoTypeField3->addMultiOptions($BillIdFields);
      $this->lobjinitialconfigForm->BillNoTypeField4->addMultiOptions($BillIdFields);

      $studentIdFields[''] = 'Select';
      $studentIdFields['year'] = 'Year';
      $studentIdFields['intake'] = 'Intake';
      $this->lobjinitialconfigForm->ResetStudentIdSeq->addMultiOptions($studentIdFields);
      
      $ReceiptFields[''] = 'Select';
      $ReceiptFields['Year'] = 'Year';
      $ReceiptFields['Date'] = 'Date';
      $ReceiptFields['Receiptid'] = 'Receiptid';
      $ReceiptFields['Student id'] = 'Student id';

      $this->lobjinitialconfigForm->ReceiptTypeField1->addMultiOptions($ReceiptFields);
      $this->lobjinitialconfigForm->ReceiptTypeField2->addMultiOptions($ReceiptFields);
      $this->lobjinitialconfigForm->ReceiptTypeField3->addMultiOptions($ReceiptFields);
      $this->lobjinitialconfigForm->ReceiptTypeField4->addMultiOptions($ReceiptFields);
    }else{
      $this->view->Blockcode = 0;
      $this->view->Regcode1= 0;
      $this->view->Regcode2= 0;
      $this->view->Regcode3= 0;
      $this->view->Regcode4= 0;
      $this->view->Regcode5= 0;
      $this->view->Regcode6= 0;
      $this->view->Regcode7= 0;
      $this->view->Regcode8= 0;
      $this->view->Regcode9= 0;
      $this->view->Regcode10= 0;
      $this->view->Regcode11= 0;
      $this->view->Regcode12= 0;
      $this->view->Regcode13= 0;
      $this->view->Regcode14= 0;
      $this->view->Regcode15= 0;
    }
		if(count($larrInitialConfigDetails) > 0 && $larrInitialConfigDetails != ""){
			$this->lobjinitialconfigForm->populate($larrInitialConfigDetails);	
		}
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();						
				unset($larrFormData['Save']);
				if($larrFormData['NoofWarnings'] == "")$larrFormData['NoofWarnings'] = 0;
				if(count($larrInitialConfigDetails) > 0 && $larrInitialConfigDetails != ""){
					$this->lobjinitialconfig->fnUpdateInitialconfig($larrFormData['idConfig'],$larrFormData);	
				} else {
					unset($larrFormData['idConfig']);
					$this->lobjinitialconfig->fnAddInitialConfig($larrFormData);	
				}	
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Initial Configuration Edit Id=' . $lintiduniversity,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'initialconfiguration', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/initialconfiguration/index');
		}

	}
}