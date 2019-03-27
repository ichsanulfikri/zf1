<?php
class GeneralSetup_Model_DbTable_Initialconfiguration extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_config';


        public function fnGetConf($idUniversity){
          $db = Zend_Db_Table::getDefaultAdapter();
          $select = $db->select()
                ->from('tbl_config')
                ->where('idUniversity  = ?',$idUniversity);
          $result = $this->fetchRow($select);
          return $result->toArray();
        }
	
	public function fnSearchUniversity($post = array()) { //Function for searching the user details
    	$db = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_universitymaster'),array('IdUniversity'))
			   ->where('a.Univ_Name like "%" ? "%"',$post['field3']);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function fnGetInitialConfigDetails($iduniversity) {		
           $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
           $select = $lobjDbAdpt->select()
                                  ->from(array("a" => "tbl_config"),array("a.*"))
                      ->where("a.idUniversity = ?",$iduniversity);
                      
           return $result = $lobjDbAdpt->fetchRow($select);
	}
	
	public function fngetunivdetails($idUniversity){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
						->from(array('a'=>'tbl_universitymaster'),array('a.*'))
						->joinLeft(array('b'=>'tbl_city'),'b.idCity = a.City',array('b.CityName'))
						->joinLeft(array('c'=>'tbl_state'),'c.idState = a.State',array('c.StateName'))
						->joinLeft(array('d'=>'tbl_countries'),'d.idCountry = a.Country',array('d.CountryName'))
						->where('a.IdUniversity=?',$idUniversity);
		return $result = $db->fetchRow($select);
	}
	
	
	public function fnAddInitialConfig($larrformData) { //Function for adding the user details to the table
		$this->insert($larrformData);
	}
	
	public function fnUpdateInitialconfig($idconfig,$formData)
	{
		$where = 'idConfig = '.$idconfig;
		$this->update($formData,$where);
	}
	public function fnGenerateCode($idUniversity,$IdInserted,$semShortName,$page){								 
			$result = 	$this->fnGetInitialConfigDetails($idUniversity);
			$sepr	=	$result[$page.'Separator'];
			$str	=	$page."CodeField";
			for($i=1;$i<=3;$i++){
				$check = $result[$str.$i];
				switch ($check){
					case 'Year':
					  $code	= date('Y');
					  break;
					case 'Uniqueid':
					  $code	= $IdInserted;
					  break;						
					case 'ShortName':
					  $code	= $semShortName;
					  break;	
					default:
					  break;
				}
				if($i == 1) $accCode 	 =  $code;
				else 		$accCode	.=	$sepr.$code;
			}				
			return $accCode;	
		}
}