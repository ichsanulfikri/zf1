<?php
class GeneralSetup_Model_DbTable_Subjectdetails extends Zend_Db_Table {
	protected $_name = 'tbl_subjectdetails';
	
	public function init() {
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();		
	}
	
	public function fngetsubjectdetailsbysub($IdSubject) {
		$result = $this->fetchAll("IdSubject = ".$IdSubject);
		return $result;
	}
	
	public function fnaddsubjectdetail($data) {
		$this->insert($data);
	}
	
	public function fndeletesubjectdetails($IdSubject) {
		$where = $this->lobjDbAdpt->quoteInto('IdSubject = ?', $IdSubject);
		$this->lobjDbAdpt->delete($this->_name, $where);
	}
}