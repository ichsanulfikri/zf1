<?php 
class GeneralSetup_Model_DbTable_Sponsor extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_sponsor';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fnGetSponsorList(){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_sponsor"),array("key"=>"a.idsponsor","value"=>"a.Organisation"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.Organisation");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
    public function fnaddSponsor($formData) { //Function for adding the University details to the table
   		 $formData['HomePhone'] = $formData['HomePhonecountrycode']."-".$formData['HomePhonestatecode']."-".$formData['HomePhone'];
			unset($formData['HomePhonecountrycode']);
			unset($formData['HomePhonestatecode']);
		$formData['WorkPhone'] = $formData['WorkPhonecountrycode']."-".$formData['WorkPhonestatecode']."-".$formData['WorkPhone'];
			unset($formData['WorkPhonecountrycode']);
			unset($formData['WorkPhonestatecode']);	
		$formData['CellPhone'] = $formData['CellPhonecountrycode']."-".$formData['CellPhone'];
			unset($formData['CellPhonecountrycode']);
			
		$formData['Fax'] = $formData['Faxcountrycode']."-".$formData['Faxstatecode']."-".$formData['Fax'];
			unset($formData['Faxcountrycode']);
			unset($formData['Faxstatecode']);	
			unset ($formData['IdQuota']);
    		unset ($formData['Quota']);
    	/*if($formData['DOB']){
		$formData['DOB']= date('Y-m-d',strtotime($formData['DOB']));
    		}*/
		$this->insert($formData);
		$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
		
	}
	
    public function fnupdateSponsor($formData,$idsponsor) { //Function for updating the university
    	 $formData['HomePhone'] = $formData['HomePhonecountrycode']."-".$formData['HomePhonestatecode']."-".$formData['HomePhone'];
			unset($formData['HomePhonecountrycode']);
			unset($formData['HomePhonestatecode']);
		$formData['WorkPhone'] = $formData['WorkPhonecountrycode']."-".$formData['WorkPhonestatecode']."-".$formData['WorkPhone'];
			unset($formData['WorkPhonecountrycode']);
			unset($formData['WorkPhonestatecode']);	
		$formData['CellPhone'] = $formData['CellPhonecountrycode']."-".$formData['CellPhone'];
			unset($formData['CellPhonecountrycode']);
			
		$formData['Fax'] = $formData['Faxcountrycode']."-".$formData['Faxstatecode']."-".$formData['Fax'];
			unset($formData['Faxcountrycode']);
			unset($formData['Faxstatecode']);	
		unset ( $formData ['Save'] );		
		$where = 'idsponsor = '.$idsponsor;
		$this->update($formData,$where);
    }
    
	public function fnSearchSponsor($formData) {			
		$searchString = $formData['field2'];
		$active = $formData['field7'];			
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
				->from(array("a" => "tbl_sponsor"),array("a.idsponsor","CONCAT(a.fName,' ', IFNULL(a.mName,' '),' ',IFNULL(a.lName,' ')) AS SponsorName",
							"a.City", "a.Email", "a.CellPhone","a.Organisation","a.typeSponsor","a.url" ))
				->where('a.Email like "%" ? "%"',$formData['field3']);
		
							
		if($formData['field5'] != "") {	 				
			if($formData['field5'] == 1) {
				$select	->where("a.Organisation LIKE '%".$searchString."%'")
						->where('a.Email like "%" ? "%"',$formData['field3'])
						->where("a.typeSponsor =".$formData['field5']."");	
			} else if($formData['field5'] == 0)	 {
				$select	->where("a.fName LIKE '%".$searchString."%'")
						->where('a.Email like "%" ? "%"',$formData['field3'])
						->where("a.typeSponsor =".$formData['field5']."");					
			}
		} else {
				$select	->where("a.Organisation LIKE '%".$searchString."%'")
						->orWhere("a.fName LIKE '%".$searchString."%'")
						->where('a.Email like "%" ? "%"',$formData['field3']);	
		}
		
		$select	->where('a.Active = ' . $active)
	        	->order("fName");	
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	
	
/*public function fnSearchSponsor($formData) {	
	//echo "<PRE>";print_r($formData);die();		
		$active = $formData['field7'];			
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
				->from(array("a" => "tbl_sponsor"),array("a.idsponsor","CONCAT(a.fName,' ', IFNULL(a.mName,' '),' ',IFNULL(a.lName,' ')) AS SponsorName",
							"a.City", "a.Email", "a.CellPhone","a.Organisation","a.typeSponsor","a.url" ))
				->where('a.Active = ' . $active)
				->where('a.fName like "%" ? "%"',$formData['field2'])	
				->where('a.Email like "%" ? "%"',$formData['field3']);
		if(isset($formData['field5']) && !empty($formData['field5'])){
		$select = $select->where("a.typeSponsor = ?",$formData['field5']);
		echo $select;die();			
			}			
				$select->order("SponsorName");
				//echo $select;die();									
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	*/
	public function fnGetSponsorDetails() {			
		$select = $this->select()
			->from(array("a" => "tbl_sponsor"),array("a.idsponsor","CONCAT(a.fName,' ', IFNULL(a.mName,' '),' ',IFNULL(a.lName,' ')) AS SponsorName",
							"a.City", "a.Email", "a.CellPhone","a.Organisation","a.typeSponsor","a.url"  ))			
				->where("a.Active = 1")
				->order("SponsorName");
			$result = $this->fetchAll($select);		
			return $result->toArray(); 
		}
}
?>