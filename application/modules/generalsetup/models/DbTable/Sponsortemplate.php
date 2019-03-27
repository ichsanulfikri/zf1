<?php 
class GeneralSetup_Model_DbTable_Sponsortemplate extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_sponsortemplate';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	public function fngetSponsorTemplateDetails() { //Function to get the user details
       $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("st"=>"tbl_sponsortemplate"),array("st.*"))
       								->order("st.templateName");
       					//echo $lstrSelect;die();
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
     }
	public function fnSearchSponsorTemplate($post = array()) {	
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   $lstrSelect = $lobjDbAdpt->select()
       								->from(array("st"=>"tbl_sponsortemplate"),array("st.*"))
       								 ->join(array('std'=>'tbl_sponsortemplatedetails'),'st.idSponsortemplate  = std.idSponsortemplate');
		if(isset($post['field5']) && !empty($post['field5'])){
				$lstrSelect = $lstrSelect->where("std.idAccount = ?",$post['field5']);
			}							 
       		$lstrSelect->where('st.templateName like "%" ? "%"',$post['field2'])
       								->order("st.templateName");
       $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	public function fnGetCharges(){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_accountmaster"),array("key"=>"a.idAccount","value"=>"a.AccountName"))
				 				 ->order("a.AccountName");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	/*public function fnGetMultipleCharges($lintidSponsortemplate){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_charges"),array("key"=>"a.IdCharges","value"=>"a.ChargeName"))
				 				 ->where('a.idSponsortemplate  = ?',$lintidSponsortemplate)		
				 				 ->order("a.ChargeName");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	*/
    public function fnaddSponsorTemplate($larrformData) { //Function for adding the University details to the table
   			unset ($larrformData['idAccount']);
    	$this->insert($larrformData);
		$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
		
	}
	
	public function fnaddSponsorTemplateDetails($larrformData,$idSponsortemplate) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_sponsortemplatedetails";
		for($i = 0 ;$i<count($larrformData['idAccount']); $i++) {
		$larrdata = array('idSponsortemplate'=>$idSponsortemplate,
									'idAccount'=>$larrformData['idAccount'][$i],
									'UpdDate'=>$larrformData['UpdDate'],
									'UpdUser'=>$larrformData['UpdUser']
		);
		$db->insert($table,$larrdata);	
		}
	}
	
	public function fnsponsortemplatedetails($lintidSponsortemplate) { //Function for the view user 
    	//echo $lintidepartment;die();
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_sponsortemplatedetails"),array("a.*"))
						->where("a.idSponsortemplate = ?",$lintidSponsortemplate);
		//echo $select;die();					
		return $result = $lobjDbAdpt->fetchAll($select);
    }
	public function fnviewSponsorTemplateDetails($lintidSponsortemplate) { //Function for the view user 
    	
	 	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select 	= $lobjDbAdpt->select()
						->from(array("a" => "tbl_sponsortemplate"),array("a.*"))
						->join(array('b' => 'tbl_sponsortemplatedetails'),'a.idSponsortemplate  = b.idSponsortemplate')				
		            	->where("a.idSponsortemplate = ?",$lintidSponsortemplate);	
		return $result = $lobjDbAdpt->fetchAll($select);
    }
 	public function fnupdateSponsorTemplate($lintiidSponsortemplate,$larrformData) { //Function for updating the user
 	unset ($larrformData['idAccount']);	
 	unset ($larrformData['idSponsortemplatedetails']);	
	$where = 'idSponsortemplate = '.$lintiidSponsortemplate;
	$this->update($larrformData,$where);
	$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
		
	}
	
	public function fnDeleteSponsorTemplateDetails($lintiidSponsortemplate) { //Function for Delete Purchase order terms
			$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_sponsortemplatedetails";
	    	$where = $db->quoteInto('idSponsortemplate  = ?', $lintiidSponsortemplate);
	    	$db->delete($table, $where);
	}
	/*public function fnupdateSponsorTemplateDtls($idSponsortemplatedetails,$larrformData) { 
		unset($larrformData['templateName']);
		unset($larrformData['Description ']);
 		$db = Zend_Db_Table::getDefaultAdapter();
 		$table = "tbl_tempprogramrequirement";
 		for($i = 0 ;$i<count($larrformData['idAccount']); $i++) {
			$where = 'idSponsortemplatedetails = '.$idSponsortemplatedetails;
			$this->update($larrformData,$where);
			$db->update($table,$larrformData,$where);	
			
		}
	}*/
}
?>