<?php
class GeneralSetup_CourseGroupController extends Base_Base { 

	
	public function indexAction(){
	
		$this->view->title = "Course Groupping : Subject List";
		
		$form = new GeneralSetup_Form_SearchCourse();
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				
				$form->populate($formData);
				
				$progDB= new GeneralSetup_Model_DbTable_Program();
				$landscapeDB= new GeneralSetup_Model_DbTable_Landscape();
				$landsubDB= new GeneralSetup_Model_DbTable_Landscapesubject();
				$landsubBlkDB= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
				
				
				//Subject list base on Program Landscape from the selected faculty 
				$programs = $progDB->fngetProgramDetails($formData["IdCollege"]);
				$i=0;
				$j=0;
				$allblocklandscape=null;
				$allsemlandscape=null;
				foreach ($programs as $key => $program){
					$activeLandscape=$landscapeDB->getAllActiveLandscape($program["IdProgram"]);
					foreach($activeLandscape as $actl){
						if($actl["LandscapeType"]==43){
							$allsemlandscape[$i] = $actl["IdLandscape"];						
							$i++;
						}elseif($actl["LandscapeType"]==44){
							$allblocklandscape[$j] = $actl["IdLandscape"];
							$j++;
						}
					}
				}
				
				if(is_array($allsemlandscape))
					$subjectsem=$landsubDB->getMultiLandscapeCourse($allsemlandscape);
				if(is_array($allblocklandscape))
					$subjectblock=$landsubBlkDB->getMultiLandscapeCourse($allblocklandscape);
				
				if(is_array($allsemlandscape) && is_array($allblocklandscape)){
					$subjects=array_merge( $subjectsem , $subjectblock );
				}else{
					if(is_array($allsemlandscape) && !is_array($allblocklandscape)){
						$subjects=$subjectsem;
					}
					elseif(!is_array($allsemlandscape) && is_array($allblocklandscape)){
						$subjects=$subjectblock;
					}		
				}				
				
				
				
				//end subject list 
				if(count($subjects)==0){
				//Original from yati
					$subjectDb = new  GeneralSetup_Model_DbTable_Subjectmaster();
					$subjects = $subjectDb->getSubjectByCollegeId($formData);
				}
				$i=0;
				foreach($subjects as $subject){			
						
					//get total student register this subject
					$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
					$total_student = $subjectRegDB->getTotalRegister($subject["IdSubject"],$formData["IdSemester"]);
					$subject["total_student"] = $total_student;				
					
					//get total group creates
					$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
					$total_group = $courseGroupDb->getTotalGroupByCourse($subject["IdSubject"],$formData["IdSemester"]);
					$subject["total_group"] = $total_group;
					$subject["IdSemester"] = $formData["IdSemester"];
					
					//get total no of student has been assigned
					$total_assigned = $subjectRegDB->getTotalAssigned($subject["IdSubject"],$formData["IdSemester"]);
					$total_unassigned = $subjectRegDB->getTotalUnAssigned($subject["IdSubject"],$formData["IdSemester"]);
					$subject["total_assigned"] = $total_assigned;
					$subject["total_unassigned"] = $total_unassigned;
					
					$subjects[$i]=$subject;
					
				$i++;	
				}
				
												
				$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($subjects));
				$paginator->setItemCountPerPage(1000);
				$paginator->setCurrentPageNumber($this->_getParam('page',1));
				
				$this->view->list_subject = $paginator;
				
				/*echo '<pre>';
				print_r($subjects);
				echo '</pre>';*/
				
			}//end if
			
		}
		
	}
	
	
	public function createGroupAction(){
		
		$auth = Zend_Auth::getInstance(); 
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			//print_r($formData);
			
			if($formData["generate_group_type"]==2){ //auto create group

				for($i=1; $i<=$formData["no_of_group"]; $i++){
					
					//create group
					$data["IdSemester"] = $formData["idSemester"];
					$data["IdSubject"]  = $formData["IdSubject"];
					$data["GroupCode"]  = "Group ".$i;
					$data["IdUniversity"]  = 1;
					$data["UpdUser"]  = $auth->getIdentity()->id;
					$data["UpdDate"]  = date("Y-m-d H:i:s");
					$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
					$idGroup = $courseGroupDb->addData($data);
					
					
					/*echo '<pre>';
					print_r($data);
					echo '</pre>';*/
					
					
					if($formData["assign_student_type"]==2){ //auto assign student to group
						
						//check how many student 
						if($formData["generate_group_type"]>0){
							
							$student_per_group = abs($formData["total_student"])/abs($formData["no_of_group"]);
						    $student_per_group = ceil($student_per_group);
							
							//query student register order by registrationID
							$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
							$list_student = $subjectRegDB->getStudents($formData["IdSubject"],$formData["idSemester"],$student_per_group);
							
							
							foreach($list_student as $student){
								
								//Update Studenr Register Subject
								$subjectRegDB->updateData(array('IdCourseTaggingGroup'=>$idGroup),$student["IdStudentRegSubjects"]);
								
								//insert dalam student mapping
								$info["IdCourseTaggingGroup"]=$idGroup;
								$info["IdStudent"]=$student["IdStudentRegistration"];
								
								$mappingDb = new GeneralSetup_Model_DbTable_CourseGroupStudent();
								$mappingDb->addData($info);
							}//end foreach							
							
						}//end if
						
					}//end if
				}//end for
			}//end if
			
			
			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$formData["IdSubject"],'idSemester'=>$formData["idSemester"]),'default',true));
		}//end if post
	}
	
	public function groupListAction(){
		
		$this->view->title = "Course Groupping : Group List";
		
		$idSubject = $this->_getParam('idSubject', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		
		$this->view->idSemester = $idSemester;
		$this->view->idSubject = $idSubject;
		
		//semester
		$semesterDb = new GeneralSetup_Model_DbTable_Semestermaster();
		$this->view->semester = $semesterDb->getData($idSemester);
		
		//get Subject Info
		$subjectDb = new GeneralSetup_Model_DbTable_Subjectmaster();
		$subject = $subjectDb->getData($idSubject);
		$this->view->subject = $subject;
				
		$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
		$groups = $courseGroupDb->getGroupList($idSubject,$idSemester);
		
		$courseProgramDb = new GeneralSetup_Model_DbTable_CourseGroupProgram();
		
		$i=0;
		foreach($groups as $group){
			
			//get verify by name
			if($group["VerifyBy"]){
			$staffDB = new GeneralSetup_Model_DbTable_Staffmaster();
			$verifyBy = $staffDB->getData($group["VerifyBy"]);
			$group['VerifyByName']=$verifyBy["FullName"];
			}
			
			$courseGroupStudent = new GeneralSetup_Model_DbTable_CourseGroupStudent();
			//$total_student = $courseGroupStudent->getTotalStudent($group["IdCourseTaggingGroup"]);
			$total_student = $courseGroupStudent->getTotalStudentViaSubReg($group["IdCourseTaggingGroup"]);
			$group["total_student"] = $total_student;
			
			
			//program
			$group["program"] = $courseProgramDb->getGroupData($group['IdCourseTaggingGroup']);
			
			
			$groups[$i]=$group;
			
		$i++;
		}
		
		$this->view->list_groups = $groups;

	}
	
	public function deleteGroupAction(){
		
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		
		if($idGroup){
			$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
			$courseGroupDb->deleteData($idGroup);
		}
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$idSubject,'idSemester'=>$idSemester),'default',true));
		
	}
	
	public function assignStudentAction(){
		
		$this->view->title = "Assign Student to Group";
		
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		
		$this->view->idGroup = $idGroup;
		$this->view->idSemester = $idSemester;
		$this->view->idSubject = $idSubject;
		
		//get list student yg belum di assign ke any group
		$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
		$list_student = $subjectRegDB->getUnTagGroupStudents($idSubject,$idSemester);
		$this->view->list_student = $list_student;
		
		//get group info
		$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
		$group = $courseGroupDb->getInfo($idGroup);
		$this->view->group = $group;
		
		$form = new GeneralSetup_Form_SearchGroupStudent();
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
    		
    		$formData = $this->getRequest()->getPost();
    		
    		if(isset($formData["Search"])){
    			if(isset($formData["bil_student"])){
    				$limit = $formData["bil_student"];
    				
    			}

				$list_student = $subjectRegDB->getUnTagGroupStudents($idSubject,$idSemester,$limit);
				$this->view->list_student = $list_student;
    		}else{
	    		for($i=0; $i<count($formData["IdStudentRegistration"]); $i++){
	    			
	    			$idStudentRegistration = $formData["IdStudentRegistration"][$i];
	    			$IdStudentRegSubjects = $formData["IdStudentRegSubjects"][$idStudentRegistration];
	    			
	    			$data["IdCourseTaggingGroup"] = $formData["idGroup"];
	    			$data["IdStudent"] = $idStudentRegistration;
	    			
		    		$studentGroupDb = new GeneralSetup_Model_DbTable_CourseGroupStudent();
		    		$studentGroupDb->addData($data);
		    			    		
		    		//update student reg subject table
		    		$subjectRegDB->updateData(array('IdCourseTaggingGroup'=>$formData["idGroup"]),$IdStudentRegSubjects);
	    		}
    		
    		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$idSubject,'idSemester'=>$idSemester,'msg'=>1),'default',true));
    		}
    	}
	}
	
	
	public function removeStudentAction(){
		
		$this->view->title = "View & Remove Student from Group";
		
		
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		
		$this->view->idGroup = $idGroup;
		$this->view->idSemester = $idSemester;
		$this->view->idSubject = $idSubject;
		
		//get list student yg dah di assign ke  group
		$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
		$list_student = $subjectRegDB->getTaggedGroupStudents($idGroup);		
		$this->view->list_student = $list_student;
		
		
		
		//get group info
		$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
		$group = $courseGroupDb->getInfo($idGroup);
		$this->view->group = $group;
		
		$form = new GeneralSetup_Form_SearchGroupStudent();
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
    		
    		$formData = $this->getRequest()->getPost();
    		//print_r($formData);
    		
    		for($i=0; $i<count($formData["IdStudentRegSubjects"]); $i++){
    			
    			$IdStudentRegSubjects = $formData["IdStudentRegSubjects"][$i];
    			
    			$IdStudentRegistration = $formData["IdStudentRegistration"][$IdStudentRegSubjects];
    		
	    		//remove dari student mapping
	    		$studentGroupDb = new GeneralSetup_Model_DbTable_CourseGroupStudent();	    	
		    	$studentGroupDb->removeStudent($idGroup,$IdStudentRegistration);
		    	
		    	//update reg subject to remove tagging to group
		    	$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
		    	$subjectRegDB->updateData(array('IdCourseTaggingGroup'=>0),$IdStudentRegSubjects);
    		}
    	
    		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$idSubject,'idSemester'=>$idSemester,'msg'=>2),'default',true));
    		
		}
		
		
	}
	
	
	public function addGroupAction(){
	  
	  $this->view->title = "Course Groupping : Add Group";
        	  
	  $idSubject = $this->_getParam('idSubject', 0);
	  $idSemester = $this->_getParam('idSemester', 0);
	  
	  $this->view->idSemester = $idSemester;
	  $this->view->idSubject = $idSubject;
	  
	  //semester
	  $semesterDb = new GeneralSetup_Model_DbTable_Semestermaster();
	  $this->view->semester = $semesterDb->getData($idSemester);
	  
	  //get Subject Info
	  $subjectDb = new GeneralSetup_Model_DbTable_Subjectmaster();
	  $subject = $subjectDb->getData($idSubject);
	  $this->view->subject = $subject;
	  
	  //faculty list
	  $facultyDb = new App_Model_General_DbTable_Collegemaster();
	  $this->view->faculty_list = $facultyDb->getFaculty();
	  
	  //program list
	  $programDb = new App_Model_Record_DbTable_Program();
	  $program = array();
	  foreach ($this->view->faculty_list as $faculty){
	    $where = array(
	        'IdCollege = ?' => $faculty['IdCollege'],
	        'Active = ?' => 1
	        );
	    $programList = $programDb->fetchAll($where);
	    
	    $program[] = array(
	          'faculty' => $faculty,
	          'program' => $programList->toArray()
	        );
	  }
	  $this->view->program_list = $program;
	  
	  
	  //form
      $form = new GeneralSetup_Form_CourseGroup(array('idSubject'=>$idSubject,'IdSemester'=>$idSemester,'idFaculty'=>$subject["IdFaculty"]));
      $this->view->form = $form;
      
      if ($this->getRequest()->isPost()) {
      	
      	$formData = $this->getRequest()->getPost();
      	
      	if($form->isValid($formData)){
      	
        	$auth = Zend_Auth::getInstance();
        	
        	
        	//create group
        	$data["IdSemester"] = $formData["idSemester"];
        	$data["IdSubject"]  = $formData["IdSubject"];
        	$data["GroupName"]  = $formData["GroupName"];
        	$data["GroupCode"]  = $formData["GroupCode"];
        	$data["maxstud"]  = $formData["maxstud"];
        	$data["remark"]  = $formData["remark"];
        	$data["IdLecturer"]  = isset($formData["IdLecturer"])?$formData["IdLecturer"]:null;
        	$data["VerifyBy"]  = isset($formData["VerifyBy"])?$formData["VerifyBy"]:null;
        	$data["IdUniversity"]  = 1;
        	$data["UpdUser"]  = $auth->getIdentity()->id;
        	$data["UpdDate"]  = date("Y-m-d H:i:s");
        	$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
        	$idGroup = $courseGroupDb->addData($data);
        	
        	//course group program
        	if( isset($formData['program']) ){
        	  $courseGroupProgramDb = new GeneralSetup_Model_DbTable_CourseGroupProgram();
        	  foreach($formData['program'] as $program){
        	    $dt = array(
        	          'group_id' => $idGroup,
        	          'program_id' => $program
        	        );
        	    
        	    $courseGroupProgramDb->insert($dt);
        	  }
        	}
        	
        	
        	$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$formData["IdSubject"],'idSemester'=>$formData["idSemester"]),'default',true));
      	}else{
      	  $form->populate($formData);
      	  
      	}
      }
      
      $this->view->form = $form;
		
	}
	
	
	public function editGroupAction(){
		
	  $this->view->title = "Course Groupping : Edit Group";

	  
	  $idGroup = $this->_getParam('idGroup', 0);
	  $idSubject = $this->_getParam('idSubject', 0);
	  $idSemester = $this->_getParam('idSemester', 0);
	  
	  $this->view->idSemester = $idSemester;
	  $this->view->idSubject = $idSubject;
	  $this->view->idGroup = $idGroup;
	  
	  
	  //semester
	  $semesterDb = new GeneralSetup_Model_DbTable_Semestermaster();
	  $this->view->semester = $semesterDb->getData($idSemester);
	  
	  //get Subject Info
	  $subjectDb = new GeneralSetup_Model_DbTable_Subjectmaster();
	  $subject = $subjectDb->getData($idSubject);
	  $this->view->subject = $subject;
	  
	  //faculty list
	  $facultyDb = new App_Model_General_DbTable_Collegemaster();
	  $this->view->faculty_list = $facultyDb->getFaculty();
	  
	  //program list
	  $programDb = new App_Model_Record_DbTable_Program();
	  $program = array();
	  foreach ($this->view->faculty_list as $faculty){
	    $where = array(
	        'IdCollege = ?' => $faculty['IdCollege'],
	        'Active = ?' => 1
	        );
	    $programList = $programDb->fetchAll($where);
	    
	    $program[] = array(
	          'faculty' => $faculty,
	          'program' => $programList->toArray()
	        );
	  }
	  $this->view->program_list = $program;
	  
	  //group
	  $courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
	  $data = $courseGroupDb->getInfo($idGroup);
	   
	  //group program
	  $courseGroupProgramDb = new GeneralSetup_Model_DbTable_CourseGroupProgram();
	  $data_program = $courseGroupProgramDb->getGroupData($idGroup);
	  $this->view->data_program = $data_program;
	  
	  //form
      $form = new GeneralSetup_Form_CourseGroup(array('idSubject'=>$idSubject,'IdSemester'=>$idSemester,'idGroup'=>$idGroup));
      $this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();

			if($form->isValid($formData)){
			  
			  
			    //course group
    			$data_upd["GroupCode"]  = $formData["GroupCode"];
    			$data_upd["GroupName"]  = $formData["GroupName"];
    			$data_upd["IdLecturer"]  = isset($formData["IdLecturer"]) && $formData["IdLecturer"]!=0?$formData["IdLecturer"]:null;
    			$data_upd["VerifyBy"]  = isset($formData["VerifyBy"]) && $formData["VerifyBy"]!=0?$formData["VerifyBy"]:null;
    			$data_upd["maxstud"]  = $formData["maxstud"];
    			$data_upd["remark"]  = $formData["remark"];
    			
    			$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
    			$courseGroupDb->updateData($data_upd, $formData["IdCourseTaggingGroup"]);
    			
    			
    			$courseGroupProgramDb = new GeneralSetup_Model_DbTable_CourseGroupProgram();
    			//group program
    			if(isset($formData['program_remove'])){
    			  foreach ($formData['program_remove'] as $id_to_remove){
    			    $courseGroupProgramDb->delete('id = '.$id_to_remove);
    			  }
    			}
    			
    			if(isset($formData['program_add'])){
    			  foreach ($formData['program_add'] as $id_program_to_add){
    			    
    			    $dt = array(
        	          'group_id' => $formData["IdCourseTaggingGroup"],
        	          'program_id' => $id_program_to_add
        	        );
        	    
        	        $courseGroupProgramDb->insert($dt);
    			  }
    			}
    			
    			
    			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'group-list','idSubject'=>$formData["IdSubject"],'idSemester'=>$formData["idSemester"]),'default',true));
			}else{
			  $form->populate($formData);
			}
		}else{
			$form->populate($data);
		}
			
		$this->view->form = $form;
			
		
	}
	
	public function scheduleAction(){
		
		$this->view->title = "Schedule";
		
		setlocale(LC_TIME, 'id_ID');
				
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		
		$this->view->idGroup = $idGroup;
		$this->view->idSemester = $idSemester;
		$this->view->idSubject = $idSubject;
		
		$form = new GeneralSetup_Form_ScheduleForm(array('idSubject'=>$idSubject,'IdSemester'=>$idSemester,'IdGroup'=>$idGroup));
		$this->view->form = $form;
		
		$groupSchdeleDb = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
		$schedule = $groupSchdeleDb->getSchedule($idGroup);
		$this->view->schedule = $schedule;		
		
		//get group info
		$courseGroupDb = new GeneralSetup_Model_DbTable_CourseGroup();
		$group = $courseGroupDb->getInfo($idGroup);
		$this->view->group = $group;
	}
	
	public function addScheduleAction(){
	  
        if($this->_request->isXmlHttpRequest()){
          $this->_helper->layout->disableLayout();
        }
		
		$auth = Zend_Auth::getInstance();
		 
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		
		$form = new GeneralSetup_Form_ScheduleForm(array('idSubject'=>$idSubject,'IdSemester'=>$idSemester,'IdGroup'=>$idGroup));
		
		if ($this->getRequest()->isPost()) {
		  
		    $formData = $this->getRequest()->getPost();
		    
		    if($form->isValid($formData)){
			
		      if($formData["sc_date"]!=null){
    			$sc_date = date_create_from_format('d/m/Y', $formData["sc_date"]);
    			$data["sc_date"]=$sc_date->format('Y-m-d');
		      }
		      
    			$data["idGroup"]=$formData["idGroup"];
    			
    			$data["sc_day"]=$formData["sc_day"];
    			$data["sc_start_time"]=$formData["sc_start_time"];
    			$data["sc_end_time"]=$formData["sc_end_time"];
    			$data["sc_venue"]=$formData["sc_venue"];
    			if(isset($formData["idCollege"])&&$formData["idCollege"]!=""&&$formData["idCollege"]!=0){
    				$data["idCollege"]=$formData["idCollege"];
    			}
    			if(isset($formData["idLecturer"])&&$formData["idLecturer"]!=0&&$formData["idLecturer"]!=""){
    				$data["IdLecturer"]=$formData["idLecturer"];
    			}
    			$data["sc_class"]=$formData["sc_class"];
    			$data["sc_createdby"]=$auth->getIdentity()->id;
    			$data["sc_createddt"]=date("Y-m-d H:i:s");
    			
    			$data["sc_remark"]=$formData["sc_remark"];
    
    			$scheduleDB = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
    			$scheduleDB->addData($data);
    			
    			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'schedule','idSubject'=>$formData["IdSubject"],'idSemester'=>$formData["idSemester"],'idGroup'=>$formData["idGroup"]),'default',true));
			
		    }else{
		      $form->populate($formData);
		    }
		}
		
		$this->view->form = $form;
		
		
	}
	
	
	public function editScheduleAction(){
		
        if($this->_request->isXmlHttpRequest()){
          $this->_helper->layout->disableLayout();
        }
		
		
		$this->view->title = "Edit Schedule";
		
		setlocale(LC_TIME, 'id_ID');
				
		$idGroup = $this->_getParam('idGroup', 0);
		$idSemester = $this->_getParam('idSemester', 0);
		$idSubject = $this->_getParam('idSubject', 0);
		$idSchedule = $this->_getParam('idSchedule', 0);
					
		//get data
		$scheduleDB = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
		$data = $scheduleDB->getData($idSchedule);
		
		$form = new GeneralSetup_Form_ScheduleForm(array('idSubject'=>$idSubject,'IdSemester'=>$idSemester,'IdGroup'=>$idGroup,'IdSchedule'=>$idSchedule));		
		
		if ($this->getRequest()->isPost()) {
			
		     $formData = $this->getRequest()->getPost();
		    
		    if($form->isValid($formData)){
			
                if($formData["sc_date"]!=null && $formData["sc_date"]!=""){
                  $sc_date = date_create_from_format('d/m/Y', $formData["sc_date"]);
                  $data["sc_date"]=$sc_date->format('Y-m-d');
                }else{
                  $data["sc_date"]=null;
                }  
    			$data["sc_day"]=$formData["sc_day"];
    			$data["sc_start_time"]=$formData["sc_start_time"];
    			$data["sc_end_time"]=$formData["sc_end_time"];
    			$data["sc_venue"]=$formData["sc_venue"];
    			$data["sc_class"]=$formData["sc_class"];
    			$data["idLecturer"]=$formData["idLecturer"];
    			$data["sc_remark"]=$formData["sc_remark"];
    			
    			$scheduleDB->updateData($data, $idSchedule);
    			
    			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'schedule','idSubject'=>$formData["IdSubject"],'idSemester'=>$idSemester,'idGroup'=>$idGroup),'default',true));
			
			}else{
			  $form->populate($formData);
			}
		}
		if($data["sc_date"]!=null){
		  $data['sc_date'] = date('d/m/Y',strtotime($data['sc_date']));
		}
		$form->populate($data);
		$this->view->form = $form;
	}
	
	
	public function deleteScheduleAction(){
		
			$idGroup = $this->_getParam('idGroup', 0);
			$idSemester = $this->_getParam('idSemester', 0);
			$idSubject = $this->_getParam('idSubject', 0);
			$idSchedule = $this->_getParam('idSchedule', 0);
		
			$scheduleDB = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
			$scheduleDB->deleteData($idSchedule);
			
			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'course-group', 'action'=>'schedule','idSubject'=>$idSubject,'idSemester'=>$idSemester,'idGroup'=>$idGroup),'default',true));
			
	}
	
	public function attendanceListAction(){
		
		$this->_helper->layout->setLayout('preview');
		
		$this->view->title = "Daftar Hadir Mahasiswa";
		
		$idSchedule = $this->_getParam('idSchedule', 0);
		$this->view->idSchedule = $idSchedule;
		
		//get schedule info
		$scheduleDB = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
		$schedule = $scheduleDB->getDetailsInfo($idSchedule);
		$this->view->schedule = $schedule;
		
		//get list student yg dah di assign ke  group
		$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
		$list_student = $subjectRegDB->getTaggedGroupStudents($schedule["idGroup"]);		
		$this->view->list_student = $list_student;
		
		
	}
	
	public function attendanceListPdfAction(){
		
		$this->view->title = "Daftar Hadir Mahasiswa";
		
		$idSchedule = $this->_getParam('idSchedule', 0);
		
		//get schedule info
		$scheduleDB = new GeneralSetup_Model_DbTable_CourseGroupSchedule();
		$schedule_data = $scheduleDB->getDetailsInfo($idSchedule);
				
				
		//get list student yg dah di assign ke  group
		$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
		$list_student = $subjectRegDB->getTaggedGroupStudents($schedule_data["idGroup"]);
		
		
		global $schedule;
		$schedule = $schedule_data;
		
		global $data;
		$data = $list_student;
		
		/*
		 * PDF Generation
		 */
		
		require_once 'dompdf_config.inc.php';
		
		$autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller
		$autoloader->pushAutoloader('DOMPDF_autoload');
		
		$html_template_path = DOCUMENT_PATH."/template/CourseGroupingAttendance.html";
		
		$html = file_get_contents($html_template_path);
		
		//echo $html;
		//exit;
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'potrait');
		$dompdf->render();
		
		
		$dompdf->stream("Attendance.pdf");
		exit;
	}
	
	public function ajaxGetLecturerAction(){
		
    	$idCollege = $this->_getParam('idCollege',null);
    	$idLecturer = $this->_getParam('IdLecturer',null);
    	
     	$this->_helper->layout->disableLayout();
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$staffDb = new GeneralSetup_Model_DbTable_Staffmaster();
	  	
	  	if($idLecturer!=null){
	  	  $staff = $staffDb->getData($idLecturer);
	  	}else{
	  	  $staff = $staffDb->getAcademicStaff($idCollege);
	  	}        
	    
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($staff);
		
		echo $json;
		exit();
    }
    
    
    public function updateGroupDataMigrationAction(){
    	
    	$studentGroupDb = new GeneralSetup_Model_DbTable_CourseGroupStudent();
    	$subjectRegDB = new Registration_Model_DbTable_Studentregsubjects();
    	
    	$student = $subjectRegDB->getStudentWithGroup();
    	
    	foreach($student as $s){
    		
    		//cek dah ada lom?
    		$return_row = $studentGroupDb->checkStudentMappingGroup($s['IdCourseTaggingGroup'],$s["IdStudentRegistration"]);
    		
    		if(!$return_row){
		    	$data["IdCourseTaggingGroup"]=$s['IdCourseTaggingGroup'];
		    	$data["IdStudent"]=$s['IdStudentRegistration'];   	
	    	echo '<br>add  => '.$s['IdCourseTaggingGroup'].' | '.$s["IdStudentRegistration"] .'<br>';
	    	//$studentGroupDb->addData($data);
    		}else{
    			
    			echo '<br>exist<br>';
    		}
    	
    		
    	}exit;
    }
	
	
}
?>