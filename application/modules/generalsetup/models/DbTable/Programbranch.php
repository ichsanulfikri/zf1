<?php 
class GeneralSetup_Model_DbTable_Programbranch extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_programbranchlink';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
    public function fnaddProgrambranch($formData) { //Function for adding the Program Branch details to the table
			unset ( $formData ['Save'] );
			unset ( $formData ['Back'] );
			unset ( $formData ['CollegeName'] );
			unset ( $formData ['IdProgramBranchLink']);
    		$this->insert($formData);
	}
    
     public function fngetProgrambranchDetails() { //Function to get the Program Branch details
 		$select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => 'tbl_programbranchlink'),array('IdProgramBranchLink'))
                ->join(array('b'=>'tbl_program'),'a.IdProgram  = b.IdProgram')
                ->join(array('c'=>'tbl_collegemaster'),'a.IdCollege = c.IdCollege')
                ->group('a.IdProgram');
       $result = $this->fetchAll($select);
       return $result->toArray();
     }
     
     public function fnEditProgrambranchDetails($IdProgramBranchLink) { //Function to get the Program Branch details
 		$select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => 'tbl_programbranchlink'),array('IdProgramBranchLink'))
                ->join(array('b'=>'tbl_program'),'a.IdProgram  = b.IdProgram')
                ->join(array('c'=>'tbl_collegemaster'),'a.IdCollege = c.IdCollege')
                ->where('a.IdProgramBranchLink = ?',$IdProgramBranchLink);
       $result = $this->fetchAll($select);
       return $result->toArray();
     }
     

     
	public function fnSearchProgrambranch($post = array()) { //Function for searching the user details
    	$field7 = "Active = ".$post["field7"];
		 $select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_collegemaster'),array('IdCollege'))
			   ->where('a.CollegeName  like "%" ? "%"',$post['field3'])
			   ->where('a.ShortName like  "%" ? "%"',$post['field2'])
			   ->where('a.Email like  "%" ? "%"',$post['field4']);
			   
		if(isset($post['field5']) && !empty($post['field5'])){
			$select = $select->where("a.AffiliatedTo = ?",$post['field5']);
			}	   
		$select ->where($field7)
					->order("a.CollegeName");
		$result = $this->fetchAll($select);	
		return $result->toArray();
	}
	public function fnSearchUserProgrambranch($post = array(),$IdCollege) { //Function for searching the user details
    	$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_collegemaster'),array('IdCollege'))
			   ->where('a.CollegeName  like "%" ? "%"',$post['field3'])
			   ->where('a.ShortName like  "%" ? "%"',$post['field2'])
			   ->where('a.Email like  "%" ? "%"',$post['field4']);
			   
			if(isset($post['field5']) && !empty($post['field5'])){
				$select = $select->where("a.AffiliatedTo = ?",$post['field5']);
			}	   
			 $select ->where('a.IdCollege = ?',$IdCollege)
			   ->where($field7)
			   ->order("a.CollegeName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
     public function fnGetBranchLinkDetails($IdCollege) { //Function to get the Program Branch details
 		$select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => 'tbl_programbranchlink'),array('IdProgramBranchLink'))
                ->join(array('b'=>'tbl_program'),'a.IdProgram  = b.IdProgram')
                ->join(array('c'=>'tbl_collegemaster'),'a.IdCollege = c.IdCollege')
                ->join(array('d'=>'tbl_semester'),'a.IdSemester = d.IdSemester')
                ->join(array('e'=>'tbl_semestermaster'),'d.Semester = e.IdSemesterMaster')
                ->where('a.	IdCollege = ?',$IdCollege);
       $result = $this->fetchAll($select);
       return $result->toArray();
     }
     
	   public function fnGetCollegeName($IdCollege) { 
	     	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()	
							->from(array('a' => 'tbl_collegemaster'),array('a.*'))
							->where("a.IdCollege  = $IdCollege");
			$result = $db->fetchRow($sql);
			return $result;
		}
		
    
		
     public function fnGetUniversityName(){
   			$db = Zend_Db_Table::getDefaultAdapter();
			$sql =$db->select()->from('tbl_universitymaster')->order('IdUniversity');
			$result = $db->fetchAll($sql);
			return $result;
   		}
		
	   public function fnCheckExistingLink($idcollege,$idprogram) { 
	     	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()	
							->from(array('a' => 'tbl_programbranchlink'),array('a.*'))
							->where("a.IdCollege  = $idcollege")
							->where("a.IdProgram  = $idprogram");
			$result = $db->fetchRow($sql);
			return $result;
		}
		
	   public function fnEditBranckLinkDetails($idbranchlink) { 
	     	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()	
							->from(array('a' => 'tbl_programbranchlink'),array('a.*'))
							->where("a.IdProgramBranchLink  = '$idbranchlink'");
			$result = $db->fetchRow($sql);
			return $result;
		}
		
	  	public function fnUpdateProgrambranch($lintidbranchlink,$formData) { //Function for update the Unit of Measurement details
			unset ( $formData ['Save'] );
			unset ( $formData ['Back'] );
			unset ( $formData ['CollegeName'] );
			$where = 'IdProgramBranchLink  = '.$lintidbranchlink;
			$this->update($formData,$where);
    	}
    	
		public function fnGetProgrambranchlist($lintidCollege){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_programbranchlink"),array("key"=>"a.IdProgram","value"=>"b.ProgramName"))
					 				 ->join(array('b'=>'tbl_program'),'a.IdProgram  = b.IdProgram')
					 				 ->where("a.IdCollege  = ?",$lintidCollege)
					 				 ->where("a.Active = 1");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

}
?>