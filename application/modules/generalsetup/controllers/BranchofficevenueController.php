<?php
class GeneralSetup_BranchofficevenueController extends Base_Base {
	private $locale;
	private $registry;
	private $lobjBranchofficevenueform;
	private $Branchofficevenue;
	private $lobjuser;
	private $_gobjlog;
	private $lintidbrc;

	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis');
		$this->locale = $this->registry->get('Zend_Locale');
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->lintidbrc = $lintidbrc = ( int ) $this->_getParam ( 'idbrc' );
		if($lintidbrc ==1){
			$this->view->idbrch='Branch';
		}
		if($lintidbrc ==2){
			$this->view->idbrch='Office';
		}
		if($lintidbrc ==3){
			$this->view->idbrch='Venue';
		}
	}

	public function fnsetObj(){
		$this->lobjform = new App_Form_Search ();
		$this->lobjBranchofficevenueform = new GeneralSetup_Form_Branchofficevenue();
		$this->Branchofficevenue = new GeneralSetup_Model_DbTable_Branchofficevenue();
		$this->lobjsubjectsofferedmodel = new GeneralSetup_Model_DbTable_Subjectsoffered();
		$this->lobjuser = new GeneralSetup_Model_DbTable_User(); //user model object
	}

	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		// Function	to get Country List
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjCountryNameList = $this->Branchofficevenue->fnGetCountryList();
		$this->lobjform->field5->addMultiOptions($lobjCountryNameList);
		$lobjStateNameList = $this->Branchofficevenue->fnGetStateList();
		$this->lobjform->field8->addMultiOptions($lobjStateNameList);
		$this->view->lintidbrc = $lintidbrc = ( int ) $this->_getParam ( 'idbrc' );
		$larrresult = $this->Branchofficevenue->fngetBranchDetails($lintidbrc);
		if(!$this->_getParam('search'))
		unset($this->gobjsessionsis->Branchofficevenuepaginatorresult);
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1);// Paginator instance
		if(isset($this->gobjsessionsis->Branchofficevenuepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->Branchofficevenuepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->Branchofficevenue->fnSearchBranchDetails( $this->lobjform->getValues (),$lintidbrc ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->Branchofficevenuepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect( $this->baseUrl . '/generalsetup/branchofficevenue/index');
			$this->_redirect( $this->baseUrl . '/generalsetup/branchofficevenue/index/idbrc/'.$lintidbrc);
		}
	}

	public function newbranchofficevenueAction() {
		$this->view->lobjBranchofficevenueform= $this->lobjBranchofficevenueform;
		$lobjUniversity = new GeneralSetup_Model_DbTable_University(); //To get university details
		$lobjUniversity = $lobjUniversity->fnGetUniversityList();
		$this->lobjBranchofficevenueform->AffiliatedTo->addMultiOptions($lobjUniversity);
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjProgramme = new GeneralSetup_Model_DbTable_Program(); //To get programme list
		$lobjProgramme = $lobjProgramme->fnGetProgramList();
		if(count($lobjProgramme) > 0) {
			$lobjProgramme[] = array('key' => 'all', 'value' => 'All');
		}
		$this->lobjBranchofficevenueform->Programme->addMultiOptions($lobjProgramme);

		$larrresult = $this->Branchofficevenue->fnGetRegistrationlocList();

		$this->lobjBranchofficevenueform->RegistrationLoc->addMultiOptions($larrresult);
		$this->view->lintidbrc = $lintidbrc = ( int ) $this->_getParam ( 'idbrc' );
		if ($lintidbrc == 2){
			$larrbranchset = $this->lobjsubjectsofferedmodel->fngetAllBranchset(1);
			$this->lobjBranchofficevenueform->Branch->addMultiOptions($larrbranchset);
		}
		else if ($lintidbrc == 3){
			$larrbranchset = $this->lobjsubjectsofferedmodel->fngetAllBranchset(2);
			$this->lobjBranchofficevenueform->Branch->addMultiOptions($larrbranchset);
		}
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBranchofficevenueform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBranchofficevenueform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$lobjcountry = $this->lobjuser->fnGetCountryList();
		$this->lobjBranchofficevenueform->idCountry->addMultiOptions($lobjcountry);
		$this->view->lobjBranchofficevenueform->idCountry->setValue ($this->view->DefaultCountry);
		echo $this->lintidbrc;
		$this->view->lobjBranchofficevenueform->IdType->setValue ($this->view->lintidbrc);
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post

			if ($this->lobjBranchofficevenueform->isValid ( $larrformData )) {
				$larrregistration = $larrformData;
				$lintIdBranch= $this->Branchofficevenue->fnaddBranchDetails($larrformData);
				$this->Branchofficevenue->fninsertBranchregistration($larrregistration,$lintIdBranch);
				if($lintidbrc ==1){
					$idbrch='Branch';
				}
				if($lintidbrc ==2){
					$idbrch='Office';
				}
				if($lintidbrc ==3){
					$idbrch='Venue';
				}
					
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New'.' '.$idbrch.' '.'Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				$this->_redirect( $this->baseUrl . '/generalsetup/branchofficevenue/index/idbrc/'.$lintidbrc);
			}
		}
	}

	public function branchofficevenuelistAction() { //Action for the updation and view of the  details
		$this->view->lobjBranchofficevenueform= $this->lobjBranchofficevenueform;
		$lintIdBranch = ( int ) $this->_getParam ( 'id' );
		$this->view->lobjBranchofficevenueform->IdBranch->setValue( $lintIdBranch );
		$this->view->lintidbrc = $lintidbrc = ( int ) $this->_getParam ( 'idbrc' );
		if ($lintidbrc == 2){
			$larrbranchset = $this->lobjsubjectsofferedmodel->fngetAllBranchset(1);
			$this->lobjBranchofficevenueform->Branch->addMultiOptions($larrbranchset);
		}
		else if ($lintidbrc == 3){
			$larrbranchset = $this->lobjsubjectsofferedmodel->fngetAllBranchset(2);
			$this->lobjBranchofficevenueform->Branch->addMultiOptions($larrbranchset);
		}
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBranchofficevenueform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBranchofficevenueform->UpdUser->setValue( $auth->getIdentity()->iduser);
		$lobjcountry = $this->lobjuser->fnGetCountryList();
		$this->lobjBranchofficevenueform->idCountry->addMultiOptions($lobjcountry);
		$this->view->lobjBranchofficevenueform->idCountry->setValue ($this->view->DefaultCountry);

		$lobjUniversity = new GeneralSetup_Model_DbTable_University(); //intialize user Model
		$lobjUniversity = $lobjUniversity->fnGetUniversityList();
		$this->lobjBranchofficevenueform->AffiliatedTo->addMultiOptions($lobjUniversity);

		$lobjProgramme = new GeneralSetup_Model_DbTable_Program(); //To get programme list
		$lobjProgramme = $lobjProgramme->fnGetProgramList();
		if(count($lobjProgramme) > 0) {
			$lobjProgramme[] = array('key' => 'all', 'value' => 'All');
		}
		$this->lobjBranchofficevenueform->Programme->addMultiOptions($lobjProgramme);

		$larrresult = $this->Branchofficevenue->fnGetRegistrationlocList();
		$this->lobjBranchofficevenueform->RegistrationLoc->addMultiOptions($larrresult);

		$larrResult = $this->Branchofficevenue->fnviewBranchofficevenueDtls($lintIdBranch);
		$lobjCommonModel = new App_Model_Common();
		$larrCountyrStatesList = $lobjCommonModel->fnGetStateList();
		$this->lobjBranchofficevenueform->idState->addMultiOptions($larrCountyrStatesList);
		$this->view->lobjBranchofficevenueform->idState->setValue ($larrResult['idState']);
		$this->lobjBranchofficevenueform->populate($larrResult);
		$arrPhone = explode("-",$larrResult ['Phone']);
		$this->view->lobjBranchofficevenueform->countrycode->setValue ( $arrPhone [0] );
		$this->view->lobjBranchofficevenueform->statecode->setValue ( $arrPhone [1] );
		$this->view->lobjBranchofficevenueform->Phone->setValue ( $arrPhone [2] );
		$larrmaplist =	$this->Branchofficevenue->fnviewBranchRegistrationMap($lintIdBranch);
		$this->view->branchregistrationmap = $larrmaplist;
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			$larrregistration = $larrformData;
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				//				unset (	$larrformData ['RegistrationLoc']);
				//				unset ( $larrformData ['Programme']);
				//				unset ( $larrformData ['Save'] );
				if ($this->lobjBranchofficevenueform->isValid ( $larrformData )) {
					$lintIdBranch = $larrformData ['IdBranch'];
					$this->Branchofficevenue->fnDeleteBranchregistration($larrformData);//function to delete from  database
					$this->Branchofficevenue->fninsertBranchregistration($larrformData,$lintIdBranch);
					$result=$this->Branchofficevenue->fnupdateBranchofficevenueDtls($lintIdBranch, $larrformData, $larrregistration );
					if($lintidbrc ==1){
						$idbrch='Branch';
					}
					if($lintidbrc ==2){
						$idbrch='Office';
					}
					if($lintidbrc ==3){
						$idbrch='Venue';
					}
					// Write Logs
					$priority=Zend_Log::INFO;
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => $idbrch.' '.'Edit Id=' . $lintIdBranch,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/branchofficevenue/index/idbrc/'.$lintidbrc);
				}
			}
		}
		//$this->view->lobjBranchofficevenueform= $this->lobjBranchofficevenueform;
	}

	public function deleteregistrationlocationAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintidbrc =1;
		$idRegLoc = $this->_getParam('id', 0);
		$larrDelete = $this->Branchofficevenue->fnDeleteRegLoc($idRegLoc);
		$this->_redirect( $this->baseUrl . '/generalsetup/branchofficevenue/index/idbrc/'.$lintidbrc);
	}
}