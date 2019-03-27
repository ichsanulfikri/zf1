<?php

class GeneralSetup_IntakeController extends Base_Base {
	private $lobjagentpayment;

	/**
	 *
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');
	}

	/**
	 *
	 * @see Base_Base::fnsetObject()
	 */
	public function fnsetObj() {
		$this->lobjIntakeForm = new GeneralSetup_Form_Intake();
		$this->lobjcollegemaster = new GeneralSetup_Model_DbTable_Program();
		$this->lobjintake = new GeneralSetup_Model_DbTable_Intake();
		$this->lobjintakemapping = new GeneralSetup_Model_DbTable_IntakeBranchMapping();
		$this->lobjform = new App_Form_Search ();
		$this->lobjagentpayment = new GeneralSetup_Model_DbTable_Agentpaymentdetail();
	}

	/**
	 *
	 * Method to handle the index action
	 */
	public function indexAction() {
		$this->view->title="Program Setup";
		$this->view->lobjform = $this->lobjform;
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$larrresult = $this->lobjintake->fngetIntakeList();
		$lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$larrscheme = $lobjsemester->fnGetShcemeList();
		$this->view->lobjform->field5->addMultiOptions($larrscheme);

		if(!$this->_getParam('search'))
		unset($this->gobjsessionsis->intakepaginatorresult);
		$lintpagecount =$this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$lobjPaginator = new App_Model_Common(); // Definitiontype model

		if(isset($this->gobjsessionsis->intakepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->intakepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjintake->fnSearchIntake( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->intakepaginatorresult = $larrresult;
			}
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/intake/index');
		}
	}

	public function newintakeAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjIntakeForm = $this->lobjIntakeForm;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjIntakeForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjIntakeForm->UpdUser->setValue( $auth->getIdentity()->iduser);

        // THIS PART COMES if there is mismatch when performing Copy intake
		$errorMsg = $this->getRequest()->getParam('errorMsg');
		if($errorMsg=='1') { $this->view->errorMsg = '1'; }
		// END

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjIntakeForm->isValid($formData)) {
				// Write Logs
				$priority=Zend_Log::INFO;
				$IdIntake = $this->lobjintake->fnaddIntake($formData);

				if($IdIntake=='mismatch') {
					$this->view->errorMsg = '1';
				} else {

				for($i = 0; $i < count($formData['IdProgram']); $i++) {
					$intakeMap = array();
					$intakeMap['IdIntake'] = $IdIntake;
					$intakeMap['IdProgram'] = $formData['IdProgram'][$i];
					$intakeMap['IdBranch'] = $formData['IdBranch'][$i];
					$intakeMap['UpdUser'] = $formData['UpdUser'];
					$intakeMap['UpdDate'] = $formData['UpdDate'];
					$this->lobjintakemapping->fnaddIntakeMapping($intakeMap);
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
									  'level' => $priority,
									  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
					                  'time' => date ( 'Y-m-d H:i:s' ),
					   				  'message' => 'Branch Intake Mapping Added',
									  'Description' =>  Zend_Log::DEBUG,
									  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				}

				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
									  'level' => $priority,
									  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
					                  'time' => date ( 'Y-m-d H:i:s' ),
					   				  'message' => 'New Intake added',
									  'Description' =>  Zend_Log::DEBUG,
									  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/intake/index');
				}

			}
		}
	}

	/**
	 *
	 * Action to get program list
	 */
	public function getprogramlistAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintidCollege = $this->_getParam('idCollege');
		$larrprogramList = $this->lobjcollegemaster->fngetProgramFromColl($lintidCollege);

		//$data = new Zend_Dojo_Data('value', $larrprogramList->toArray(), 'label');

		//$this->_helper->autoCompleteDojo($data);
		echo json_encode($larrprogramList);
		exit;
	}

	public function editintakeAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->title="Edit Semester";
		$this->view->lobjIntakeForm = $this->lobjIntakeForm;
		$IdIntake = $this->_getParam('id', 0);
		$result = $this->lobjintake->fetchAll('IdIntake  ='.$IdIntake);
		$result = $result->toArray();
		foreach($result as $intakeresult){
		}
		$this->view->lobjIntakeForm->populate($intakeresult);
		$this->view->lobjIntakeForm->IdIntake->setValue( $IdIntake );
		$this->view->IdIntake = $IdIntake;

		$this->view->larrintakedetails = $this->lobjintakemapping->fngetIntakeMappingDetails($IdIntake);

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjIntakeForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjIntakeForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		$priority=Zend_Log::INFO;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjIntakeForm->isValid($formData)) {
				$idIntake = $this->lobjintake->fnupdateIntake($formData,$IdIntake);
				$this->lobjintakemapping->fndeleteIntakeMappings($IdIntake);
				for($i = 0; $i < count($formData['IdProgram']); $i++) {
					$intakeMap = array();
					$intakeMap['IdIntake'] = $IdIntake;
					$intakeMap['IdProgram'] = $formData['IdProgram'][$i];
					$intakeMap['IdBranch'] = $formData['IdBranch'][$i];
					$intakeMap['UpdUser'] = $formData['UpdUser'];
					$intakeMap['UpdDate'] = $formData['UpdDate'];
					$this->lobjintakemapping->fnaddIntakeMapping($intakeMap);
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
									  'level' => $priority,
									  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
					                  'time' => date ( 'Y-m-d H:i:s' ),
					   				  'message' => 'Branch Intake Mapping Added',
									  'Description' =>  Zend_Log::DEBUG,
									  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				}

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Semester Edit Id=' . $IdSemester,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'semester', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/intake/index');
			}
		}
	}

	function deleteintakedetailsAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		//Get Po details Id
		$Id = $this->_getParam('id');
		$this->lobjintakemapping->fnDeleteIntakeDetailById($Id);
		echo "1";
	}

	function copyintakeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$auth = Zend_Auth::getInstance();
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			unset($formData['Copy']);
			$IdIntake = $formData['FromIntake'];
			$larrintakedetails = $this->lobjintake->fngetIntakeDetails($IdIntake);
			foreach($larrintakedetails as $larrintakedetails){}
			$larrnewintake = array();
			$larrnewintake['IntakeId'] = $formData['CopyIntakeId'];
			$larrnewintake['IntakeDesc'] = $formData['CopyIntakeDescription'];
			$larrnewintake['IntakeDefaultLanguage'] = $larrintakedetails['IntakeDefaultLanguage'];
			$larrnewintake['ApplicationStartDate'] = $formData['CopyApplicationStartDate'];
			$larrnewintake['ApplicationEndDate'] = $formData['CopyApplicationEndDate'];
			$larrnewintake['UpdUser'] = $formData['UpdUser'];
			$larrnewintake['UpdDate'] = $formData['UpdDate'];
			$lintnewid = $this->lobjintake->fnaddIntake($larrnewintake);

			if($lintnewid=='mismatch') {
					$this->_redirect( $this->baseUrl . '/generalsetup/intake/newintake/errorMsg/1');
				} else {

			//Copy Intake Mappings
			$larroldmapping = $this->lobjintakemapping->fngetIntakeMappingDetails($IdIntake);
			foreach($larroldmapping as $larrmapping) {
				unset($larrmapping['Id']);
				unset($larrmapping['BranchName']);
				unset($larrmapping['Program']);
				$larrmapping['IdIntake'] = $lintnewid;
				$larrmapping['UpdUser'] = $formData['UpdUser'];
				$larrmapping['UpdDate'] = $formData['UpdDate'];
				$this->lobjintakemapping->fnaddIntakeMapping($larrmapping);
			}
			//Copy Agent Rate Details
			$larragentrate = $this->lobjagentpayment->fnGetIntakeListbyIntake($IdIntake);
			foreach($larragentrate as $temp) {
				unset($temp['IdPayment']);
				$temp['Intake'] = $lintnewid;
				$temp['UpdUser'] = $formData['UpdUser'];
				$temp['UpdDate'] = $formData['UpdDate'];
				$this->lobjagentpayment->fnaddpaymentdetails($temp);
			}

			// Write Logs
			$priority=Zend_Log::INFO;
			$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Intake Copied=',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
			$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
			$this->_redirect( $this->baseUrl . '/generalsetup/intake/index');
		}

		}
	}

}