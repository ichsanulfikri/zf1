<?php
class GeneralSetup_Model_DbTable_Schemesetup extends Zend_Db_Table_Abstract//model class for schemesetup module
{
	protected $_name = 'tbl_scheme';
	private $lobjDbAdpt;
	public function init(){
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fnSearchscheme($post = array()){ //function to search a particular scheme details

		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_scheme"),array("a.*"));
		if(isset($post['field2']) && !empty($post['field2']) ){
			$lstrSelect = $lstrSelect->where("a.EnglishDescription = ?",$post['field2']);
		}
		if(isset($post['field3']) && !empty($post['field3']) ){
			$lstrSelect = $lstrSelect->where("a.ArabicDescription  LIKE ?", '%'.$post['field3'].'%');
		}
		if(isset($post['field4']) && !empty($post['field4']) ){
			$lstrSelect = $lstrSelect->where("a.SchemeCode  = ?",$post['field4']);
		}
		$lstrSelect	->where("a.Active = ".$post["field7"]);
			
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fnGetSchemeDetails(){//function to display all schemesetup details in list
		 
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_scheme"),array("a.*"))
		->where("a.Active = 1");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fnGetMode(){//function to get a type of mode
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionDesc"))
		->join(array("b"=>"tbl_definationtypems"),"a.idDefType = b.idDefType AND defTypeDesc='Learning Mode'")
		->where("a.Status = 1")
		->order("a.DefinitionDesc");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	function fnInsertToDb($formData){//function to insert data to database
		$db = Zend_Db_Table::getDefaultAdapter();
		unset ( $formData ['Save'] );
		$table = "tbl_scheme";
		$db->insert($table,$formData);
	}

	public  function fnupdateSchemeDetails($formData,$IdScheme){//function to update data
		unset ( $formData ['Save'] );
		$where = 'IdScheme = '.$IdScheme;
		$this->update($formData,$where);
	}
	public function fnViewSchemeDetails($IdScheme){ //function to find the data to populate in a page of a selected english description to edit.

		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
		->from(array('a' => 'tbl_scheme'),array('a.*'))
		->where('a.IdScheme = '.$IdScheme);
		$result = $db->fetchRow($select);
		return $result;
	}



	public function fngetSchemes() {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_scheme"),array("key"=>"a.IdScheme","value"=>"a.EnglishDescription"));
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
     public function getSchemeByFaculty($idfaculty){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		if($idfaculty == '0'){
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_scheme"),array("key"=>"a.IdScheme","name"=>"a.EnglishDescription"));

		}else{
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_scheme"),array("key"=>"a.IdScheme","name"=>"a.EnglishDescription"))
			->joinLeft(array("b"=>"tbl_program_scheme"),"a.IdScheme = b.IdScheme",array(""))
			->joinLeft(array('c' => 'tbl_program'),'b.IdProgram = c.IdProgram',array(""))
			->where('c.IdCollege = '.$idfaculty)
			->group('a.EnglishDescription');
		}
		
		
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	

}
?>