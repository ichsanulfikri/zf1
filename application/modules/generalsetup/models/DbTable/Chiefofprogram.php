<?php

/**
 * RegistrarList
 * 
 * @author Arun
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class GeneralSetup_Model_DbTable_Chiefofprogram extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_chiefofprogramlist';
	protected $_primary = "IdChiefOfProgramList";
	private $lobjDbAdpt;
	
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}	
	
	public function fninsertChiefofProgramList($larrformdata,$lintIdProgram)
	{
    	$larrreglst['IdProgram'] = $lintIdProgram;
    	$larrreglst['IdStaff'] = $larrformdata['IdStaff'];
    	$larrreglst['FromDate'] = $larrformdata['FromDate'];
    	$larrreglst['ToDate'] = $larrformdata['ToDate'];
    	$larrreglst['Active'] = $larrformdata['Active'];
    	$larrreglst['UpdDate'] = $larrformdata['UpdDate'];
    	$larrreglst['UpdUser'] = $larrformdata['UpdUser'];
    	
		//$this->lobjDbAdpt->insert('tbl_registrarlist',$larrreglst);
		$this->insert($larrreglst);
		
	}	
	
	public function fnupdateChiefofProgramList($larrformdata,$lintIdProgram)
	{
		$lstrselectsql = $this->lobjDbAdpt->select()
									->from(array('cop'=>'tbl_chiefofprogramlist'),array('IdChiefOfProgramList'=>'MAX(cop.IdChiefOfProgramList)'))
									->where("cop.IdProgram = $lintIdProgram");
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
		$larrresultset = $this->lobjDbAdpt->fetchRow($lstrselectsql);	
		
		if(!empty($larrresultset['IdChiefOfProgramList']))
		{
	    	$larrreglst['ToDate'] = $larrformdata['FromDate'];
	    	$lstrwhere = "IdChiefOfProgramList = ".$larrresultset['IdChiefOfProgramList'];
			$this->lobjDbAdpt->update('tbl_chiefofprogramlist',$larrreglst,$lstrwhere);
		}
	}
	
	public function fngetChiefofProgramList($lintIdProgram)
	{
		$lstrselectsql1 = $this->lobjDbAdpt->select()
									->from(array('cop'=>'tbl_chiefofprogramlist'),array('IdChiefOfProgramList'=>'MAX(cop.IdChiefOfProgramList)'))
									->where("cop.IdProgram = $lintIdProgram");
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
		$larrresultset1 = $this->lobjDbAdpt->fetchRow($lstrselectsql1);			
		
		if(!empty($larrresultset1['IdChiefOfProgramList']))
		{
			$lstrselectsql = $this->lobjDbAdpt->select()
									->from(array('cop'=>'tbl_chiefofprogramlist'),array('IdStaff','FromDate','ToDate'))
									->where("cop.IdChiefOfProgramList = ".$larrresultset1['IdChiefOfProgramList']);
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
			$larrresultset = $this->lobjDbAdpt->fetchRow($lstrselectsql);	

			return $larrresultset;
		}
		else 
			return 0;
		
	}
	
	
}