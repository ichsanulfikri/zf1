<?php
class GeneralSetup_UniversityController extends Base_Base 
{
	private $lobjuniversity;
	private $lobjuniversityForm;
	private $lobjUser;
	private $lobjStaffForm;
	private $lobjdeftype;
	private $lobjcommonmodel;
	private $lobjstaffmodel;
	private $lobjreglistmodel;
	private $registry;
	private $locale;
	private $_gobjlog;
	
	public function init() 
	{
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate');
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis'); 
		Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->fnsetObj();
   	    if(!$this->_getParam('search')) 
			unset($this->gobjsessionstudent->universitypaginatorresult);
	}
	
	public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search ();
		$this->lobjPaginator = new App_Model_Definitiontype();
		$this->lobjuniversity = new GeneralSetup_Model_DbTable_University();
		$this->lobjuniversityForm = new GeneralSetup_Form_University (); //intialize user lobjuniversityForm
		$this->lobjUser = new GeneralSetup_Model_DbTable_User(); //intialize user Model
		$this->lobjStaffForm = new GeneralSetup_Form_Staffmaster();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjcommonmodel = new App_Model_Common();
		$this->lobjstaffmodel = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->lobjreglistmodel = new GeneralSetup_Model_DbTable_RegistrarList();
		$this->registry = Zend_Registry::getInstance();
		
		
	}
	
	public function indexAction() {
          $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
    	 //intialize search lobjuniversityForm
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		 //user model object
		$larrresult = $this->lobjuniversity->fngetUniversityDetails (); //get user details
		$lintpagecount = $this->gintPageCount;
		 // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionstudent->universitypaginatorresult)) {
			$this->view->paginator = $this->lobjPaginator->fnPagination($this->gobjsessionstudent->universitypaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjuniversity->fnSearchUniversity ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionstudent->universitypaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'university', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/university/index');
		}
		
	}
	
	public function newuniversityAction() { //title
    	$this->view->title="Add New University";
		
		$this->view->lobjuniversityForm = $this->lobjuniversityForm; //send the lobjuniversityForm object to the view
		$this->view->lobjstaffmasterForm = $this->lobjStaffForm;
		$this->view->lobjuniversityForm->Univ_Name->setAttrib('validator', 'validateUniversityName');
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		
		if($this->locale == 'ar_YE') 
		{
			$this->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}
		
		$this->lobjStaffForm->StaffType->setValue(0);
		$this->lobjStaffForm->StaffType->setAttrib('readonly','readonly');
		
		
		
		
			if($this->view->DefaultDropDownLanguage==0){
				$this->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
			}else{
				
				$BahasStaffMasterList=$this->lobjstaffmodel->fngetBahasStaffMasterListforDD();
				foreach($BahasStaffMasterList as $BahasStaffMasterListDtls){
				if($BahasStaffMasterListDtls ['ArabicName'] == NULL ) {
					$BahasStaffMasterListDtls ['ArabicName'] = $BahasStaffMasterListDtls ['EngName'];
				}
					$this->lobjuniversityForm->IdStaff->addMultiOption($BahasStaffMasterListDtls ['IdStaff'],$BahasStaffMasterListDtls ['ArabicName']);
					
				}
				
				
			}
		
		$lobjcountry = $this->lobjUser->fnGetCountryList();
		$this->lobjuniversityForm->Country->addMultiOptions($lobjcountry);
		$this->view->lobjuniversityForm->Country->setValue ($this->view->DefaultCountry);
		$larrcountrylist = $this->lobjcommonmodel->fnResetArrayFromValuesToNames($lobjcountry);
		$this->view->jsondata = 
				 '{
    				"label":"name",
					"identifier":"key",
					"items":'.Zend_Json_Encoder::encode($larrcountrylist).
				  '}';
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjuniversityForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
//			$languageId = $this->lobjUser->fngetDefaultlanguage($formData['Country']);
//			$languageName = $this->lobjUser->fngetLanguageName($languageId[0]['value']);
//			echo "<pre>";
//			print_r($languageId);
//			print_r($languageName[0]['value']);die;
//			print_r($formData);die;
			//if ($this->lobjuniversityForm->isValid($formData)) {//process form 
				unset ( $formData ['Save'] );
				unset ( $formData ['Back'] );
				
				$this->lobjuniversity->fnaddUniversity($formData);
				
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New University Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'university', 'action'=>'index'),'default',true));	//redirect
				$this->_redirect( $this->baseUrl . '/generalsetup/university/index');	
			//}  	
        }     
    }
    
	public function edituniversityAction(){
    	$this->view->title="Edit University";  //title
    	
		$this->view->lobjuniversityForm = $this->lobjuniversityForm; //send the lobjuniversityForm object to the view
		
		if($this->locale == 'ar_YE') 
		{
			$this->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}
		
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->UpdDate->setValue ( $ldtsystemDate );		
		$lobjcountry = $this->lobjUser->fnGetCountryList();
		$this->lobjuniversityForm->Country->addMultiOptions($lobjcountry);
	
    	$IdUniversity = $this->_getParam('id', 0);
    	$this->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
    	
    	$result = $this->lobjuniversity->fneditUniversity($IdUniversity);
    	$language = $this->lobjCommon->fngetLanguage($result[0]['Country']);
    	if(isset($language[0]['DefinitionDesc'])){
    	$this->view->defaultlanguage = $language[0]['DefinitionDesc'];
    	}
    	else{
    		$language[0]['DefinitionDesc'] = "Arabic";
    		$this->view->defaultlanguage = $language[0]['DefinitionDesc'];
    	}
    	foreach($result as $unvresult){
		}
		$lobjstate = $this->lobjUser->fnGetStateListcountry($unvresult['Country']);
		$this->lobjuniversityForm->State->addMultiOptions($lobjstate);

		$lobjCommonModel = new App_Model_Common();
		$larrStateCityList = $lobjCommonModel->fnGetCityList($unvresult['State']);
		$this->lobjuniversityForm->City->addMultiOptions($larrStateCityList);	
		
		$this->lobjuniversityForm->populate($unvresult);
		
		$arrPhone1 = explode("-",$unvresult ['Phone1']);
		$this->view->lobjuniversityForm->Phone1countrycode->setValue ( $arrPhone1 [0] );
		$this->view->lobjuniversityForm->Phone1statecode->setValue ( $arrPhone1 [1] );
		$this->view->lobjuniversityForm->Phone1->setValue ( $arrPhone1 [2] );	
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );
		
		$arrPhone2 = explode("-",$unvresult ['Phone2']);
		$this->view->lobjuniversityForm->Phone2countrycode->setValue ( $arrPhone2 [0] );
		$this->view->lobjuniversityForm->Phone2statecode->setValue ( $arrPhone2 [1] );
		$this->view->lobjuniversityForm->Phone2->setValue ( $arrPhone2 [2] );

		$arrfax = explode("-",$unvresult ['Fax']);
		$this->view->lobjuniversityForm->faxcountrycode->setValue ( $arrfax[0] );
		$this->view->lobjuniversityForm->faxstatecode->setValue ( $arrfax[1] );
		$this->view->lobjuniversityForm->Fax->setValue ( $arrfax[2] );
    	
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjuniversityForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
			
		
    	//$this->lobjuniversityForm->Univ_Name->removeValidator ('Db_NoRecordExists' );
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
			if($this->gobjsessionsis->idUniversity == $formData['IdUniversity']){
				$language = $this->lobjCommon->fngetLanguage($formData['Country']);
			if(isset($language[0]['DefinitionDesc'])){
    			$this->gobjsessionsis->UniversityLanguage = $language[0]['DefinitionDesc'];
    		}
    		else{
    			$language[0]['DefinitionDesc'] = "Arabic";
    			$this->gobjsessionsis->UniversityLanguage = $language[0]['DefinitionDesc'];
    		}
				
    			
			}
//    		$languageId = $this->lobjUser->fngetDefaultlanguage($formData['Country']);
//			$languageName = $this->lobjUser->fngetLanguageName($languageId[0]['value']);
//			echo "<pre>";
//			print_r($languageId);
//			print_r($languageName[0]['value']);die;
	    	if ($this->lobjuniversityForm->isValid($formData)) {
	   			$lintIdUniversity = $formData ['IdUniversity'];
				
				$this->lobjuniversity->fnupdateUniversity($formData,$lintIdUniversity);//update university
				
				$this->lobjreglistmodel->fnupdateRegistrarList($formData,$lintIdUniversity);//update registrar
				
				$this->lobjreglistmodel->fninsertRegistrarList($formData,$lintIdUniversity);//insert new registrar
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Universit Edit Id=' . $IdUniversity,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'university', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/university/index');
			}
    	}
    }
    
	public function getuniversitynameAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$UniversityName = $this->_getParam('UniversityName');	
		$larrDetails = $this->lobjuniversity->fngetValidateUniversityName($UniversityName);
		echo $larrDetails['Univ_Name'];
		
	}
	
////Action To Set the label of Arabic Caption
//	public function setlabelAction(){
//		$this->_helper->layout->disableLayout();
//		$this->_helper->viewRenderer->setNoRender();
//		//Get Country Id
//		$lintIdCountry = $this->_getParam('idCountry');
//		$languageId = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjUser->fngetDefaultlanguage($lintIdCountry));
////		$language = $this->lobjUser->fngetLanguageName($languageId[0]['value']);
////		//echo "<pre>";
////		//print_r($language);
//	}
}