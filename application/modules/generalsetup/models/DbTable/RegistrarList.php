<?php

/**
 * RegistrarList
 * 
 * @author Arun
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class GeneralSetup_Model_DbTable_RegistrarList extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_registrarlist';
	protected $_primary = "IdRegistrarList";
	private $lobjDbAdpt;
	
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function fnupdateRegistrarList($larrformdata,$lintIdUniversity)
	{
		$lstrselectsql = $this->lobjDbAdpt->select()
									->from(array('reglst'=>'tbl_registrarlist'),array('IdRegistrarList'=>'MAX(reglst.IdRegistrarList)'))
									->where("reglst.IdUniversity = $lintIdUniversity");
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
		$larrresultset = $this->lobjDbAdpt->fetchRow($lstrselectsql);	
		
		if(!empty($larrresultset['IdRegistrarList']))
		{
	    	$larrreglst['ToDate'] = $larrformdata['FromDate'];
	    	$lstrwhere = "IdRegistrarList = ".$larrresultset['IdRegistrarList'];
			$this->lobjDbAdpt->update('tbl_registrarlist',$larrreglst,$lstrwhere);
		}
	}
	
	public function fninsertRegistrarList($larrformdata,$lintIdUniversity)
	{
    	$larrreglst['IdUniversity'] = $lintIdUniversity;
    	$larrreglst['IdStaff'] = $larrformdata['IdStaff'];
    	$larrreglst['FromDate'] = $larrformdata['FromDate'];
    	$larrreglst['ToDate'] = $larrformdata['ToDate'];
    	$larrreglst['Active'] = $larrformdata['Active'];
    	$larrreglst['UpdDate'] = $larrformdata['UpdDate'];
    	$larrreglst['UpdUser'] = $larrformdata['UpdUser'];
    	
		$this->lobjDbAdpt->insert('tbl_registrarlist',$larrreglst);
		
	}
}
