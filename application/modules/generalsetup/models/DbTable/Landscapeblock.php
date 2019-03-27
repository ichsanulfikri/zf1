<?php
class GeneralSetup_Model_DbTable_Landscapeblock extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_landscapeblock';
    private $lobjDbAdpt;

	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

    public function fnaddlandscapeblock($formData) { //Function for adding the University details to the table.
    		 $count = count($formData['semesteridnamegrid']);
    		 for($i = 0;$i<$count;$i++) {
    		$data = array('idlandscape' =>$formData['IdLandscape'],
    					  'block' => $formData['blocknamegrid'][$i],
    					  'semesterid' => $formData['semesteridnamegrid'][$i]);
			 $this->insert($data);
    		 }
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
			return $lobjdb->lastInsertId();
	}


	/**
      * Function to get the landscape BLOCK
      * @author: Vipul
      */
	public function getlandscapeblock($lid){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_landscapeblock"))
		 				 ->where("a.idlandscape = ?",$lid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }

	/**
      * Function to INSERT the landscape BLOCK
      * @author: Vipul
      */
	public function insertlandscapeblock($formData){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$data = array('idlandscape' =>$formData['idlandscape'],
    					  'block' => $formData['block'],
    					  'blockname' => $formData['blockname'],
    					  'CreditHours' => $formData['CreditHours']);
		$this->insert($data);
		$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
     }


     /**
      * Function to get the landscape BLOCK SEMESTER
      * @author: Vipul
      */
	public function getlandscapeblocksemester($lid){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_landscapeblocksemester"))
		 				 ->where("a.IdLandscape = ?",$lid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }

	/**
      * Function to INSERT the landscape BLOCK SEMESTER
      * @author: Vipul
      */
	public function insertlandscapeblocksemester($formData){
     	   $db = Zend_Db_Table::getDefaultAdapter();
		   $table = "tbl_landscapeblocksemester";
		   $data = array('IdLandscape' => $formData['IdLandscape'],
						 'blockid' => $formData['blockid'],
						 'semesterid' => $formData['semesterid']
						);
		   $db->insert($table,$data);
     }

 	/**
      * Function to get the landscape BLOCK SUBJECT
      * @author: Vipul
      */
	public function getlandscapeblocksubject($lid){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>"tbl_landscapeblocksubject"))
		 				 ->where("a.IdLandscape = ?",$lid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }


     /**
      * Function to INSERT the landscape BLOCK SUBJECT
      * @author: Vipul
      */
	public function fnaddlandscapeBlockSubject($formData){
     	   $db = Zend_Db_Table::getDefaultAdapter();
		   $table = "tbl_landscapeblocksubject";
		   $data = array('IdLandscape' => $formData['IdLandscape'],
						 'blockid' => $formData['blockid'],
						 'subjectid' => $formData['subjectid'],
						 'coursetypeid' => $formData['coursetypeid']
						);
		   $db->insert($table,$data);
     }

/* Start Yati */
     
	public function addData($data)
    {    	     
        $id = $this->insert($data);        
        return $id;
    }
    
	public function updateData($data,$id){
		 $this->update($data,'idblock 	 = '. (int)$id);
	}
	
	public function deleteData($id){		
	  $this->delete('idblock 	 =' . (int)$id);
	}
    
    public function getData($idBlock){
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  $lstrSelect = $db->select()
		 				 ->from(array("lb"=>"tbl_landscapeblock"))
		 				 ->joinLeft(array('lbs'=>'tbl_landscapeblocksemester'),'lbs.blockid=lb.idblock',array('semesterid'))
		 				 ->where("lb.idblock = ?",$idBlock);
		  $larrResult = $db->fetchRow($lstrSelect);
		  return $larrResult;
    }
    
	public function getBlockBySemester($lid,$semester){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("lb"=>"tbl_landscapeblock"))
		 				 ->where("lb.idlandscape = ?",$lid)
		 				 ->where("lb.semester = ?",$semester);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
     }
     
     public function getBlockByLandscape ($idLandscape){
    	  $db = Zend_Db_Table::getDefaultAdapter();
    	  $lstrSelect = $db->select()
		 				 ->from(array("lb"=>"tbl_landscapeblock"))
		 				 ->joinLeft(array('lbs'=>'tbl_landscapeblocksemester'),'lbs.blockid=lb.idblock',array('semesterid'))
		 				 ->where("lb.idlandscape = ?",$idLandscape);
		  $larrResult = $db->fetchAll($lstrSelect);
		  return $larrResult;
    }
     
	public function getBlockSemesterLevel($lid,$semester_level,$block_level){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("lb"=>"tbl_landscapeblock"))
		 				 ->where("lb.idlandscape = ?",$lid)
		 				 ->where("lb.semester = ?",$semester_level)
		 				 ->where("lb.block = ?",$block_level)
		 				 ->order("lb.semester")
		 				 ->order("lb.block");
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
     }
     
	public function getBlockByLandscapeBlockNo($lid,$block_no){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("lb"=>"tbl_landscapeblock"))
		 				 ->where("lb.idlandscape = ?",$lid)
		 				 ->where("lb.block = ?",$block_no);
		 				 //echo $lstrSelect;
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;		
		
	}     

}
?>