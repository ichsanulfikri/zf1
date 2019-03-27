<?php

class GeneralSetup_Model_DbTable_LandscapeBlockSemester extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_landscapeblocksemester';
    
	public function init()
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
	}
	
    public function getlandscapeblockBySem($idlandscape,$semester){
    	
     	$select = $this->db->select()
		 				   ->from(array("lbs"=>$this->_name))
		 				   ->joinLeft(array('lb'=>'tbl_landscapeblock'),'lb.idblock=lbs.blockid')
		 				   ->where("lbs.idlandscape = ?",$idlandscape)
		 				   ->where("lbs.semesterid = ?",$semester);
		$larrResult = $this->db->fetchAll($select);
		return $larrResult;
     	
    }
    
	public function addData($data)
    {    	     
        $id = $this->insert($data);        
        return $id;
    }
    
    public function checkBlockSemester($idblock,$idsemester,$idlandscape){
     	$select = $this->db->select()
		 				   ->from(array("lbs"=>$this->_name))
		 				   ->where("lbs.idlandscape = ?",$idlandscape)
		 				   ->where("lbs.semesterid = ?",$idsemester)
		 				   ->where("lbs.blockid= ?",$idblock);
		$larrResult = $this->db->fetchAll($select);
		return $larrResult;	
    }    
}

?>