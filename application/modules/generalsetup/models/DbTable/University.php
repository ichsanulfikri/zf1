<?php 
class GeneralSetup_Model_DbTable_University extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_universitymaster';
	protected $_primary = "IdUniversity";
	private $lobjDbAdpt;
	
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
     public function fngetUniversityDetails() { //Function to get the university details
        $result = $this->fetchAll('Active = 1', "Univ_Name ASC");
       return $result;
     }
     
	public function fnGetUniversityList(){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_universitymaster"),array("key"=>"a.IdUniversity","value"=>"Univ_Name"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.Univ_Name");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnaddUniversity($larrformData) { //Function for adding the University details to the table
		
		$larrformData['Phone1'] = $larrformData['Phone1countrycode']."-".$larrformData['Phone1statecode']."-".$larrformData['Phone1'];
		unset($larrformData['Phone1countrycode']);
		unset($larrformData['Phone1statecode']);
		
		$larrformData['Phone2'] = $larrformData['Phone2countrycode']."-".$larrformData['Phone2statecode']."-".$larrformData['Phone2'];
		unset($larrformData['Phone2countrycode']);
		unset($larrformData['Phone2statecode']);
		
		$larrformData['Phone'] = $larrformData['Phonecountrycode']."-".$larrformData['Phonestatecode']."-".$larrformData['Phone'];
		unset($larrformData['Phonecountrycode']);
		unset($larrformData['Phonestatecode']);
		
		$larrformData['Mobile'] = $larrformData['Mobilecountrycode']."-".$larrformData['Mobilestatecode']."-".$larrformData['Mobile'];
		unset($larrformData['Mobilecountrycode']);
		unset($larrformData['Mobilestatecode']);
		
		$larrformData['Fax'] = $larrformData['faxcountrycode']."-".$larrformData['faxstatecode']."-".$larrformData['Fax'];
		unset($larrformData['faxcountrycode']);
		unset($larrformData['faxstatecode']);
		
		$larrstaffdata = $larrformData;
		$larrreglistdata = $larrformData;
		
		unset($larrformData['ToDate']);
		unset($larrformData['FromDate']);
		unset($larrformData['IdLevel']);
		unset($larrformData['StaffType']);
		unset($larrformData['StEmail']);
		unset($larrformData['Mobile']);
		unset($larrformData['Phone']);
		unset($larrformData['StZip']);
		unset($larrformData['StState']);
		unset($larrformData['StCountry']);
		unset($larrformData['StCity']);
		unset($larrformData['StAdd2']);
		unset($larrformData['StAdd1']);
		unset($larrformData['ArabicName']);
		unset($larrformData['FullName']);
		unset($larrformData['FourthName']);
		unset($larrformData['ThirdName']);
		unset($larrformData['SecondName']);
		unset($larrformData['FirstName']);
		unset($larrformData['IdStaff']);
		
		unset($larrstaffdata['Univ_Name']);
		unset($larrstaffdata['Univ_ArabicName']);
		unset($larrstaffdata['ShortName']);
		unset($larrstaffdata['Add1']);
		unset($larrstaffdata['Add2']);
		unset($larrstaffdata['City']);
		unset($larrstaffdata['Country']);
		unset($larrstaffdata['State']);
		unset($larrstaffdata['Zip']);
		unset($larrstaffdata['Phone1']);
		unset($larrstaffdata['Phone2']);
		unset($larrstaffdata['Fax']);
		unset($larrstaffdata['Email']);
		unset($larrstaffdata['Url']);
		unset($larrstaffdata['ToDate']);
		unset($larrstaffdata['FromDate']);
		

		unset($larrreglistdata['IdLevel']);
		unset($larrreglistdata['StaffType']);
		unset($larrreglistdata['StEmail']);
		unset($larrreglistdata['Mobile']);
		unset($larrreglistdata['Phone']);
		unset($larrreglistdata['StZip']);
		unset($larrreglistdata['StState']);
		unset($larrreglistdata['StCountry']);
		unset($larrreglistdata['StCity']);
		unset($larrreglistdata['StAdd2']);
		unset($larrreglistdata['StAdd1']);
		unset($larrreglistdata['ArabicName']);
		unset($larrreglistdata['FullName']);
		unset($larrreglistdata['FourthName']);
		unset($larrreglistdata['ThirdName']);
		unset($larrreglistdata['SecondName']);
		unset($larrreglistdata['FirstName']);
		unset($larrreglistdata['Univ_Name']);
		unset($larrreglistdata['Univ_ArabicName']);
		unset($larrreglistdata['ShortName']);
		unset($larrreglistdata['Add1']);
		unset($larrreglistdata['Add2']);
		unset($larrreglistdata['City']);
		unset($larrreglistdata['Country']);
		unset($larrreglistdata['State']);
		unset($larrreglistdata['Zip']);
		unset($larrreglistdata['Phone1']);
		unset($larrreglistdata['Phone2']);
		unset($larrreglistdata['Fax']);
		unset($larrreglistdata['Email']);
		unset($larrreglistdata['Url']);
		
		//$lintunivid = $this->insert($larrformData);
		$this->lobjDbAdpt->insert('tbl_universitymaster',$larrformData);//insert university
		
		$lintunivid = $this->lobjDbAdpt->lastInsertId();

		//$larrreglistdata['IdStaff'] = $this->lobjDbAdpt->lastInsertId();
		$larrreglistdata['IdUniversity'] =  $lintunivid;

		$this->lobjDbAdpt->insert('tbl_registrarlist',$larrreglistdata);//insert registrar
	}
	
    public function fneditUniversity($IdUniversity) { //Function for the view University 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('univ' => 'tbl_universitymaster'),array('univ.IdUniversity'))
			->join(array('reglst'=>'tbl_registrarlist'),'univ.IdUniversity = reglst.IdUniversity',array('reglst.IdStaff','reglst.FromDate'))
            ->where('univ.IdUniversity = ?',$IdUniversity)
            ->where('reglst.FromDate < now()');		
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
    public function fnupdateUniversity($formData,$lintIdUniversity) { //Function for updating the university
    	$formData['Phone1'] = $formData['Phone1countrycode']."-".$formData['Phone1statecode']."-".$formData['Phone1'];
		unset($formData['Phone1countrycode']);
		unset($formData['Phone1statecode']);
		
		$formData['Phone2'] = $formData['Phone2countrycode']."-".$formData['Phone2statecode']."-".$formData['Phone2'];
		unset($formData['Phone2countrycode']);
		unset($formData['Phone2statecode']);
		
		$formData['Fax'] = $formData['faxcountrycode']."-".$formData['faxstatecode']."-".$formData['Fax'];
		unset($formData['faxcountrycode']);
		unset($formData['faxstatecode']);
		
    	unset ( $formData ['Save'] );
    	unset ( $formData ['IdStaff'] );
    	unset ( $formData ['FromDate'] );
    	unset ( $formData ['ToDate'] );
    	
		$where = 'IdUniversity = '.$lintIdUniversity;
		$this->update($formData,$where);
    }
    
	public function fnSearchUniversity($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_universitymaster'),array('IdUniversity'))
			   ->where('a.Univ_Name like "%" ? "%"',$post['field3'])
			   ->where('a.ShortName like  "%" ? "%"',$post['field2'])
			   ->where('a.City like "%" ? "%"',$post['field4'])
			   ->where('a.Email like "%" ? "%"',$post['field6'])
			   ->where('a.Univ_ArabicName like "%" ? "%"',$post['field18'])			   
			   ->where($field7)
			   ->order("a.Univ_Name");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function fnGetUniversityName($IdUniversity)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$select =$db->select('Univ_Name')
		            ->from(array('a' => 'tbl_universitymaster'),'Univ_Name')
					->where('a.IdUniversity='.$IdUniversity);
		$result = $db->fetchRow($select);
		return $result;
    }
    
	public function fngetValidateUniversityName($UniversityName) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("u"=>"tbl_universitymaster"),array("u.*"))			
		            	->where("u.Univ_Name= ?",$UniversityName);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
    public function fngetUniversityLanguage($IdUniversity) {
    	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('univ' => 'tbl_universitymaster'),array('univ.IdUniversity'),array())
			->join(array('c'=>'tbl_countries'),'univ.Country = c.idCountry',array())
			->join(array('d'=>'tbl_definationms'),'c.DefaultLanguage = d.idDefinition',array('d.DefinitionDesc AS UnivLang'))
            ->where('univ.IdUniversity = ?',$IdUniversity);
	$result = $this->fetchAll($select);
	return $result->toArray();
    }

    public function fngetActiveUniversity(){
      $select = $this->lobjDbAdpt->select()
		->from("tbl_universitymaster")
		->where("Active= ?",1);
      return $result = $this->lobjDbAdpt->fetchRow($select);
    }
}
?>