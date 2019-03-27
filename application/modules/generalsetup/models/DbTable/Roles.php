<?php 
class GeneralSetup_Model_DbTable_Roles extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_roleresources';
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
	
	
    public function fnaddRoles($formData) { //Function for adding the University details to the table
    	$count = count($formData['Action']);
        for($i = 0;$i<$count;$i++) {
    		$data = array('idRoles' => $formData['idrole'],
    					  'idResources'=> $formData['Action'][$i],
    					  'modified'=> $formData['UpdDate'],
    					  'created'=> $formData['UpdDate']);
   			$this->insert($data);
    	}
	}
    
	public function fngetRoles($lintidrole)
	{
		$larrresult = $this->fetchAll("idRoles = $lintidrole");
		
		return $larrresult->toArray();
	}


	public function fngetRoleResourcesModules($lintidrole)
	{
		$lstrselectsql = $this->lobjDbAdpt->select()
										->from(array("role"=>"tbl_roleresources"))
										->join(array("rsc"=>"tbl_resources"),"role.idResources = rsc.idResources",array("rsc.Module","rsc.Controller","rsc.Action", "rsc.RouteName"))
										->where("role.idRoles = $lintidrole")
										->group("rsc.Module");
		
			
		$larrresultset = $this->lobjDbAdpt->fetchAll($lstrselectsql);

		return $larrresultset;
	}
	
	
	public function fngetRoleResourcesControllers($lintidrole)
	{
		$lstrselectsql = $this->lobjDbAdpt->select()
										->from(array("role"=>"tbl_roleresources"),array("role.idRoles"))
										->join(array("rsc"=>"tbl_resources"),"role.idResources = rsc.idResources",array("rsc.Module","rsc.Controller","rsc.Action"))
										->where("role.idRoles = $lintidrole")
										->group("rsc.Controller");
								
		$larrresultset = $this->lobjDbAdpt->fetchAll($lstrselectsql);

		return $larrresultset;
	}
	
	public function fngetRoleResourcesRouteNames($lintidrole)
	{
		$lstrselectsql = $this->lobjDbAdpt->select()
										->from(array("role"=>"tbl_roleresources"),array("role.idRoles"))
										->join(array("rsc"=>"tbl_resources"),"role.idResources = rsc.idResources",array("rsc.RouteName"))
										->where("role.idRoles = $lintidrole")
										->group("rsc.RouteName");
										
		$larrresultset = $this->lobjDbAdpt->fetchAll($lstrselectsql);

		return $larrresultset;
	}
	
	
	public function fnSearchRoles($post = array(),$defms) { 
		$select = $this->select()
							->setIntegrityCheck(false) 
							->join(array('dtms' => 'tbl_definationtypems'),array())
                       		->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType')
                       		->where('dms.DefinitionDesc  like "%" ? "%"',$post['field3'])
                       		->where('dms.BahasaIndonesia  like "%" ? "%"',$post['field2'])
                       		->where('dtms.defTypeDesc = ?', $defms)
                       		->where( "dms.Status = ".$post["field7"])
                       		->where( "dtms.Active = ".$post["field7"])
                       		->order('dms.DefinitionDesc');               
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
}
?>