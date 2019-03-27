<?php
class GeneralSetup_Model_DbTable_IntakeBranchMapping extends Zend_Db_Table {
	protected $_name = "tbl_intake_branch_mapping";
	private $lobjDbAdpt;
	private $lobjbranch;

	/**
	 *
	 * @see Zend_Db_Table_Abstract::init()
	 */
	public function init() {
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$this->lobjbranch = new GeneralSetup_Model_DbTable_Branchofficevenue();

	}

	public function fnaddIntakeMapping($formData) {
		if($formData['IdBranch'] == "all") {
			$larrresult = $this->lobjbranch->fnGetBranchList();
			foreach($larrresult as $branch) {
				$formData['IdBranch'] = $branch['id'];
				$lboolresult = $this->fncheckmappingexists($formData['IdProgram'], $formData['IdBranch'], $formData['IdIntake']);
				if($lboolresult) {
					$this->insert($formData);
				}
			}
		}
		else {
			$lboolresult = $this->fncheckmappingexists($formData['IdProgram'], $formData['IdBranch'], $formData['IdIntake']);
			if($lboolresult) {
				$this->insert($formData);
			}
		}

	}

	private function fncheckmappingexists($IdProgram,$IdBranch,$IdIntake) {
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("id"=>"a.IdBranch"))
		->where("a.IdProgram = $IdProgram")
		->where("a.IdBranch = $IdBranch")
		->where("a.IdIntake = $IdIntake");
		$larrresult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return (count($larrresult) == 0);
	}

	public function fngetIntakeMappingDetails($IdIntake) {
		$lstrSelect = $this->select()
		->setIntegrityCheck(false)
		->from(array("a"=>"tbl_intake_branch_mapping"))
		->join(array('b' => 'tbl_branchofficevenue'),'a.IdBranch =b.IdBranch',array('b.BranchName as BranchName','b.IdBranch as IdBranch'))
		->join(array('c' => 'tbl_program'),'a.IdProgram =c.IdProgram',array('c.ProgramName as Program','c.IdProgram as IdProgram'))
		->where("a.IdIntake = $IdIntake");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	/**
	 *
	 * Delete all the mapping based in intake id
	 * @param unknown_type $IdIntake
	 */
	public function fndeleteIntakeMappings($IdIntake) {
		$where = $this->lobjDbAdpt->quoteInto('IdIntake = ?', $IdIntake);
		$this->lobjDbAdpt->delete($this->_name, $where);
	}

	public function fnDeleteIntakeDetailById($Id) {
		$where = $this->lobjDbAdpt->quoteInto('Id = ?', $Id);
		$this->lobjDbAdpt->delete($this->_name, $where);
	}


}