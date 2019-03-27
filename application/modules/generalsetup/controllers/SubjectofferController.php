<?php
class GeneralSetup_SubjectofferController extends Base_Base {
	public function indexAction() {
		$this->view->title = $this->view->translate("Course Offered");
		$form = new GeneralSetup_Form_SearchCourse();
		$form->removeElement('SubjectName');
		$form->removeElement('SubjectCode');
		$idsemester = $this->_getparam("IdSemester");
		$idcollege = $this->_getparam("IdCollege");
		if($this->getRequest()->getPost()){
			$formData =$this->getRequest()->getPost();
			$idsemester = $formData["IdSemester"];
			$idcollege = $formData["IdCollege"];			
		}		
		$sofferedDB = new GeneralSetup_Model_DbTable_Subjectsoffered();
		if($idsemester!="" and $idcollege!=""){
			$form->populate(array("IdSemester"=>$idsemester,"IdCollege"=>$idcollege));
			
			$progDB= new GeneralSetup_Model_DbTable_Program();
			$landscapeDB= new GeneralSetup_Model_DbTable_Landscape();
			$landsubDB= new GeneralSetup_Model_DbTable_Landscapesubject();
			$landsubBlkDB= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
			
			$this->view->idsem = $idsemester;
			$this->view->idcol = $idcollege;
			//Subject list base on Program Landscape from the selected faculty 
			$programs = $progDB->fngetProgramDetails($idcollege);
			$i=0;
			$j=0;
			$allblocklandscape=null;
			$allsemlandscape=null;
			foreach ($programs as $key => $program){
				$activeLandscape=$landscapeDB->getAllLandscape($program["IdProgram"]);
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
					$subjectsem=$sofferedDB->getMultiLandscapeCourseOffer($allsemlandscape,"",$idsemester);
			if(is_array($allblocklandscape))
				$subjectblock=$sofferedDB->getMultiBlockLandscapeCourseOffer($allblocklandscape,null,$idsemester);
			
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
			$this->view->courses= $subjects;		
		}
		$this->view->form = $form;
	}
	
	public function addAction(){
		$this->view->title = $this->view->translate("Add Course Offer");
		$form = new GeneralSetup_Form_SearchCourse();
		$form->removeElement('SubjectName');
		$form->removeElement('SubjectCode');
		$idsemester = $this->_getparam("idsem");
		$idcollege = $this->_getparam("idcol");
		if($this->getRequest()->getPost()){
			$formData =$this->getRequest()->getPost();
			$idsemester = $formData["IdSemester"];
			$idcollege = $formData["IdCollege"];			
		}		
		$form->populate(array("IdSemester"=>$idsemester));
		$form->populate(array("IdCollege"=>$idcollege));

		$this->view->form = $form;
		if($idsemester!="" && $idcollege!=""){
			$progDB= new GeneralSetup_Model_DbTable_Program();
			$sofferedDB = new GeneralSetup_Model_DbTable_Subjectsoffered();
			$landscapeDB= new GeneralSetup_Model_DbTable_Landscape();
			$landsubDB= new GeneralSetup_Model_DbTable_Landscapesubject();
			$landsubBlkDB= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
			
			$this->view->idsem = $idsemester;
			$this->view->idcol = $idcollege;
			//Subject list base on Program Landscape from the selected faculty 
			$programs = $progDB->fngetProgramDetails($idcollege);
			$i=0;
			$j=0;
			$allblocklandscape=null;
			$allsemlandscape=null;
			foreach ($programs as $key => $program){
				$activeLandscape=$landscapeDB->getAllLandscape($program["IdProgram"]);
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
					$subjectsem=$sofferedDB->getMultiLandscapeNotCourseOffer($allsemlandscape,"",$idsemester);
			if(is_array($allblocklandscape))
				$subjectblock=$sofferedDB->getMultiBlockLandscapeNotCourseOffer($allblocklandscape,null,$idsemester);
			
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
			$this->view->courses= $subjects;
		}
	}
	
	public function runAction(){
		$sofferedDB = new GeneralSetup_Model_DbTable_Subjectsoffered();
		$auth = Zend_Auth::getInstance();
		$userId = $auth->getIdentity()->iduser;
		$formData =$this->getRequest()->getPost();
		if($this->_getparam("type")=="add"){
			foreach($formData["idsubject"] as $key=>$val){
				$idsub = $val;
				$minq = $formData["minquota"][$key];
				$maxq = $formData["maxquota"][$key];
				if($minq==""){
					$minq = $formData["dminquota"];
				}
				if($maxq==""){
					$maxq = $formData["dmaxquota"];
				}				
				$data=array(
				'IdSemester'=>$formData["IdSemester"],
											'IdSubject'=>$idsub,
											'MinQuota'=>$minq,
											'MaxQuota'=>$maxq,
											'UpdDate'=>date("Y-m-d H:i:s"),
											'UpdUser'=>$userId);
				//echo "$idsub - $minq - $maxq -".$formData["IdSemester"]."=".$formData["IdCollege"]."<br>";
				$sofferedDB->saveAllBranch($data);
		
			}
			//print_r($formData); 
		}elseif($this->_getparam("type")=="del"){
			foreach($formData["idsubject"] as $key=>$val){
				$sofferedDB->unoffered($key,$formData["IdSemester"]);
			}
		}
		$this->_redirect( $this->baseUrl . '/generalsetup/subjectoffer/index/IdSemester/'.$formData["IdSemester"].'/IdCollege/'.$formData["IdCollege"]);
		exit;
	}
}