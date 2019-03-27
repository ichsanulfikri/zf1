<?php
class GeneralSetup_LandscapeController extends Base_Base {
	
	public function indexAction(){
		
		$this->view->title = $this->view->translate("Setup Landscape");
		
		$form = new GeneralSetup_Form_SearchProgram();
		$this->view->form = $form;
		
		$programDB = new GeneralSetup_Model_DbTable_Program();
		
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			$larrresult = $programDB->getPaginateProgramDetails($formData);
			
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($larrresult));		
			$paginator->setItemCountPerPage(20);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
			
		}else{
						
			$larrresult =	$programDB->getProgramDetails();
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($larrresult));			
			$paginator->setItemCountPerPage(20);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
		}
		
		//$this->view->form = $form;
		
		$this->view->paginator = $paginator;
		
	}
	
	
	public function landscapeListAction() {
		
		$this->view->title = $this->view->translate("Landscape List");
		
		$IdProgram = $this->_getParam('id', 0);
		$this->view->programId = $IdProgram;
		
		//get landscape for this program
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();		
		
		$landscapeResult = $landscapeDB->LandscapeListByProgram($IdProgram);
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($landscapeResult));			
		$paginator->setItemCountPerPage(20);
		$paginator->setCurrentPageNumber($this->_getParam('page',1));
		$this->view->paginator = $paginator;
				
	}
	
	public function addLandscapeAction() {		
				
		$this->view->title = $this->view->translate("Create New Landscape");
		
		$auth = Zend_Auth::getInstance(); 
		
		$programId = $this->_getParam('programId', null);
		$this->view->programId = $programId;
		
		$form =  new GeneralSetup_Form_LandscapeForm(array('programId'=>$programId));
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {	
							
				//create landscape	
				
				$data["IdProgram"]	= $formData["IdProgram"];	
				$data["LandscapeType"]	= $formData["LandscapeType"];			
				$data["IdStartSemester"]	= $formData["IdStartSemester"];	
				$data["TotalCreditHours"]	= $formData["TotalCreditHours"];
				$data["SemsterCount"]	= $formData["SemsterCount"];
				if($formData["LandscapeType"]==44){
					$data["Blockcount"]	= $formData["Blockcount"];	
				}
				$data["ProgramDescription"]	= $formData["ProgramDescription"];
				$data["AddDrop"]	        = $formData["AddDrop"];		
				$data["UpdDate"]=date("Y-m-d H:i:s");
				$data["UpdUser"]=$auth->getIdentity()->id;
				
				$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
				$idLandscape = $landscapeDB->addData($data);
								
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'edit-landscape','idlandscape'=>$idLandscape,'id'=>$programId),'default',true));
		
			}
		}
		
	}
	
	public function editLandscapeAction() {
		
		$this->view->title = $this->view->translate("Edit Landscape");
		
		$auth = Zend_Auth::getInstance(); 
				
		$landscapeId= $this->_getParam('idlandscape', null);	
		$this->view->landscapeId = $landscapeId;
			
		$programId = $this->_getParam('id', null);
		$this->view->programId = $programId;		
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape = $landscape;
		
		$myform =  new GeneralSetup_Form_LandscapeForm(array('programId'=>$programId,'landscapeId'=>$landscapeId,'landscapeType'=>$landscape["LandscapeType"]));
		$myform->populate($landscape);
		$this->view->form = $myform;
		
		$formupload = new GeneralSetup_Form_Batchlandscape();
    	$this->view->formupload = $formupload;		
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($myform->isValid($formData)) {				
				
				//edit landscape
				$data["IdStartSemester"]	= $formData["IdStartSemester"];	
				$data["TotalCreditHours"]	= $formData["TotalCreditHours"];
				$data["SemsterCount"]	    = $formData["SemsterCount"];	
				$data["ProgramDescription"]	= $formData["ProgramDescription"];	
				$data["AddDrop"]	        = $formData["AddDrop"];	
				$data["modifydt"]=date("Y-m-d H:i:s");
				$data["modifyby"]=$auth->getIdentity()->id;
				
				$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
				$landscapeDB->updateData($data,$landscapeId);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'edit-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
		
			}
		}
					
	
	}
	
	public function programRequirementAction(){
		
		//$this->_helper->layout->disableLayout();
		$this->view->title = $this->view->translate("Edit Landscape");
		
		$auth = Zend_Auth::getInstance(); 
		 
		$landscapeId = $this->_getParam('idlandscape', 0);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id', 0);
		$this->view->programId = $programId;
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape = $landscape;
		
		//get program requirement info
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$programRequirement = $progReqDB->getlandscapecoursetype($programId,$landscapeId);
		$this->view->programrequirement = $programRequirement;
		
		$form = new GeneralSetup_Form_ProgramRequirement(array('programID'=>$programId));
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				
				//print_r($formData);
				
				$info["IdLandscape"]=$landscapeId;
				$info["IdProgram"]=$programId;
				$info["SubjectType"]=$formData["SubjectType"];
				$info["CreditHours"]=$formData["CreditHours"];
				$info["Compulsory"]=$formData["Compulsory"];
				$info["UpdDate"]=date("Y-m-d H:i:s");
				$info["UpdUser"]=$auth->getIdentity()->id;
				
				//ad prog requirement
				$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
				$progReqDB->addData($info);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'program-requirement','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
		
			}
		}
	}
	
	public function editProgramRequirementAction(){
		
		$this->_helper->layout->disableLayout();
		
		$auth = Zend_Auth::getInstance(); 
		 
		$landscapeId = $this->_getParam('landscapeId', 0);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('programId', 0);
		$this->view->programId = $programId;
		
		$IdProgramReq = $this->_getParam('IdProgramReq', 0);
		$this->view->IdProgramReq = $IdProgramReq;
		
		//get program requirement info
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$programRequirement = $progReqDB->getInfo($IdProgramReq);		
		
		$form = new GeneralSetup_Form_ProgramRequirement(array('IdProgramReq'=>$IdProgramReq,'programID'=>$programId));
		$form->populate($programRequirement);
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				
				//print_r($formData);				
				$info["CreditHours"]=$formData["CreditHours"];
				$info["Compulsory"]=$formData["Compulsory"];
				$info["modifydt"]=date("Y-m-d H:i:s");
				$info["modifyby"]=$auth->getIdentity()->id;
				
				
				//ad prog requirement
				$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
				$progReqDB->updateData($info,$IdProgramReq);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'program-requirement','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
		
			}
		}
	}
	
	
	public function deleteProgramRequirementAction(){
		
		$landscapeId = $this->_getParam('landscapeId', 0);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('programId', 0);
		$this->view->programId = $programId;
		
		$IdProgramReq = $this->_getParam('IdProgramReq', 0);
		$this->view->IdProgramReq = $IdProgramReq;
		
		
		//ad prog requirement
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$progReqDB->deleteData($IdProgramReq);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'program-requirement','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
	}
	
	
	public function courseLandscapeAction(){
		
		$this->view->title = $this->view->translate("Course Landscape");		
		
		$auth = Zend_Auth::getInstance(); 
		
		$landscapeId = $this->_getParam('idlandscape', 0);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id', 0);
		$this->view->programId = $programId;
		
		//get program requirement info
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$programRequirement = $progReqDB->getlandscapecoursetype($programId,$landscapeId);
		$this->view->programrequirement = $programRequirement;
		
		//get majoring for this program
		$progMajDb = new GeneralSetup_Model_DbTable_ProgramMajoring();	
		$majoring = $progMajDb->getData($programId);
		$this->view->majoring = $majoring;
		
		//get Landscape Info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
						
		//get Compulsory Common Course
		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$compulsory_course = $landscapeSubjectDb->getCommonCourse($programId,$landscapeId,1);
		$this->view->compulsory_course = $compulsory_course;		
		
		//get Not Compulsory Common Course
		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$elective_course = $landscapeSubjectDb->getCommonCourse($programId,$landscapeId,2);
		$this->view->elective_course = $elective_course;
		
		
	}
		
	
	public function addCourseAction(){
		
		$this->_helper->layout->disableLayout();
		
		$auth = Zend_Auth::getInstance(); 
		
		$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idMajoring = $this->_getParam('idMajoring',null);
		$this->view->idMajoring = $idMajoring;
		
		$type = $this->_getParam('type',null);
		$this->view->type = $type;
						
		//get Landscape Info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
						
		$form = new GeneralSetup_Form_LandscapeCourse(array('landscapeId'=>$landscapeId,'programId'=>$programId,'SemsterCount'=>$landscape["SemsterCount"],'idMajoring'=>$idMajoring,'type'=>$type));
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				
				
					if($formData["IDProgramMajoring"]==''){
						$data["IDProgramMajoring"]=0;	
					}else{
						$data["IDProgramMajoring"]=$formData["IDProgramMajoring"];
					}
				
					$data["IdProgram"]=$formData["IdProgram"];
					$data["IdLandscape"]=$formData["IdLandscape"];
					$data["IdSubject"]=$formData["IdSubject"];
					$data["SubjectType"]=$formData["SubjectType"];
					$data["IdSemester"]=$formData["idSemester"];
					$data["CreditHours"]=$formData["CreditHours"];
					$data["IdProgramReq"]=$formData["IdProgramReq"];
					$data["UpdDate"]=date("Y-m-d H:i:s");
					$data["UpdUser"]=$auth->getIdentity()->id;				
				
					$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
					$landscapeSubjectDb->addData($data);
				
				    $this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'course-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
			}
			
		}
		
	}
	
	public function deleteCourseAction(){
		
		$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idLandscapeSub = $this->_getParam('idLandscapeSub',null);
		$this->view->idLandscapeSub = $idLandscapeSub;		
		
		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$landscapeSubjectDb->deleteData($idLandscapeSub);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'course-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
	}
	
	public function ajaxGetSubjectAction(){
		
		$SubjectType = $this->_getParam('SubjectType', null);
    	$IdSubject = $this->_getParam('IdSubject', 0);
    	$IdLandscape = $this->_getParam('idlandscape', null);
    	$IdProgram = $this->_getParam('id', null);
    	
        $this->_helper->layout->disableLayout();
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	
	  	//get minimum credit hours
	  	$select = $db->select()
	                 ->from(array('pr'=>'tbl_programrequirement'),array('CreditHours','Compulsory'))
	                 ->where('pr.IdLandscape = ?',$IdLandscape)
	                 ->where('pr.IdProgram  = ?',$IdProgram)
	                 ->where('pr.SubjectType = ?',$SubjectType);      
       $row = $db->fetchRow($select);
       
       //get course credit hours
        $selectm = $db->select()
	                 ->from(array('s'=>'tbl_subjectmaster'),array('CreditHours'))
	                 ->where('s.IdSubject = ?',$IdSubject);       
        $rowx = $db->fetchRow($selectm);
        
        
       if($row["Compulsory"]==1){//Subject Type Compulsory (then culculate addded course = minimumcredithours)
       		        
		        //get total added       		
				$selectl = $db->select()
				 				 ->from(array("ls"=>"tbl_landscapesubject"),array('sum(CreditHours) as sum'))		 				 				
				 				 ->where("ls.IdProgram = ?",$IdProgram)
				 				 ->where("ls.IdLandscape = ?",$IdLandscape)
				 				 ->where("ls.SubjectType = ?",$SubjectType);
				$row2 = $db->fetchRow($selectl);
			 
		        
		       //echo 'Minimum credit hour is:'.$row["CreditHours"];
		       //echo 'Course credit hour is :'.$rowx["CreditHours"];
		       //echo 'Total Added Course credit hour is :'.$row2["sum"];

				
				//available balance credit hours
				$available = $row["CreditHours"] - $row2["sum"];
				
				
				if($rowx["CreditHours"] <= $available){
					$status = array('CreditHours'=>$rowx["CreditHours"],'status'=>"1",'available'=>$available);
				}else{
					$status = array('CreditHours'=>$rowx["CreditHours"],'status'=>"0",'available'=>$available);
				}
		
       }else{ //Not Compulsory user may add how many courses they want
        	//true
        	$status = array('CreditHours'=>$rowx["CreditHours"],"status"=>"1");
       }
       
	  	
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($status);
		
		echo $json;
		exit();
    }
    
	public function ajaxGetProgramRequirementAction(){
		
    	$IdProgramReq = $this->_getParam('IdProgramReq', null);
    	$IdLandscape = $this->_getParam('idlandscape', null);
    	$IdProgram = $this->_getParam('idprogram', null);
    	
        $this->_helper->layout->disableLayout();
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	
	  	//get minimum credit hours
	  	$select = $db->select()
	                 ->from(array('pr'=>'tbl_programrequirement'),array('CreditHours','Compulsory','SubjectType'))
	                 ->where('pr.IdProgramReq = ?',$IdProgramReq);
	                // ->where('pr.IdLandscape = ?',$IdLandscape)
	                // ->where('pr.IdProgram  = ?',$IdProgram)
	                // ->where('pr.SubjectType = ?',$SubjectType);      
        $row = $db->fetchRow($select);
        
        $SubjectType = $row["SubjectType"];
        
        if($row["Compulsory"]==1){//Subject Compulsory (then culculate addded course = minimumcredithours)

        	//get total added  courses     		
			$select = $db->select()
			 				 ->from(array("ls"=>"tbl_landscapesubject"),array('sum(CreditHours) as sum'))		 				 				
			 				 ->where("ls.IdProgram = ?",$IdProgram)
			 				 ->where("ls.IdLandscape = ?",$IdLandscape)
			 				 ->where("ls.SubjectType = ?",$SubjectType);
			$row2 = $db->fetchRow($select);
			
			//available balance credit hours
			$available = $row["CreditHours"] - $row2["sum"];
			
	        if($row2["sum"] < $row["CreditHours"]){
				$status = array('status'=>"1",'SubjectType'=>$SubjectType,'available'=>$available);
			}else{
				$status = array('status'=>"0",'SubjectType'=>$SubjectType,'available'=>$available);
			}
			
			
        }else{ //Not Compulsory user may add how many course they want
        	//true
        	$status = array("status"=>"1",'SubjectType'=>$SubjectType);
        }
        
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($status);
		
		echo $json;
		exit();
    }
	
 	public function manageBlockLandscapeAction(){
    	
 		$auth = Zend_Auth::getInstance(); 
 		
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape = $landscape;
				
		//count how many blocks added
		$blockDb = new GeneralSetup_Model_DbTable_Landscapeblock();
		$blocks = $blockDb->getBlockByLandscape($landscapeId);
		$this->view->total_block = count($blocks);
		
		
    }
    
    public function addBlockAction(){
    	
    	$this->_helper->layout->disableLayout();
    	
    	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		
   		$form =  new GeneralSetup_Form_AddBlockForm(array('programId'=>$programId,'landscapeId'=>$landscapeId,'SemsterCount'=>$landscape["SemsterCount"],'BlockCount'=>$landscape["Blockcount"]));
		$this->view->form =$form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				
				//ge no of block  that particular semester
				$blockDb = new GeneralSetup_Model_DbTable_Landscapeblock();
				$blocks = $blockDb->getBlockBySemester($landscapeId,$formData["semesterid"]);
								
				$block_data["idlandscape"]=$formData["idlandscape"];
				$block_data["blockname"]=$formData["blockname"];
				//$block_data["block"]    =count($blocks)+1;
				$block_data["block"]    =$formData["block"];
				$block_data["semester"] =$formData["semesterid"];
				$block_data["createddt"]=date("Y-m-d H:i:s");
				$block_data["createdby"]=$auth->getIdentity()->id;	
					
				//print_r($block_data);
				
				$block_id = $blockDb->addData($block_data);
				
				$blocksem_data["idLandscape"]=$formData["idlandscape"];
				$blocksem_data["blockid"]=$block_id;
				$blocksem_data["semesterid"]=$formData["semesterid"];
				$blocksem_data["createddt"]=date("Y-m-d H:i:s");
				$blocksem_data["createdby"]=$auth->getIdentity()->id;	
				
				//print_r($blocksem_data);
				
				$semblockDb = new GeneralSetup_Model_DbTable_LandscapeBlockSemester();
				$semblockDb->addData($blocksem_data);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'manage-block-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
			}
		}
		
    }
    
 	public function editBlockAction(){
    	
    	$this->_helper->layout->disableLayout();
    	
    	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$blockId = $this->_getParam('idblock',null);
		$this->view->blockId = $blockId;
		
		$blockDb = new GeneralSetup_Model_DbTable_Landscapeblock();
		$blocks = $blockDb->getData($blockId);
	
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape  = $landscape;
			
   		$form =  new GeneralSetup_Form_AddBlockForm(array('programId'=>$programId,'landscapeId'=>$landscapeId,'SemsterCount'=>$landscape["SemsterCount"],'idBlock'=>$blockId,'BlockCount'=>$landscape["Blockcount"]));
		$form->populate($blocks);
   		$this->view->form =$form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {				
				
				$block_data["blockname"]=$formData["blockname"];
				$block_data["createddt"]=date("Y-m-d H:i:s");
				$block_data["createdby"]=$auth->getIdentity()->id;	
				
				$blockDb = new GeneralSetup_Model_DbTable_Landscapeblock();
				$blockDb->updateData($block_data,$formData["idblock"]);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'manage-block-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
			}
		}
		
    }
    
    public function addBlockCourseAction(){
    	
    	$this->_helper->layout->disableLayout();
    	
    	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$blockId = $this->_getParam('idblock',null);
		$this->view->blockId = $blockId;
		
		$type = $this->_getParam('type',null);
		$this->view->type = $type;
		
		$semester = $this->_getParam('semester',null);
		$this->view->semester = $semester;
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape  = $landscape;
		
		$form = new GeneralSetup_Form_LandscapeCourse(array('landscapeId'=>$landscapeId,'programId'=>$programId,'SemsterCount'=>$landscape["SemsterCount"],'idBlock'=>$blockId,'type'=>$type));
		$formdata = array('idSemester'=>$semester);
		$form->populate($formdata);
		$this->view->form = $form;
					
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {								
					
					$data["IdLandscape"]=$formData["IdLandscape"];
					$data["blockid"]=$formData["blockid"];
					$data["subjectid"]=$formData["IdSubject"];
					$data["coursetypeid"]=$formData["SubjectType"];
					$data["IdProgramReq"]=$formData["IdProgramReq"];
					$data["createddt"]=date("Y-m-d H:i:s");
					$data["createdby"]=$auth->getIdentity()->id;				
				
					$landscapeBlockSubjectDb =  new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
					$landscapeBlockSubjectDb->addData($data);
				
				    $this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'manage-block-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
			}
		}
    }
    
    
	public function deleteBlockCourseAction(){
		
		$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idLandscapeSub = $this->_getParam('idLandscapeSub',null);
		$this->view->idLandscapeSub = $idLandscapeSub;		
		
		$landscapeBlockSubjectDb =  new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
		$landscapeBlockSubjectDb->deleteData($idLandscapeSub);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'manage-block-landscape','idlandscape'=>$landscapeId,'id'=>$programId),'default',true));
	}
	
	public function prerequisiteAction(){
		
		$this->view->title = $this->view->translate("Setup Prerequisite");
		
		$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;		
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$landscapeSubjectDb = new GeneralSetup_Model_DbTable_Landscapesubject();
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			$result = $landscapeSubjectDb->getPaginateLandscapeCourse($landscapeId,$formData);			
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($result));		
			$paginator->setItemCountPerPage(10);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
			
		}else{
						
			$result =	$landscapeSubjectDb->getLandscapeCourse($landscapeId);
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($result));			
			$paginator->setItemCountPerPage(10);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
		}
		
		$this->view->paginator = $paginator;
		
	}
	
	
	 public function addPrerequisiteAction(){
    	
	 	$this->view->title = $this->view->translate("Add Prerequisite");
	 	
    	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idSubject= $this->_getParam('idSubject',null);
		$this->view->idSubject = $idSubject;
		
		$idLandscapeSub= $this->_getParam('idLandscapeSub',null);
		$this->view->idLandscapeSub = $idLandscapeSub;
		
		$idProgramMajoring= $this->_getParam('idProgramMajoring',null);
		$this->view->idProgramMajoring = $idProgramMajoring;
		
		//get subject landscape info
		$landscapeSubjectDb = new GeneralSetup_Model_DbTable_Landscapesubject();
		$subject = $landscapeSubjectDb->getProgramMajoring($idLandscapeSub);	
		$this->view->subject = $subject;
		
				
		//get list preprequisite
		$prerequisiteDb = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$subject_prerequisite = $prerequisiteDb->getCoursePrerequisite($landscapeId,$idSubject,$idLandscapeSub);
		$this->view->subject_prerequisite = $subject_prerequisite;
		
		$form = new GeneralSetup_Form_Prerequisite(array('landscapeId'=>$landscapeId,'idSubject'=>$idSubject,'idLandscapeSub'=>$idLandscapeSub,'idProgramMajoring'=>$idProgramMajoring));		
		$this->view->form = $form;
		
		 if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {								
					
				
					$data["IdLandscape"]=$formData["IdLandscape"];
					$data["IdSubject"]=$formData["IdSubject"];
					$data["IdLandscapeSub"]=$formData["IdLandscapeSub"];
					$data["IdRequiredSubject"]=$formData["IdRequiredSubject"];
					$data["PrerequisiteType"]=$formData["PrerequisiteType"];
					$data["PrerequisiteGrade"]=$formData["PrerequisiteGrade"];
					$data["createddt"]=date("Y-m-d H:i:s");
					$data["createdby"]=$auth->getIdentity()->id;								
										
					$prerequisiteDb->addData($data);				
				 
					$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'add-prerequisite','idlandscape'=>$landscapeId,'id'=>$programId,'idSubject'=>$idSubject,'idLandscapeSub'=>$idLandscapeSub,'idProgramMajoring'=>$idProgramMajoring),'default',true));
			}
		}
		
	 }
	 
	 
	 public function deletePrerequisiteAction(){
	 	
	
	 	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idSubject= $this->_getParam('idSubject',null);
		$this->view->idSubject = $idSubject;
		
		$IdSubjectPrerequisites= $this->_getParam('IdSubjectPrerequisites',null);
		$this->view->IdSubjectPrerequisites = $IdSubjectPrerequisites;
		
		$idLandscapeSub= $this->_getParam('idLandscapeSub',null);
		$this->view->idLandscapeSub = $idLandscapeSub;
		
		$idProgramMajoring= $this->_getParam('idProgramMajoring',null);
		$this->view->idProgramMajoring = $idProgramMajoring;
		
		$prerequisiteDb = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$prerequisiteDb->deleteData($IdSubjectPrerequisites);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'add-prerequisite','idlandscape'=>$landscapeId,'id'=>$programId,'idSubject'=>$idSubject,'idLandscapeSub'=>$idLandscapeSub,'idProgramMajoring'=>$idProgramMajoring),'default',true));
	 }
	 
	 
	public function changeStatusAction(){
	 	
	
	 	$auth = Zend_Auth::getInstance();     	
	 	    	
		 if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
						
			//get landscape for this program
			$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();	
			
			$data["Active"]=$formData["Active"];
			
			$landscapeDB->updateData($data,$formData["idLandscape"]);			
		 }
	
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'landscape-list','id'=>$formData["idProgram"]),'default',true));
	 }
	 
	 
	 public function setDefaultAction(){
	 	
	 	$auth = Zend_Auth::getInstance(); 
    	
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		//get landscape for this program
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();	
		$landscape = $landscapeDB->getData($landscapeId);
		
		//update default utk program ni, semester ni landscape type ni default
		$landscapeDB->updateDefault(array("Default"=>0),$programId,$landscape["LandscapeType"],$landscape["IdStartSemester"]);
		
		$landscapeDB->updateData(array("Default"=>1),$landscapeId);	
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'landscape-list','id'=>$programId),'default',true));
				
	 }
	 
	 public function viewLandscapeAction(){
	 	
	 	$this->view->title = $this->view->translate("View Landscape");
	 	
	 	$landscapeId = $this->_getParam('idlandscape', 0);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id', 0);
		$this->view->programId = $programId;
		
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape = $landscape;
		
		//get program requirement info
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$programRequirement = $progReqDB->getlandscapecoursetype($programId,$landscapeId);
		$this->view->programrequirement = $programRequirement;
		
		//get majoring for this program
		$progMajDb = new GeneralSetup_Model_DbTable_ProgramMajoring();	
		$majoring = $progMajDb->getData($programId);
		$this->view->majoring = $majoring;
		
		//get Landscape Info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
						
		//get Compulsory Common Course
		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$compulsory_course = $landscapeSubjectDb->getCommonCourse($programId,$landscapeId,1);
		$this->view->compulsory_course = $compulsory_course;		
		
		//get Not Compulsory Common Course
		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
		$elective_course = $landscapeSubjectDb->getCommonCourse($programId,$landscapeId,2);
		$this->view->elective_course = $elective_course;
	 }
	 
	 
	 public function viewPrerequisiteAction(){
	 	
	 	$this->_helper->layout->disableLayout();
	 	
	 	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
		
		$idSubject= $this->_getParam('idSubject',null);
		$this->view->idSubject = $idSubject;
		
		$idLandscapeSub= $this->_getParam('idLandscapeSub',null);
		$this->view->idLandscapeSub = $idLandscapeSub;
		
		$idProgramMajoring= $this->_getParam('idProgramMajoring',null);
		$this->view->idProgramMajoring = $idProgramMajoring;
		
		//get subject landscape info
		$landscapeSubjectDb = new GeneralSetup_Model_DbTable_Landscapesubject();
		$subject = $landscapeSubjectDb->getProgramMajoring($idLandscapeSub);	
		$this->view->subject = $subject;
		
				
		//get list preprequisite
		$prerequisiteDb = new GeneralSetup_Model_DbTable_Subjectprerequisites();
		$subject_prerequisite = $prerequisiteDb->getCoursePrerequisite($landscapeId,$idSubject,$idLandscapeSub);
		$this->view->subject_prerequisite = $subject_prerequisite;
	 }
	 
	 
	public function viewBlockLandscapeAction(){
    	
		$this->view->title = $this->view->translate("View Landscape");
			
 		$auth = Zend_Auth::getInstance(); 
 		
    	$landscapeId = $this->_getParam('idlandscape', null);
		$this->view->landscapeId = $landscapeId;
		
		$programId = $this->_getParam('id',null);
		$this->view->programId = $programId;
				
		//get landscape info
		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape();
		$landscape = $landscapeDB->getLandscapeDetails($landscapeId);
		$this->view->landscape = $landscape;
		
		//get program requirement info
		$progReqDB = new GeneralSetup_Model_DbTable_Programrequirement();
		$programRequirement = $progReqDB->getlandscapecoursetype($programId,$landscapeId);
		$this->view->programrequirement = $programRequirement;
				
		
		
		
    }
    
    public function batchLandscapeAction(){
    	
    	$form = new GeneralSetup_Form_Batchlandscape();
    	$auth = Zend_Auth::getInstance(); 
    	
    	if ($this->_request->isPost()) { 
    		
    		$progMajDb = new GeneralSetup_Model_DbTable_Programrequirement();
    		$progdb = new GeneralSetup_Model_DbTable_Program();
    		$subjectDB = new GeneralSetup_Model_DbTable_Subjectmaster();
    		$progdb = new GeneralSetup_Model_DbTable_Program();
    		$landscapeDB = new GeneralSetup_Model_DbTable_Landscape;
    		$lblockDB = new GeneralSetup_Model_DbTable_Landscapeblock();
    		$semblockDb = new GeneralSetup_Model_DbTable_LandscapeBlockSemester();
    		$landscapeBlockSubjectDb =  new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
    		$landscapeSubjectDb =  new GeneralSetup_Model_DbTable_Landscapesubject();
    		$prerequisiteDb = new GeneralSetup_Model_DbTable_Subjectprerequisites();
    		
    		$landscape = $landscapeDB->getData($this->_getParam('idlandscape', null));
			/*    		
			 * $subjectDB->delMultipleCode();
    		exit;*/
    		
    		$formData = $this->_request->getPost();
            $uploadedData = $form->getValues();
            $fullFilePath = $form->file->getFileName();
             //echo $fullFilePath;
			$file = fopen($fullFilePath, "r") or exit("Unable to open file!");

			$process = 1;
			
			$compulsory = array("yes"=>1,"no"=>2);
			$datap["UpdDate"]=date("Y-m-d H:i:s");
			$datap["UpdUser"]=$auth->getIdentity()->id;		
			$datap["IdLandscape"]=$this->_getParam('idlandscape', null);
			$datap["IdProgram"]=$this->_getParam('id', null);
		
			while(!feof($file)){
					$idsubject ="";
					$line_data = fgets($file); 
					$data = explode("\t",$line_data);					
					
					if($data[0]=="xxxxx"){
						$process = 2;
					}
					if($process==1){						
						
						//Masukkan compenent dalam program requirement
						$componentname = $data[0];
						$component = $progMajDb->getDefinitionID($componentname);
						$datap["SubjectType"] = $component["idDefinition"];
						$datap["CreditHours"] = $data[1];
						$datap["Compulsory"] = $compulsory[strtolower($data[2])];
						
						$idProgReq=$progMajDb->addData($datap);
					}
					
					if($process==2 && $data[0]!="xxxxx"){
						
					
							if($landscape["LandscapeType"]=="44"){ //Block Landscape
							$redirectURL=$this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'manage-block-landscape','id'=>$this->_getParam('id', null),'idlandscape'=>$this->_getParam('idlandscape', null)),'default',true);

							//Check Subject master kalau takdak lagi create new subject
							if($data[0]!=""){							
								if(isset($data[2])){
									$subjectexist = $subjectDB->fngetsubjcode($data[2]);
									if(count($subjectexist)==0){ 
										$program = $progdb->fngetProgramData($this->_getParam('id', null));
										
										if($data[4]=="") $data[4]=$data[3];
										echo $data[2].":Create<br><pre>";
										$subject["IdFaculty"]=$program["IdCollege"];
										$subject["SubjectName"]=$data[4];
										$subject["ShortName"]=$data[2];
										$subject["subjectMainDefaultLanguage"]=$data[3];
										$subject["BahasaIndonesia"]=$data[3];
										$subject["SubCode"]=$data[2];
										$subject["Active"]=1;
										$subject["CreditHours"]=$data[5];
										$subject["CourseType"]=1;
										$subject["ReligiousSubject"]=0;
										$subject["IdReligion"]=0;
										$subject["UpdDate"]=date("Y-m-d H:i:s");
										$subject["UpdUser"]=$auth->getIdentity()->id;
										
										$idsubject=$subjectDB->fnaddSubject($subject,1,0);

									}else{								
										$idsubject=$subjectexist[0]["IdSubject"];
									}
								}									
								//check block exist
								$block=$lblockDB->getBlockByLandscapeBlockNo($this->_getParam('idlandscape', null),$data[1]);
								
								if(!is_array($block)){
								
									$block_data["idlandscape"]=$this->_getParam('idlandscape', null);
									$block_data["blockname"]="Block ".$data[1];
									$block_data["block"]    = $data[1];
									$block_data["semester"]=$data[0];
									$block_data["createddt"]=date("Y-m-d H:i:s");
									$block_data["createdby"]=$auth->getIdentity()->id;
									$idblock=$lblockDB->addData($block_data);

									$blocksem_data["idLandscape"]=$block_data["idlandscape"];
									$blocksem_data["blockid"]=$idblock;
									$blocksem_data["semesterid"]=$data[0];
									$blocksem_data["createddt"]=date("Y-m-d H:i:s");
									$blocksem_data["createdby"]=$auth->getIdentity()->id;
									
									$semblockDb->addData($blocksem_data);									
									
								}else{
									$idblock=$block["idblock"];
								}
								
								$componentx = $progMajDb->getDefinitionID($data[6]);
								$idcom=(int)$componentx["idDefinition"];
								$idprogreq=$progMajDb->getIdProgReq($idcom,$this->_getParam('idlandscape', null));
								
								$sdata["IdLandscape"]=$this->_getParam('idlandscape', null);
								$sdata["blockid"]=$idblock;
								$sdata["subjectid"]=$idsubject;
								$sdata["coursetypeid"]=$idcom;
								$sdata["IdProgramReq"]=$idprogreq;
								$sdata["createddt"]=date("Y-m-d H:i:s");
								$sdata["createdby"]=$auth->getIdentity()->id;	
								
								$landscapeBlockSubjectDb->addData($sdata);

							}	
								
							}elseif($landscape["LandscapeType"]=="43"){ //Semester landscape
								$redirectURL=$this->view->url(array('module'=>'generalsetup','controller'=>'landscape', 'action'=>'course-landscape','id'=>$this->_getParam('id', null),'idlandscape'=>$this->_getParam('idlandscape', null)),'default',true);
								
								if(isset($data[1])){
									$componentx = $progMajDb->getDefinitionID($data[5]);
									$subjectexist = $subjectDB->fngetsubjcode($data[1]);
									
									
										if(count($subjectexist)==0){ 
											$program = $progdb->fngetProgramData($this->_getParam('id', null));

											if($data[3]=="") $data[3]=$data[2];

											$subject["IdFaculty"]=$program["IdCollege"];
											$subject["SubjectName"]=$data[3];
											$subject["ShortName"]=$data[1];
											$subject["subjectMainDefaultLanguage"]=$data[2];
											$subject["BahasaIndonesia"]=$data[2];
											$subject["SubCode"]=$data[1];
											$subject["Active"]=1;
											$subject["CreditHours"]=$data[4];
											$subject["CourseType"]=1;
											$subject["ReligiousSubject"]=0;
											$subject["IdReligion"]=0;
											$subject["UpdDate"]=date("Y-m-d H:i:s");
											$subject["UpdUser"]=$auth->getIdentity()->id;
											
											if(trim($data[1])!=""){												
												$idsubject=$subjectDB->fnaddSubject($subject,1,0);
											}

										}else{								
											$idsubject=$subjectexist[0]["IdSubject"];
										}
									}//end Subject checking		

									if($idsubject!=""){
										//Add subject dlm landscape
										$idcom=(int)$componentx["idDefinition"];
										$idprogreq=$progMajDb->getIdProgReq($idcom,$this->_getParam('idlandscape', null));									

										$data[7]=preg_replace('/[\n\r\t]/',' ',$data[7]);
										if($data[7]!="" || strlen($data[7])!=0){
											$major = $progdb->getMajorByCode($data[7]);
											$idmajor=$major["IDProgramMajoring"];
											if($idmajor==""){
												$idmajor=0;
											}
										}else{
											$idmajor=0;
										}
										
									
										
										$sdata["IdProgram"]=$this->_getParam('id', null);
										$sdata["IdLandscape"]=$this->_getParam('idlandscape', null);
										$sdata["IdSubject"]=$idsubject;
										$sdata["SubjectType"]=$idcom;
										$sdata["IdSemester"]=$data[0];
										$sdata["CreditHours"]=$data[4];
										$sdata["IdProgramMajoring"]=$idmajor;
										$sdata["IdProgramReq"]=$idprogreq;
										$sdata["UpdDate"]=date("Y-m-d H:i:s");
										$sdata["UpdUser"]=$auth->getIdentity()->id;

										
										$idlandscapesub=$landscapeSubjectDb->addData($sdata);	
										if(array_key_exists(10,$data)){
											if($data[10]!=""){
												unset($preq);
												$preq=explode(",",$data[10]);
												$preqtype =explode(",",$data[11]); 
												$grade =explode(",",$data[12]); 
												foreach($preq as $key=>$pre){
													$cid="";
													$pgrade="";
													$ptype="";
													if(array_key_exists($key,$preqtype)){
														$ptype=trim(preg_replace("/[^ \w]+/", "", $preqtype[$key]));
													}else{
														$ptype=1;
													}
													if($ptype==""){
														$ptype=1;
													}
													
													if($ptype==0){
														
														$pgrade=trim(preg_replace("/[^ \w]+/", "", $grade[$key]));
													}							
													
													$cid = trim(preg_replace("/[^ \w]+/", "", $pre));
													$psubject=$subjectDB->fngetsubjcode($cid);
													$pidsubject= $psubject[0]["IdSubject"];
													
													
													//utk checking kalo xada course
													if($pidsubject){
														$prdata["IdLandscape"]=$this->_getParam('idlandscape', null);
														$prdata["IdSubject"]=$idsubject;
														$prdata["IdLandscapeSub"]=$idlandscapesub;
														$prdata["IdRequiredSubject"]=$pidsubject;
														$prdata["PrerequisiteType"]=$ptype;
														$prdata["PrerequisiteGrade"]=$pgrade;
														$prdata["createddt"]=date("Y-m-d H:i:s");
														$prdata["createdby"]=$auth->getIdentity()->id;
														$prerequisiteDb->addData($prdata);
													}else{
														echo $cid;
													    echo '- tak ada prerequisite <br>';
													}
												}
											}
											
										}
										
										
									}														
							  }//end Semester Base
						}//end process 2
				}//end while
			
				
			$this->_redirect($redirectURL);
            
    		}
    	
   	 }    
    
}
?>