<?php
class GeneralSetup_ProgramController extends Base_Base {
	private $lobjprogram;
	private $lobjprogramForm;
	private $lobjcoursemaster;
	private $lobjdeftype;
	private $lobjprogramquota;
	private $lobjSubjectprerequisites;
	private $_gobjlog;
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate); 	    
	}
	
	public function fnsetObj(){
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjprogramForm = new GeneralSetup_Form_Program (); 
	  	$this->lobjcoursemaster = new GeneralSetup_Model_DbTable_Coursemaster();
	  	$this->lobjdeftype = new App_Model_Definitiontype();
	  	$this->lobjSubjectprerequisites = new GeneralSetup_Model_DbTable_Subjectprerequisites();
	  	$this->lobjprogramquota = new GeneralSetup_Model_DbTable_Programquota();
	  	$this->lobjStaffForm = new GeneralSetup_Form_Staffmaster();
	  	$this->lobjuniversityForm = new GeneralSetup_Form_University (); //intialize user lobjuniversityForm
	  	$this->lobjstaffmodel = new GeneralSetup_Model_DbTable_Staffmaster();
	  	$this->lobjchiefofprogram = new GeneralSetup_Model_DbTable_Chiefofprogram();
		$this->registry = Zend_Registry::getInstance();		
		$this->locale = Zend_Registry::get('Zend_Locale');  			
	}
	
	public function indexAction() {
		$sessionID = Zend_Session::getId();
        $this->lobjprogram->fnDeleteTempAccredictionDetailsBysession($sessionID);
    	$this->view->title="Program Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$larrresult = $this->lobjprogram->fngetProgramDetails (); //get user details
		
		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->programpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionsis->programpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->programpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjform->field5->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		$sessionID = Zend_Session::getId();
        $this->lobjprogram->fnDeleteTempprogramquotaDetailsBysession($sessionID);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjprogram->fnSearchProgram ( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				@$this->gobjsessionsis->programpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/program');
		}		
	}
	
	public function newprogramAction() { //title
    	$this->view->title="Add New Profram";
		$this->view->lobjprogramForm = $this->lobjprogramForm;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		//$lobjcourse = $this->lobjcoursemaster->fnGetCourseList();
		//$this->lobjprogramForm->IdCourseMaster->addMultiOptions($lobjcourse);
		
		$this->view->lobjuniversityForm = $this->lobjuniversityForm; //send the lobjuniversityForm object to the view
		$this->view->lobjstaffmasterForm = $this->lobjStaffForm;
		
		if($this->locale == 'ar_YE') 
		{
			$this->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );		
		$this->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
		$this->lobjprogramForm->AccreditionType->addMultiOptions($this->lobjprogram->fnGetAccreditationList());
		
		//-----------
		$lobjSalutationList = $this->lobjprogram->fnGetSalutationList();
		$this->lobjprogramForm->programSalutation->addMultiOptions($lobjSalutationList);
		//----------
		
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjprogramForm->Award->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Learning Mode');
		$learningmodeid=0;
		
		foreach($larrdefmsresultset as $larrdefmsresult) {$learningmodeid++;
			$this->lobjprogramForm->LearningMode->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			$iddef[]=$larrdefmsresult['idDefinition'];
			
		}
		
		
		
		$larrplacementtestresultset = $this->lobjdeftype->fnGetDefinationMs('Placement Test Type');
	   $this->lobjprogramForm->PlacementTestType->addMultiOptions($larrplacementtestresultset);
	  //$larrplacementtestresultset = $this->lobjprogram->fnGetMajoringList();
	  //$this->lobjprogramForm->EnglishDescription->addMultiOptions($larrplacementtestresultset);
		
		$this->view->iddef=$iddef;
		$this->view->hiddencount=$learningmodeid;
		$this->view->lobjprogramForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjprogramForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$countofLearningmode = count($formData['EnglishDescriptiongrid']);
			
			//echo "<pre>";
			//print_r($formData);die();
				
			if(@count($formData['LearningMode']) == '0') {
	    		echo '<script language="javascript">alert("Select Atleast One Learning Mode");</script>';
	    	} else {
				$larrformdata = $formData;
				unset ( $formData ['Save'] );
				unset($formData['Clear']);
				//unset ( $formData ['EnglishDescription'] );
				//unset($formData['BahasaDescription']);
				unset ( $larrformdata ['EnglishDescription'] );
				unset($larrformdata['BahasaDescription']);
				unset($formData['Add']);
				unset ( $formData ['Back'] );
	   			unset($formData['IdStaff']);
	   			unset($formData['FromDate']);
	   			unset($formData['ToDate']);
	   			unset($formData['LearningMode']);
	   			unset($formData['AccreditionType']);	
	   			unset($formData['AccreditionTypegrid']); 
	   			
	   			unset($formData['AccredictionReferences']);	
	   			unset($formData['AccreditionRferencegrid']); 
	   			unset($formData['AccredictionDate']);	
	   			unset($formData['AccreditionDategrid']); 
	   			
	   			unset($formData['MoheDate']); 
	   			unset($formData['MoheReferences']); 
	   			
	   			for($i=1;$i<=$this->view->AccDtlCount;$i++){
	   				unset($formData['AccDtlgrid'.$i]);
	   			}
	   			
	    		for($i=1;$i<=$this->view->AccDtlCount;$i++){
	   				unset($formData['AccDtl'.$i]);
	   			}
			   	for($i=1;$i<=$this->view->MoheDtlCount;$i++){
	   				unset($formData['MoheDtl'.$i]);
	   			}
                
	   			$result = $this->lobjprogram->fnaddProgram($formData);
                $this->lobjprogram->fninsertMajoring($formData,$result);				
				$accredition = $this->lobjprogram->fnaddAccreditiondetails($larrformdata,$result,$this->view->AccDtlCount);
				$mohedetails = $this->lobjprogram->fnaddMohedetails($larrformdata,$result,$this->view->MoheDtlCount);
				$learningmodes = $this->lobjprogram->fnaddLearningMode($larrformdata,$result);
				$this->lobjchiefofprogram->fninsertChiefofProgramList($larrformdata,$result);
								
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Program Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/program/index');
	    	}
        }     
    }
    
	public function editprogramAction(){
    	$this->view->title="Edit Program";  //title
		$this->view->lobjprogramForm = $this->lobjprogramForm; //send the lobjuniversityForm object to the view
		
		$this->view->lobjuniversityForm = $this->lobjuniversityForm; //send the lobjuniversityForm object to the view
		$this->view->lobjstaffmasterForm = $this->lobjStaffForm;
		
	 	if($this->_getParam('update')!= 'true') {
			$sessionID = Zend_Session::getId();
        	$this->lobjprogram->fnDeleteTempAccredictionDetailsBysession($sessionID);
 		}
		
		if($this->locale == 'ar_YE') 
		{
			$this->lobjuniversityForm->FromDate->setAttrib('datePackage',"dojox.date.islamic");
			$this->lobjuniversityForm->ToDate->setAttrib('datePackage',"dojox.date.islamic");
		}		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$result=$this->lobjprogram->fnviewprogrammajoring(38);
		$this->view->resultTempsubjectequivalent=$result;
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );			
		$this->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());	
		$this->lobjprogramForm->AccreditionType->addMultiOptions($this->lobjprogram->fnGetAccreditationList());
		
		$IdProgram = $this->_getParam('id', 0);
		$this->view->LintIdProgram=$IdProgram;
		$larrresult = $this->lobjchiefofprogram->fngetChiefofProgramList($IdProgram);
		
		$this->view->lobjuniversityForm->FromDate->setValue ($larrresult['FromDate'] );
		$this->view->lobjuniversityForm->IdStaff->setValue ($larrresult['IdStaff'] );
		
		//-----------
		$lobjSalutationList = $this->lobjprogram->fnGetSalutationList();
		$this->lobjprogramForm->programSalutation->addMultiOptions($lobjSalutationList);
		//----------
		
		//$lobjcourse = $this->lobjcoursemaster->fnGetCourseList();
		//$this->lobjprogramForm->IdCourseMaster->addMultiOptions($lobjcourse);
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjprogramForm->Award->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Learning Mode');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjprogramForm->LearningMode->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
    	
		$larrplacementtestresultset = $this->lobjdeftype->fnGetDefinationMs('Placement Test Type');
	   $this->lobjprogramForm->PlacementTestType->addMultiOptions($larrplacementtestresultset);
	   
		$this->view->accredentialdtls=$accredintialdtls = $this->lobjprogram->fnviewAccredentialDetails($IdProgram);
		
		if($IdProgram) {
	    	$result = $this->lobjprogram->fetchAll('IdProgram = '.$IdProgram);
			
	    	$result = $result->toArray();
			
			foreach($result as $programresult) {
			}
			$this->lobjprogramForm->SelectColgDept->setValue($programresult['SelectColgDept']);	
			
			if($programresult['SelectColgDept'] == 1){
				$larrDetails = $this->lobjSubjectprerequisites->fnGetDepartmentList();
			}else {
				$larrDetails = $this->lobjSubjectprerequisites->fnGetCollegeList();
			}
			$this->lobjprogramForm->IdCollege->addMultiOptions($larrDetails);
			$this->lobjprogramForm->IdCollege->setValue($programresult['IdCollege']);
			
			$this->lobjprogramForm->populate($programresult);
					
			if($this->_getParam('update')!= 'true') {
		  		$temparrayreult=$this->lobjprogram->fninserttempAccredictitionrequriments($accredintialdtls,$programresult['IdProgram']);
		  	}
		}	  	
		$this->view->tempaccredictiondetails = $larraccredicationresult= $this->lobjprogram->fnGetTempaccredicationDetails($programresult['IdProgram']);
		
		$this->view->mohedtls=$mohedtls = $this->lobjprogram->fnviewMoheDetails($IdProgram);
		
		$this->lobjprogramForm->MoheDate->setValue($mohedtls['MoheDate']);
		//$this->view->mohedtls=$mohedtls1 = $this->lobjprogram->fnGetMajoringList($IdProgram);
		//$this->lobjprogramForm->EnglishDescription->setValue($mohedtls1);
		$this->lobjprogramForm->MoheReferences->setValue($mohedtls['MoheReferences']);
				
		$this->view->learningmodetls=$learningdtls = $this->lobjprogram->fnviewLearningModeDetails($IdProgram);
		$learningModeDtls=array();
		foreach($learningdtls as $learningdtlss) {
			$learningModeDtls[] = $learningdtlss['IdLearningMode'];
		}	
		$this->view->lobjprogramForm->LearningMode->setValue($learningModeDtls);
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjprogramForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjprogramForm->UpdUser->setValue( $auth->getIdentity()->iduser);
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->lobjprogramForm->isValid($formData)) {
			
	    		if(@count($formData['LearningMode']) == '0') {
	    			echo '<script language="javascript">alert("Select Atleast One Learning Mode")</script>';
	    		} else {
		   			$lintIdProgram = $formData ['IdProgram'];
					$this->lobjchiefofprogram->fnupdateChiefofProgramList($formData,$lintIdProgram);
					$this->lobjchiefofprogram->fninsertChiefofProgramList($formData,$lintIdProgram);
										
					$accredition = $this->lobjprogram->fnaddAccreditiondetails($formData,$lintIdProgram,$this->view->AccDtlCount);
														
					$sessionID = Zend_Session::getId();
					$fetchtempprogramreqdetails = $this->lobjprogram->fnGetAccordictionTemDetails($lintIdProgram,$sessionID);
					foreach($fetchtempprogramreqdetails as $fetchtempaccrdiction) {
						if($fetchtempaccrdiction['deleteFlag'] == '0') {
							$this->lobjprogram->fnDeleteMainAccridctionDetails($fetchtempaccrdiction['idExists']);
						} 
					}
					
					$this->lobjprogram->fnDeleteTempAccrdictiononSubmitDetails($lintIdProgram,$sessionID);
										
					$this->lobjprogram->fnUpdateMoheDetails($formData,$lintIdProgram,$this->view->MoheDtlCount);
					$this->lobjprogram->fnUpdateProgramMajoring($formData,$lintIdProgram);
					$this->lobjprogram->fnDeleteLearningMode($lintIdProgram);
					$this->lobjprogram->fnaddLearningMode($formData,$lintIdProgram);
					
		   			unset($formData['IdStaff']);
		   			unset($formData['FromDate']);
		   			unset($formData['ToDate']);
		   			
		    		unset($formData['LearningMode']);
		   			unset($formData['AccreditionType']);
	   				unset($formData['AccredictionReferences']);	
	   				unset($formData['AccreditionRferencegrid']); 
	   				unset($formData['AccredictionDate']);	
	   				unset($formData['AccreditionDategrid']); 		   			

	    			unset($formData['AccreditionTypegrid']); 
	    			unset($formData['MoheDate']); 
	   				unset($formData['MoheReferences']); 
	   				for($i=1;$i<=$this->view->AccDtlCount;$i++){
	   					unset($formData['AccDtlgrid'.$i]);
	   				}
		   			for($i=1;$i<=$this->view->AccDtlCount;$i++){
		   				unset($formData['AccDtl'.$i]);
		   			}
		   			
				   	for($i=1;$i<=$this->view->MoheDtlCount;$i++){
		   				unset($formData['MoheDtl'.$i]);
		   			}
					$this->lobjprogram->fnupdateProgram($formData,$lintIdProgram);//update university
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Program Edit Id=' . $IdProgram,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
					$this->_redirect( $this->baseUrl . '/generalsetup/program/index');
	    		}
			}
    	}
    }
    
	public function deleteprogramquotadetailsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$IdTemp = $this->_getParam('IdTemp');
	
		$larrUOM = $this->lobjprogram->fnUpdateTempprogramquotadetails($IdTemp);	
		echo "1";
	}
	
	public function getawardcodeAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$idaward = $this->_getParam('idaward');
	    $larrresultaward = $this->lobjprogram->fnGetAwardCode($idaward);
	    echo $larrresultaward['DefinitionCode'];	
	}
	
	public function deleteaccridictionAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
						
		//Get Po details Id
		$lintidtemp = $this->_getParam('idtemp');
	
		$larrDelete = $this->lobjprogram->fnUpdateTempAccridictionDetails($lintidtemp);	
		echo "1";
	}
	
	public function getcolgdeptidAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$lobjCommonModel = new App_Model_Common();
		$lintColgDeptid = $this->_getParam('ColgDeptid');
		if($lintColgDeptid == 1){
			$larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($this->lobjSubjectprerequisites->fnGetDepartmentList());
		} else {
			
			$larrDetails = $lobjCommonModel->fnResetArrayFromValuesToNames($this->lobjSubjectprerequisites->fnGetCollegeList());	
		}
		echo Zend_Json_Encoder::encode($larrDetails);
		
	}
}