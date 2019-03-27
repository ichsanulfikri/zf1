<?php 
class Workflow_Model_DbTable_WorkflowSetup extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'R_FLOW_FORM';

	public function fnGetRefWorkflow($idform){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("Status"=>"a.sts_disposisi","Letter"=>"a.no_surat","dtLetter"=>"a.tgl_surat","To"=>"to.NAMA_UNIT","From"=>"f.NAMA_UNIT","dtArrival"=>"a.dt_arrival","Subject"=>"a.kd_form") )
		->join(array("to"=>"R_UNIT"),"a.kd_unit_dest=to.unit_id")
		->joinLeft(array("f"=>"R_UNIT"),"a.kd_unit_from=f.unit_id")
		->where("a.unit_id = ?",$idunit)
		->where("a.sts_disposisi =?",$status)
		->where("TRIM(a.no_surat)!=''")
		->order("a.dt_arrival");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
}
?>