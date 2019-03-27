<?php
class GeneralSetup_SubjectprerequisitesController extends Base_Base { //Controller for the User Module

	private $lobjSubjectprerequisitesForm;
	private $lobjSubjectprerequisites;
	private $lobjStaffmaster;
	private $lobjsubject;
	private $lobjdeftype;
	private $_gobjlog;
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();	
		
  	}
	public function fnsetObj(){
		
		$this->lobjSubjectprerequisitesForm = new GeneralSetup_Form_Subjectprerequisites();
		$this->lobjSubjectprerequisites = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$this->lobjStaffmaster = new GeneralSetup_Model_DbTable_Staffmaster();
		$this->lobjsubject = new GeneralSetup_Model_DbTable_Subjectmaster();
		$this->lobjdeftype = new App_Model_Definitiontype();
	}
	public function indexAction() { // action for search and view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->subjectprepaginatorresult);	
			
		$lobjSubjectprerequisites = new GeneralSetup_Model_DbTable_Subjectprerequisites(); //user model object
		
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $lobjSubjectprerequisites->fngetSubjectDetails();
		} else {
			$larrresult = $lobjSubjectprerequisites->fngetUserSubjectDetails($this->gobjsessionsis->UserCollegeId); //get user details
		}
		
		$lobjUniversityMasterList = $lobjSubjectprerequisites->fnGetUniversityMasterList();
		$lobjform->field1->addMultiOptions($lobjUniversityMasterList);
		
		$lobjCollegeList = $lobjSubjectprerequisites->fnGetCollegeList();
		$lobjform->field5->addMultiOptions($lobjCollegeList);
		
		$lobjBranchList = $lobjSubjectprerequisites->fnGetBranchList();
		$lobjform->field8->addMultiOptions($lobjBranchList);
		
		$lobjDepartmentList = $lobjSubjectprerequisites->fnGetDepartmentList();
		$lobjform->field20->addMultiOptions($lobjDepartmentList);

		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$sessionID = Zend_Session::getId();
        $this->lobjSubjectprerequisites->fnDeleteTempSubjectprerequisitesDetailsBysession($sessionID);
	
		if(isset($this->gobjsessionsis->subjectprepaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->subjectprepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ( $larrformData )) {
				
			if($this->gobjsessionsis->UserCollegeId == '0') {
				$larrresult = $lobjSubjectprerequisites->fnSearchSubject($lobjform->getValues());
			} else {
				$larrresult = $lobjSubjectprerequisites->fnSearchUserSubject($lobjform->getValues(),$this->gobjsessionsis->UserCollegeId); //get user details
			}
			
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->subjectprepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectprerequisites', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectprerequisites/index');
		}

	}

	
	public function subjectprerequisiteslistAction() { //Action for the updation 
		$auth = Zend_Auth::getInstance();
		$this->view->lobjSubjectprerequisitesForm = $this->lobjSubjectprerequisitesForm; 
		$lobjSubjectprerequisites = new GeneralSetup_Model_DbTable_Subjectprerequisites(); 
		
		$lintisubject = ( int ) $this->_getParam ( 'id' );
		$this->view->idsubject = $lintisubject;
		$this->view->CheckEmptyGrid = 0;
		$this->view->lobjSubjectprerequisitesForm->IdSubject->setValue ( $lintisubject );
		
		$larrresult = $this->lobjSubjectprerequisites->fnviewSubject($lintisubject); //getting user details based on userid
		$this->view->SubjectName = $larrresult['SubjectName'];
		$this->view->SubCode = $larrresult['SubCode'];

		/*if($this->_getParam('update')!='true' ) {
			$sessionID = Zend_Session::getId();
        	$this->lobjSubjectprerequisites->fnDeleteTempSubjectprerequisitesDetailsBysession($sessionID);
		}
		*/
		
		
		$resultsubjectprerequisitest = $this->lobjSubjectprerequisites->fnViewSubjectPrerequisitesDetails($lintisubject);
	
		/*if($lintisubject) {
			if($this->_getParam('update') != 'true') {
	   			$temparrayreult=$this->lobjSubjectprerequisites->fninserttempsubjectprerequisites($resultsubjectprerequisitest,$lintisubject);
			}
	   	}*/
	  	
	  //	$this->view->resultTempsubjectprerequisitest=$resultTempsubjectprerequisitest = $this->lobjSubjectprerequisites->fnViewTempSubjectPrerequisitesDetails($lintisubject);

		$this->view->resultTempsubjectprerequisitest=$resultsubjectprerequisitest;
		
		if($resultsubjectprerequisitest)$this->view->CheckEmptyGrid = 1;		
		
		$subjectlist = $this->lobjsubject->fnGetSubjectList();
		$this->lobjSubjectprerequisitesForm->IdRequiredSubject->addMultiOptions($subjectlist);
		
		$larrdefmsGraderesultset = $this->lobjdeftype->fnGetDefinations('Grade');
		foreach($larrdefmsGraderesultset as $larrGraderesultset) {
			$this->lobjSubjectprerequisitesForm->PrerequisiteGrade->addMultiOption($larrGraderesultset['idDefinition'],$larrGraderesultset['DefinitionDesc']);
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				
				unset ( $larrformData ['Save'] );
				unset ( $larrformData ['IdSubjectPrerequisites'] );
				unset ( $larrformData ['IdRequiredSubject'] );
				unset ( $larrformData ['PrerequisiteType'] );
				unset ( $larrformData ['SubjectCode'] );
				
				if ($this->lobjSubjectprerequisitesForm->isValid ( $larrformData )) {
				
					
					
					
				$this->lobjSubjectprerequisites->fnDeleteSubjectprerequisits($larrformData['IdSubject']);
			
				$result = $this->lobjSubjectprerequisites->fnaddSubjectPrerequisites($larrformData);
					
				/*$sessionID = Zend_Session::getId();
				$fetchtempSubjectprerequisitesdetails = $this->lobjSubjectprerequisites->fnGetSubjectprerequisitesTemDetails($larrformData['IdSubject'],$sessionID);
			
				foreach($fetchtempSubjectprerequisitesdetails as $fetchdtempSubjectprerequisitesdtls) {
					
							$this->lobjSubjectprerequisites->fnDeleteSubjectPrerequists($fetchdtempSubjectprerequisitesdtls['idExists']);
				
				}
				$this->lobjSubjectprerequisites->fnDeleteTempSubjectprerequisits($larrformData['IdSubject'],$sessionID);*/
							
               	// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Subject prerequisities Edit Id=' . $lintisubject,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
				//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectprerequisites', 'action'=>'index'),'default',true));
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectprerequisites/index');
					
			}
		}
		
	}
}
	public function getdepartmentlistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$lobjCommonModel = new App_Model_Common();
		$lintidCollege = $this->_getParam('idCollege');
		
		$larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($this->lobjSubjectprerequisites->fnGetDepartmentListofCollege($lintidCollege));
		echo Zend_Json_Encoder::encode($larrDetails);
		
	}
	
	public function getsubjectlistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$lobjCommonModel = new App_Model_Common();
		$lintiddepartment = $this->_getParam('iddepartment');
		$lintidsubject = $this->_getParam('idsubject');
		$larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($this->lobjSubjectprerequisites->fnGetSubjectListofDepartment($lintiddepartment,$lintidsubject));
		echo Zend_Json_Encoder::encode($larrDetails);
		
	}
	
	public function getsubjectcodeAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$lintidsubject = $this->_getParam('idsubject');	
		$larrDetails = $this->lobjSubjectprerequisites->getSubjectCode($lintidsubject);
		echo $larrDetails['SubCode'];
		
	}
	
	public function deletesubjectprerequisitesAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
						
		$IdTempSubjectPrerequisites = $this->_getParam('IdTempSubjectPrerequisites');
		
		$larrDelete = $this->lobjSubjectprerequisites->fnUpdateTemptempsubjectprerequisitesdetails($IdTempSubjectPrerequisites);	
		echo "1";
	}
	
}