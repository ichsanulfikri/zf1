<?php 

class GeneralSetup_Model_DbTable_CourseGroup extends Zend_Db_Table_Abstract {
	
	protected $_name = 'tbl_course_tagging_group';
	protected $_primary = "IdCourseTaggingGroup";
	
	
	public function addData($data){		
	   $id = $this->insert($data);
	   return $id;
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function deleteData($id){		
	  $this->delete($this->_primary .' =' . (int)$id);
	}
	
	
	public function getGroupList($idSubject,$idSemester){
		
		$auth = Zend_Auth::getInstance();
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('cg'=>$this->_name))
					  ->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=cg.IdSemester',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('stm'=>'tbl_staffmaster'),'stm.IdStaff=cg.IdLecturer',array('FrontSalutation','FullName','BackSalutation'))
					 // ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=cg.IdSubject',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage'))
					  ->where('IdSubject = ?',$idSubject)
					  ->where('IdSemester = ?',$idSemester);
		
		// echo $select;
		 $row = $db->fetchAll($select);	
		 return $row;
	}
	
	public function getTotalGroupByCourse($idCourse,$idSemester){
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->where("IdSubject = ?",$idCourse)
					  ->where('IdSemester = ?',$idSemester);					  
		 $row = $db->fetchAll($select);	
		 
		 if($row)
		 	return count($row);
		 else
		 return 0;
	}
	
	public function getInfo($idGroup){
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('cg'=>$this->_name))
					  ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=cg.IdSubject',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage','faculty_id'=>'IdFaculty'))
					  ->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=cg.IdSemester',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('stm'=>'tbl_staffmaster'),'stm.IdStaff=cg.IdLecturer',array('FrontSalutation','FullName','BackSalutation'))
					  ->where('IdCourseTaggingGroup = ?',$idGroup);					  
		 $row = $db->fetchRow($select);	
		 return $row;
	}
	
	public function getMarkEntryGroupList($idSubject,$idSemester){
		
		$auth = Zend_Auth::getInstance();
		
		//print_r($auth->getIdentity());
	
		$db = Zend_Db_Table::getDefaultAdapter();	

		
		
		$select = $db ->select()
					  ->from(array('cg'=>$this->_name))
					  ->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=cg.IdSemester',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('stm'=>'tbl_staffmaster'),'stm.IdStaff=cg.IdLecturer',array('FrontSalutation','FullName','BackSalutation'))
					 // ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=cg.IdSubject',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage'))
					  ->where('IdSubject = ?',$idSubject)
					  ->where('IdSemester = ?',$idSemester);	

		 $rows = $db->fetchAll($select);	
		 	
		
		 if($auth->getIdentity()->IdRole!=1 && $auth->getIdentity()->IdRole!=298){//sekiranya bukan admin dia boleh tgk group dia sahaja
		 	
		 	
				 foreach($rows as $index=>$row){		 	
				 	
				 	//cari list lecturer dalam group tu
				 	//course group schedule
					$select_group_schedule = $db ->select()
								 				 ->from(array('cgs'=>'course_group_schedule'))
								 				 ->where("cgs.idGroup = ?",$row["IdCourseTaggingGroup"])
								 				 ->where("cgs.IdLecturer = ?",$auth->getIdentity()->iduser);
					$lecturer = $db->fetchRow($select_group_schedule);	
											
					if(!$lecturer["sc_id"]){							
						//check adakah dia coordinator
						if($row["IdLecturer"]!=$auth->getIdentity()->iduser){
							unset($rows[$index]);
						}
					}
								
				 }//end foreach
		 	
		 }//end if
		 
		
		 return $rows;
	}
	
	
	public function getMarkApprovalGroupList($subject_id,$idSemester){
		
		$auth = Zend_Auth::getInstance();
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('cg'=>$this->_name))
					  ->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=cg.IdSemester',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('stm'=>'tbl_staffmaster'),'stm.IdStaff=cg.IdLecturer',array('FrontSalutation','FullName','BackSalutation'))
					  ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=cg.IdSubject',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage'))
					  ->where('IdSemester = ?',$idSemester);	

		 if($auth->getIdentity()->IdRole!=1 && $auth->getIdentity()->IdRole!=298){//sekiranya bukan admin dia boleh tgk group dia sahaja
		 	$select->where("cg.VerifyBy = ?",$auth->getIdentity()->IdStaff);
		 }else{
		 	 $select->where('cg.IdSubject = ?',$subject_id);
		 }	
		 
		
		 $row = $db->fetchAll($select);	
		 return $row;
	}
	
	
	public function getCourseTaggingGroupList($idSemester){		
		
		$auth = Zend_Auth::getInstance();
		
		$id_user = $auth->getIdentity()->IdStaff;
		$idRole  = $auth->getIdentity()->IdRole;
		
	/*	$id_user = 747;
		$idRole=3;*/
		
		$db = Zend_Db_Table::getDefaultAdapter();	
		
		
		//check if user is coordinator so display all
	    $select = $db ->select()
					  ->from(array('cg'=>$this->_name))
					  ->join(array('cgs'=>'course_group_schedule'),'cgs.idGroup=cg.IdCourseTaggingGroup',array())
					  ->join(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=cg.IdSemester',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('stm'=>'tbl_staffmaster'),'stm.IdStaff=cg.IdLecturer',array('FrontSalutation','coordinator'=>'FullName','BackSalutation'))
					  ->join(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=cg.IdSubject',array('IdSubject','subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage'))					  
					  ->where('cg.IdSemester = ?',$idSemester)					  
					  ->order('SubCode')
					  ->order('IdCourseTaggingGroup');	
					  
					  if($idRole!=1){ //bukan admin
					  	$select->where('(cg.IdLecturer = ?',$id_user);
					  	$select->orwhere('cgs.IdLecturer = ?)',$id_user);
					  }		
		
		 $rows = $db->fetchAll($select);
		 return $rows;				
	}
	
}