<?php

class GeneralSetup_DepartmentmasterController extends Base_Base { //Controller for the User Module

  private $_gobjlog;

  public function init() {
    $this->_gobjlog = Zend_Registry::get('log'); //instantiate log object
    $this->fnsetObj();
    $this->gstrsessionSIS = new Zend_Session_Namespace('sis');
    $this->view->translate = Zend_Registry::get('Zend_Translate');
    Zend_Form::setDefaultTranslator($this->view->translate);
  }

  public function fnsetObj() {
    $this->lobjstaffmodel = new GeneralSetup_Model_DbTable_Staffmaster();
    $this->lobjhodmodel = new GeneralSetup_Model_DbTable_Headofdepartment();
    $this->lobjDepartmentmaster = new GeneralSetup_Model_DbTable_Departmentmaster();
  }

  public function indexAction() { // action for search and view
    $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
    $lobjform = new App_Form_Search (); //intialize search lobjuserForm
    $this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
    $lobjDepartmentmaster = new GeneralSetup_Model_DbTable_Departmentmaster(); //user model object
    if ($this->gobjsessionsis->UserCollegeId == '0') {
      $larrresult = $lobjDepartmentmaster->fngetDepartmentDetails(); //get user details
    } else {
      $larrresult = $lobjDepartmentmaster->fngetDepartementmasterDetailsById($this->gobjsessionsis->UserCollegeId); //get user details
    }

    $lobjColgBranchList = $lobjDepartmentmaster->fnGetColgBranchList();
    $lobjform->field5->addMultiOptions($lobjColgBranchList);

    if (!$this->_getParam('search'))
      unset($this->gobjsessionsis->departmentpaginatorresult);

    $lintpagecount = $this->gintPageCount;
    $lobjPaginator = new App_Model_Common(); // Definitiontype model
    $lintpage = $this->_getParam('page', 1); // Paginator instance

    if (isset($this->gobjsessionsis->departmentpaginatorresult)) {
      $this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->departmentpaginatorresult, $lintpage, $lintpagecount);
    } else {
      $this->view->paginator = $lobjPaginator->fnPagination($larrresult, $lintpage, $lintpagecount);
    }
    if ($this->_request->isPost() && $this->_request->getPost('Search')) {
      $larrformData = $this->_request->getPost();
      if ($lobjform->isValid($larrformData)) {


        if ($this->gobjsessionsis->UserCollegeId == '0') {
          $larrresult = $lobjDepartmentmaster->fnSearchDepartment($lobjform->getValues()); //searching the values for the user
        } else {
          $larrresult = $lobjDepartmentmaster->fnSearchUserDepartment($lobjform->getValues(), $this->gobjsessionsis->UserCollegeId); //get user details
        }

        $this->view->paginator = $lobjPaginator->fnPagination($larrresult, $lintpage, $lintpagecount);
        $this->gobjsessionsis->departmentpaginatorresult = $larrresult;
      }
    }



    if ($this->_request->isPost() && $this->_request->getPost('Clear')) {


      //$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'departmentmaster', 'action'=>'index'),'default',true));
      $this->_redirect($this->baseUrl . '/generalsetup/departmentmaster/index');
    }
  }

  public function newdepartmentmasterAction() { //Action for creating the new user
    $lobjDepartmentmasterForm = new GeneralSetup_Form_Departmentmaster(); //intialize user lobjuserForm
    $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;

    $this->view->lobjdepartmentmasterForm = $lobjDepartmentmasterForm; //send the lobjuserForm object to the view
    $this->view->lobjdepartmentmasterForm->DepartmentName->setAttrib('validator', 'validateDepartmentname');

    $lobjDepartmentmaster = new GeneralSetup_Model_DbTable_Departmentmaster(); //intialize user Model

    $idUniversity = $this->gobjsessionsis->idUniversity;
    $lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
    $initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);

    if ($initialConfig['DepartmentCodeType'] == 1) {
      $this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('readonly', 'true');
      $this->view->lobjdepartmentmasterForm->DeptCode->setValue('xxx-xxx-xxx');
    } else {
      $this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('required', 'true');
      $this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('validator', 'validateDepartmentCode');
    }

    $ldtsystemDate = date('Y-m-d H:i:s');
    $this->view->lobjdepartmentmasterForm->UpdDate->setValue($ldtsystemDate);
    $auth = Zend_Auth::getInstance();
    $this->view->lobjdepartmentmasterForm->UpdUser->setValue($auth->getIdentity()->iduser);
    $this->view->lobjdepartmentmasterForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
    if ($this->gobjsessionsis->UserCollegeId == '0') {
      $lobjCollegeList = $lobjDepartmentmaster->fnGetCollegeList();
    } else {
      $lobjCollegeList = $lobjDepartmentmaster->fnGetUserCollegeList($this->gobjsessionsis->UserCollegeId);
    }
    $lobjDepartmentmasterForm->IdCollege->addMultiOptions($lobjCollegeList);



    if ($this->_request->isPost() && $this->_request->getPost('Save')) {
      $larrformData = $this->_request->getPost(); //getting the values of lobjuserFormdata from post
      unset($larrformData ['Save']);
      $larrheadofdept = $larrformData;
      if ($lobjDepartmentmasterForm->isValid($larrformData)) {
        $result = $lobjDepartmentmaster->fnaddDepartment($larrformData, $idUniversity, $initialConfig['DepartmentCodeType']); //instance for adding the lobjuserForm values to DB
        //$lastIdDepartment = Zend_Db_Table::getDefaultAdapter()->lastInsertId('tbl_departmentmaster','IdDepartment');
        $this->lobjhodmodel->fninsertHeadofDepartment($larrheadofdept, $result);

        // Write Logs
        $priority = Zend_Log::INFO;
        $larrlog = array('user_id' => $auth->getIdentity()->iduser,
            'level' => $priority,
            'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'time' => date('Y-m-d H:i:s'),
            'message' => 'New Department Add',
            'Description' => Zend_Log::DEBUG,
            'ip' => $this->getRequest()->getServer('REMOTE_ADDR'));
        $this->_gobjlog->write($larrlog); //insert to tbl_log
        //$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'departmentmaster', 'action'=>'index'),'default',true));
        $this->_redirect($this->baseUrl . '/generalsetup/departmentmaster/index');
      }
    }
  }

  public function departmentmasterlistAction() { //Action for the updation and view of the user details
    $lobjDepartmentmasterForm = new GeneralSetup_Form_Departmentmaster(); //intialize user lobjuserForm
    $this->view->lobjdepartmentmasterForm = $lobjDepartmentmasterForm; //send the lobjuserForm object to the view
    $lobjDepartmentmaster = new GeneralSetup_Model_DbTable_Departmentmaster(); //intialize user Model
    $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
    $idUniversity = $this->gobjsessionsis->idUniversity;
    $lobjinitialconfig = new GeneralSetup_Model_DbTable_Initialconfiguration ();
    $initialConfig = $lobjinitialconfig->fnGetInitialConfigDetails($idUniversity);

    if ($initialConfig['DepartmentCodeType'] == 1) {
      $this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('readonly', 'true');
      $this->view->lobjdepartmentmasterForm->DeptCode->setValue('xxx-xxx-xxx');
    } else {
      $this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('required', 'true');
    }

    $lintidepartment = (int) $this->_getParam('id');
    $this->view->iddepartment = $lintidepartment;
    //$this->view->lobjdepartmentmasterForm->DeptCode->setAttrib('readonly','true');
    $this->view->lobjdepartmentmasterForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
    $larrhoddetails = $this->lobjhodmodel->fngethodList($lintidepartment);
    $this->view->lobjdepartmentmasterForm->FromDate->setValue($larrhoddetails['FromDate']);
    $this->view->lobjdepartmentmasterForm->IdStaff->setValue($larrhoddetails['IdStaff']);
    $larrresult = $lobjDepartmentmaster->fnviewDepartment($lintidepartment); //getting user details based on userid

    if ($larrresult['DepartmentType'] == '0') {
      if ($this->gobjsessionsis->UserCollegeId == '0') {
        $lobjCollegeList = $lobjDepartmentmaster->fnGetCollegeList();
      } else {
        $lobjCollegeList = $lobjDepartmentmaster->fnGetUserCollegeList($this->gobjsessionsis->UserCollegeId);
//					echo "<pre>";
//					print_r($lobjCollegeList);die;
      }
      $lobjDepartmentmasterForm->IdCollege->addMultiOptions($lobjCollegeList);
    } else {

      if ($this->gobjsessionsis->UserCollegeId == '0') {
        $lobjBranchList = $lobjDepartmentmaster->fnGetBranchList();
      } else {
        $lobjBranchList = $lobjDepartmentmaster->fnGetUserBranchList($this->gobjsessionsis->UserCollegeId);
      }
      $lobjDepartmentmasterForm->IdCollege->addMultiOptions($lobjBranchList);
    }


    $lobjDepartmentmasterForm->populate($larrresult);


    $ldtsystemDate = date('Y-m-d H:i:s');
    $this->view->lobjdepartmentmasterForm->UpdDate->setValue($ldtsystemDate);
    $auth = Zend_Auth::getInstance();
    $this->view->lobjdepartmentmasterForm->UpdUser->setValue($auth->getIdentity()->iduser);

    if ($this->_request->isPost() && $this->_request->getPost('Save')) {
      $larrformData = $this->_request->getPost();
      $formData = $larrformData;
      unset($larrformData ['Save']);
      if ($lobjDepartmentmasterForm->isValid($larrformData)) {
        $lintIdDepartment = $larrformData ['IdDepartment'];
        $this->lobjhodmodel->fnupdatehodList($larrformData, $lintIdDepartment);
        $this->lobjhodmodel->fninsertHeadofDepartment($formData, $lintIdDepartment);



        $lobjDepartmentmaster->fnupdateDepartment($larrformData, $lintIdDepartment);

        // Write Logs
        $priority = Zend_Log::INFO;
        $larrlog = array('user_id' => $auth->getIdentity()->iduser,
            'level' => $priority,
            'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'time' => date('Y-m-d H:i:s'),
            'message' => 'Department Edit Id=' . $lintidepartment,
            'Description' => Zend_Log::DEBUG,
            'ip' => $this->getRequest()->getServer('REMOTE_ADDR'));
        $this->_gobjlog->write($larrlog); //insert to tbl_log
        //$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'departmentmaster', 'action'=>'index'),'default',true));
        $this->_redirect($this->baseUrl . '/generalsetup/departmentmaster/index');
      }
    }
    $this->view->lobjdepartmentmasterForm = $lobjDepartmentmasterForm;
  }

  public function getunivesitycolglistAction() {

    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();

    $lobjCommonModel = new App_Model_Common();
    $lobjDepartmentModel = new GeneralSetup_Model_DbTable_Departmentmaster();
    $lintvalue = $this->_getParam('value');
    if ($lintvalue == '0') {
      $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetUniversityList());
    } else {
      if ($this->gobjsessionsis->UserCollegeId == '0') {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetCollegeList());
      } else {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetUserCollegeList($this->gobjsessionsis->UserCollegeId));
      }
    }
    echo Zend_Json_Encoder::encode($larrDetails);
  }

  public function getcolgbranchlistAction() {

    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();

    $lobjCommonModel = new App_Model_Common();
    $lobjDepartmentModel = new GeneralSetup_Model_DbTable_Departmentmaster();
    $lintvalue = $this->_getParam('value');


    if ($lintvalue == '0') {

      if ($this->gobjsessionsis->UserCollegeId == '0') {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetCollegeList());
      } else {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetUserCollegeList($this->gobjsessionsis->UserCollegeId));
      }
    } else {

      if ($this->gobjsessionsis->UserCollegeId == '0') {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetBranchList());
      } else {
        $larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($lobjDepartmentModel->fnGetUserBranchList($this->gobjsessionsis->UserCollegeId));
      }
    }
    echo Zend_Json_Encoder::encode($larrDetails);
  }

  public function getdepartmentnameAction() {
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();
    $DeptName = $this->_getParam('DeptName');
    $larrDetails = $this->lobjDepartmentmaster->fngetDeptname($DeptName);
    echo $larrDetails['DepartmentName'];
  }

  public function getdepartmentcodeAction() {
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();
    $DeptCode = $this->_getParam('DeptCode');
    $larrDetails = $this->lobjDepartmentmaster->fngetDeptCode($DeptCode);
    echo $larrDetails['DeptCode'];
  }

}