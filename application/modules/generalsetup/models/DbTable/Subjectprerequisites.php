
<?php
class GeneralSetup_Model_DbTable_Subjectprerequisites extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_subjectprerequisites';
	
	public function fngetSubjectDetails() { //Function to get the user details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"))
       								->where('sm.Active = 1')
       								//->where('cm2.CollegeType = 0')
       								->group("sm.IdSubject")
       								->order("sm.SubjectName");
       								//->where("dm.DepartmentType  = 0");
       					//echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
     }
     
	public function fngetUserSubjectDetails($IdCollege) { //Function to get the user details
       	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"))
       								 ->where('dm.IdCollege = ?',$IdCollege)
       								->where('sm.Active = 1')
       								//->where('cm2.CollegeType = 0')
       								->group("sm.IdSubject")
       								->order("sm.SubjectName");
       								//->where("dm.DepartmentType  = 0");
       					//echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }
     
	public function fnSearchSubject($post = array()) { //Function to get the user details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"));
       								
		if(isset($post['field5']) && !empty($post['field5']) ){
				$lstrSelect = $lstrSelect->where("dm.IdCollege = ?",$post['field5']);
										
		}
		if(isset($post['field8']) && !empty($post['field8']) ){
				$lstrSelect = $lstrSelect->where("cm.Idhead = ?",$post['field8']);
										
		}
		if(isset($post['field20']) && !empty($post['field20']) ){
				$lstrSelect = $lstrSelect->where("sm.IdDepartment = ?",$post['field20']);
										
		}
       							//	->where("cm.IdCollege= ?",$post['field5'])
       								//->where("cm.Idhead= ?",$post['field5'])
       								//->where('sm.IdDepartment like "%" ? "%"',$post['field20'])
       	if($post['field4'])	$lstrSelect		->where('sm.BahasaIndonesia like "%" ? "%"',$post['field4']);								
       	$lstrSelect	->where('sm.SubjectName like "%" ? "%"',$post['field2'])
       				->where('sm.SubCode like "%" ? "%"',$post['field3'])
       				->where("sm.Active = ".$post["field7"])
       				->group("sm.IdSubject")
       			   ->order("sm.SubjectName");
       					//echo $lstrSelect;die();
       					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
	 
	public function fnSearchUserSubject($post = array(),$IdCollege) { //Function to get the user details		
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"));
       								
		if(isset($post['field5']) && !empty($post['field5']) ){
				$lstrSelect = $lstrSelect->where("dm.IdCollege = ?",$post['field5']);
										
		}
		if(isset($post['field8']) && !empty($post['field8']) ){
				$lstrSelect = $lstrSelect->where("cm.Idhead = ?",$post['field8']);
										
		}
		if(isset($post['field20']) && !empty($post['field20']) ){
				$lstrSelect = $lstrSelect->where("sm.IdDepartment = ?",$post['field20']);
										
		}
       							//	->where("cm.IdCollege= ?",$post['field5'])
       								//->where("cm.Idhead= ?",$post['field5'])
       								//->where('sm.IdDepartment like "%" ? "%"',$post['field20'])
       	if($post['field4'])	$lstrSelect		->where('sm.BahasaIndonesia like "%" ? "%"',$post['field4']);								
       	$lstrSelect	->where('sm.SubjectName like "%" ? "%"',$post['field2'])
       				->where('sm.SubCode like "%" ? "%"',$post['field3'])
       				->where('dm.IdCollege = ?',$IdCollege)
       				->where("sm.Active = ".$post["field7"])
       				->group("sm.IdSubject")
       			   	->order("sm.SubjectName");
       					//echo $lstrSelect;die();
       					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
    /* public function fnSearchSubject($post = array()) { //Function for searching the user details
    	$db = Zend_Db_Table::getDefaultAdapter();
		$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_subjectmaster'),array('IdSubject'))
			   ->join(array("dm"=>"tbl_departmentmaster"),'dm.IdDepartment = a.IdDepartment')
			   ->join(array("cm"=>"tbl_collegemaster"),'cm.IdCollege = dm.IdCollege')
			   //->join(array("um"=>"tbl_universitymaster"),'um.IdUniversity = cm.AffiliatedTo')
			   ->where('cm.AffiliatedTo like "%" ? "%"',$post['field1'])
			   ->where('cm.IdCollege like "%" ? "%"',$post['field5'])
			   ->where('a.SubjectName like "%" ? "%"',$post['field2'])
			   ->where('a.SubCode like  "%" ? "%"',$post['field3'])
			   ->where($field7);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}*/
	
     
/*public function fngetSubjectDetails() { //Function to get the user details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->join(array("dm"=>"tbl_departmentmaster"),'dm.IdDepartment = sm.IdDepartment',array("dm.*"))
       								->join(array("cm"=>"tbl_collegemaster"),'cm.IdCollege = dm.IdCollege',array("cm.CollegeName as CollegeName"))
       								->join(array("cm1"=>"tbl_collegemaster"),'cm1.IdCollege = dm.IdCollege',array("cm1.CollegeName as BranchName"))
       								->where("cm.CollegeType = 0")
       								->where("cm1.CollegeType = 1");
       								echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
     }*/
     
	public function fnGetDepartmentListofCollege($lintidCollege){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_departmentmaster"),array("key"=>"a.IdDepartment","value"=>"a.DepartmentName"))
		 				 ->where("a.IdCollege = ?",$lintidCollege)
		 				 ->where("a.Active = 1")
		 				 ->order("a.DepartmentName");
		 				 //echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetSubjectListofDepartment($lintiddepartment,$lintidsubject){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"a.SubjectName"))
		 				 ->where("a.IdDepartment = ?",$lintiddepartment)
		 				 ->where("a.IdSubject NOT IN (".$lintidsubject.")")
		 				 ->where("a.Active = 1")
		 				 ->order("a.SubjectName");
		 				 //echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetUniversityMasterList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("um"=>"tbl_universitymaster"),array("key"=>"um.IdUniversity","value"=>"um.Univ_Name"))
		 				 ->where("um.Active = 1")
		 				 ->order("um.Univ_Name");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetcoursetypeDetails(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("ct"=>"tbl_coursetype"),array("key"=>"ct.IdCourseType","name"=>"ct.CourseType"))
		 				 ->where("ct.Active = 1")
		 				 ->order("ct.CourseType");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnaddSubjectPrerequisites($larrformData) { //Function for adding the user details to the table
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
				
		//$count = count($larrformData['IdRequiredSubject']);
		for($i = 0 ;$i<count($larrformData['IdRequiredSubjectgrid']); $i++) {
		
		$lstrTable = "tbl_subjectprerequisites";
		$larrInsertData = array(//'IdSubjectPrerequisites' => $larrformData["IdSubjectPrerequisites"],
								'IdSubject' => $larrformData["IdSubject"],
								'IdRequiredSubject' => $larrformData["IdRequiredSubjectgrid"][$i],
								'PrerequisiteType' => $larrformData["PrerequisiteTypegrid"][$i],
								'PrerequisiteGrade' => $larrformData["PrerequisiteGradegrid"][$i],
								'MinCreditHours' => $larrformData["MinCreditHoursgrid"][$i],
							);
		$lobjDbAdpt->insert($lstrTable,$larrInsertData);
		}
	 
	}
	
	
	public function fnViewSubjectPrerequisitesDetails($lintisubject){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sp"=>"tbl_subjectprerequisites"),array("sp.*"))
		 				 ->join(array("sm"=>"tbl_subjectmaster"),'sp.IdRequiredSubject = sm.IdSubject',array("sm.SubjectName","sm.IdSubject","sm.SubCode"))
		 				 ->join(array('dms'=>'tbl_definationms'),'sp.PrerequisiteGrade = dms.idDefinition',array("dms.idDefinition","dms.DefinitionCode"))
		 				 ->where('sp.IdSubject = ?',$lintisubject);	
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
	}
	
	
	public function fnViewTempSubjectPrerequisitesDetails($lintisubject){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("tsp"=>"tbl_tempsubjectprerequisites"),array("tsp.*"))
		 				 ->join(array("sm"=>"tbl_subjectmaster"),'tsp.IdRequiredSubject = sm.IdSubject',array("sm.*"))
		 				 ->joinLeft(array('dms'=>'tbl_definationms'),'tsp.PrerequisiteGrade = dms.idDefinition')
		 				 ->where('tsp.unicode = ?',$lintisubject)
		 				 ->where('tsp.deleteFlag =1');
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnUpdateTemptempsubjectprerequisitesdetails($IdTempSubjectPrerequisites) {  // function to update po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempsubjectprerequisites";
			$larramounts = array('deleteFlag'=>'0');
			$where = "IdTempSubjectPrerequisites = '".$IdTempSubjectPrerequisites."'";
			$db->update($table,$larramounts,$where);	
		}
		
	public function fnGetSubjectprerequisitesTemDetails($IdSubject,$sessionID) { // Function to edit Purchase order details 			
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_tempsubjectprerequisites'),array('a.IdTempSubjectPrerequisites'))
							->where("a.unicode = '$IdSubject'")
							->where("a.sessionId = '$sessionID'")
							->where("a.deleteFlag = '0'");
			$result = $this->fetchAll($select);
			return $result;
		}	
	public function fnUpdateMainSubjectprerequisites($transferedSubjectprereqdetails,$IdSubjectPrerequisites) {
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectprerequisites";
			$where = "IdSubjectPrerequisites = '$IdSubjectPrerequisites'";
			$db->update($table,$transferedSubjectprereqdetails,$where);	
		}	
	public function fnInsertSubjectprerequisitesDetails($transferedinsertSubjectprerequisitesdetails) {  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectprerequisites";
			$db->insert($table,$transferedinsertSubjectprerequisitesdetails);	
		}	
	public function fninserttempsubjectprerequisites($resultsubjectprerequisitest,$lintisubject) {  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempsubjectprerequisites";
			$sessionID = Zend_Session::getId();
			
			foreach($resultsubjectprerequisitest as $resultsubjectprerequisitestData) {
						$larrtepsubjectprerequisites = array('IdRequiredSubject'=>$resultsubjectprerequisitestData['IdRequiredSubject'],
									'PrerequisiteType'=>$resultsubjectprerequisitestData['PrerequisiteType'],
									'PrerequisiteGrade'=>$resultsubjectprerequisitestData['PrerequisiteGrade'],
									'unicode'=>$lintisubject,
									'Date'=>date("Y-m-d"),
									'sessionId'=>$sessionID,
									'idExists'=>$resultsubjectprerequisitestData['IdSubjectPrerequisites'],
									'deleteFlag'=>'1'
							);
							
				$db->insert($table,$larrtepsubjectprerequisites);	
			}
			//print_r($larrcourse);die();
		}
	
	public function fnDeleteSubjectprerequisitesDetails($IdSubjectPrerequisites) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectprerequisites";
	    	$where = $db->quoteInto('IdSubjectPrerequisites  = ?', $IdSubjectPrerequisites);
	    	$db->delete($table, $where);
	}
	
	public function fnGetDepartmentList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_departmentmaster"),array("key"=>"a.IdDepartment","value"=>"a.DepartmentName"))
		 				 ->where("a.Active = 1")
		 				 ->order("a.DepartmentName");
		 			//echo $lstrSelect;die();	 
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetCollegeList(){
	  	$session = new Zend_Session_Namespace('sis');

		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 //->where("a.CollegeType = 0")
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		if($session->IdRole == 311 || $session->IdRole == 298){ 			
			$lstrSelect->where("a.IdCollege =?",$session->idCollege);
		}
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetColgDepartmentList($lintIdCollege){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		
		$locale =  Zend_Registry::get('Zend_Locale');
		
		if($locale!="en_US" && $locale!="en_GB"){
			$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_departmentmaster"),array("key"=>"a.IdDepartment","value"=>"a.ArabicName"))
		 				 ->join(array('b' => 'tbl_collegemaster'),'a.IdCollege = b.IdCollege',array('b.IdCollege'))
		 				 ->where("a.Active = 1")
		 				 ->where('a.IdCollege  = ?', $lintIdCollege)
		 				 ->order("a.DepartmentName");	
		}else{
			$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_departmentmaster"),array("key"=>"a.IdDepartment","value"=>"a.DepartmentName"))
		 				 ->join(array('b' => 'tbl_collegemaster'),'a.IdCollege = b.IdCollege',array('b.IdCollege'))
		 				 ->where("a.Active = 1")
		 				 ->where('a.IdCollege  = ?', $lintIdCollege)
		 				 ->order("a.DepartmentName");	
		}
		 				 
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetDeptSubjectsList($lintIdDepartment){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"CONCAT_WS('-',IFNULL(a.SubjectName,''),IFNULL(a.SubCode,''))"))
		 				 ->join(array('b' => 'tbl_departmentmaster'),'a.IdDepartment = b.IdDepartment',array('b.IdDepartment'))
		 				  ->where('a.IdDepartment  = ?', $lintIdDepartment)
		 				 ->where("a.Active = 1")
		 				 ->order("a.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetSubjectsList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"CONCAT_WS('-',IFNULL(a.SubjectName,''),IFNULL(a.SubCode,''))"))
		 				 ->where("a.Active = 1")
		 				 ->order("a.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
		
	
		public function fnGetSubjectList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"a.SubjectName"))
		 				 ->where("a.Active = 1")
		 				 ->order("a.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetBranchList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 1")
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
		
    public function fnviewSubject($lintisubject) { //Function for the view user 
    	//echo $lintidepartment;die();
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_subjectmaster"),array("a.*"))				
		            	->where("a.IdSubject= ?",$lintisubject);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
   
    public function getSubjectCode($lintidsubject) { //Function for the view user 
    	//echo $lintidepartment;die();
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_subjectmaster"),array("a.SubCode"))				
		            	->where("a.IdSubject= ?",$lintidsubject);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
	    public function fnDeleteSubjectPrerequists($IdSubjectPrerequisites ) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectprerequisites";
	    	$where = $db->quoteInto('IdSubjectPrerequisites = ?', $IdSubjectPrerequisites);
			$db->delete($table, $where);
	    }
	    
	    public function fnDeleteTempSubjectprerequisits($IdSubject,$sessionID) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempsubjectprerequisites";
	    	$where = $db->quoteInto('unicode = ?', $IdSubject);
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	    }
	    
	public function fnDeleteSubjectprerequisits($IdSubject) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectprerequisites";
	    	$where = $db->quoteInto('IdSubject = ?', $IdSubject);	    	
			$db->delete($table, $where);
	    }
	    
 	public function fnDeleteTempSubjectprerequisitesDetailsBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_tempsubjectprerequisites";
	    	$where = $db->quoteInto('sessionId = ?', $sessionID);
			$db->delete($table, $where);
	}

	/*Start Yatie*/
	
	public function getCoursePrerequisite($idLandscape,$idSubject,$idLandscapeSub){
		
			$db = Zend_Db_Table::getDefaultAdapter();
		    $select 	= $db->select()
							 ->from(array("sp" =>"tbl_subjectprerequisites"))
							 ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=sp.IdRequiredSubject',array('BahasaIndonesia','SubCode'))				
		            		 ->where("sp.IdLandscape= ?",$idLandscape)
		            		 ->where("sp.IdLandscapeSub= ?",$idLandscapeSub);
		            		// ->where("sp.IdSubject= ?",$idSubject);
			return $result = $db->fetchAll($select);
		
	}
	
	public function addData($data)
    {    	     
        $id =$this->insert($data);        
        return $id;
    }
     
     
	public function updateData($data,$id) {		
		
    	  $this->update($data,'IdLandscape='.$id);    	
	}
	
	public function deleteData($id){			
	  $this->delete('IdSubjectPrerequisites =' . (int)$id);
	}
	
	public function getSubjectsList($idCollege){
		
		
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"CONCAT_WS('-',IFNULL(a.BahasaIndonesia,''),IFNULL(a.SubCode,''))"))		 				
		 				  ->where('a.IdFaculty  = ?', $idCollege)
		 				 ->where("a.Active = 1")
		 				 ->order("a.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
}