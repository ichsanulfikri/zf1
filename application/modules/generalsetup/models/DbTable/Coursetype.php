<?php 
class GeneralSetup_Model_DbTable_Coursetype extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_coursetype';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	public function fngetCourseTypeDetails() { //Function to get the user details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("ct"=>"tbl_coursetype"),array("ct.*"))
       								->where("ct.Active = 1")
       								->order("ct.CourseType");
       					//echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
     }
	public function fnSearchCourseType($post = array()) {	
				
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$field7 = "ct.Active = ".$post["field7"];
	  $lstrSelect = $lobjDbAdpt->select()
       								->from(array("ct"=>"tbl_coursetype"),array("ct.*"))
       								->where('ct.CourseType like "%" ? "%"',$post['field2'])
       								->where('ct.Bahasaindonesia like "%" ? "%"',$post['field3'])      								
       								->where("ct.Active = ".$post["field7"])
       								->where('ct.MandatoryCreditHrs like "%" ? "%"',$post["field14"])
       								->where("ct.Project = ".$post["field21"])
       								//->order("ct.IdCourseType");
       								->order("ct.CourseType");
       $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
    public function fnaddCoursetype($larrformData) { //Function for adding the University details to the table    	   	
   		$this->insert($larrformData);
	}
	
	
	public function fnviewCourseTypeDtls($lintIdCourseType) { //Function for the view user 
    	
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("ct"=>"tbl_coursetype"),array("ct.*"))
						->where("ct.IdCourseType = ?",$lintIdCourseType);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
 	public function fnupdateCourseTypeDtls($lintIdCourseType,$larrformData) { //Function for updating the user
 	$where = 'IdCourseType = '.$lintIdCourseType;
	$this->update($larrformData,$where);
	}
	
	
}
?>