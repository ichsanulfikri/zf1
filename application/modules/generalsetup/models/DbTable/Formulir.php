<?php
class GeneralSetup_Model_DbTable_Formulir extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_formulir';
	
	//Get Country List
	public function fnInsertintoformulier($larrformData,$FamulirNo,$password){
		//echo "<pre>";print_r($larrformData);
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
	
	
	public function fnGetFormulirDetails(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $this->select()
                 ->from(array("pc"=>"tbl_formulir"),array("pc.FamulirNo","pc.Password"));   
       $result = $this->fetchAll($select);
	   return $result->toArray();
		
	}
		
   
}