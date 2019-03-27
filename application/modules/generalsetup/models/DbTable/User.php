<?php
class GeneralSetup_Model_DbTable_User extends Zend_Db_Table { //Model Class for Users Details
	protected $_name = 'tbl_user';
	
	//Get Country List
	public function fnGetCountryList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_countries"),array("key"=>"a.idCountry","value"=>"a.CountryName"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.CountryName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetDefaultlanguage($countryId){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_countries"),array("key"=>"a.idCountry","value"=>"a.DefaultLanguage"))
				 				 ->where("a.Active = 1")
				 				 ->where("a.idcountry=?",$countryId)
				 				 ->order("a.DefaultLanguage");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetLanguageName($languageId) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		                 ->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionDesc"))
		 				 ->where("a.Status = 1")
		 				 ->where("a.idDefinition = ?",$languageId)
		 				 ->order("a.DefinitionDesc");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);		
		return $larrResult;
		
	}
		
    public function fnGetReligionList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		                 ->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionCode"))
		 				 ->join(array("b"=>"tbl_definationtypems"),"a.idDefType = b.idDefType AND defTypeDesc='Religion'")
		 				 ->where("a.Status = 1")
		 				 ->order("a.DefinitionDesc");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);		
		return $larrResult;
	}
    
	
   public function fnGetSubjectList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_subjectmaster"),array("key"=>"a.IdSubject","value"=>"CONCAT_WS('-',IFNULL(a.SubjectName,''),IFNULL(a.SubCode,''))"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.SubjectName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
    public function fnGetBloodGroupList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect=$lobjDbAdpt->select()
		                 ->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionCode"))
		 				 ->join(array("b"=>"tbl_definationtypems"),"a.idDefType = b.idDefType AND defTypeDesc='Blood Group'")
		 				 ->where("a.Status = 1")
		 				 ->order("a.DefinitionDesc");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);		
		return $larrResult;
	}
	
	
	//Get State List
	public function fnGetStateList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_state"),array("key"=>"a.idState","value"=>"StateName"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.StateName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetStateListcountry($idcountry){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_state"),array("key"=>"a.idState","value"=>"StateName"))
				 				 ->where("a.Active = 1")
				 				 ->where("a.idCountry = $idcountry")
				 				 ->order("a.StateName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function fngetcountrycode($countryId){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_countries"),array("a.CountryCode"))
				 				 ->where("a.idCountry = ?",$countryId);
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
	
	public function fnGetStateCityList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_city"),array("key"=>"a.idCity","value"=>"a.CityName"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.CityName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetStaffList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_staffmaster"),array("key"=>"a.IdStaff","value"=>"a.FullName"))
				 				 ->where("a.Active = 1")
				 				 ->order("a.FullName");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	/*//Function to Get Initial Config Data
	public function fnGetInitialConfigData(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_config"),array("a.Language"));
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}*/
	
	//Function To Get Pagination Count from Initial Config
	public function fnGetPaginationCountFromInitialConfig(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lintPageCount = "";
		$lstrSelect = $lobjDbAdpt->select()
								 ->from(array("a"=>"tbl_config"),array("noofrowsingrid") );
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		if($larrResult['noofrowsingrid'] == "" || $larrResult['noofrowsingrid'] == "0"){
			$lintPageCount = "5";
		}else{
			$lintPageCount = $larrResult['noofrowsingrid'];
		}
		
		return $lintPageCount;
	}
	
	//Function to Get Initial Config Data
	public function fnGetInitialConfigData(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
								 ->from(array("a"=>"tbl_definationms"),array("LCASE(SUBSTRING(a.DefinitionCode,1,2)) AS Language") )
					 			 ->join(array("b"=>"tbl_definationtypems"),'a.idDefType = b.idDefType',array())
				 				 ->join(array("c"=>"tbl_config"),'c.Language = a.idDefinition',array("c.HtmlDir","c.DefaultCountry"));
		//echo $lstrSelect;exit();
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
     public function fngetUserDetails() { //Function to get the user details
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
     	$select = $lobjDbAdpt->select()
     				->from(array('user'=>$this->_name))
     				->joinLeft(array('staff'=>'tbl_employeeProfile'), 'staff.idEmp  = user.IdStaff')
     				->joinleft(array('bio'=>'BIOSDM'),'staff.idEmp=bio.idEmp')
     				->joinLeft(array('unit'=>'R_UNIT'), 'unit.UNIT_ID  = bio.unit_id')
     				->joinLeft(array('role'=>'tbl_definationms'), 'role.idDefinition  = user.IdRole')
     				->where('user.UserStatus = 1')
     				->where('bio.STS_AKTIF="00"')
     				->order('user.loginName ASC');
     				
     	$result = $lobjDbAdpt->fetchAll($select);
        //$result = $this->fetchAll('UserStatus = 1',"loginName ASC");
        return $result;
     }
        
    public function fnuserinfo($lstrusername) { //Function for getting the user information based on the username
        $result = $this->fetchAll( "loginName = '$lstrusername'") ;
        return $result;
    }
        
	public function fnSearchUser($post = array()) { //Function for searching the user details
    	$db = Zend_Db_Table::getDefaultAdapter();
		$field7 = "UserStatus = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_user'),array('iduser'))
			   ->where('a.loginName like "%" ? "%"',$post['field3'])
			   ->where('a.fName like  "%" ? "%"',$post['field2'])
			   ->where('a.mName like "%" ? "%"',$post['field4'])
			   ->where('a.lName like "%" ? "%"',$post['field6'])
			   ->where($field7)
			   ->order("a.loginName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function fnaddUser($larrformData) { //Function for adding the user details to the table
    	$larrformData['workPhone'] = $larrformData['workcountrycode']."-".$larrformData['workstatecode']."-".$larrformData['workPhone'];
    	$larrformData['homePhone'] = $larrformData['homecountrycode']."-".$larrformData['homestatecode']."-".$larrformData['homePhone'];
		$larrformData['cellPhone'] = $larrformData['countrycode']."-".$larrformData['cellPhone'];
		$larrformData['fax'] = $larrformData['faxcountrycode']."-".$larrformData['faxstatecode']."-".$larrformData['fax'];
		unset($larrformData['countrycode']);
		//unset($larrformData['statecode']);
		unset($larrformData['workcountrycode']);
		unset($larrformData['workstatecode']);
		unset($larrformData['homecountrycode']);
		unset($larrformData['homestatecode']);
		unset($larrformData['faxcountrycode']);
		unset($larrformData['faxstatecode']);
		$this->insert($larrformData);
	}
	
    public function fnviewUser($iduser) { //Function for the view user 
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_user'),array('iduser'))
            ->where('iduser = ?',$iduser);		
	$result = $this->fetchAll($select);
	return $result->toArray();
    }
    
    public function fnupdateUser($lintiduser,$larrformData) { //Function for updating the user
    	$larrformData['workPhone'] = $larrformData['workcountrycode']."-".$larrformData['workstatecode']."-".$larrformData['workPhone'];
    	$larrformData['homePhone'] = $larrformData['homecountrycode']."-".$larrformData['homestatecode']."-".$larrformData['homePhone'];
		$larrformData['cellPhone'] = $larrformData['countrycode']."-".$larrformData['cellPhone'];
		$larrformData['fax'] = $larrformData['faxcountrycode']."-".$larrformData['faxstatecode']."-".$larrformData['fax'];
		unset($larrformData['countrycode']);
		//unset($larrformData['statecode']);
		unset($larrformData['workcountrycode']);
		unset($larrformData['workstatecode']);
		unset($larrformData['homecountrycode']);
		unset($larrformData['homestatecode']);
		unset($larrformData['faxcountrycode']);
		unset($larrformData['faxstatecode']);
		$where = 'iduser = '.$lintiduser;
		
		$this->update($larrformData,$where);
    }
	public function fnviewUserSpecialRole($iduser) { //Function for the view user  special roles
		 $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_specialroles"),array("a.*"))
					->join(array("b" => "tbl_user"),"a.idUser=b.iduser",array("b.lName"))
					->join(array("c" => "tbl_definationms"),"a.idRole=c.idDefinition",array("c.DefinitionCode"))				
		            ->where("a.iduser = ?",$iduser);		   
		 return $result = $lobjDbAdpt->fetchAll($select);	
	}
	public function fnAddUserSpecialRoles($larrformData) { //Function for adding the user details to the table
		 $db = Zend_Db_Table::getDefaultAdapter();
		 return $db->insert('tbl_specialroles',$larrformData);		
	}
	public function fnGetUserSpecialRolesEdit($iduserSpecial) { //Function for the view user  special roles
		 $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_specialroles"),array("a.*"))
					->join(array("b"=>"tbl_definationms"),"a.idRole	=b.idDefinition",array("b.DefinitionCode"))
					->where("a.idSpecialRole = ?",$iduserSpecial);		   
		 return $result = $lobjDbAdpt->fetchRow($select);	
	}
	 public function fnUpdateUserSpecialRoles($larrformData) { //Function for updating the user
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$where = 'idSpecialRole = '.$larrformData['edit'];
		unset($larrformData ['edit']);
		return $lobjDbAdpt->update('tbl_specialroles',$larrformData,$where);
    }
	public function fnGetRolesDetails($idRoles){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionCode"))
				 				 ->join(array("b"=>"tbl_definationtypems"),"a.idDefType=b.idDefType AND b.defTypeDesc='Role'",array()) 
				 				 ->where("a.idDefinition  NOT IN".$idRoles);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	
	public function getstaffdetails($idstaff) { //Function for the view user  special roles
		 $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_staffmaster"),array("a.FirstName","a.SecondName","a.ThirdName","a.ArabicName","a.Email","a.Add1","a.Add2","a.City","a.State","a.Country","a.Zip","a.DOB","a.gender","a.JoiningDate"))
					->where("a.IdStaff = ?",$idstaff);		   
		 return $result = $lobjDbAdpt->fetchRow($select);	
	}
	
	public function getagentstaffdetails(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_staffmaster"),array("key"=>"a.IdStaff","name"=>"a.FirstName"));	   
		 return $result = $lobjDbAdpt->fetchAll($select);	
	}
	
   public function getinchargedetails(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_staffmaster"),array("key"=>"a.IdStaff","value"=>"a.FirstName"));	   
		 return $result = $lobjDbAdpt->fetchAll($select);	
	}
	
	public function fngetagentstaff(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_staffmaster"),array("key"=>"a.IdStaff","value"=>"a.FirstName"));	
		 return $result = $lobjDbAdpt->fetchAll($select);	
	}
	
	public function fngetusername($UserName) {  
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("u"=>"tbl_user"),array("u.loginName"))			
		            	->where("u.loginName= ?",$UserName);	
		return $result = $lobjDbAdpt->fetchRow($select);
    }
    
    public function fngetagentdetails($AgentId){
    	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $select = $lobjDbAdpt->select()
					->from(array("a" => "tbl_staffmaster"),array("a.Add1","a.Add2","a.State","a.City","a.Mobile","a.Email","a.Country"))
					->where("a.IdStaff = ?",$AgentId);		   
		 return $result = $lobjDbAdpt->fetchRow($select);	
    }
}