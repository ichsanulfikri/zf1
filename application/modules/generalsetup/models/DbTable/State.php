<?php
class GeneralSetup_Model_DbTable_State extends Zend_Db_Table {
	
	protected $_name = 'tbl_state';
	

	
public function fnGetStateListDetails() { //Function to get the university details
        $result = $this->fetchAll('Active = 1',"StateName ASC");
        return $result;
     }
     
	public function fnGetStateDetails() { // Function to fetch the State  for dropdown
        	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()->from('tbl_state',array('key' => 'idState','value' => 'StateName'))->where('Active=?',1)->order('StateName');
			$result = $db->fetchAll($sql); 
			return $result;
		}
        
	public function fnSearchCountries($post = array()) { 
		
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("c"=>"tbl_countries"),array("c.*"))
       								->where('c.CountryName like "%" ? "%"',$post['field2'])
       								->where('c.Alias like "%" ? "%"',$post['field3'])
       								->where('c.CountryISO3 like "%" ? "%"',$post['field4'])
       								->where("c.Active = ".$post["field7"])
       								->group("c.CountryName")
       			   					->order("c.CountryName");
       			
       					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
	public function fnSearchStates($post = array()) { 
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("s"=>"tbl_state"),array("s.*"))
       								->join(array("c"=>"tbl_countries"),'c.idCountry=s.idCountry')
       								->where('s.StateName like "%" ? "%"',$post['field2'])
       								->where('c.CountryName like "%" ? "%"',$post['field3'])
       								->where("s.Active = ".$post["field7"])
       								->group("s.StateName")
       			   					->order("s.StateName");
       					//echo $lstrSelect;die();
       					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
	public function fnName($idCountry)
	{
	    $db = Zend_Db_Table::getDefaultAdapter();
	    $select =$db->select('CountryName')
	            ->from(array('a' => 'tbl_countries'),'CountryName')
				->where('a.idCountry='.$idCountry);
	$result = $db->fetchRow($select);
	return $result;
    }
	
    
    public function fnAddState($insertData) {
		return $this->insert($insertData);
	}
	
   public function fnUpdateState($UpdateData,$idState) { //Function for updating the advancereceipt
 	 $where = "idState = '$idState'";
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
	
    public function fnstateinfodtl($idCountry,$idState) {
    //fuction for updating in the grid
        $result = $this->fetchRow( "idCountry = '$idCountry' and idState = '$idState'") ;
        return $result->toArray();
    }
        
	public function fnGetCurrencies(){
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
                        ->group('CurrencyShortName');
	return $this->fetchAll($select);		
	}    
       

	public function fnGetCurrency($countryid){
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
			->where('idCountry = '.$countryid);			
	return $this->fetchAll($select);		
	} 
	
	public function fngetstatename($StatName,$Contry) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_state"),array("c.*"))			
		            	->where("c.idCountry= ?",$Contry)
		            	->where("c.StateName= ?",$StatName);	
		            
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
    
    
	public function fngetstatecode($StatCode,$Contry) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_state"),array("c.*"))			
		            	->where("c.idCountry= ?",$Contry)
		            	->where("c.StateCode= ?",$StatCode);			            
		return $result = $lobjDbAdpt->fetchRow($select);
    }

}
