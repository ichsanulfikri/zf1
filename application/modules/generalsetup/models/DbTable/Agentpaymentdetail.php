<?php


class GeneralSetup_Model_DbTable_Agentpaymentdetail extends Zend_Db_Table {
	protected $_name = 'tbl_agentpaymentdetails';
	private $lobjDbAdpt;

	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}


	//Get Intake List
	public function fnGetIntakeList(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_intake"),array("key"=>"a.IdIntake","value"=>"a.IntakeId"))
		->order("a.IntakeId");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	//	Get Intake List  By Intake
	public function fnGetIntakeListbyIntake($idintake){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_agentpaymentdetails"))
		->where("a.Intake = ".$idintake);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	//add agent payment details to the table
	public function fninsertPaymentdetails($larrformdata){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_agentpaymentdetails";
		$countofPayment = count($larrformdata['AgentTypegrid']);
		for($i=0;$i<$countofPayment;$i++) {
			if($larrformdata['AgentTypegrid'][$i] == 1)
			{
				$agent = "Staff";
			}
			else if($larrformdata['AgentTypegrid'][$i] == 2)
			{
				$agent = "Student";
			}
			else if($larrformdata['AgentTypegrid'][$i] == 3)
			{
				$agent = "Other";
			}

			$larrtepaccridiction = array(

										'AgentType'=>$agent,
										'Intake'=>$larrformdata['Intakegrid'][$i],
										'Amount'=>$larrformdata['Amountgrid'][$i],
										'UpdDate'=>$larrformdata['UpdDate'],
										'UpdUser'=>$larrformdata['UpdUser']

			);

			$db->insert($table,$larrtepaccridiction);
		}
	}
	
	public function fnaddpaymentdetails($data) {
		$this->insert($data);
	}
	 
	public function fnviewAgentPayment(){
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array("a"=>"tbl_agentpaymentdetails"),array('a.*'))
		->join(array("b"=>"tbl_intake"),'a.Intake = b.IdIntake',array('b.IntakeId'));
		//echo $select;die();
		$result = $this->fetchAll($select);
		return $result;
	}
	 
	public function fnDeletePayment($idPayment) {  // function to delete payment details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = 'tbl_agentpaymentdetails';
		$where = $db->quoteInto('idPayment = ?', $idPayment);
		$db->delete($table, $where);
	}
}