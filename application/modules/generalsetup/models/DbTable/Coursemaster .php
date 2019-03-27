<?php 
class GeneralSetup_Model_DbTable_Coursemaster extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_coursemaster';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
     public function fngetCoursemasterDetails() { //Function to get the user details
        $result = $this->fetchAll();
        return $result;
     }
     
	public function fnGetCourseList(){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_coursemaster"),array("key"=>"a.IdCoursemaster","value"=>"CourseName"))
				 				 ->order("a.CourseName");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
    
    public function fnaddCourse($formData) { //Function for adding the University details to the table
			$this->insert($formData);
	}
    
    public function fnupdateCourse($formData,$lintIdCoursemaster) { //Function for updating the university
    	unset ( $formData ['Save'] );
		$where = 'IdCoursemaster = '.$lintIdCoursemaster;
		$this->update($formData,$where);
    }
    
	public function fnSearchCourse($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_coursemaster'),array('IdCoursemaster'))
			   ->where('a.CourseName  like "%" ? "%"',$post['field3'])
			   ->where($field7)
			   ->order("a.CourseName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

}
?>