<?php
class GeneralSetup_Model_DbTable_Subjectcategory extends Zend_Db_Table { //Model Class for Subject Category
	protected $_name = 'tbl_subjectcategory';
	private $lobjDbAdpt;
    
public function init()
{
	    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
}
// Function to get all the details of Subject Category
public function fnGetSubjectcategory()
{
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()		 				 
								 ->from(array("a"=>"tbl_subjectcategory"),array("a.*"))								 
		 				 		 ->where("a.Active = 1");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
}   
// Function to get all the Subject Category Names
public function fngetSubjectCategoryList()
{
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()		 				 
								 ->from(array("a"=>"tbl_subjectcategory"),array("key"=>"a.IdSubjectCategory","value"=>"a.SubjectCategoryName"));
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
}


// Function to Search all the details of Subject Category
public function fnSearchSubjectCategory($post = array()) 
{ 
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $lstrSelect = $lobjDbAdpt->select()		 				 
								 ->from(array("a"=>"tbl_subjectcategory"));								 
		 				 		 //->where("a.Active = 1"); 
								 
		if(isset($post['field5']) && !empty($post['field5']) ){
				$lstrSelect = $lstrSelect->where("a.IdSubjectCategory = ?",$post['field5']);
		}	
			
		$lstrSelect	->where('a.Description like "%" ? "%"',$post['field3'])
		            ->where("a.Active = ".$post["field7"]);				
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		    
		return $larrResult;
}	
// Function to add Subject Category
public function fnaddsubjectcategory($formData) 
{
   	$this->insert($formData);
} 	    
// Function to View the Subject Category	
public function fnViewsubjectcategory($IdAccount) 
{
    	$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array('a' => 'tbl_subjectcategory'),array('a.*'))
                ->where('a.IdSubjectCategory = ?',$IdAccount);
               
		$result = $db->fetchRow($select);	
		return $result;
}
 // Function to update Subject Category   
public function fnupdatesubjectcategory($formData,$lintIdAccount) 
{ 
    	unset ( $formData ['Save'] );
    	$where = 'IdSubjectCategory = '.$lintIdAccount;
		$this->update($formData,$where);
}   	
}