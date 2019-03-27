<?php 
class GeneralSetup_Model_DbTable_Language extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_language';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
     public function fngetLanguageDetails($languageid) { //Function to get the user details
        $result = $this->fetchAll("Language = ".$languageid);
        return $result;
     }

    public function fnaddLang($formData) { //Function for adding the University details to the table
    	
			$this->insert($formData);
	}
    
    public function fnupdateLanguage($formData,$lintIdLanguage) { //Function for updating the university
    	unset ( $formData ['Save'] );
		$where = 'id = '.$lintIdLanguage;
		$this->update($formData,$where);
    }
    
	public function fnSearchLang($post = array(), $lang=1) { //Function for searching the university details
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_language'),array('IdCoursemaster'))
			   ->where('a.system  like  ? "%"',$post['field3'])
			   ->where('a.Language =?',$lang);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	public function fnGetlang($id){
		$lstrSelect = $this->lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_language"),array("a.id","a.system","a.english","a.arabic"))
				 				 ->where("a.id = ?",$id);
		$larrResult = $this->lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}

}
?>