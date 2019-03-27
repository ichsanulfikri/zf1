<?php
class GeneralSetup_ProgramController extends Base_Base {
	private $lobjprogram;
	private $lobjprogramForm;
	private $lobjcoursemaster;
	private $lobjdeftype;
	private $lobjSubjectprerequisites;
	private $lobjprogramquota;

	public function init() {
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
//	public function testAction(){
//		$this->view->title="Add New Profram";
//		$this->view->lobjform = $this->lobjform;
//		echo ("hello");
//	}

	public function indexAction() {
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
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
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
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
		//------------------
		$lobjSalutationList = $this->lobjprogram->fnGetSalutationList();
		$this->lobjprogramForm->programSalutation->addMultiOptions($lobjSalutationList);


		$larrCollegeList = $this->lobjSubjectprerequisites->fnGetCollegeList();
		$this->lobjprogramForm->IdCollege->addMultiOptions($larrCollegeList);


		//-------------------
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

		$this->view->iddef=$iddef;
		//print_r($iddef);
		//die();
		$this->view->hiddencount=$learningmodeid;
		$this->view->lobjprogramForm->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjprogramForm->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
//			echo "<pre>";
//			print_r($formData);die;
			if(@count($formData['LearningMode']) == '0') {
	    		echo '<script language="javascript">alert("Select Atleast One Learning Mode");</script>';
	    	} else {
				$larrformdata = $formData;
				unset ( $formData ['Save'] );
				unset($formData['Clear']);
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


	   			unset($formData['EnglishDescription']);
	   			unset($formData['BahasaDescription']);
	   			unset($formData['Insert']);
	   			unset($formData['Erase']);
	   			unset($formData['EnglishDescriptiongrid']);
	   			unset($formData['BahasaDescriptiongrid']);


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
	   			$this->lobjprogram->fninsertMajoring($larrformdata,$result);
				$accredition = $this->lobjprogram->fnaddAccreditiondetails($larrformdata,$result,$this->view->AccDtlCount);
				$mohedetails = $this->lobjprogram->fnaddMohedetails($larrformdata,$result,$this->view->MoheDtlCount);
				$learningmodes = $this->lobjprogram->fnaddLearningMode($larrformdata,$result);
				$this->lobjchiefofprogram->fninsertChiefofProgramList($larrformdata,$result);
				$this->_redirect( $this->baseUrl . '/generalsetup/program/index');
	    	}
        }
    }

	public function editprogramAction(){
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
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
		$this->view->lobjuniversityForm->ToDate->setValue ($ldtsystemDate );
		$this->lobjuniversityForm->IdStaff->addMultiOptions($this->lobjstaffmodel->fngetStaffMasterListforDD());
		$this->lobjprogramForm->AccreditionType->addMultiOptions($this->lobjprogram->fnGetAccreditationList());
		//------------------
		$lobjSalutationList = $this->lobjprogram->fnGetSalutationList();
		$this->lobjprogramForm->programSalutation->addMultiOptions($lobjSalutationList);

		$larrCollegeList = $this->lobjSubjectprerequisites->fnGetCollegeList();
		$this->lobjprogramForm->IdCollege->addMultiOptions($larrCollegeList);
		//-------------------
		$IdProgram = $this->_getParam('id', 0);
		$this->view->LintIdProgram=$IdProgram;
		$larrresult = $this->lobjchiefofprogram->fngetChiefofProgramList($IdProgram);
		$larrhistory = $this->lobjprogram->fngethistory($IdProgram);
		$this->view->history = $larrhistory;
		$this->view->lobjuniversityForm->FromDate->setValue ($larrresult['FromDate'] );
		$this->view->lobjuniversityForm->IdStaff->setValue ($larrresult['IdStaff'] );

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

			$deptfromcoll = $this->lobjprogram->fnGetDepartmentFromColl($programresult['IdCollege']);
			$this->lobjprogramForm->IdDepartment->addMultiOptions($deptfromcoll);

			//$this->lobjprogramForm->SelectColgDept->setValue($programresult['SelectColgDept']);

			//if($programresult['SelectColgDept'] == 1){
			//	$larrDetails = $this->lobjSubjectprerequisites->fnGetDepartmentList();
			//}else {
			//	$larrDetails = $this->lobjSubjectprerequisites->fnGetCollegeList();
			//}
			//$this->lobjprogramForm->IdCollege->addMultiOptions($larrDetails);
			//$this->lobjprogramForm->IdCollege->setValue($programresult['IdCollege']);
//			echo "<pre>";
//			print_r($programresult);die;
			$this->lobjprogramForm->ActiveDB->setValue($programresult['Active']);
			$this->lobjprogramForm->populate($programresult);


			if($this->_getParam('update')!= 'true') {
		  		$temparrayreult=$this->lobjprogram->fninserttempAccredictitionrequriments($accredintialdtls,$programresult['IdProgram']);
		  	}
		}

		$this->view->tempaccredictiondetails = $larraccredicationresult= $this->lobjprogram->fnGetTempaccredicationDetails($programresult['IdProgram']);

//		$this->view->mohedtls=$mohedtls = $this->lobjprogram->fnviewMoheDetails($IdProgram);
//		$this->lobjprogramForm->MoheDate->setValue($mohedtls['MoheDate']);
//		$this->lobjprogramForm->MoheReferences->setValue($mohedtls['MoheReferences']);


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

		$this->view->programmajoring=$this->lobjprogram->fnviewProgramMajoring($IdProgram);
		
		$programScheme = new GeneralSetup_Model_DbTable_Programscheme();
		$this->view->programscheme=$programScheme->fngetprogramsscheme($IdProgram);
				
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		
    		
    		
    		$history = $formData;
	    	if ($this->lobjprogramForm->isValid($formData)) {
	    		
	    		
	    		if(@count($formData['LearningMode']) == '0') {
	    			echo '<script language="javascript">alert("Select Atleast One Learning Mode");</script>';
	    			echo "error learning mode";
	    			exit;
	    		} else {


		   			$lintIdProgram = $formData ['IdProgram'];
		   			
		   			$this->lobjprogram->fnDeleteProgramScheme($formData,$lintIdProgram);//function to delete from  database
		   			$this->lobjprogram->fninsertProgramScheme($formData,$lintIdProgram);
		   			

			    	$this->lobjprogram->fnDeleteProgramMajoring($formData);//function to delete from  database
			    	$this->lobjprogram->fninsertMajoring($formData,$lintIdProgram);

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



//					$this->lobjprogram->fnUpdateMoheDetails($formData,$lintIdProgram,$this->view->MoheDtlCount);
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

		   			unset($formData['EnglishDescription']);
		   			unset($formData['BahasaDescription']);
		   			unset($formData['Insert']);
		   			unset($formData['Erase']);
		   			unset($formData['Remarks']);
		   			unset($formData['ActiveDB']);
		   			unset($formData['EnglishDescriptiongrid']);
		   			unset($formData['BahasaDescriptiongrid']);
		   			unset($formData['IdProgramMajoring']);
		   			unset($formData['IdScheme']);
		   			unset($formData['IdScheme_add']);
		   			unset($formData['delete_scheme']);
		   		
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
					$this->lobjprogram->fnupdateProgram($formData,$lintIdProgram);//update program
					if($history['Remarks']!= NULL){
					$this->lobjprogram->fnupdateHistory($history,$lintIdProgram);//update history
					}
					$this->_redirect( $this->baseUrl . '/generalsetup/program/editprogram/id/'.$lintIdProgram);
	    		}
			}else{
				echo "invalid form";
				exit;
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
	
	
	public function ajaxGetMajoringAction(){
    	
    	$idProgram = $this->_getParam('idProgram', 0);
    	$idProgramMajoring = $this->_getParam('idProgramMajoring', 0);
    	 
    	$this->_helper->layout->disableLayout();
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$programMajoringDB = new GeneralSetup_Model_DbTable_ProgramMajoring();
	  	$row = $programMajoringDB->getInfo($idProgramMajoring);
	  
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    public function saveEditMajoringAction(){
    	
    	$auth = Zend_Auth::getInstance();
    	
    	if ($this->_request->isPost() ) {
    		
			$formData = $this->_request->getPost ();
			
			$programMajoringDB = new GeneralSetup_Model_DbTable_ProgramMajoring();
			
			$data["IdMajor"]=$formData["EditIdMajor"];
			$data["EnglishDescription"]=$formData["EditEnglishDescription"];
			$data["BahasaDescription"]=$formData["EditBahasaDescription"];
			$data["modifyby"]=$auth->getIdentity()->iduser;
			$data["modifydt"]=date ( 'Y-m-d H:i:s');
			
			$programMajoringDB->updateData($data,$formData["idProgramMajoring"]);
			
			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'program', 'action'=>'editprogram','id'=>$formData["idProgram"]),'default',true));
    	
    	}
    	
    }
    
    public function editProgramGpaAction()
    {
        
        $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
        $program_id = $this->_getParam('id',0);
        $this->view->program_id = $program_id;
        
        $intake_id = $this->_getParam('intake',0);
        $this->view->intake_id = $intake_id;
        
        if ($program_id != 0)
        {
            $program = $this->lobjprogram->fetchRow('IdProgram = '.$program_id);
            $this->view->program = $program;
        
            $Intake  = new GeneralSetup_Model_DbTable_Intake();
            $intakes = $Intake->fngetallIntakelist();
            
            $this->view->intakes = $intakes;
            
            if($intake_id != 0)
            {
                $GpaDetail  = new GeneralSetup_Model_DbTable_Programlimithead();
                $gpaHead    = $GpaDetail->fnGetHeadProgramIntake($program_id,$intake_id);
                
                if ($this->getRequest()->isPost()) 
                {
                    $formData = $this->getRequest()->getPost();
                    $count = count($formData['lower']);
                    
                    if($count > 0)
                    {
                        if(!isset($gpaHead['clid']))
                        {
                            $auth = Zend_Auth::getInstance();
                            ;
                            $gpa_head = array(
                                'progid'   => $program_id,
                                'intakeid' => $intake_id,
                                'createdby' => $auth->getIdentity()->iduser
                            );
                            
                            $GpaDetail->fnAddHead($gpa_head);
                            $gpaHead    = $GpaDetail->fnGetHeadProgramIntake($program_id,$intake_id);
                        }
                        
                        $GpaDetail->fnDeleteDetail($gpaHead['clid']);
                        
                        for($i=0;$i<$count;$i++)
                        {
                            $data = array(
                                'clid' => $gpaHead['clid'],
                                'rstart' => $formData['lower'][$i],
                                'rend' => $formData['upper'][$i],
                                'chlimit' => $formData['grade'][$i]
                            );
                        
                            if(($formData['lower'][$i] != '')&&($formData['upper'][$i] != '')&&($formData['grade'][$i] != ''))
                            {
                                $GpaDetail->fnAddDetail($data);
                            }
                        }
                    }
                
                }
                
                if(isset($gpaHead['clid']))
                {
                    $gpaDetails = $GpaDetail->fnGetDetailProgramIntake($gpaHead['clid']);
            
                    $this->view->gpaDetails = $gpaDetails;
                }
                else
                {
                    $this->view->gpaDetails = array();
                }
            }
        }
        
        //$ProgramLimit = new GeneralSetup_Model_DbTable_Programlimithead();
        
    }
    
    

}