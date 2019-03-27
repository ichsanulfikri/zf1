<?php
class GeneralSetup_LandscapeController extends Base_Base {
	private $lobjprogram;
	private $lobjlandscapeform;
	private $lobjdeftype;
	private $lobjsemester;
	private $lobjlandscape;
	private $lobjprogramrequirement;
	private $lobjsubjectmaster;
	private $lobjlandscapesubject;
	private $lobjlandscapetemp;
	private $_gobjlog;
	private $_gobjlogger;
	private $lobjprogramschemeModel;

	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->_gobjlogger = Zend_Registry::get ( 'logger' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate');
		Zend_Form::setDefaultTranslator($this->view->translate);
	}

	public function fnsetObj(){
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjlandscapeform = new GeneralSetup_Form_Landscape();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$this->lobjlandscape = new GeneralSetup_Model_DbTable_Landscape();
		$this->lobjprogramrequirement = new GeneralSetup_Model_DbTable_Programrequirement();
		$this->lobjsubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster();
		$this->lobjlandscapesubject = new GeneralSetup_Model_DbTable_Landscapesubject();
		$this->lobjcoursemaster = new GeneralSetup_Model_DbTable_Coursemaster();
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjlandscapetemp = new GeneralSetup_Model_DbTable_Landscapetemp();
		$this->lobjintake = new GeneralSetup_Model_DbTable_Intake();
		$this->lobjprogramschemeModel = new GeneralSetup_Model_DbTable_Programscheme();
	}

	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult = $this->lobjprogram->fngetProgramDetails ();
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$sessionID = Zend_Session::getId();
		$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		$this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
		if(!$this->_getParam('search'))
			unset($this->gobjsessionsis->landscapepaginatorresult);

		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->landscapepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->landscapepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}

		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjlandscape->fnSearchProgram ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				@$this->gobjsessionsis->landscapepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/landscape/index');
		}
	}

	public function addlandscapeAction() {
		$this->view->lobjform = $this->lobjform;
		$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
			$this->view->TotalCreditHours = $programlistdetail['TotalCreditHours'];
		}
		$sessionID = Zend_Session::getId();
		$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		$this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
		$this->lobjlandscape->fnDeleteTemplandsacpeblockBysession($sessionID);
		$this->lobjlandscape->fnDeleteTemplandsacpeblocksemesterBysession($sessionID);
		$this->lobjlandscape->fnDeleteTemplandsacpeblocksubjectBysession($sessionID);

		$larrresult = $this->lobjlandscape->fnLandscapeList($IdProgram);

		$larrresultActive = $this->lobjlandscape->checkActiveLandscape($IdProgram);
		if(count($larrresultActive)>0) {
			$this->view->hasActive = '1';
		} else { $this->view->hasActive = '0';
		}

		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$this->view->id = $this->_getParam('id',0);

		if(isset($this->gobjsessionstudent->landscapeprgpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionstudent->landscapeprgpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
	}

	//add level landscape
	public function levellandscapeAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		//$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');

		$this->view->lobjlandscapeform->CopyActive->setValue (125);
		$this->view->lobjlandscapeform->CopyActive->setAttrib ('readonly','true');

		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$this->view->lobjlandscapeform->TotalCreditHours->setValue ($larrprogramresult['TotalCreditHours']);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			$this->lobjlandscapeform->CopyActive->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (42);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		//		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyIdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyFromIdStartSemester->addMultiOptions($larrintakelist);


		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//		$larrscheme = $schemeobject->fetchAll();
		//		foreach($larrscheme as $larrschemearr) {
		//			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyFromScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//		}
		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyScheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyFromScheme->addmultiOptions($larrscheme);

		$majoringlist = $this->lobjlandscape->fnGetMajoringListAll();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);


		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjlandscapeform->isValid($formData)) {
				$resultLandscape=$this->lobjlandscapetemp->fninsertLandscape($formData);

				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$resultLandscape);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$resultLandscape);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$resultLandscape);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$resultLandscape);

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'New Level Landscape Add',
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log


				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}

	//edit level landscape
	public function levellandscapelistAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->TotalCreditHours->setAttrib ('readonly','true');
		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$majoringlist = $this->lobjlandscape->fnGetMajoringListAll();
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (42);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		//		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//		//echo "<pre>";
		//		//print_r($larrsemesterresult);die();
		//
		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//		$larrscheme = $schemeobject->fetchAll();
		//		foreach($larrscheme as $larrschemearr) {
		//			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//		}
		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);

		if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
			$this->lobjlandscapetemp->fnDeleteTemplandsacpesubBysession($sessionID);
			$this->lobjlandscapetemp->fnDeleteTemplandscapeblocksubsemBysession($sessionID);
			$this->lobjlandscapetemp->fnDeleteTemplandscapeblockBysession($sessionID);
			$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		}

		$idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
		$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
		$result = $result->toArray();
		//		echo "<pre>";
		//		print_r($result);
		foreach($result as $landscaperesult) {
			$this->view->LandscapeType = $landscaperesult['LandscapeType'];
			$lintsemestercount = $this->view->SemsterCount = $landscaperesult['SemsterCount'];
			$lintblockcount = $this->view->Blockcount = 5;//$landscaperesult['Blockcount'];
			$description = $landscaperesult['ProgramDescription'];
			$landscapeDefaultLanguage = $landscaperesult['landscapeDefaultLanguage'];
			$this->view->IdStartSemester = $landscaperesult['IdStartSemester'];
			$this->view->IdLandscape = $landscaperesult['IdLandscape'];
			$this->view->TotalCreditHours = $landscaperesult['TotalCreditHours'];
			$AddDrop = $landscaperesult['AddDrop'];
			$this->view->IdScheme = $landscaperesult['Scheme'];
		}
		$this->view->lobjlandscapeform->Scheme->setValue($this->view->IdScheme);
		$this->view->lobjlandscapeform->IdStartSemester->setValue($this->view->IdStartSemester);
		$this->view->lobjlandscapeform->Blockcount->setValue($this->view->Blockcount);
		$this->view->lobjlandscapeform->Blockcount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->SemsterCount->setValue($this->view->SemsterCount);
		$this->view->lobjlandscapeform->SemsterCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->IdLandscape->setValue($this->view->IdLandscape);
		$this->view->lobjlandscapeform->TotalCreditHours->setValue($this->view->TotalCreditHours);
		$this->view->lobjlandscapeform->ProgramDescription->setValue($description);
		$this->view->lobjlandscapeform->landscapeDefaultLanguage->setValue($landscapeDefaultLanguage);
		$this->view->lobjlandscapeform->AddDrop->setValue($AddDrop);


		$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape);
		$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape);
		if($this->_getParam('update')!= 'true') {
			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape,$sessionID);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);

		$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
		$templandscapelist = $this->view->larrLandScapeBlock = $larrLandScapeBlock;
		$sessionID = Zend_Session::getId();
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblock($templandscapelist,$sessionID);
		}
		$this->view->larrtemplandscapeblock = $larrtemplandscapeblock = $this->lobjlandscapetemp->fnaddLandscapetempblocklist($idlandscape,$sessionID);


		$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
		$templandscapeblocksublistsem = $this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblocksubjectsem($templandscapeblocksublistsem,$sessionID);
		}
		$this->view->tempblocksubsemlist = $tempblocksubsemlist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockSubjectSemDtls($idlandscape,$sessionID);


		foreach($this->view->larrLandScapeBlock as $larrLandScapeBlockSubject) {
			$this->lobjlandscapeform->BlockDtlsList->addMultiOption($larrLandScapeBlockSubject['block'], $larrLandScapeBlockSubject['blockname']);
		}
		for($i=1;$i<=$lintsemestercount;$i++) {
			$this->lobjlandscapeform->SemNameList->addMultiOption($i,$i);
			$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
		}

		for($i=1;$i<=$lintblockcount;$i++) {
			$this->lobjlandscapeform->block->addMultiOption($i,$i);
		}

		// GET THE COURSE TYPE dropdown registered values on basis of landscape and program ID
		$getProgReqdData = $this->lobjprogramrequirement->getlandscapecoursetype($IdProgram,$idlandscape);
		$this->view->resultCourseType = $getProgReqdData;

		$this->view->updateValue = '';
		// GET THE REGISTERED COURSE TYPE
		if($this->_getParam('update')!='') {
			$getcoursetypeDataResult = $this->lobjprogramrequirement->getCourseTypeDeleted($IdProgram,$idlandscape);
			$this->view->resultDropDownCourseType = $getcoursetypeDataResult;
			$this->view->updateValue = '1';
		}


		//asd($this->view->landscapesubresult );
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($this->lobjlandscapeform->isValid($formData)) {
				$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester'],$formData['ProgramDescription'],$formData['AddDrop'],$formData['landscapeDefaultLanguage']);

				$idlandscpae = $formData['IdLandscape'];
				$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetailslevel($formData['IdLandscape'],$sessionID);
				$this->lobjlandscape->fnDeleteFromProgramRequirements($idlandscpae);
				foreach($fetchtempprogramrequrimesntsdetails as $fetchtempprogramrequrimesntsdetailslevel)
					$this->lobjlandscape->fninsertToProgramRequirements($fetchtempprogramrequrimesntsdetailslevel,$idlandscpae);
				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$idlandscpae);

				$templandsubject=$this->lobjlandscape->fnGetTempLandscapeSubject($formData['IdLandscape'],$sessionID);
				$deleteLandscapeSubject=$this->lobjlandscape->deleteLandscapeSubjectLevel($idlandscpae);
				foreach($templandsubject as $templandsubjectlevel)
					$this->lobjlandscape->fninsertToLandScapeSubjectLevel($templandsubjectlevel,$idlandscpae);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$idlandscpae);



				$templandScapeBlock=$this->lobjlandscape->fnGetTempLandscapeBlockLevel($formData['IdLandscape'],$sessionID);
				$deleteLandscapeBlock=$this->lobjlandscape->deleteLandscapeBlockLevel($idlandscpae);
				foreach($templandScapeBlock as $templandScapeBlock)
					$this->lobjlandscape->fninsertToLandScapeBlockLevel($templandScapeBlock,$idlandscpae);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$idlandscpae);


				$templandScapeSemester=$this->lobjlandscape->fnGetTempLandscapeSemesterLevel($formData['IdLandscape'],$sessionID);
				$deleteLandscapeSemester=$this->lobjlandscape->deleteLandscapeSemesterLevel($idlandscpae);
				foreach($templandScapeSemester as $templandScapeSemester)
					$this->lobjlandscape->fninsertToLandScapeSemesterLevel($templandScapeSemester,$idlandscpae);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$idlandscpae);



				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'Level Landscape Edit Id=' . $IdProgram,
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log



				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}


	//blocklandscape add blocklandscapeAction
	public function blocklandscapeAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		//$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');

		$this->view->lobjlandscapeform->CopyActive->setValue (125);
		$this->view->lobjlandscapeform->CopyActive->setAttrib ('readonly','true');

		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$this->view->lobjlandscapeform->TotalCreditHours->setValue ($larrprogramresult['TotalCreditHours']);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			$this->lobjlandscapeform->CopyActive->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (44);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		//		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyIdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyFromIdStartSemester->addMultiOptions($larrintakelist);


		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);
		//$this->lobjlandscapeform->subjectblock->addMultiOptions($larrsubjectresult);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//		$larrscheme = $schemeobject->fetchAll();
		//		foreach($larrscheme as $larrschemearr) {
		//			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyFromScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//		}

		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyScheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyFromScheme->addmultiOptions($larrscheme);


		$majoringlist = $this->lobjlandscape->fnGetMajoringList($IdProgram);
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);


		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			//			echo "<pre>";
			//			print_r($formData);die;
			if ($this->lobjlandscapeform->isValid($formData)) {
				$resultLandscape=$this->lobjlandscapetemp->fninsertLandscape($formData);

				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$resultLandscape);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$resultLandscape);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$resultLandscape);
				$resultLandscapeBlockSubject=$this->lobjlandscapetemp->fninsertLandscapeBlockSubject($formData,$resultLandscape);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$resultLandscape);
				$resultLandscapeBlockYear=$this->lobjlandscapetemp->fninsertLandscapeBlockYear($formData,$resultLandscape);
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'New Block Landscape Add',
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}

	//edit blockbased blocklandscapelistAction
	public function blocklandscapelistAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->TotalCreditHours->setAttrib ('readonly','true');
		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$majoringlist = $this->lobjlandscape->fnGetMajoringList($IdProgram);
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (44);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		//		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//		//echo "<pre>";
		//		//print_r($larrsemesterresult);die();
		//
		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();

		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);

		$laddedsubjects =  $this->lobjsubjectmaster->fnGetLandscapeSubjectList($idlandscape);
		$this->view->addedSubjects = $laddedsubjects;
		//echo '</pre>';
		//print_r($laddedsubjects);
		//die;
		$this->lobjlandscapeform->subjectblock->addMultiOptions($laddedsubjects);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//		$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//		$larrscheme = $schemeobject->fetchAll();
		//		foreach($larrscheme as $larrschemearr) {
		//			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//		}
		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);

		if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
			$this->lobjlandscapetemp->fnDeleteTemplandsacpesubBysession($sessionID);
			$this->lobjlandscapetemp->fnDeleteTemplandscapeblocksubsemBysession($sessionID);
			$this->lobjlandscapetemp->fnDeleteTemplandscapeblockBysession($sessionID);
			$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
			$this->lobjlandscapetemp->fnDeleteTemplandscapeYearBysession($sessionID);
		}


		$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
		$result = $result->toArray();

		//		echo "<pre>";
		//		print_r($result);
		foreach($result as $landscaperesult) {
			$this->view->LandscapeType = $landscaperesult['LandscapeType'];
			$lintsemestercount = $this->view->SemsterCount = $landscaperesult['SemsterCount'];
			$lintblockcount = $this->view->Blockcount = $landscaperesult['Blockcount'];
			$lintyearcount = $this->view->YearCount = $landscaperesult['YearCount'];
			$description = $landscaperesult['ProgramDescription'];
			$landscapeDefaultLanguage = $landscaperesult['landscapeDefaultLanguage'];
			$this->view->IdStartSemester = $landscaperesult['IdStartSemester'];
			$this->view->IdLandscape = $landscaperesult['IdLandscape'];
			$this->view->TotalCreditHours = $landscaperesult['TotalCreditHours'];
			$AddDrop = $landscaperesult['AddDrop'];
			$this->view->IdScheme = $landscaperesult['Scheme'];
		}
		$this->view->lobjlandscapeform->Scheme->setValue($this->view->IdScheme);
		$this->view->lobjlandscapeform->IdStartSemester->setValue($this->view->IdStartSemester);
		$this->view->lobjlandscapeform->Blockcount->setValue($lintblockcount);//$this->view->Blockcount);
		$this->view->lobjlandscapeform->Blockcount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->YearCount->setValue($lintyearcount);//$this->view->Blockcount);
		$this->view->lobjlandscapeform->YearCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->SemsterCount->setValue($this->view->SemsterCount);
		$this->view->lobjlandscapeform->SemsterCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->IdLandscape->setValue($this->view->IdLandscape);
		$this->view->lobjlandscapeform->TotalCreditHours->setValue($this->view->TotalCreditHours);
		$this->view->lobjlandscapeform->ProgramDescription->setValue($description);
		$this->view->lobjlandscapeform->landscapeDefaultLanguage->setValue($landscapeDefaultLanguage);
		$this->view->lobjlandscapeform->AddDrop->setValue($AddDrop);

		$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape);
		$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape);

		if($this->_getParam('update')!= 'true') {
			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);

		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape,$sessionID);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
		//asd($this->view->landscapesubresult,false);

		$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
		$templandscapelist = $this->view->larrLandScapeBlock = $larrLandScapeBlock;
		$sessionID = Zend_Session::getId();
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblock($templandscapelist,$sessionID);
		}
		$this->view->larrtemplandscapeblock = $larrtemplandscapeblock = $this->lobjlandscapetemp->fnaddLandscapetempblocklist($idlandscape,$sessionID);


		$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
		$templandscapeblocksublistsem = $this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblocksubjectsem($templandscapeblocksublistsem,$sessionID);
		}
		$this->view->tempblocksubsemlist = $tempblocksubsemlist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockSubjectSemDtls($idlandscape,$sessionID);
		/////
		$larrLandScapeBlockYear = $this->lobjlandscape->fnGetLandScapeBlockYearDtls($idlandscape);
		$templandscapeblockyearlistsem = $this->view->larrLandScapeBlockYear = $larrLandScapeBlockYear;
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblockyearsem($templandscapeblockyearlistsem,$sessionID);
		}
		$this->view->tempblockyearsemlist = $tempblockyearsemlist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockYearDtls($idlandscape,$sessionID);

		$larrBlockLandScapeSubject = $this->lobjlandscape->fnGetLandScapeBlockSubjectDtls($idlandscape);
		$templandscapeblocksub = $this->view->larrBlockLandScapeSubject = $larrBlockLandScapeSubject;
		//		echo "<pre>";
		//		print_r($larrBlockLandScapeSubject);
		if($this->_getParam('update')!= 'true') {
			$this->lobjlandscapetemp->fninserttempblocksubject($templandscapeblocksub,$sessionID);
		}
		$this->view->tempblocksublist = $tempblocksublist = $this->lobjlandscapetemp->fnGetTempLandScapeBlockSubjectDtls($idlandscape,$sessionID);
		//		echo "<pre>";
		//		print_r($tempblocksublist);
		foreach($this->view->larrLandScapeBlock as $larrLandScapeBlockSubject) {
			$this->lobjlandscapeform->BlockDtlsList->addMultiOption($larrLandScapeBlockSubject['block'], $larrLandScapeBlockSubject['blockname']);
		}
		//		$coursetype = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		//		$result = $coursetype->fngetcoursetypeDetails();
		//		foreach($result as $key=>$name)
		//		{
		//			$temp['key'] = $name['key'];
		//			$temp['value']= $name['name'];
		//		}
		//		echo "<pre>";
		//		print_r($temp);
		//		$this->lobjlandscapeform->CourseType->addMultiOption($temp);
		for($i=1;$i<=$lintsemestercount;$i++) {
			$this->lobjlandscapeform->SemNameList->addMultiOption($i,$i);
			$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
			$this->lobjlandscapeform->YearSemester->addMultiOption($i,$i);
		}

		for($i=1;$i<=$lintyearcount;$i++) {
			$this->lobjlandscapeform->Year->addMultiOption($i,$i);

		}

		for($i=1;$i<=$lintblockcount;$i++) {
			$this->lobjlandscapeform->block->addMultiOption($i,$i);
			$this->lobjlandscapeform->blocksub->addMultiOption($i,$i);
		}

		// GET THE COURSE TYPE dropdown registered values on basis of landscape and program ID
		$getProgReqdData = $this->lobjprogramrequirement->getlandscapecoursetype($IdProgram,$idlandscape);
		$this->view->resultCourseType = $getProgReqdData;

		$this->view->updateValue = '';
		// GET THE REGISTERED COURSE TYPE
		if($this->_getParam('update')!='') {
			$getcoursetypeDataResult = $this->lobjprogramrequirement->getCourseTypeDeleted($IdProgram,$idlandscape);
			$this->view->resultDropDownCourseType = $getcoursetypeDataResult;
			$this->view->updateValue = '1';
		}



		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			//			echo "<pre>";
			//			print_r($formData);
			if ($this->lobjlandscapeform->isValid($formData)) {
				$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester'],$formData['ProgramDescription'],$formData['AddDrop'],$formData['landscapeDefaultLanguage']);

				$idlandscpae = $formData['IdLandscape'];
				$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetailslevel($formData['IdLandscape'],$sessionID);
				$this->lobjlandscape->fnDeleteFromProgramRequirements($idlandscpae);
				foreach($fetchtempprogramrequrimesntsdetails as $fetchtempprogramrequrimesntsdetailslevel)
					$this->lobjlandscape->fninsertToProgramRequirements($fetchtempprogramrequrimesntsdetailslevel,$idlandscpae);
				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$idlandscpae);

				$templandsubject=$this->lobjlandscape->fnGetTempLandscapeSubject($formData['IdLandscape'],$sessionID);
				$deleteLandscapeSubject=$this->lobjlandscape->deleteLandscapeSubjectLevel($idlandscpae);
				foreach($templandsubject as $templandsubjectlevel)
					$this->lobjlandscape->fninsertToLandScapeSubjectLevel($templandsubjectlevel,$idlandscpae);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$idlandscpae);



				$templandScapeBlock=$this->lobjlandscape->fnGetTempLandscapeBlockLevel($formData['IdLandscape'],$sessionID);
				$deleteLandscapeBlock=$this->lobjlandscape->deleteLandscapeBlockLevel($idlandscpae);
				foreach($templandScapeBlock as $templandScapeBlock)
					$this->lobjlandscape->fninsertToLandScapeBlockLevel($templandScapeBlock,$idlandscpae);
				$resultLandscapeBlock=$this->lobjlandscapetemp->fninsertLandscapeBlockLevel($formData,$idlandscpae);



				$templandScapesubjectBlock=$this->lobjlandscape->fnGetTempLandscapeSubjectLevel($formData['IdLandscape'],$sessionID);
				$deleteLandscapesubjectBlock=$this->lobjlandscape->deleteLandscapeBlocksubjectLevel($idlandscpae);
				foreach($templandScapesubjectBlock as $templandScapesubjectBlock)
					$this->lobjlandscape->fninsertToLandScapeBlocksubjectLevel($templandScapesubjectBlock,$idlandscpae);
				$resultLandscapeBlockSubject=$this->lobjlandscapetemp->fninsertLandscapeBlockSubject($formData,$idlandscpae);

				$templandScapeSemester=$this->lobjlandscape->fnGetTempLandscapeSemesterLevel($formData['IdLandscape'],$sessionID);
				$deleteLandscapeSemester=$this->lobjlandscape->deleteLandscapeSemesterLevel($idlandscpae);
				foreach($templandScapeSemester as $templandScapeSemester)
					$this->lobjlandscape->fninsertToLandScapeSemesterLevel($templandScapeSemester,$idlandscpae);
				$resultLandscapeBlockSemester=$this->lobjlandscapetemp->fninsertLandscapeBlockSemester($formData,$idlandscpae);

				$templandScapeYear=$this->lobjlandscape->fnGetTempLandscapeYear($formData['IdLandscape'],$sessionID);
				$deleteLandscapeYear=$this->lobjlandscape->deleteLandscapeyear($idlandscpae);
				foreach($templandScapeYear as $templandScapeYear)
					$this->lobjlandscape->fninsertToLandScapeYear($templandScapeYear,$idlandscpae);
				$resultLandscapeBlockYear=$this->lobjlandscapetemp->fninsertLandscapeBlockYear($formData,$idlandscpae);


				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'Block Landscape Edit Id=' . $IdProgram,
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}





	//semester based add editlandscapeAction
	public function editlandscapeAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		//$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');

		$this->view->lobjlandscapeform->CopyActive->setValue (125);
		$this->view->lobjlandscapeform->CopyActive->setAttrib ('readonly','true');

		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);


		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');
		$this->view->lobjlandscapeform->TotalCreditHours->setValue ($larrprogramresult['TotalCreditHours']);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			$this->lobjlandscapeform->CopyActive->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (43);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyIdStartSemester->addMultiOptions($larrintakelist);
		$this->lobjlandscapeform->CopyFromIdStartSemester->addMultiOptions($larrintakelist);

		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//$larrscheme = $schemeobject->fetchAll();
		//echo $IdProgram;
		//echo "<pre>";
		//print_r($larrscheme);
		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyScheme->addmultiOptions($larrscheme);
		$this->lobjlandscapeform->CopyFromScheme->addmultiOptions($larrscheme);



		//		foreach($larrscheme as $larrschemearr) {
		//			$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//			$this->lobjlandscapeform->CopyFromScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//		}

		$majoringlist = $this->lobjlandscape->fnGetMajoringList($IdProgram);
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);


		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			//			echo "<pre>";
			//			print_r($formData);die();
			if ($this->lobjlandscapeform->isValid($formData)) {
				$resultLandscape=$this->lobjlandscapetemp->fninsertLandscape($formData);

				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$resultLandscape);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$resultLandscape);
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'New Semester Landscape Add',
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				//log file
				$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
				$message = " ".$controller."		"."New Semester Landscape Add"."		".$this->getRequest ()->getServer ( 'REMOTE_ADDR' )."			"."Success"."			".$auth->getIdentity()->iduser."\r";
				$this->_gobjlogger->log($message,5);
				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}




	//semester based edit editlandscapelistAction
	public function editlandscapelistAction(){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->lobjlandscapeform->Active->setValue (125);
		$this->view->lobjlandscapeform->Active->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->TotalCreditHours->setAttrib ('readonly','true');
		$IdProgram = $this->_getParam('id', 0);
		$this->view->IdProgram = $IdProgram;
		$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
		foreach($programlist as $programlistdetail){
			$this->view->program = $programlistdetail['ProgramName'];
		}
		$majoringlist = $this->lobjlandscape->fnGetMajoringList($IdProgram);
		$this->lobjlandscapeform->IdProgramMajoring->addMultiOptions($majoringlist);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$sessionID = Zend_Session::getId();
		$this->view->lobjlandscapeform->session_id->setValue ( $sessionID );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjlandscapeform->UpdUser->setValue(1);
		$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
		$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
		$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
		$subjecttyperesult = $larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Landscape');

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('LandscapeStatus');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->Active->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$this->view->lobjlandscapeform->LandscapeType->setValue (43);
		$this->view->lobjlandscapeform->LandscapeType->setAttrib ('readonly','true');
		//		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		//		//echo "<pre>";
		//		//print_r($larrsemesterresult);die();
		//
		//		foreach($larrsemesterresult as $larrsemesterresult) {
		//			$this->lobjlandscapeform->IdStartSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		//		}
		$larrintakelist = $this->lobjintake->fngetallIntake();
		$this->lobjlandscapeform->IdStartSemester->addMultiOptions($larrintakelist);
		$larroldsemesterresult = $this->lobjsemester->fngetlandscapeoldSemesterLandscapeDetails();
		foreach($larroldsemesterresult as $larroldsemesterresult) {
			$this->lobjlandscapeform->Semester->addMultiOption($larroldsemesterresult['IdSemester'],$larroldsemesterresult['SemesterMainName']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);


		$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
		$this->lobjlandscapeform->SubjectNameList->addMultiOptions($larrsubjectresult);

		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}

		//$schemeobject = new GeneralSetup_Model_DbTable_Schemesetup();
		//$larrscheme = $schemeobject->fetchAll();
		//foreach($larrscheme as $larrschemearr) {
		//$this->lobjlandscapeform->Scheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		//}
		$larrscheme = $this->lobjprogramschemeModel->fngetschemelist($IdProgram);
		$this->lobjlandscapeform->Scheme->addmultiOptions($larrscheme);

		if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
			$this->lobjlandscapetemp->fnDeleteTemplandsacpesubBysession($sessionID);
			$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
		}

		$idlandscape = $this->view->idlandscape = $this->_getParam('idlandscape', 0);
		$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
		$result = $result->toArray();

		foreach($result as $landscaperesult) {
			$this->view->LandscapeType = $landscaperesult['LandscapeType'];
			$lintsemestercount = $this->view->SemsterCount = $landscaperesult['SemsterCount'];
			$lintblockcount = $this->view->Blockcount = 5;//$landscaperesult['Blockcount'];
			$description = $landscaperesult['ProgramDescription'];
			$landscapeDefaultLanguage = $landscaperesult['landscapeDefaultLanguage'];
			$this->view->IdStartSemester = $landscaperesult['IdStartSemester'];
			$this->view->IdLandscape = $landscaperesult['IdLandscape'];
			$this->view->TotalCreditHours = $landscaperesult['TotalCreditHours'];
			$AddDrop = $landscaperesult['AddDrop'];
			$this->view->IdScheme = $landscaperesult['Scheme'];
		}
		$this->view->lobjlandscapeform->Scheme->setValue($this->view->IdScheme);
		$this->view->lobjlandscapeform->IdStartSemester->setValue($this->view->IdStartSemester);
		$this->view->lobjlandscapeform->Blockcount->setValue($this->view->Blockcount);
		$this->view->lobjlandscapeform->Blockcount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->SemsterCount->setValue($this->view->SemsterCount);
		$this->view->lobjlandscapeform->SemsterCount->setAttrib ('readonly','true');
		$this->view->lobjlandscapeform->IdLandscape->setValue($this->view->IdLandscape);
		$this->view->lobjlandscapeform->TotalCreditHours->setValue($this->view->TotalCreditHours);
		$this->view->lobjlandscapeform->ProgramDescription->setValue($description);
		$this->view->lobjlandscapeform->landscapeDefaultLanguage->setValue($landscapeDefaultLanguage);
		$this->view->lobjlandscapeform->AddDrop->setValue($AddDrop);
		
		
		//nak dapatkan
		$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape);
		$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape);
		
		if($this->_getParam('update')!= 'true') {
			$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
			$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
		}
		$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape,$sessionID);
		$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
		
		
		
		//echo "<pre>";
		//print_r($this->view->landscapesubresult);die();
		//asd($this->view->programreqresult,false);
		//asd($this->view->landscapesubresult,false);

		for($i=1;$i<=$lintsemestercount;$i++) {
			$this->lobjlandscapeform->SemNameList->addMultiOption($i,$i);
			$this->lobjlandscapeform->IdSemester->addMultiOption($i,$i);
		}



		// GET THE COURSE TYPE dropdown registered values on basis of landscape and program ID
		$getProgReqdData = $this->lobjprogramrequirement->getlandscapecoursetype($IdProgram,$idlandscape);
		$this->view->resultCourseType = $getProgReqdData;
		//asd($getProgReqdData,false);
		$this->view->updateValue = '';
		// GET THE REGISTERED COURSE TYPE
		if($this->_getParam('update')!='') {
			$getcoursetypeDataResult = $this->lobjprogramrequirement->getCourseTypeDeleted($IdProgram,$idlandscape);
			$this->view->resultDropDownCourseType = $getcoursetypeDataResult;
			$this->view->updateValue = '1';
		}

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			//			echo "<pre>";
			//			print_r($formData);die;
			//
			//			asd();
			//
			//
			//-------------------
			//asd($formData);


			//if(@$formData['Scheme'])$formData['Scheme']=0;
			//-------------------
			if ($this->lobjlandscapeform->isValid($formData)) {
				$larrUpdatelandscape = $this->lobjlandscape->fnUpdateMainLandscape($formData['IdLandscape'],$formData['IdStartSemester'],$formData['ProgramDescription'],$formData['AddDrop'],$formData['landscapeDefaultLanguage'],$formData['Scheme']);

				$idlandscpae = $formData['IdLandscape'];
				$fetchtempprogramrequrimesntsdetails = $this->lobjlandscape->fnGetTempProgramrequrimentsDetailslevel($formData['IdLandscape'],$sessionID);
				$this->lobjlandscape->fnDeleteFromProgramRequirements($idlandscpae);
				
				foreach($fetchtempprogramrequrimesntsdetails as $fetchtempprogramrequrimesntsdetailslevel)
					$this->lobjlandscape->fninsertToProgramRequirements($fetchtempprogramrequrimesntsdetailslevel,$idlandscpae);
				$IdProgramReq = $this->lobjprogramrequirement->fnaddProgramrequirementlevel($formData,$idlandscpae);

				$templandsubject=$this->lobjlandscape->fnGetTempLandscapeSubject($formData['IdLandscape'],$sessionID);
				$deleteLandscapeSubject=$this->lobjlandscape->deleteLandscapeSubjectLevel($idlandscpae);
				
				foreach($templandsubject as $templandsubjectlevel)
					$this->lobjlandscape->fninsertToLandScapeSubjectLevel($templandsubjectlevel,$idlandscpae);
				$this->lobjlandscapesubject->fnaddLandscapesubjectLevel($formData,$idlandscpae);

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
						'level' => $priority,
						'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
						'time' => date ( 'Y-m-d H:i:s' ),
						'message' => 'Semester Landscape Edit Id=' . $idlandscape,
						'Description' =>  Zend_Log::DEBUG,
						'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log

				//log file
				$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
				$message = " ".$controller."		"."Semester Landscape Edit Id=$idlandscape"."		".$this->getRequest ()->getServer ( 'REMOTE_ADDR' )."			"."Success"."			".$auth->getIdentity()->iduser."\r";
				$this->_gobjlogger->log($message,5);

				$IdProgram = $formData['IdProgram'];
				$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);
			}
		}
	}

	public function viewlandscapelistAction (){
		$this->view->lobjlandscapeform = $this->lobjlandscapeform; //send the lobjuniversityForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
		$this->view->IdProgram = $IdProgram = $this->_getParam('id', 0);
		$idlandscape = $this->_getParam('idlandscape', 0);
		$LandscapeType = $this->_getParam('LandscapeType');
		$this->view->LandscapeType = $LandscapeType;
		if($LandscapeType ==43 ) {

			$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);
			foreach($programlist as $programlistdetail){
				$this->view->program = $programlistdetail['ProgramName'];
			}
			$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
			$auth = Zend_Auth::getInstance();
			$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
			$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);
			$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
			$this->view->programcredit = $larrprogramresult['TotalCreditHours'];
			$larrLandscapeType=$this->lobjlandscape->fnLandscapeTypeList($IdProgram);
			$this->view->DefinitionDesc = $larrLandscapeType['DefinitionDesc'];
			$this->view->SemesterMasterName = $larrLandscapeType['SemesterMainName'].'-'.$larrLandscapeType['year'];
			$this->view->SemsterCount = $larrLandscapeType['SemsterCount'];
			if( $larrLandscapeType['Active']== 1){
				$this->view->Active = "Active";
			}else {
				$this->view->Active = "In-Active";
			}

			$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
			foreach($larrdefmsresultset as $larrdefmsresult) {
				$this->lobjlandscapeform->SubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			}
			$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Subject Type');
			foreach($larrdefmsresultset as $larrdefmsresult) {
				$this->lobjlandscapeform->LandscapeSubjectType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			}
			$larrsemesterresult = $this->lobjsemester->fngetlandscapeprogSemesterDetails();
			foreach($larrsemesterresult as $larrsemesterresult) {
				$this->lobjlandscapeform->IdSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
			}
			$larrsubjectresult = $this->lobjsubjectmaster->fnGetSubjectList();
			$this->lobjlandscapeform->IdSubject->addMultiOptions($larrsubjectresult);

			$this->view->idlandscape = $this->_getParam('idlandscape', 0);
			$larrmainprogramreqresult = $this->lobjlandscape->fnGetProgramRequrimentEditDetails($this->view->idlandscape); // Get the Item Sales details
			$larrmainlandscapesubresult = $this->lobjlandscape->fnGetLandscapeEditDetails($this->view->idlandscape); // Get the Item Sales details

			if($this->_getParam('update')!= 'true') {
				$sessionID = Zend_Session::getId();
				$this->lobjlandscape->fnDeleteTempProgramreqBysession($sessionID);
				$this->lobjlandscape->fnDeleteTemplandsacpesubBysession($sessionID);
			}

			if($this->view->idlandscape) {
				$result = $this->lobjlandscape->fetchAll('IdLandscape = '.$this->view->idlandscape);
				$result = $result->toArray();
				foreach($result as $landscaperesult) {
					$this->view->LandscapeType = $landscaperesult['LandscapeType'];
				}
				$this->view->lobjlandscapeform->populate($landscaperesult);
				if($this->_getParam('update')!= 'true') {
					$temparrayreult=$this->lobjlandscape->fninserttempprogramentryrequriments($larrmainprogramreqresult,$this->view->idlandscape);
					$templanscapesubarrayreult=$this->lobjlandscape->fninserttemplanscapesubject($larrmainlandscapesubresult,$this->view->idlandscape);
				}
			}
			$this->view->programreqresult = $this->lobjlandscape->fnGetTemproryProgReqDetails($this->view->idlandscape);
			$this->view->landscapesubresult = $this->lobjlandscape->fnGetTemproryLandscapesubResult($this->view->idlandscape,$landscaperesult['LandscapeType']);
		}else{//Block Based

			$programlist = $this->lobjprogram->fetchAll('IdProgram ='.$IdProgram);

			foreach($programlist as $programlistdetail){
				$this->view->program = $programlistdetail['ProgramName'];
			}
			$this->view->lobjlandscapeform->UpdDate->setValue ( $ldtsystemDate );
			$auth = Zend_Auth::getInstance();
			$this->view->lobjlandscapeform->UpdUser->setValue( $auth->getIdentity()->iduser);
			$this->view->lobjlandscapeform->IdProgram->setValue( $IdProgram);

			$larrprogramresult = $this->lobjprogram->fnViewProgramquota($IdProgram);
			$this->view->programcredit = $larrprogramresult['TotalCreditHours'];

			$larrLandscapeType=$this->lobjlandscape->fnLandscapeTypeList($IdProgram);

			//print_r( $larrLandscapeType['SemesterMasterName'].'-'.$larrLandscapeType['year']);die();
			$this->view->DefinitionDesc = 'Block Based';
			$this->view->SemesterMasterName = $larrLandscapeType['SemesterMainName'];
			$this->view->SemsterCount = $larrLandscapeType['SemsterCount'];
			if( $larrLandscapeType['Active']== 1){
				$this->view->Active = "Active";

			}else{
				$this->view->Active = "In-Active";

			}

			//Get LandScapeBlock Details
			$larrLandScapeBlock = $this->lobjlandscape->fnGetLandScapeBlockDtls($idlandscape);
			$this->view->larrLandScapeBlock = $larrLandScapeBlock;

			//Get LandScapeBlockSubject Details
			$larrLandScapeBlockSubject = $this->lobjlandscape->fnGetLandScapeBlockSubjectDtls($idlandscape);

			$this->view->larrLandScapeBlockSubject = $larrLandScapeBlockSubject;

			//Get LandScapeBlockSemester Details
			$larrLandScapeBlockSemester = $this->lobjlandscape->fnGetLandScapeBlockSemesterDtls($idlandscape);
			$this->view->larrLandScapeBlockSemester = $larrLandScapeBlockSemester;

		}
	}









	public function deleteprogramrequrimentsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$IdTempProgReq = $this->_getParam('IdTempProgReq');

		$larrDelete = $this->lobjlandscape->fnUpdateTempProgramRequriments($IdTempProgReq);
		echo "1";
	}


	public function deletesubjectprerequisitesAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$IdTempLandscapesub = $this->_getParam('IdTempLandscapesub');

		$larrDelete = $this->lobjlandscape->fnUpdateTempLandscapesubject($IdTempLandscapesub);
		echo "1";
	}

	public function fngetsubjectcredithoursAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lintidsubject = $this->_getParam('idsubject');
		$larrresult = $this->lobjsubjectmaster->fnviewSubject($lintidsubject);
		echo $larrresult['CreditHours'];
	}

	public function getblocklistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$Blockcount = $this->_getParam('Blockcount');
		if($this->view->BlockType == 1){
		 $value = $this->view->BlockName.$this->view->BlockSeparator;
		}

		for($i=1;$i<=$Blockcount;$i++) {
			if($this->view->BlockType == 1){
				$block[] = array("name"=>"$value"."$i","key"=>"$i");
			}else
				$block[] = array("name"=>"$i","key"=>"$i");
		}
		echo Zend_Json_Encoder::encode($block);
	}

	public function semlistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$SemsterCount= $this->_getParam('SemsterCount');
		for($i=1;$i<=$SemsterCount;$i++) {
			$block[] = array("name"=>"$i","key"=>"$i");
		}
		echo Zend_Json_Encoder::encode($block);
	}

	public function deleteblockAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdLandscapetempblock = $this->_getParam('IdLandscapetempblock');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblock($IdLandscapetempblock);
		echo "1";
	}

	public function deleteblocksubAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdLandscapetempblocksubject = $this->_getParam('IdLandscapetempblocksubject');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblocksub($IdLandscapetempblocksubject);
		echo "1";
	}

	public function deleteblocksemAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdLandscapetempblocksemester = $this->_getParam('IdLandscapetempblocksemester');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblocksem($IdLandscapetempblocksemester);
		echo "1";
	}

	public function deleteblockyearAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$IdLandscapetempblockyear = $this->_getParam('IdLandscapetempblockyear');
		$larrDelete = $this->lobjlandscape->fnUpdateTempblockyear($IdLandscapetempblockyear);
		echo "1";
	}

	public function activeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idlandscape = $this->_getParam('idlandscape');
		$IdProgram = $this->_getParam('idprogram');
		$idintake = $this->_getParam('idintake');
		$idscheme = $this->_getParam('idscheme');
		$this->lobjlandscape->fnupdateLandscapeActiveaction($idlandscape,$IdProgram,$idintake,$idscheme);
		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);

	}

	public function inactiveAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idlandscape = $this->_getParam('idlandscape');
		$IdProgram = $this->_getParam('idprogram');
		$this->lobjlandscape->fnupdateLandscapeInActiveaction($idlandscape);
		$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);

	}

	/**
	 *
	 * Action to handle block landscape view
	 */
	public function viewblocklandscapeAction() {
		$idlandscape = $this->_getParam('idlandscape');
		$this->view->IdProgram = $this->_getParam('id');
		$larrresult = $this->lobjlandscape->fngetlandscapeblock($idlandscape);
		$semsters = array();
		foreach ($larrresult as $item) {
			$key = $item['semesterid'];
			if (!isset($semsters[$key])) {
				$semsters[$key][] = $item;
			} else {
				$semsters[$key][] = $item;
			}
		}


		// GET THE FACULTY NAME
		$programmodel = new GeneralSetup_Model_DbTable_Program();
		$reusltPF = $programmodel->fnfetchProgramFaculty($this->view->IdProgram);
		if(count($reusltPF)>0) {
			$this->view->faculty_name =$reusltPF[0]['CollegeName'];
		}
		// ENDS

		// GET THE INTAKE NAME
		$landscapemodel = new GeneralSetup_Model_DbTable_Landscape();
		$reusltLSIntake = $landscapemodel->fnfetchLandScapeIntake($idlandscape);
		if(count($reusltLSIntake)>0) {
			$this->view->intake_name =$reusltLSIntake[0]['IntakeId'];
		}
		// ENDS


		$this->view->larrblocklist = $semsters;
		$larrdetails = $this->lobjlandscape->fngetlandscapedetails($idlandscape);
		foreach($larrdetails as $landscapedetails) {
		}
		$this->view->larrdetails = $landscapedetails;
	}

	/**
	 *
	 * Action to handle semester landscape view
	 */
	public function viewsemesterlandscapeAction() {
		$idlandscape = $this->_getParam('idlandscape');
		$this->view->IdProgram = $this->_getParam('id');
		$larrresult = $this->lobjlandscape->fngetlandscapesemester($idlandscape);

		$semsters = array();
		foreach ($larrresult as $item) {
			$key = $item['IdSemester'];
			if (!isset($semsters[$key])) {
				$semsters[$key][] = $item;
			} else {
				$semsters[$key][] = $item;
			}
		}
		//echo "<pre>";print_r($semsters);die;
		$this->view->larrsemesterlist = $semsters;
		$larrdetails = $this->lobjlandscape->fngetlandscapedetails($idlandscape);
		//asd($larrdetails);

		// GET THE FACULTY NAME
		$programmodel = new GeneralSetup_Model_DbTable_Program();
		$reusltPF = $programmodel->fnfetchProgramFaculty($this->view->IdProgram);
		if(count($reusltPF)>0) {
			$this->view->faculty_name =$reusltPF[0]['CollegeName'];
		}
		// ENDS

		// GET THE INTAKE NAME
		$landscapemodel = new GeneralSetup_Model_DbTable_Landscape();
		$reusltLSIntake = $landscapemodel->fnfetchLandScapeIntake($idlandscape);
		if(count($reusltLSIntake)>0) {
			$this->view->intake_name =$reusltLSIntake[0]['IntakeId'];
		}
		// ENDS




		foreach($larrdetails as $landscapedetails) {
		}
		$this->view->larrdetails = $landscapedetails;
	}

	/**
	 *
	 * Enter description here ...
	 */
	public function viewlevellandscapeAction() {
		$larrfinal = array();
		$idlandscape = $this->_getParam('idlandscape');
		$this->view->IdProgram = $this->_getParam('id');
		$larrresult = $this->lobjlandscape->fngetlandscapelevel($idlandscape);
		$larrresult = $this->groupArray($larrresult, "blockname");
		foreach($larrresult as $key => $larrlevel) {
			$larrfinal[$key] = $this->groupArray($larrlevel, "semesterid");
		}
		$this->view->larrlevellist = $larrfinal;
		$larrdetails = $this->lobjlandscape->fngetlandscapedetails($idlandscape);
		foreach($larrdetails as $landscapedetails) {
		}

		// GET THE FACULTY NAME
		$programmodel = new GeneralSetup_Model_DbTable_Program();
		$reusltPF = $programmodel->fnfetchProgramFaculty($this->view->IdProgram);
		if(count($reusltPF)>0) {
			$this->view->faculty_name =$reusltPF[0]['CollegeName'];
		}
		// ENDS

		// GET THE INTAKE NAME
		$landscapemodel = new GeneralSetup_Model_DbTable_Landscape();
		$reusltLSIntake = $landscapemodel->fnfetchLandScapeIntake($idlandscape);
		if(count($reusltLSIntake)>0) {
			$this->view->intake_name =$reusltLSIntake[0]['IntakeId'];
		}
		// ENDS

		$this->view->larrdetails = $landscapedetails;
	}

	public function fngetsubjectpreAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idsubject = $this->_getParam('idsubject');
		$subprereq = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$result = $subprereq->fnViewSubjectPrerequisitesDetails($idsubject);
		echo Zend_Json_Encoder::encode($result);

	}

	public function getyearlistAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$count = $this->_getParam('YearCount');
		for($i=1;$i<=intval($count);$i++) {
			$result[] = array('key'=>$i,'name'=>$i);
		}
		echo Zend_Json_Encoder::encode($result);
	}

	public function getcoursetypeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$coursetype = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$result = $coursetype->fngetcoursetypeDetails();
		echo Zend_Json_Encoder::encode($result);
	}

	public function groupArray($input,$field) {
		$return = array();
		foreach ($input as $item) {
			$key = $item[$field];
			if (!isset($return[$key])) {
				$return[$key][] = $item;
			} else {
				$return[$key][] = $item;
			}
		}
		return $return;
	}



	/**
	 * Function to copy semester based landscape
	 * @author: vipul
	 */

	public function copyfirstlandscapeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$formData = $this->getRequest()->getParams();

		$id_program = $formData['id_program'];
		$copyfrom_intakes = $formData['copyfrom_intakes'];
		$copyfrom_schemes = $formData['copyfrom_schemes'];
		$copy_intake = $formData['copy_intake'];
		$copy_active = $formData['copy_active'];
		$copy_schemes = $formData['copy_schemes'];

		$landscpaemodel =  new GeneralSetup_Model_DbTable_Landscape();
		$landscapesubjectmodel =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$landscapeprogramreqmntmodel =  new GeneralSetup_Model_DbTable_Programrequirement();

		// GET THE landscape id from program id, intake id, scheme_id
		$getlID = $landscpaemodel->getlandscapeID($id_program,$copyfrom_intakes,$copyfrom_schemes,$landscapetype='43');
		//asd($getlID,false);
		if(count($getlID)>0) {
			$firstInsert = $secondInsert = $thirdInsert = array();
			foreach ($getlID as $values) {

				$lid = $values['IdLandscape'];
				$pid = $id_program;
				$firstInsert['IdProgram'] = $pid;
				$firstInsert['LandscapeType'] = $values['LandscapeType'];
				$firstInsert['IdStartSemester'] = $copy_intake;
				$firstInsert['SemsterCount'] = $values['SemsterCount'];
				$firstInsert['Blockcount'] = $values['Blockcount'];
				$firstInsert['YearCount'] = $values['YearCount'];
				$firstInsert['Active'] = $copy_active;
				$firstInsert['UpdDate'] = date('Y-m-d H:i:s');
				$firstInsert['UpdUser'] = $values['UpdUser'];
				$firstInsert['TotalCreditHours'] = $values['TotalCreditHours'];
				$firstInsert['ProgramDescription'] = $values['ProgramDescription'];
				$firstInsert['Scheme'] = $copy_schemes;
				$new_lid = $landscpaemodel->fnaddLandscape($firstInsert);

				// GET THE landscape programrequirement from program id, landscape id
				$getProgReqdData = $landscapeprogramreqmntmodel->getlandscapeprogramreqmnt($id_program,$lid);
				if(count($getProgReqdData)>0) {
					foreach ($getProgReqdData as $valuesreqdmore) {
						$secondInsert['IdProgram'] = $pid;
						$secondInsert['IdLandscape'] = $new_lid;
						$secondInsert['SubjectType'] = $valuesreqdmore['SubjectType'];
						$secondInsert['CreditHours'] = $valuesreqdmore['CreditHours'];
						$secondInsert['UpdDate'] = date('Y-m-d H:i:s');
						$secondInsert['UpdUser'] = $valuesreqdmore['UpdUser'];
						$insertProgReqtData = $landscapeprogramreqmntmodel->fninsertLandscapeProgramReqd($secondInsert);
					}
				}


				// GET THE landscape subjects from program id, landscape id
				$getsubjData = $landscapesubjectmodel->getlandscapesubjects($id_program,$lid);
				if(count($getsubjData)>0) {
					foreach ($getsubjData as $valuesmore) {

						$thirdInsert['IdProgram'] = $pid;
						$thirdInsert['IdLandscape'] = $new_lid;
						$thirdInsert['IdSubject'] = $valuesmore['IdSubject'];
						$thirdInsert['SubjectType'] = $valuesmore['SubjectType'];
						$thirdInsert['IdSemester'] = $valuesmore['IdSemester'];
						$thirdInsert['CreditHours'] = $valuesmore['CreditHours'];
						$thirdInsert['IDProgramMajoring'] = $valuesmore['IDProgramMajoring'];
						$thirdInsert['Compulsory'] = $valuesmore['Compulsory'];
						$thirdInsert['Active'] = $valuesmore['Active'];
						$thirdInsert['UpdDate'] = date('Y-m-d H:i:s');
						$thirdInsert['UpdUser'] = $valuesmore['UpdUser'];

						$insertSubjectData = $landscapesubjectmodel->fninsertLandscapesubjectLevel($thirdInsert);

					}
				}
			}
			echo "done";
			die;
		} else {
			echo "nodata"; die;
		}

	}

	/**
	 * Function to copy level based landscape
	 * @author: vipul
	 */

	public function copysecondlandscapeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$formData = $this->getRequest()->getParams();

		$id_program = $formData['id_program'];
		$copyfrom_intakes = $formData['copyfrom_intakes'];
		$copyfrom_schemes = $formData['copyfrom_schemes'];
		$copy_intake = $formData['copy_intake'];
		$copy_active = $formData['copy_active'];
		$copy_schemes = $formData['copy_schemes'];

		$landscpaemodel =  new GeneralSetup_Model_DbTable_Landscape();
		$landscapesubjectmodel =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$landscapeprogramreqmntmodel =  new GeneralSetup_Model_DbTable_Programrequirement();
		$landscapeblock =  new GeneralSetup_Model_DbTable_Landscapeblock();
		$landscapeblocksemester =  new GeneralSetup_Model_DbTable_Landscapeblock();


		// GET THE landscape id from program id, intake id, scheme_id
		$getlID = $landscpaemodel->getlandscapeID($id_program,$copyfrom_intakes,$copyfrom_schemes,$landscapetype='42');

		if(count($getlID)>0) {
			$firstInsert = $secondInsert = $thirdInsert = $fourthInsert = $fifthInsert = array();
			foreach ($getlID as $values) {

				$lid = $values['IdLandscape'];
				$pid = $id_program;
				$firstInsert['IdProgram'] = $pid;
				$firstInsert['LandscapeType'] = $values['LandscapeType'];
				$firstInsert['IdStartSemester'] = $copy_intake;
				$firstInsert['SemsterCount'] = $values['SemsterCount'];
				$firstInsert['Blockcount'] = $values['Blockcount'];
				$firstInsert['YearCount'] = $values['YearCount'];
				$firstInsert['Active'] = $copy_active;
				$firstInsert['UpdDate'] = date('Y-m-d H:i:s');
				$firstInsert['UpdUser'] = $values['UpdUser'];
				$firstInsert['TotalCreditHours'] = $values['TotalCreditHours'];
				$firstInsert['ProgramDescription'] = $values['ProgramDescription'];
				$firstInsert['Scheme'] = $copy_schemes;
				$new_lid = $landscpaemodel->fnaddLandscape($firstInsert);

				// GET THE landscape programrequirement from program id, landscape id
				$getProgReqdData = $landscapeprogramreqmntmodel->getlandscapeprogramreqmnt($id_program,$lid);
				if(count($getProgReqdData)>0) {
					foreach ($getProgReqdData as $valuesreqdmore) {
						$secondInsert['IdProgram'] = $pid;
						$secondInsert['IdLandscape'] = $new_lid;
						$secondInsert['SubjectType'] = $valuesreqdmore['SubjectType'];
						$secondInsert['CreditHours'] = $valuesreqdmore['CreditHours'];
						$secondInsert['UpdDate'] = date('Y-m-d H:i:s');
						$secondInsert['UpdUser'] = $valuesreqdmore['UpdUser'];
						$insertProgReqtData = $landscapeprogramreqmntmodel->fninsertLandscapeProgramReqd($secondInsert);
					}
				}


				// GET THE landscape subjects from program id, landscape id
				$getsubjData = $landscapesubjectmodel->getlandscapesubjects($id_program,$lid);
				if(count($getsubjData)>0) {
					foreach ($getsubjData as $valuesmore) {

						$thirdInsert['IdProgram'] = $pid;
						$thirdInsert['IdLandscape'] = $new_lid;
						$thirdInsert['IdSubject'] = $valuesmore['IdSubject'];
						$thirdInsert['SubjectType'] = $valuesmore['SubjectType'];
						$thirdInsert['IdSemester'] = $valuesmore['IdSemester'];
						$thirdInsert['CreditHours'] = $valuesmore['CreditHours'];
						$thirdInsert['IDProgramMajoring'] = $valuesmore['IDProgramMajoring'];
						$thirdInsert['Compulsory'] = $valuesmore['Compulsory'];
						$thirdInsert['Active'] = $valuesmore['Active'];
						$thirdInsert['UpdDate'] = date('Y-m-d H:i:s');
						$thirdInsert['UpdUser'] = $valuesmore['UpdUser'];

						$insertSubjectData = $this->lobjlandscapesubject->fninsertLandscapesubjectLevel($thirdInsert);

					}
				}


				// GET THE landscape block from landscape id
				$getblockData = $landscapeblock->getlandscapeblock($lid);
				if(count($getblockData)>0) {
					foreach ($getblockData as $valuesblock) {
						$fourthInsert['idlandscape'] = $new_lid;
						$fourthInsert['block'] = $valuesblock['block'];
						$fourthInsert['blockname'] = $valuesblock['blockname'];
						$fourthInsert['CreditHours'] = $valuesblock['CreditHours'];
						$insertblockData = $landscapeblock->insertlandscapeblock($fourthInsert);
					}
				}


				// GET THE landscape block semester from landscape id
				$getblocksemesterData = $landscapeblock->getlandscapeblocksemester($lid);
				if(count($getblocksemesterData)>0) {
					foreach ($getblocksemesterData as $valuesblocksem) {
						$fifthInsert['IdLandscape'] = $new_lid;
						$fifthInsert['blockid'] = $valuesblocksem['blockid'];
						$fifthInsert['semesterid'] = $valuesblocksem['semesterid'];
						$insertblockData = $landscapeblock->insertlandscapeblocksemester($fifthInsert);
					}
				}


			}
			echo "done";
			die;
		} else { echo "nodata"; die;
		}

	}



	/**
	 * Function to copy block based landscape
	 * @author: vipul
	 */

	public function copythirdlandscapeAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$formData = $this->getRequest()->getParams();

		$id_program = $formData['id_program'];
		$copyfrom_intakes = $formData['copyfrom_intakes'];
		$copyfrom_schemes = $formData['copyfrom_schemes'];
		$copy_intake = $formData['copy_intake'];
		$copy_active = $formData['copy_active'];
		$copy_schemes = $formData['copy_schemes'];

		$landscpaemodel =  new GeneralSetup_Model_DbTable_Landscape();
		$landscapesubjectmodel =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$landscapeprogramreqmntmodel =  new GeneralSetup_Model_DbTable_Programrequirement();
		$landscapeblock =  new GeneralSetup_Model_DbTable_Landscapeblock();

		// GET THE landscape id from program id, intake id, scheme_id
		$getlID = $landscpaemodel->getlandscapeID($id_program,$copyfrom_intakes,$copyfrom_schemes,$landscapetype='44');

		if(count($getlID)>0) {
			$firstInsert = $secondInsert = $thirdInsert = $fourthInsert = $fifthInsert = $sixthInsert = $seventhInsert = array();
			foreach ($getlID as $values) {

				$lid = $values['IdLandscape'];
				$pid = $id_program;
				$firstInsert['IdProgram'] = $pid;
				$firstInsert['LandscapeType'] = $values['LandscapeType'];
				$firstInsert['IdStartSemester'] = $copy_intake;
				$firstInsert['SemsterCount'] = $values['SemsterCount'];
				$firstInsert['Blockcount'] = $values['Blockcount'];
				$firstInsert['YearCount'] = $values['YearCount'];
				$firstInsert['Active'] = $copy_active;
				$firstInsert['UpdDate'] = date('Y-m-d H:i:s');
				$firstInsert['UpdUser'] = $values['UpdUser'];
				$firstInsert['TotalCreditHours'] = $values['TotalCreditHours'];
				$firstInsert['ProgramDescription'] = $values['ProgramDescription'];
				$firstInsert['Scheme'] = $copy_schemes;
				$new_lid = $landscpaemodel->fnaddLandscape($firstInsert);

				// GET THE landscape programrequirement from program id, landscape id
				$getProgReqdData = $landscapeprogramreqmntmodel->getlandscapeprogramreqmnt($id_program,$lid);
				if(count($getProgReqdData)>0) {
					foreach ($getProgReqdData as $valuesreqdmore) {
						$secondInsert['IdProgram'] = $pid;
						$secondInsert['IdLandscape'] = $new_lid;
						$secondInsert['SubjectType'] = $valuesreqdmore['SubjectType'];
						$secondInsert['CreditHours'] = $valuesreqdmore['CreditHours'];
						$secondInsert['UpdDate'] = date('Y-m-d H:i:s');
						$secondInsert['UpdUser'] = $valuesreqdmore['UpdUser'];
						$insertProgReqtData = $landscapeprogramreqmntmodel->fninsertLandscapeProgramReqd($secondInsert);
					}
				}


				// GET THE landscape subjects from program id, landscape id
				$getsubjData = $landscapesubjectmodel->getlandscapesubjects($id_program,$lid);
				if(count($getsubjData)>0) {
					foreach ($getsubjData as $valuesmore) {

						$thirdInsert['IdProgram'] = $pid;
						$thirdInsert['IdLandscape'] = $new_lid;
						$thirdInsert['IdSubject'] = $valuesmore['IdSubject'];
						$thirdInsert['SubjectType'] = $valuesmore['SubjectType'];
						$thirdInsert['IdSemester'] = $valuesmore['IdSemester'];
						$thirdInsert['CreditHours'] = $valuesmore['CreditHours'];
						$thirdInsert['IDProgramMajoring'] = $valuesmore['IDProgramMajoring'];
						$thirdInsert['Compulsory'] = $valuesmore['Compulsory'];
						$thirdInsert['Active'] = $valuesmore['Active'];
						$thirdInsert['UpdDate'] = date('Y-m-d H:i:s');
						$thirdInsert['UpdUser'] = $valuesmore['UpdUser'];

						$insertSubjectData = $this->lobjlandscapesubject->fninsertLandscapesubjectLevel($thirdInsert);

					}
				}


				// GET THE landscape block from landscape id
				$getblockData = $landscapeblock->getlandscapeblock($lid);
				if(count($getblockData)>0) {
					foreach ($getblockData as $valuesblock) {
						$fourthInsert['idlandscape'] = $new_lid;
						$fourthInsert['block'] = $valuesblock['block'];
						$fourthInsert['blockname'] = $valuesblock['blockname'];
						$fourthInsert['CreditHours'] = $valuesblock['CreditHours'];
						$insertblockData = $landscapeblock->insertlandscapeblock($fourthInsert);
					}
				}


				// GET THE landscape block semester from landscape id
				$getblocksemesterData = $landscapeblock->getlandscapeblocksemester($lid);
				if(count($getblocksemesterData)>0) {
					foreach ($getblocksemesterData as $valuesblocksem) {
						$fifthInsert['IdLandscape'] = $new_lid;
						$fifthInsert['blockid'] = $valuesblocksem['blockid'];
						$fifthInsert['semesterid'] = $valuesblocksem['semesterid'];
						$insertblockData = $landscapeblock->insertlandscapeblocksemester($fifthInsert);
					}
				}


				// GET THE LandscapeBlockSubject Data from landscapeID
				$getblocksubjectData = $landscapeblock->getlandscapeblocksubject($lid);
				if(count($getblocksubjectData)>0) {
					foreach ($getblocksubjectData as $valuessubject) {
						$sixthInsert['IdLandscape'] = $new_lid;
						$sixthInsert['blockid'] = $valuessubject['blockid'];
						$sixthInsert['subjectid'] = $valuessubject['subjectid'];
						$sixthInsert['coursetypeid'] = $valuessubject['coursetypeid'];
						$insertlsubjectata = $landscapeblock->fnaddlandscapeBlockSubject($sixthInsert);
					}
				}


				// GET THE blocksemesteryear Data from landscapeID
				$getblocksemyearData = $landscpaemodel->getlandscapeblocksemesteryear($lid);
				if(count($getblocksemyearData)>0) {
					foreach ($getblocksemyearData as $valuesyear) {
						$seventhInsert['IdLandscape'] = $new_lid;
						$seventhInsert['Year'] = $valuesyear['Year'];
						$seventhInsert['YearSemester'] = $valuesyear['YearSemester'];
						$insertlsemyearata = $landscpaemodel->fnaddlandscapeblocksemesteryear($seventhInsert);
					}
				}




			}
			echo "done";
			die;
		} else { echo "nodata"; die;
		}
	}

	/*
	 * Function to active landscape, only one per program, intake and scheme.
	*/
	public function makeactiveAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		//$idlandscape = $this->_getParam('idlandscape');
		$IdProgram = $this->_getParam('idprogram');
		$idintake = $this->_getParam('idintake');
		$idscheme = $this->_getParam('idscheme');
		$getResult = $this->lobjlandscape->fngetActiveLandscape($IdProgram,$idintake,$idscheme);
		if(count($getResult)>0) {
			echo 'ONEActive';
		}
		else { echo 'ZEROActive';
		} die;
		//$this->_redirect( $this->baseUrl . '/generalsetup/landscape/addlandscape/id/'.$IdProgram);

	}



}