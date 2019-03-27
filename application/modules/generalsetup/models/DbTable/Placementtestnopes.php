<?php
class GeneralSetup_Model_DbTable_Placementtestnopes extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_formulir';
	
	//Get Country List
	public function fnInsertintoformulier($larrformData,$FamulirNo,$password){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();	
		$data = array("Year"=>$larrformData['Year'],
					  "ApplicationType" => $larrformData['Apptype'],
		              "EnterNo" =>$larrformData['EnterNo'],
					  "FamulirNo" =>$FamulirNo,
					  "Password" =>$password);
		$larrResult = $this->Insert($data);
		return $larrResult;
	}
	
	
	public function fnGetmaxid(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $this->select()
                 ->from(array('a' => 'tbl_formulir'),array('max(a.Idfamulir) as maxid'));    
       $result = $this->fetchRow($select);
	   return $result;
		
	}
	
	
	
	
	
	 public function fnGetPlacementtestnopesDetails(){//Function to get the subject master list
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
								 ->from(array('a'=>'tbl_placementtest'),array("a.*"))
								 ->where('a.Active=1')
								 ->order("a.PlacementTestName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	 public function fnGetPlacementtestnopesDetailsyear($lintiduser){//Function to get the subject master list
        $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
								 ->from(array('a'=>'tbl_placementtestnopes'),array("a.FamulirNo","a.Year"))
								 ->where('a.IdPlacementTest = ?',$lintiduser);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	
	
	public function fnSearchPlacementestname($larrformData) { //Function for searching the user details\
	     //echo "<pre>";
		 
	   //print_r($larrformData);die();
    	$db = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->from(array('a' => 'tbl_placementtest'))
			   ->where('a.PlacementTestName like "%" ? "%"',$larrformData['field2'])
			   ->where('a.Active like "%" ? "%"',$larrformData['field7'])
			   ->order("a.PlacementTestName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
		
   
}