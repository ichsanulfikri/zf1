<?php
class GeneralSetup_AgentpaymentdetailController extends Base_Base {
	private $lobjAgentpayment;
	private $_gobjlog;
	private $lobjAgentpaymentForm;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);		
	}
	public function fnsetObj(){	
		$this->lobjform = new App_Form_Search ();
		$this->lobjAgentpaymentForm = new GeneralSetup_Form_Agentpaymentdetail();
		$this->lobjAgentpayment = new GeneralSetup_Model_DbTable_Agentpaymentdetail();			
    }

	//Index Action to add the agent payment details
	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$this->view->lobjAgentpaymentForm = $this->lobjAgentpaymentForm;
		$lobjintake = $this->lobjAgentpayment->fnGetIntakeList();
		$this->lobjAgentpaymentForm->Intake->addMultiOptions($lobjintake);
		$this->view->agentpayment=$this->lobjAgentpayment->fnviewAgentPayment();
//		echo "<pre>";
//		print_r($this->view->agentpayment);die;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjAgentpaymentForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjAgentpaymentForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$formData = $this->_request->getPost (); //getting the values of lobjAgentpaymentForm data from post
			$this->lobjAgentpayment->fninsertPaymentdetails($formData);
			$this->_redirect( $this->baseUrl . '/generalsetup/agentpaymentdetail/index');
		}
	}
	
	//Action to delete Payment details
	public function deletepaymentAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idPayment = $this->_getParam('id', 0);
		$larrDelete = $this->lobjAgentpayment->fnDeletePayment($idPayment);	
		echo "1";
		$this->_redirect( $this->baseUrl . '/generalsetup/agentpaymentdetail/index');
		
	}
}