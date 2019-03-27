<?php
class GeneralSetup_Model_DbTable_City extends Zend_Db_Table {

	protected $_name = 'tbl_city';



	public function fncityinfodtl($idCountry,$idState) {
		//fuction for updating in the grid
		$result = $this->fetchRow( "idState = '$idCountry' and idCity = '$idState'") ;
		return $result->toArray();
	}

	public function fnGetcitydetailslist($idCountry) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = "SELECT a.StateName,b.* ";
		$select .=" FROM tbl_state a, tbl_city b";
		$select .=" WHERE a.idState = b.idState and a.Active ='1' and b.Active ='1'";
		$select .=" AND b.idState =".$idCountry;
		//echo $select;exit();$idCountry
		$result = $db->fetchAll($select);

		return $result;
	}

	public function fnAddCity($insertData) {
		return $this->insert($insertData);
	}

	public function fnStateName($idCountry)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$select =$db->select('StateName')
		->from(array('a' => 'tbl_state'),'StateName')
		->where('a.idState='.$idCountry);
		$result = $db->fetchRow($select);
		return $result;
	}

	public function fnGetStateDetails() { // Function to fetch the State  for dropdown
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = $db->select()->from('tbl_state',array('key' => 'idState','value' => 'StateName'))->where('Active=?',1)->order('StateName');
		$result = $db->fetchAll($sql);
		return $result;
	}


	public function fnAddState($insertData) {
		return $this->insert($insertData);
	}

	public function fnUpdateCity($UpdateData,$idCity) { //Function for updating the advancereceipt
		$where = "idCity = '$idCity'";
		//print_r($updateData);exit();
		$this->update($UpdateData,$where);
	}

	public function fnGetstatedetailslist($idCountry) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = "SELECT a.CountryName,b.* ";
		$select .=" FROM tbl_countries a, tbl_state b";
		$select .=" WHERE a.idCountry = b.idCountry and a.Active ='1' and b.Active ='1'";
		$select .=" AND b.idCountry =".$idCountry;
		//echo $select;exit();$idCountry
		$result = $db->fetchAll($select);

		return $result;
	}

	 
	public function fngetcityname($cityName,$statid) {

		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
		->from(array("c"=>"tbl_city"),array("c.*"))
		->where("c.idState= ?",$statid)
		->where("c.CityName= ?",$cityName);

		return $result = $lobjDbAdpt->fetchRow($select);
	}



	public function fngetcitycode($cityCode,$statid) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
		->from(array("c"=>"tbl_city"),array("c.*"))
		->where("c.idState= ?",$statid)
		->where("c.CityCode= ?",$cityCode);

		return $result = $lobjDbAdpt->fetchRow($select);
	}



}
