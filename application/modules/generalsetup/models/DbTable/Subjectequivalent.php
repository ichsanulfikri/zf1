<?php

class GeneralSetup_Model_DbTable_Subjectequivalent extends Zend_Db_Table { //Model Class for Subjectequivalent Details
	protected $_name = 'tbl_subjectmaster';
	
	public function fnGetSubjectMasterList($editsubid){//Function to get the subject master list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sm"=>"tbl_subjectmaster"),array("key"=>"sm.IdSubject","value"=>"sm.SubjectName"))
		 				 ->where('sm.IdSubject != ?',$editsubid)
		 				 ->where("sm.Active = 1")
		 				 ->order("sm.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetSubjectDetails() { //Function to get the subject details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"))
       								->where('sm.Active = 1')
       							     ->group("sm.IdSubject")
       								->order("sm.SubjectName");
      $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }
	
     public function fngetUserSubjectDetails($IdCollege) { //Function to get the user subject details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("sm"=>"tbl_subjectmaster"),array("sm.*"))
       								->joinleft(array("dm"=>"tbl_departmentmaster"),'sm.IdDepartment = dm.IdDepartment',array("dm.*"))
       								->joinleft(array("cm"=>"tbl_collegemaster"),'dm.IdCollege = cm.IdCollege',array("cm.CollegeName as BranchName","cm.CollegeType"))
       								->joinleft(array("cm2"=>"tbl_collegemaster"),'cm.Idhead =cm2.IdCollege',array("cm2.CollegeName"))
       								->joinleft(array("ct"=>"tbl_coursetype"),'ct.IdCourseType =sm.CourseType',array("ct.CourseType AS CourseName"))
       								 ->where('dm.IdCollege = ?',$IdCollege)
       								->where('sm.Active = 1')
       								
       								->group("sm.IdSubject")
       								->order("sm.SubjectName");
       $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }
	
     public function fnSearchSubject($post = array()) { //Function to get the searched subject details
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
    	 if($post['field4'])	$lstrSelect		->where('sm.BahasaIndonesia like "%" ? "%"',$post['field4']);								
       	$lstrSelect	->where('sm.SubjectName like "%" ? "%"',$post['field2'])
       				->where('sm.SubCode like "%" ? "%"',$post['field3'])       				
       				->where("sm.Active = ".$post["field7"])
       				->group("sm.IdSubject")
       			   ->order("sm.SubjectName");
       $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
	 
	public function fnSearchUserSubject($post = array(),$IdCollege) { //Function to get the user subject details
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
        if($post['field4'])	$lstrSelect		->where('sm.BahasaIndonesia like "%" ? "%"',$post['field4']);					
       	$lstrSelect	->where('sm.SubjectName like "%" ? "%"',$post['field2'])
       				->where('sm.SubCode like "%" ? "%"',$post['field3'])
       				->where('dm.IdCollege = ?',$IdCollege)
       				->where("sm.Active = ".$post["field7"])
       				->group("sm.IdSubject")
       			   ->order("sm.SubjectName");
       $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }

	 public function fnGetUniversityMasterList(){//function to get university master list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("um"=>"tbl_universitymaster"),array("key"=>"um.IdUniversity","value"=>"um.Univ_Name"))
		 				 ->where("um.Active = 1")
		 				 ->order("um.Univ_Name");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetCollegeList(){//function to get college list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 0")
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetBranchList(){//function to get branch list
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 1")
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

    public function fnGetSubjectCodeList($editsubid) { //function to get equivalent subjectcode list 
		
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("sm"=>"tbl_subjectmaster"),array("key"=>"sm.IdSubject","value"=>"sm.SubCode"))
						->where('sm.IdSubject != ?',$editsubid)
		 				->where("sm.Active = 1");			
		 $result = $lobjDbAdpt->fetchAll($select);
		return $result;
    }
    
    public function fnGetCreditHours($equivalentsubcode){//function to get credit hour of selected  equivalent subject
    	    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		        $select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_subjectmaster"),array("c.*"))			
		            	->where("c.SubCode= ?",$equivalentsubcode);
		        return $result = $lobjDbAdpt->fetchRow($select);
    
    }
    
    public function fnInsertToDb($formData,$ldtsystemDate,$user){//function to insert to database
    	//print_r($formData);die();
    	$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_subjectequivalent";
		
		unset($formData['CreditHourgrid']);	
		unset($formData['Active']);	
		unset($formData['EquivalentSubjectCode']);	
		unset($formData['Save']);
		unset($formData['EquivalentSubjectCode']);
		//$cc=$formData['OriginalSubjectCode'];
		$counteq = count($formData['Idcomponentsgrid']);
        for($i=0;$i<$counteq;$i++){	 
		              $data = array('idsubject'=>$formData['IdOriginalSubject'],
									'idsubjectequivalent'=>$formData['Idcomponentsgrid'][$i],
									'Active'=>1,
									'UpdDate'=>$ldtsystemDate,
									'UpdUser'=>$user);
	$db->insert($table,$data);
		}
	 }
   public function fnDeleteFromDb($formData){//function to delete from  database
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  if($formData['Iditem']){
    	     $id=$formData['Iditem'];
    	     $arrid=explode(",",$id);
    	     for($i=0;$i<count($arrid);$i++){
    	     $where = "idequivalent =$arrid[$i]";
 		     $db->delete('tbl_subjectequivalent',$where);
    	     }
 		     return;
    	  }
    	  return;
     }
     


    public function fnViewSubjectEquivalentDetails($editsubid){//function to view subject equivalent details
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	    $lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("sp"=>"tbl_subjectequivalent"),array("sp.*"))
		 				 ->join(array("sm"=>"tbl_subjectmaster"),'sp.idsubjectequivalent = sm.IdSubject',array("sm.SubjectName","sm.CreditHours"))
		 				 ->where('sp.Idsubject = ?',$editsubid);	
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
   }
   public function trtr(){

   		
   }

}