<?php 
class GeneralSetup_Model_DbTable_Programlimithead extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_chlimit_head';
    private $lobjDbAdpt;
    
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
    public function fnGetHeadProgramIntake($program_id,$intake_id)
    {
        $sql = $this->lobjDbAdpt->select();
        $sql->from(array('a' => $this->_name))
            ->where('a.progid = ? ',(int)$program_id)
            ->where('a.intakeid = ? ',(int)$intake_id);
        
        $head = $this->lobjDbAdpt->fetchRow($sql);
         
        return $head;
    }
    
    public function fnGetDetailProgramIntake($clid)
    {
        $query = $this->lobjDbAdpt->select();
            $query->from('tbl_chlimit_detl')
                  ->where('clid = ?', (int)$clid)
                  ->order('rstart DESC');
            
            $details = $this->lobjDbAdpt->fetchAll($query);
        return $details;
    }
    
    public function fnAddDetail($data)
    {
        $insertDetail = new Zend_Db_Table('tbl_chlimit_detl');
        $insertDetail->insert($data);
    }
    
    public function fnAddHead($formData)
    {
        $this->insert($formData);
    }
    
    public function fnDeleteDetail($clid)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $table = 'tbl_chlimit_detl';
        $where = $db->quoteInto('clid = ?', $clid);
        $db->delete('tbl_chlimit_detl', $where);
    }
    
   
    
    
	public function getMaxChourAllow($progid,$intakeid,$gpa=null)
    {
    	 /*
		     * SELECT h.progid,h.intakeid,d.*
		FROM `tbl_chlimit_head` AS h
		INNER JOIN `tbl_chlimit_detl` d ON d.`clid`=h.clid
		WHERE `progid`= 4
		AND `intakeid` = 78
		AND (rstart <= 2.50 AND 2.50 <=rend)
		     */
    	
    	if(isset($gpa)){
	         $db = Zend_Db_Table::getDefaultAdapter();
	         $select = $db ->select()
						  ->from(array('h'=>$this->_name),array())
						  ->join(array('d'=>'tbl_chlimit_detl'),'d.`clid`=h.clid',array('chlimit'))
						  ->where("progid = ?",$progid)
						  ->where('intakeid = ?',$intakeid)
						  ->where('(rstart <= ?',$gpa)
						  ->where('rend >= ?)',$gpa);
			 return $row = $db->fetchRow($select);	
    	}else{
    		return null;
    	}					  
		
    }

}
?>