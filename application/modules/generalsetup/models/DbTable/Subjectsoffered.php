<?php

/**
 * Subjectsoffered
 * 
 * @author Arun
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class GeneralSetup_Model_DbTable_Subjectsoffered extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_subjectsoffered';
	
	public function init()
	{
		$this->lobjdb = Zend_Db_Table::getDefaultAdapter();
	}

	public function fngetAllSubjectsOffered($lintIdSemester)
	{
/*		Ubah sket ytk sem ini All Subject offer to all kampus
 * $lstrselect = $this->lobjdb->select()	
								->from(array("subof"=>"tbl_subjectsoffered"),array("subof.*"))
								->join(array("sub"=>"tbl_subjectmaster"),"sub.IdSubject = subof.IdSubject",array("sub.IdSubject","sub.SubjectName","sub.SubCode"))
								->join(array("b"=>"tbl_branchofficevenue"),"b.IdBranch = subof.Branch",array("b.IdBranch","b.BranchName"))
								->where("subof.IdSemester = $lintIdSemester");*/
		$lstrselect = $this->lobjdb->select()
							->distinct()	
								->from(array("subof"=>"tbl_subjectsoffered"),array("MinQuota","MaxQuota"))
								->join(array("sub"=>"tbl_subjectmaster"),"sub.IdSubject = subof.IdSubject",array("sub.IdSubject","SubjectName"=>"sub.BahasaIndonesia","sub.SubCode"))
								->join(array("b"=>"tbl_branchofficevenue"),"b.IdBranch = subof.Branch",array())
								->where("subof.IdSemester = $lintIdSemester");		
		$larrresult = $this->lobjdb->fetchAll($lstrselect);
		
		return $larrresult;
	}
	
	public function fngetAllBranchset($lintidbrc)
	{
		$lstrselect = $this->lobjdb->select()	
								->from(array("a"=>"tbl_branchofficevenue"),array("key"=>"a.IdBranch","value"=>"a.BranchName"))
								->where("a.Active = 1")
								->where("a.IdType = ?",$lintidbrc)
								->order("a.BranchName");
		$larrresult = $this->lobjdb->fetchAll($lstrselect);
		return $larrresult;
	}
	public function fninsertMultipleSubjectsOffered($larrformData,$lintidsemester,$idx){
		$db = Zend_Db_Table::getDefaultAdapter();
			$table1 = "tbl_subjectsoffered";
			$table2 = "tbl_branchofficevenue";
			$lstrselect = $this->lobjdb->select()	
								->from(array("a"=>"tbl_branchofficevenue"),array("key"=>"a.IdBranch","value"=>"a.IdBranch"))
								->where("a.Active = 1")
								->where("a.IdType = 1");
		$larrresult = $this->lobjdb->fetchAll($lstrselect);
		$countOfBranch = count($larrresult);
		//echo $countOfBranch;

		for($i=0;$i<$countOfBranch;$i++)
		{
			$larrtsubjectsoffered = array('IdSemester'=>$lintidsemester,
											'IdSubject'=>$larrformData['IdSubjects'][$idx],
											'Branch' =>$larrresult[$i]['key'],
											'MinQuota'=>$larrformData['MinQuotagrid'][$idx],
											'MaxQuota'=>$larrformData['MaxQuotagrid'][$idx],
											'UpdDate'=>$larrformData['UpdDate'],
											'UpdUser'=>$larrformData['UpdUser']
								
							);
			$db->insert($table1,$larrtsubjectsoffered);	
		}
	}
	
	public function fninsertMultipleCourseOffered($larrformData,$lintidsemester){
		$db = Zend_Db_Table::getDefaultAdapter();
			$table1 = "tbl_subjectsoffered";
			$table2 = "tbl_subjectmaster";
			$lstrselect = $this->lobjdb->select()	
								->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"a.IdSubject"))
								->where("a.IdFaculty = ?",$larrformData['IdCollege'])
								->where("a.Active = 1");
		$larrresult = $this->lobjdb->fetchAll($lstrselect);
		$countOfCourse = count($larrresult);
		for($i=0;$i<$countOfCourse;$i++)
		{
			$larrtsubjectsoffered = array('IdSemester'=>$lintidsemester,
											'IdSubject'=>$larrresult[$i]['key'],
											'Branch' =>$larrformData['Branch'][0],
											'MinQuota'=>$larrformData['MinQuotagrid'][0],
											'MaxQuota'=>$larrformData['MaxQuotagrid'][0],
											'UpdDate'=>$larrformData['UpdDate'],
											'UpdUser'=>$larrformData['UpdUser']
								
							);
							$db->insert($table1,$larrtsubjectsoffered);	
		}
	}
	
	public function fninsertSubjectsOffered($formData,$result) {  // function to insert po details
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_subjectsoffered";
			if($formData['IdSubjects'][0]==1000){
				unset($formData['IdSubject']);
				return;
			}
			$countofIdSubjects = count($formData['IdSubjects']);
			for($i=0;$i<$countofIdSubjects;$i++) {
				if ($formData['Branch'][$i]==1000){
					continue;
				}
				$larrtsubjectsoffered = array('IdSemester'=>$result,
											'IdSubject'=>$formData['IdSubjects'][$i],
											'Branch' =>$formData['Branch'][$i],
											'MinQuota'=>$formData['MinQuotagrid'][$i],
											'MaxQuota'=>$formData['MaxQuotagrid'][$i],
											'UpdDate'=>$formData['UpdDate'],
											'UpdUser'=>$formData['UpdUser']
								
							);
							
				$db->insert($table,$larrtsubjectsoffered);	
			}
		}
		
	public function fninsertMultipleBranchCourseOffered($larrformData,$lintidsemester,$count1,$count){
		//echo "Xxxx";exit;
		$db = Zend_Db_Table::getDefaultAdapter();
			$table1 = "tbl_subjectsoffered";
			$table2 = "tbl_subjectmaster";
			$table3 = "tbl_branchofficevenue";
		
			$lstrselect = $this->lobjdb->select()	
								->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"a.IdSubject"))
								->where("a.IdFaculty = ?",$larrformData['IdCollege'])
								->where("a.Active = 1");
		$larrresult = $this->lobjdb->fetchAll($lstrselect);
	
		$countOfCourse = count($larrresult);
		$lstrbranch = $this->lobjdb->select()	
								->from(array("a"=>"tbl_branchofficevenue"),array("key"=>"a.IdBranch","value"=>"a.IdBranch"))
								->where("a.Active = 1")
								->where("a.IdType = 1");
		$larrbranch = $this->lobjdb->fetchAll($lstrbranch);
		$countOfBranch = count($larrbranch);
		
		;
		for($i=0;$i<$countOfCourse;$i++) {
			for($j=0;$j<$countOfBranch;$j++){
				$larrtsubjectsoffered = array('IdSemester'=>$lintidsemester,
											'IdSubject'=>$larrresult[$i]['key'],
											'Branch' =>$larrbranch[$j]['key'],
											'MinQuota'=>$larrformData['MinQuotagrid'][0],
											'MaxQuota'=>$larrformData['MaxQuotagrid'][0],
											'UpdDate'=>$larrformData['UpdDate'],
											'UpdUser'=>$larrformData['UpdUser']
								
							);
							
				$db->insert($table1,$larrtsubjectsoffered);	
			}
		}
			
	}
		
	 public function fnGetFacultyName($IdCollege) { // Function to view the Purchase Order details based on id
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_collegemaster"),array("a.IdCollege","a.CollegeName","a.CollegeCode"))				
		            	->where("a.IdCollege= ?",$IdCollege);	
		return $result = $lobjDbAdpt->fetchRow($select);
		
	}

	public function getSubjectsOfferBySemester($idSemester,$idSubject){
    	
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  
    	  $select = $db->select()
		 				 ->from(array("so"=>$this->_name))		
		 				 ->where("so.IdSemester = ?",$idSemester)
		 				 ->where("so.IdSubject = ?",$idSubject);		 				
		 				 
		  $larrResult = $db->fetchRow($select);
		  return $larrResult;
    }
    
	public function getMultiLandscapeCourseOffer($Landscapes,$search="",$idsemester=""){
		    	
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  
    /*->from(array("subof"=>"tbl_subjectsoffered"),array("MinQuota","MaxQuota"))
								->join(array("sub"=>"tbl_subjectmaster"),"sub.IdSubject = subof.IdSubject",array("sub.IdSubject","SubjectName"=>"sub.BahasaIndonesia","sub.SubCode"))
								->join(array("b"=>"tbl_branchofficevenue"),"b.IdBranch = subof.Branch",array())
								->where("subof.IdSemester = $lintIdSemester");*/	
    	  $lstrSelect = $db->select()
    	  				->distinct()
		 				 ->from(array("ls"=>"tbl_landscapesubject"),array())		
		 				 ->join(array("s"=>"tbl_subjectmaster"),"s.IdSubject=ls.IdSubject ",array('idsub'=>'IdSubject','SubjectName'=>'BahasaIndonesia','SubCode','CreditHours','key'=>'IdSubject','name'=>'BahasaIndonesia'))
		 				 ->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=s.IdFaculty',array('facultyName'=>'ArabicName'))
		 				 ->join(array("subof"=>"tbl_subjectsoffered"),"s.IdSubject = subof.IdSubject and subof.IdSemester = $idsemester")
		 				 ->join(array("ld"=>"tbl_landscape"),"ld.IdLandscape=ls.IdLandscape",array("ProgramDescription"))
		 				 ->group("ls.IdSubject")
		 				 ->order("s.SubCode");
		 				 
		 				 
		 foreach ($Landscapes as $landscape) {
		 	$lstrSelect->orwhere("ls.IdLandscape = ?",$landscape);
		 }		 
		 		 
		 if(isset($search["subject_code"]) && $search["subject_code"]!=''){			 
			 	$lstrSelect->where("s.SubCode LIKE '%".$search["subject_code"]."%'");
		 }
	 	 
	     if(isset($search["subject_name"]) && $search["subject_name"]!=''){			 
			 	$lstrSelect->where("s.SubjectName LIKE '%".$search["subject_name"]."%'");
			 	$lstrSelect->orwhere("s.BahasaIndonesia LIKE '%".$search["subject_name"]."%'");
		 }
			 
 		 if(isset($search["IdSemester"])&& $search["IdSemester"]!=""){			 
		 	$lstrSelect->where("ls.IdSemester = ?",$search["IdSemester"]);
		 }
		
		 
		 $rows = $db->fetchAll($lstrSelect);
		return $rows;
    }   
     
 	public function getMultiBlockLandscapeCourseOffer($Landscapes,$formdata=null,$idsemester=""){
	    	
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	
    	  $lstrSelect = $db->select()
    	  				->distinct()
		 				 ->from(array("ls"=>"tbl_landscapeblocksubject"),array())	
		 				 ->join(array("s"=>"tbl_subjectmaster"),'s.IdSubject=ls.subjectid ',array('idsub'=>'IdSubject','SubjectName'=>'BahasaIndonesia','SubCode','CreditHours','key'=>'IdSubject','name'=>'BahasaIndonesia'))
		 				 ->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=s.IdFaculty',array('facultyName'=>'ArabicName'))			 				
		 				 ->join(array("subof"=>"tbl_subjectsoffered"),"s.IdSubject = subof.IdSubject and subof.IdSemester = $idsemester")
		 				 ->group("ls.subjectid")
		 				 ->order("s.SubCode");
		 				 
		 foreach ($Landscapes as $landscape) {
		 	$lstrSelect->orwhere("(ls.IdLandscape = ?",$landscape);
		 	$lstrSelect->where("ls.type != 2)");
		 }	
		 
		 /*if(isset($formdata["IdSemester"]) && $formdata["IdSemester"]!=''){
		 	$lstrSelect->join(array('lb'=>'tbl_landscapeblocksemester'),'lb.IdLandscape=ls.IdLandscape');
		 	$lstrSelect->where("lb.semesterid = ?",$formdata["IdSemester"]);
		 }*/
		 			 
 		 if(isset($formdata["subject_code"]) && $formdata["subject_code"]!=''){			 
		 	$lstrSelect->where("s.SubCode LIKE '%".$formdata["subject_code"]."%'");
		 }
	
		 if(isset($formdata["subject_name"]) && $formdata["subject_name"]!=''){			 
		 	$lstrSelect->where("s.SubjectName LIKE '%".$formdata["subject_name"]."%'");
		 	$lstrSelect->orwhere("s.BahasaIndonesia LIKE '%".$formdata["subject_name"]."%'");
		 }
		 		
		//echo $lstrSelect;
		$rows = $db->fetchAll($lstrSelect);
		return $rows;
	}  
	       
	public function getMultiLandscapeNotCourseOffer($Landscapes,$search="",$idsemester=""){
		    	
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  
    /*->from(array("subof"=>"tbl_subjectsoffered"),array("MinQuota","MaxQuota"))
								->join(array("sub"=>"tbl_subjectmaster"),"sub.IdSubject = subof.IdSubject",array("sub.IdSubject","SubjectName"=>"sub.BahasaIndonesia","sub.SubCode"))
								->join(array("b"=>"tbl_branchofficevenue"),"b.IdBranch = subof.Branch",array())
								->where("subof.IdSemester = $lintIdSemester");*/	
    	  $lstrSelect = $db->select()
    	  				->distinct()
		 				 ->from(array("ls"=>"tbl_landscapesubject"),array())		
		 				 ->join(array("s"=>"tbl_subjectmaster"),"s.IdSubject=ls.IdSubject ",array('idsub'=>'IdSubject','SubjectName'=>'BahasaIndonesia','SubCode','CreditHours','key'=>'IdSubject','name'=>'BahasaIndonesia'))
		 				 ->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=s.IdFaculty',array('facultyName'=>'ArabicName'))
		 				 ->joinleft(array("subof"=>"tbl_subjectsoffered"),"s.IdSubject = subof.IdSubject and subof.IdSemester = $idsemester")
		 				 ->join(array("ld"=>"tbl_landscape"),"ld.IdLandscape=ls.IdLandscape",array("ProgramDescription"))
		 				 ->group("ls.IdSubject")
		 				 ->order("s.SubCode");
		 				 
		 $strwhere = implode(" OR ",$Landscapes);				 
		 foreach ($Landscapes as $key => $landscape) {
		 	$Landscapes[$key] = "ls.IdLandscape =  $landscape";
		 }	
		 $strwhere = implode(" OR ",$Landscapes);
		 $lstrSelect->where("$strwhere");	 
		 $lstrSelect->where("subof.IdSubject is null ");		 
		 if(isset($search["subject_code"]) && $search["subject_code"]!=''){			 
			 	$lstrSelect->where("s.SubCode LIKE '%".$search["subject_code"]."%'");
		 }
	 	 
	     if(isset($search["subject_name"]) && $search["subject_name"]!=''){			 
			 	$lstrSelect->where("s.SubjectName LIKE '%".$search["subject_name"]."%'");
			 	$lstrSelect->orwhere("s.BahasaIndonesia LIKE '%".$search["subject_name"]."%'");
		 }
			 
 		 if(isset($search["IdSemester"])&& $search["IdSemester"]!=""){			 
		 	$lstrSelect->where("ls.IdSemester = ?",$search["IdSemester"]);
		 }
		
		 
		 $rows = $db->fetchAll($lstrSelect);
		return $rows;
    }        

     	public function getMultiBlockLandscapeNotCourseOffer($Landscapes,$formdata=null,$idsemester=""){
	    	
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	
    	  $lstrSelect = $db->select()
    	  				->distinct()
		 				 ->from(array("ls"=>"tbl_landscapeblocksubject"),array())	
		 				 ->join(array("s"=>"tbl_subjectmaster"),'s.IdSubject=ls.subjectid ',array('idsub'=>'IdSubject','SubjectName'=>'BahasaIndonesia','SubCode','CreditHours','key'=>'IdSubject','name'=>'BahasaIndonesia'))
		 				 ->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=s.IdFaculty',array('facultyName'=>'ArabicName'))			 				
		 				 ->joinleft(array("subof"=>"tbl_subjectsoffered"),"s.IdSubject = subof.IdSubject and subof.IdSemester = $idsemester")
		 				 ->join(array("ld"=>"tbl_landscape"),"ld.IdLandscape=ls.IdLandscape",array("ProgramDescription"))
		 				 ->group("ls.subjectid")
		 				 ->order("s.SubCode");

		 $strwhere = implode(" OR ",$Landscapes);				 
		 foreach ($Landscapes as $key => $landscape) {
		 	$Landscapes[$key] = "ls.IdLandscape =  $landscape";
		 }			 				 
		 
		 $strwhere = implode(" OR ",$Landscapes);
		 $lstrSelect->where("$strwhere");		 	
		 $lstrSelect->where("ls.type != 2");
		 
		 
		 /*if(isset($formdata["IdSemester"]) && $formdata["IdSemester"]!=''){
		 	$lstrSelect->join(array('lb'=>'tbl_landscapeblocksemester'),'lb.IdLandscape=ls.IdLandscape');
		 	$lstrSelect->where("lb.semesterid = ?",$formdata["IdSemester"]);
		 }*/
		 			 
 		 if(isset($formdata["subject_code"]) && $formdata["subject_code"]!=''){			 
		 	$lstrSelect->where("s.SubCode LIKE '%".$formdata["subject_code"]."%'");
		 }
	
		 if(isset($formdata["subject_name"]) && $formdata["subject_name"]!=''){			 
		 	$lstrSelect->where("s.SubjectName LIKE '%".$formdata["subject_name"]."%'");
		 	$lstrSelect->orwhere("s.BahasaIndonesia LIKE '%".$formdata["subject_name"]."%'");
		 }
		 		
		//echo $lstrSelect;exit;
		$rows = $db->fetchAll($lstrSelect);
		return $rows;
	} 
    public function saveAllBranch($data){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table1 = "tbl_subjectsoffered";		
		$lstrbranch = $this->lobjdb->select()	
								->from(array("a"=>"tbl_branchofficevenue"),array("key"=>"a.IdBranch","value"=>"a.IdBranch"))
								->where("a.Active = 1")
								->where("a.IdType = 1");
		$branches = $this->lobjdb->fetchAll($lstrbranch);
		
		foreach($branches as $branch){
			$data["Branch"]=$branch["key"];
			$db->insert($table1,$data);	 
		}	
	}  

	public function unoffered($idsub,$idsem){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table1 = "tbl_subjectsoffered";	
		$this->delete("IdSemester=$idsem AND IdSubject=$idsub");	
	}
}