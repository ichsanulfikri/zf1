<?php
class GeneralSetup_Model_DbTable_Countrymaster extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_countries';
	
	public function fngetCountryDetails() {
        $result = $this->fetchAll('Active = 1', "CountryName ASC");
       return $result;
     }
    public function fnSearchCountries($post = array()) { 
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("c"=>"tbl_countries"),array("c.*"))
       								->where('c.CountryName like "%" ? "%"',$post['field2'])
       								->where('c.Alias like "%" ? "%"',$post['field3'])
       								->where('c.CountryIso like "%" ? "%"',$post['field4'])
       								->where("c.Active = ".$post["field7"])
       								->group("c.CountryName")
       			   					->order("c.CountryName");
       					//echo $lstrSelect;die();
       					
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	 }
	public function fnaddCountrymaster($larrformData) { 
    	$this->insert($larrformData);
	}
	 public function fnviewCountrymaster($lintidCountry) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_countries"),array("c.*"))			
		            	->where("c.idCountry= ?",$lintidCountry);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
   public function fnupdateCountrymaster($lintidCountry,$larrformData) { 
    	$where = 'idCountry = '.$lintidCountry;
		$this->update($larrformData,$where);
    } 
    
    Public function fngetlanguage($idLanguage){
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_definationms"),array("c.DefinitionDesc"))			
		            	->where("c.idDefinition= ?",$idLanguage);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
 	public function fngetcountryname($ContryName) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_countries"),array("c.*"))			
		            	->where("c.CountryName= ?",$ContryName);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
    public function fngetCountryId($idUniversity){
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("c"=>"tbl_universitymaster"),array("c.Country"))			
		            	->where("c.IdUniversity= ?",$idUniversity);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
	
	
}