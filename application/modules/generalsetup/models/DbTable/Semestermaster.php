<?php 
class GeneralSetup_Model_DbTable_Semestermaster extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_semestermaster';
    protected $_primary = 'IdSemesterMaster';
    
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
	public function fnGetSemestermaster($semester_id){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"))
		->where('a.IdSemesterMaster = ?',$semester_id)
		->order("a.SemesterMainName Desc");
		$larrResult = $this->lobjDbAdpt->fetchRow($lstrSelect);
		
		if($larrResult){
			return $larrResult;
		}else{
			return null;
		}
	}
	
    public function fngetSemestermainDetails($IdSemesterMaster="") { //Function to get the user details
        if(trim($IdSemesterMaster) == "") {
     			
     			$select = $this->select()
     			        ->setIntegrityCheck(false)
     			        ->from(array('a'=>$this->_name))
             			->join(array('acy' => 'tbl_academic_year'),'acy.ay_id = a.idacadyear',array("academicYear"=>'ay_code', 'ay_id'=>'ay_id'))
             			->order('acy.ay_code DESC')
             			->order('a.SemesterCountType DESC')
     			        ->order('a.SemesterFunctionType DESC');
     			
     			$result = $this->fetchAll($select);
        }else{
          
            $select = $this->select()
                      ->setIntegrityCheck(false)
                      ->from(array('a'=>$this->_name))
                      ->join(array('acy' => 'tbl_academic_year'),'acy.ay_id = a.idacadyear',array("academicYear"=>'ay_code', 'ay_id'=>'ay_id'))
                      ->where("a.IdSemesterMaster = $IdSemesterMaster")
                      ->order('a.SemesterMainName');
            
            $result = $this->fetchAll($select);
        }
        
        return $result->toArray();
     }
    
    public function fnaddSemester($formData) { //Function for adding the University details to the table
    	unset($formData['SemesterCode']);
    	unset($formData['StudentIntake']);
    	unset($formData['SemesterStartDate']);
    	unset($formData['SemesterEndDate']);
    	unset($formData['Program']);
    	unset($formData['SemesterStatus']);
    	unset($formData['Save']);        
    	$this->insert($formData);
    	$insertId = $this->lobjDbAdpt->lastInsertId('tbl_semestermaster','IdSemesterMaster');	
	    return $insertId;
	}
    
    public function fnupdateSemester($formData,$lintIdSemesterMaster) { //Function for updating the university
    	unset($formData['SemesterCode']);
    	unset($formData['IdSemester']);
    	unset($formData['StudentIntake']);
    	unset($formData['SemesterStartDate']);
    	unset($formData['SemesterEndDate']);
    	unset($formData['Program']);
    	unset($formData['SemesterStatus']);
    	unset($formData['Save']);
		$where = 'IdSemesterMaster = '.$lintIdSemesterMaster;
		$this->update($formData,$where);
    }
    
	public function fnSearchSemester($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_semestermaster'),array('IdSemesterMaster'))
			   ->where('a.SemesterMainName  like "%" ? "%"',$post['field3'])
			   ->where($field7);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	/**
	 * 
	 * Method to get all list of semester main to be diaplyed in a dropdown
	 */
	public function fnGetSemestermasterList(){
		$lstrSelect = $this->lobjDbAdpt->select()
 				 ->from(array("a"=>"tbl_semestermaster"),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName"))
 				 ->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
				->order("a.SemesterCountType DESC")
				->order("a.SemesterFunctionType DESC");
		
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
	}
	
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->where('IdSemesterMaster = ?', $id);
					
				$row = $db->fetchRow($select);
		}else{
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->order('SemesterMainStartDate DESC');
								
			$row = $db->fetchAll($select);
		}
		
		if(!$row){
			throw new Exception("There is No Data");
		}
		
		return $row;
	}
	
	
	/* List Countable Semester */
	public function getCountableSemester(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>'tbl_semestermaster'),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName"))
						 ->where('IsCountable=1');
		//echo $select;
		
		$row = $db->fetchAll($select);
		
		return $row;
	}
	
	
	/* Regitration Semester */
	public function getSemesterCourseRegistration($id_semester){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					     ->from(array('sm'=>'tbl_semestermaster'))						
						 ->join(array('ac'=>'tbl_activity_calender'),'ac.IdSemesterMain = sm.IdSemesterMaster')
						 ->where('CURDATE()	BETWEEN ac.StartDate AND ac.EndDate')
						 ->where('idActivity=18')	
						 ->where('IdSemesterMaster = ?',$id_semester);
		
		$row = $db->fetchRow($select);
		
		return $row;
	}
	
	
	public function getSemesterList($academicYearId=null){
		
		$lstrSelect = $this->lobjDbAdpt->select()
					->from(array("a"=>"tbl_semestermaster"))
					->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
					->order("a.SemesterCountType DESC")
					->order("a.SemesterFunctionType DESC");
	
		if($academicYearId!=null){
			$lstrSelect->where('a.idacadyear=?',$academicYearId);
		}
		
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	
		return $larrResult;
	}
		

}
?>