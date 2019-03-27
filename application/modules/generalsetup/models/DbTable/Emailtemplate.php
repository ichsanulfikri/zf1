<?php
class GeneralSetup_Model_DbTable_Emailtemplate extends Zend_Db_Table {
	
	protected $_name = 'tbl_emailtemplate'; // table name
	
	/*
	 * get all active email templates rows 
	 */
   	public function fnGetTemplateDetails() {   			    
		    $select = $this->select()
				->setIntegrityCheck(false)  	
				->join(array('a' => 'tbl_emailtemplate'),array('idTemplate'))				
				->where("Deleteflag = 1");
			$result = $this->fetchAll($select);		
			return $result->toArray(); 		    
   	}

   	/*
   	 * search by criteria   
   	 */
	public function fnSearchTemplate($post = array()) {		
		    $db = Zend_Db_Table::getDefaultAdapter();
			$select = $this->select()
							->setIntegrityCheck(false)  	
							->join(array('a' => 'tbl_emailtemplate'),array('idTemplate', 'TemplateName','TemplateFrom', 'TemplateHeader', 'TemplateFooter'))														
							->where('a.TemplateName like "%" ? "%"',$post['field3'])
							->where('a.TemplateFrom like "%" ? "%"',$post['field2'])
							->where('a.TemplateHeader like "%" ? "%"',$post['field4'])
							->where('a.TemplateFooter like "%" ? "%"',$post['field6'])
							->where('a.Deleteflag = ' . $post['field7']);										
			$result = $this->fetchAll($select);
			return $result->toArray();			
	}
	
	/*
	 * Add Email Template
	 */
	public function fnAddEmailTemplate($post,$editorData) {
		unset($post['idDefination']);
		$post['TemplateBody'] = $editorData;			
		$this->insert($post);
	}
	
	/*
	 * get single email template row values bu $id
	 */
    public function fnViewTemplte($idTemplate) {
    			
		$result = $this->fetchRow( "idTemplate = '$idTemplate'") ;
        return @$result->toArray();     // @symbol is used to avoid warning    	
    }
    
    /*
     * update email template 
     */
    public function fnUpdateTemplate($whereId,$formData,$editorData) {
    		unset($formData['idDefination']);
    			    		

    		
			$this->update($formData,$whereId);
    }
    	
}
