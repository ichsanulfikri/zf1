<?php
class GeneralSetup_Model_DbTable_Schoolmaster extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_schoolmaster';
	
public function fnGetStateList($country){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_state"),array("key"=>"a.idState","value"=>"StateName"))
				 				 ->where("a.Active = 1")
				 				 ->where("a.idCountry = ?",$country)
				 				 ->order("a.StateName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	
public function fnGetStateCityList($idCountry){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_city"),array("key"=>"a.idCity","value"=>"CityName"))
					 				 ->where("a.idState = ?",$idCountry)
					 				 ->where("a.Active = 1")
					 				 ->order("a.CityName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
     
     public function fngetSchoolDetails() {
     	//$db = Zend_Db_Table::getDefaultAdapter();
     	$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->from(array('a' => 'tbl_schoolmaster'),array('a.*'))
			   ->order("a.SchoolName");
		$result = $this->fetchAll($select);
     	  //$result = $this->fetchAll('Active = 1', 'SchoolName ASC');
        return $result;
     }
     
	public function fnSearchSchool($post = array()) { //Function for searching the user details
    	//$db = Zend_Db_Table::getDefaultAdapter();
		$field7 = "a.Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->from(array('a' => 'tbl_schoolmaster'),array('a.*'))
			   ->where('a.SchoolName like "%" ? "%"',$post['field2'])
			    ->where('a.School_ArabicName like "%" ? "%"',$post['field3'])
			   ->where($field7)
			   ->order("a.SchoolName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	
	
	public function fnaddschool($larrformData)
	{
		$larrformData['workPhone'] = $larrformData['workcountrycode']."-".$larrformData['workstatecode']."-".$larrformData['workPhone'];
		unset($larrformData['workcountrycode']);
		unset($larrformData['workstatecode']);
		$this->insert($larrformData);
	}
	
 public function fnViewSchool($IdProgram) { // Function to view the Purchase Order details based on id
        	//$result = $this->fetchRow( "idSchool = '$IdProgram'") ;
         	//return $result->toArray();
         	$db = Zend_Db_Table::getDefaultAdapter();
         	$select = $db->select()
			   ->from(array('a' => 'tbl_schoolmaster'),array('a.*'))
			   ->where('a.idSchool=?',$IdProgram);
		$result = $db->fetchRow($select);
		return $result;
	}
	public function  fnGetcityList($idstate)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
         	 $select = $db->select()
			   ->from(array('a' => 'tbl_city'),array("key"=>"a.idCity","value"=>"CityName"))
			   ->where('a.idState=?',$idstate);
		$result = $db->fetchAll($select);
		return $result;
	}
  public function fnupdateSchool($formData,$lintIdProgram) { //Function for updating the university
    	unset ( $formData ['Save'] );
    	$formData['workPhone'] = $formData['workcountrycode']."-".$formData['workstatecode']."-".$formData['workPhone'];
    	unset($formData['workcountrycode']);
		unset($formData['workstatecode']);
		$where = 'idSchool = '.$lintIdProgram;
		$this->update($formData,$where);
    }
    
	public function fnGetCountryList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_countries"),array("key"=>"a.idCountry","value"=>"CountryName"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.CountryName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
}