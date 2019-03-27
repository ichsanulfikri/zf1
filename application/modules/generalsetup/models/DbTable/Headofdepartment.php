<?php

/**
 * RegistrarList
 * 
 * @author Arun
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class GeneralSetup_Model_DbTable_Headofdepartment extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_headofdepartmentlist';
	
	private $lobjDbAdpt;
	
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}	
	
	public function fninsertHeadofDepartment($larrformdata,$lintIddepartment)
	{
    	$larrreglst['IdDepartment'] = $lintIddepartment;
    	$larrreglst['IdStaff'] = $larrformdata['IdStaff'];
    	$larrreglst['FromDate'] = $larrformdata['FromDate'];
    	$larrreglst['ToDate'] = '0000-00-00';
    	$larrreglst['Active'] = $larrformdata['Active'];
    	$larrreglst['UpdDate'] = $larrformdata['UpdDate'];
    	$larrreglst['UpdUser'] = $larrformdata['UpdUser'];
    
		$this->insert($larrreglst);
		
	}	
	

	
	public function fngethodList($lintIdProgram)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$lstrselectsql = $db->select()
									->from(array('a'=>'tbl_headofdepartmentlist'),array('IdHeadOfDepartmentList'=>'MAX(a.IdHeadOfDepartmentList)'))
									->where("a.IdDepartment = $lintIdProgram");
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
		$larrresultset = $db->fetchRow($lstrselectsql);	

		if(!empty($larrresultset['IdHeadOfDepartmentList']))
		{
			$lstrselectsql1 = $db->select()
									->from(array('a'=>'tbl_headofdepartmentlist'),array('a.*'))
									->where("a.IdHeadOfDepartmentList = ".$larrresultset['IdHeadOfDepartmentList']);
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
			$larrresultsetfinal = $db->fetchRow($lstrselectsql1);	

			return $larrresultsetfinal;
		}
		else 
			return 0;
	
	}
	
	
	public function fnupdatehodList($larrformdata,$lintIdDepartment)
	{
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$lstrselectsql = $db->select()
									->from(array('a'=>'tbl_headofdepartmentlist'),array('IdHeadOfDepartmentList'=>'MAX(a.IdHeadOfDepartmentList)'))
									->where("a.IdDepartment = $lintIdDepartment");
									//->where("reglst.IdStaff = ".$larrformdata['IdStaff']);
		$larrresultset = $db->fetchRow($lstrselectsql);	

		if(!empty($larrresultset['IdHeadOfDepartmentList']))
		{
	    	$larrreglst['ToDate'] = $larrformdata['FromDate'];
	    	$lstrwhere = "IdHeadOfDepartmentList = ".$larrresultset['IdHeadOfDepartmentList'];
			$db->update('tbl_headofdepartmentlist',$larrreglst,$lstrwhere);
			
		}
	}
	
}