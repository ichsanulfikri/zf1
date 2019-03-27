<?php 
class Workflow_Model_DbTable_Myjob extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'T_FLOW_FORM';
	
	public function fnGetInbox($idunit,$status){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("Status"=>"a.sts_disposisi","Letter"=>"a.no_surat","dtLetter"=>"a.tgl_surat","To"=>"to.NAMA_UNIT","From"=>"f.NAMA_UNIT","dtArrival"=>"a.dt_arrival","Subject"=>"a.kd_form","ivent"=>"a.no_id") )
		->join(array("to"=>"R_UNIT"),"a.unit_id=to.unit_id")
		->joinLeft(array("f"=>"R_UNIT"),"a.from_unit=f.unit_id")
		->where("a.unit_id = ?",$idunit)
		->where("a.sts_disposisi =?",$status)
		->where("TRIM(a.no_surat)!=''")
		->order("a.dt_arrival");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetOutbox($idunit,$status){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("Status"=>"a.sts_disposisi","Letter"=>"a.no_surat","dtLetter"=>"a.tgl_surat","To"=>"to.NAMA_UNIT","From"=>"f.NAMA_UNIT","dtArrival"=>"a.dt_arrival","Subject"=>"a.kd_form") )
		->join(array("to"=>"R_UNIT"),"a.unit_id=to.unit_id")
		->joinLeft(array("f"=>"R_UNIT"),"a.from_unit=f.unit_id")
		->where("a.from_unit = ?",$idunit)
		->where("a.sts_disposisi =?",$status)
		->where("TRIM(a.no_surat)!=''")
		->order("a.dt_arrival");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnSearcJob($idunit,$post=array()){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("Status"=>"a.sts_disposisi","Letter"=>"a.no_surat","dtLetter"=>"a.tgl_surat","To"=>"to.NAMA_UNIT","From"=>"f.NAMA_UNIT","dtArrival"=>"a.dt_arrival","Subject"=>"a.kd_form","ivent"=>"a.no_id") )
		->join(array("to"=>"R_UNIT"),"a.unit_id=to.unit_id")
		->joinLeft(array("f"=>"R_UNIT"),"a.from_unit=f.unit_id")
		->where("a.unit_id = ?",$idunit)
		->where("a.sts_disposisi =?",$post['lsStatus'])
		->where("a.kd_form =?",$post['lsCategory'])
		->where("a.no_surat like '%' ? '%'",$post['txtSearch'])
		->order("a.dt_arrival");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetJobStatus(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>"tbl_definationms"),array("key"=>"a.DefinitionCode","value"=>"a.DefinitionDesc","name"=>"a.DefinitionDesc") )
		->where("a.idDefType = 122")
		->order("a.definitionCode");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetJobDisposisiStatus($status){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>"tbl_definationms"),array("name"=>"a.DefinitionDesc") )
		->where("a.idDefType = 122" )
		->where("a.DefinitionCode=?",$status);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		foreach ($larrResult as $def) 
		return $def['name'];
	}
	
	public function fnGetJobCategory(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>"R_FORM"),array("key"=>"a.kd_form","value"=>"a.FormName","name"=>"a.FormName") )
		->order("a.FormName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetFormURL($formid){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>"R_FORM"),array("module"=>"a.module","controller"=>"a.controller","action"=>"a.action") )
		->where("a.kd_form =?",$formid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		foreach($larrResult as $result);
		return $result;
	}
	public function fnGetWorkflow($ivent){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		->from(array("a"=>$this->_name),array("From"=>"unit.NAMA_UNIT","nosrt"=>"a.no_surat","dtsrt"=>"a.tgl_surat","status"=>"a.sts_disposisi","dtdisposisi"=>"a.dt_disposisi","noacc"=>"a.NO_ACC","dtarival"=>"a.dt_arrival") )
		->joinLeft(array("unit"=>"R_UNIT"),"a.from_unit=unit.unit_id")
		->where("a.no_id =?",$ivent)
		->order("dtarival");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
}
?>