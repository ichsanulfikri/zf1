<?php
class GeneralSetup_Model_DbTable_Departmentmaster extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_departmentmaster';
	
	
 	public function fngetDepartmentDetails2() { //Function to get the user details
        $result = $this->fetchAll();
        return $result;
     }
     
     public function fngetDepartmentDetails() {
     	$select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => 'tbl_departmentmaster'),array('IdDepartment'))
                ->join(array('b'=>'tbl_collegemaster'),'a.IdCollege  = b.IdCollege')
                ->where('a.Active = 1')
                ->order("a.DepartmentName");
       $result = $this->fetchAll($select);
       return $result->toArray();
     }
     
	public function fngetDepartementmasterDetailsById($IdCollege) {
     	$select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => 'tbl_departmentmaster'),array('IdDepartment'))
                ->join(array('b'=>'tbl_collegemaster'),'a.IdCollege  = b.IdCollege')
                ->where('a.Active = 1')
                ->where('a.IdCollege = ?',$IdCollege)
                ->order("a.DepartmentName");
       $result = $this->fetchAll($select);
       return $result->toArray();
     }
     
	public function fnSearchDepartment($post = array()) { //Function for searching the user details
    	$db = Zend_Db_Table::getDefaultAdapter();
		$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_departmentmaster'),array('IdDepartment'))
			   ->join(array('b'=>'tbl_collegemaster'),'a.IdCollege  = b.IdCollege');
			   
		if(isset($post['field5']) && !empty($post['field5'])){
				$select = $select->where("a.IdCollege = ?",$post['field5']);
			}		   
		$select ->where('a.DepartmentName like "%" ? "%"',$post['field2'])
			   ->where('a.DeptCode like  "%" ? "%"',$post['field3'])
			   ->where('a.ArabicName like  "%" ? "%"',$post['field18'])
			   ->where($field7)
			   ->order("a.DepartmentName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	public function fnSearchUserDepartment($post = array(),$IdCollege) { //Function for searching the user details
    	$db = Zend_Db_Table::getDefaultAdapter();
		$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_departmentmaster'),array('IdDepartment'))
			   ->join(array('b'=>'tbl_collegemaster'),'a.IdCollege  = b.IdCollege');
		if(isset($post['field5']) && !empty($post['field5'])){
				$select = $select->where("a.IdCollege = ?",$post['field5']);
			}		   
		$select->where('a.IdCollege = ?',$IdCollege)
			   ->where('a.DepartmentName like "%" ? "%"',$post['field2'])
			   ->where('a.DeptCode like  "%" ? "%"',$post['field3'])
			   ->where($field7)
			   ->order("a.DepartmentName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	public function fnGetColgBranchList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnaddDepartment($larrformData,$idUniversity,$CodeType)  { //Function for adding the user details to the table
		unset($larrformData['IdStaff']);
		unset($larrformData['FromDate']);
	   	unset($larrformData['ToDate']);
		if($larrformData['DepartmentType'] == '0' || $larrformData['DepartmentType'] == '1') {
			$larrformData['IdCollege'] = $larrformData['IdCollege'];
		} 
		
		$idDept = $this->insert($larrformData);
		if($CodeType == 1){			
			$SemCode = $this->fnGenerateCode($idUniversity,$idDept,$larrformData['ShortName']);
			$formData1['DeptCode'] = $SemCode;
			$this->fnupdateDepartment($formData1,$idDept);
		}
		return $idDept;
	}
//////////////////////////////////////////////////////////////////////////////	
	
	public function fnaddCollegeDepartment($larrformData,$idUniversity,$CodeType)  { //Function for adding the user details to the table

		$idDept = $this->insert($larrformData);
		if($CodeType == 1){			
			$SemCode = $this->fnGenerateCode($idUniversity,$idDept,$larrformData['ShortName']);
			$formData1['DeptCode'] = $SemCode;
			$this->fnupdateDepartment($formData1,$idDept);
		}
		return $idDept;
	}
	
/////////////////////////////////////////	
	function fnGenerateCode($idUniversity,$idDept,$Shortname){
		    $page	=  "Department";
			$db 	= 	Zend_Db_Table::getDefaultAdapter();			
			$select =   $db->select()
					->  from('tbl_config')
					->	where('idUniversity  = ?',$idUniversity);				 
			$result = 	$db->fetchRow($select);		
			$sepr	=	$result[$page.'Separator'];
			$str	=	$page."CodeField";		
			$select =  $db->select()
						 		 -> from(array('a'=>'tbl_departmentmaster'))
						 		 -> join(array('b'=>'tbl_collegemaster'),'b.IdCollege=a.IdCollege','b.ShortName AS CShortName')
						 		 ->	where('a.IdDepartment  = ?',$idDept); 	  				 
			$resultCollage = $db->fetchRow($select);		  
					  
			for($i=1;$i<=4;$i++){
				$check = $result[$str.$i];
				switch ($check){
					case 'Uniqueid':
					  $code		= $idDept;
					  break;
					case 'ShortName':								
					  $code	    = $Shortname;
					  break;
					case 'CollegeShortName':					 	
					  $code		   = $resultCollage['CShortName'];
					  break;
					case 'Year':							
					  $code		   = date('Y');
					  break;					  
					default:
					  break;
				}
				if($i == 1) $accCode 	 =  $code;
				else 		$accCode	.=	$sepr.$code;
			}	 		
		return $accCode;			
	}
	public function fnGetUniversityList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_universitymaster"),array("key"=>"a.IdUniversity","value"=>"a.Univ_Name"))
		 				 ->where("a.Active = 1")
		 				  ->order("a.Univ_Name");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetCollegeList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 0")
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetUserCollegeList($IdCollege){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 0")
		 				  ->where('a.IdCollege = ?',$IdCollege)
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
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
	
	
	public function fnGetUserBranchList($lintidCollege){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 1")
		 				 ->where("a.Idhead= ?",$lintidCollege)
		 				 ->where("a.Active = 1")
		 				 ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetBranchListofCollege($lintidCollege){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_collegemaster"),array("key"=>"a.IdCollege","value"=>"a.CollegeName"))
		 				 ->where("a.CollegeType = 1")
		 				 ->where("a.Idhead= ?",$lintidCollege)
		 				 ->where("a.Active = 1")
		 				  ->order("a.CollegeName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
		
    public function fnviewDepartment($lintidepartment) { //Function for the view user 
    	//echo $lintidepartment;die();
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_departmentmaster"),array("a.*"))				
		            	->where("a.IdDepartment= ?",$lintidepartment);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
    public function fnupdateDepartment($larrformData,$lintIdDepartment) { //Function for updating the user
    	unset($larrformData['IdStaff']);
		unset($larrformData['FromDate']);
	   	unset($larrformData['ToDate']);
	if($larrformData['DepartmentType'] == '0' || $larrformData['DepartmentType'] == '1') {
			$larrformData['IdCollege'] = $larrformData['IdCollege'];
		} 
	$where = 'IdDepartment = '.$lintIdDepartment;
	$this->update($larrformData,$where);
    }
    
	public function fnGetCollege($lstridbranch){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_collegemaster"),array("a.Idhead"))
       								->where('a.IdCollege = ?',$lstridbranch);
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	} 
	
	public function fnupdateDepartmentMaster($formData,$lintIdCollege)
	{
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	    $larrdeplist['DepartmentName'] = $formData['CollegeName'];
	    $larrdeplist['ArabicName'] = $formData['ArabicName'];
	    $larrdeplist['ShortName'] = $formData['ShortName'];
	    $larrdeplist['Active'] = $formData['Active'];
	    $larrdeplist['UpdDate'] = $formData['UpdDate'];
	    $larrdeplist['UpdUser'] = $formData['UpdUser'];
		$lstrwhere = "IdCollege = $lintIdCollege";
		$lobjDbAdpt->update('tbl_departmentmaster',$larrdeplist,$lstrwhere);
	}
	
	public function fngetDeptname($DeptName) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_departmentmaster"),array("c.*"))			
		            	->where("c.DepartmentName= ?",$DeptName);	
		            
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
	public function fngetDeptCode($DeptCode) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_departmentmaster"),array("c.*"))			
		            	->where("c.DeptCode= ?",$DeptCode);	
		           
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
	
}