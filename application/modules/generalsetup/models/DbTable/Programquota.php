<?php 
class GeneralSetup_Model_DbTable_Programquota extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_programquota';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

    public function fnaddProgramQuota($result,$formData) { //Function for adding the University details to the table
		 $count = count($formData['IdQuotagrid']);
        for($i = 0;$i<$count;$i++) {
    		$data = array('IdProgram' => $result,
    					  'IdQuota'=> $formData['IdQuotagrid'][$i],
    					  'Quota'=> $formData['Quotagrid'][$i],
    					  'UpdDate'=> $formData['UpdDate'],
    					  'UpdUser'=> $formData['UpdUser']);
   $this->insert($data);
    	}
	}


}
?>