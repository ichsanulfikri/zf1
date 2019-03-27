<?php
class GeneralSetup_Model_DbTable_Country extends Zend_Db_Table {
	
	protected $_name = 'tbl_countries';
	
   /*
    * return all country names
    */
	
 	public function fnGetCountryListDetails() { //Function to get the university details
        $result = $this->fetchAll('Active = 1',"CountryName ASC");
        return $result;
     }
     
	public function fnGetCountryDetails() { // Function to fetch the countries  for dropdown
        	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()->from('tbl_countries',array('key' => 'idCountry','value' => 'CountryName'))->order('CountryName')->where("Active = 1");
			$result = $db->fetchAll($sql);
			return $result;
	}
	

    /*
     * return all currency short name
     */
	public function fnGetCurrencies(){
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
                        ->group('CurrencyShortName');
		return $this->fetchAll($select);		
	}    

	/*
	 * return currency short name where idCountry = $countryid
	 */
	public function fnGetCurrency($countryid){
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
			->where('idCountry = '.$countryid);		
		return $this->fetchAll($select);		
	} 

}
