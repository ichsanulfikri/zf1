<?php
class GeneralSetup_Model_DbTable_Programscheme extends Zend_Db_Table_Abstract {
	protected $_name = 'tbl_program_scheme';
	private $lobjDbAdpt;

	public function init() {
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fnaddProgramscheme($data) { //Function for adding the University details to the table
		$this->insert($data);
	}

	public function fngetprogramsscheme($programId){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a" => "tbl_program_scheme"), array("a.*"))
		->joinLeft(array("b" => "tbl_scheme"),'a.IdScheme = b.IdScheme', array("b.EnglishDescription"))
		->where("a.IdProgram = ?", $programId);
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fndelProgramscheme($lintIdProgram){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_program_scheme";
		$where = "IdProgram = '" . $lintIdProgram . "'";
		$db->delete($table,$where);
	}


	public function fngetprogramsschemelist($programId){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a" => "tbl_program_scheme"), array(""))
		->joinLeft(array("b" => "tbl_scheme"),'a.IdScheme = b.IdScheme', array('key' => "b.IdScheme","name" => "b.EnglishDescription"))
		->where("a.IdProgram = ?", $programId);
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetprogramsschemelistforfaculty($IdCollege){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("c" => "tbl_program"),array())
		->joinLeft(array("a" => "tbl_program_scheme"),'a.IdProgram = c.IdProgram' ,array(""))
		->joinLeft(array("b" => "tbl_scheme"),'a.IdScheme = b.IdScheme', array('key' => "b.IdScheme","name" => "b.EnglishDescription"))
		->where("c.IdCollege = ?", $IdCollege)
		->where("c.Active = ?",1)
		->group('b.IdScheme');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fngetschemelist($programId){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a" => "tbl_program_scheme"), array(""))
		->joinLeft(array("b" => "tbl_scheme"),'a.IdScheme = b.IdScheme', array('key' => "b.IdScheme","value" => "b.EnglishDescription"))
		->where("a.IdProgram = ?", $programId);
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

}

?>
