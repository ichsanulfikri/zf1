<?php
class AgentloginController extends Zend_Controller_Action {
	private $lobjUser;
	private $lobjStudentApplicationForm;
	private $lobjcollegemaster;
	private $lobjprogrambranch;
	private $lobjstudentapplication;
	private $lobjstudenteducation;
	private $lobjplacementtest;
	public $lobjCommon;
	private $lobjsponsor;
	private $lobjstaff;
	
	
	public function init() {
		$this->_helper->layout()->setLayout('/reg/usty');
		$this->fnsetObj();		
  	}

	public function fnsetObj(){
		$this->lobjCommon = new App_Model_Common();
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();
		$this->lobjStudentApplicationForm = new Application_Form_Manualapplication();
		$this->lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster();	
		$this->lobjprogrambranch = new GeneralSetup_Model_DbTable_Programbranch();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjstudentapplication = new Application_Model_DbTable_Studentapplication();
		$this->lobjstudenteducation = new Application_Model_DbTable_Studenteducation();
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjplacementtest = new Application_Model_DbTable_Placementtest();	
		$this->lobjsponsor = new GeneralSetup_Model_DbTable_Sponsor();	
		$this->lobjstaff = new GeneralSetup_Model_DbTable_Staffmaster();
	}
	
	function indexAction() {
		
	}
	
	

   function loginAction() {
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis');
        $lobjform = new App_Form_Login(); //intialize login form
               // echo $this->view->url(array('module'=>'application' ,'controller'=>'manualapplication', 'action'=>'index'),'default',true);die();
        $this->view->lobjform = $lobjform; //send the form object to the view

        
        if ($this->_request->isPost()) {
	        Zend_Loader::loadClass('Zend_Filter_StripTags');
	        $filter = new Zend_Filter_StripTags();
	        $username = $filter->filter($this->_request->getPost('username'));
	        $password = $filter->filter($this->_request->getPost('password'));

	        
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$authathen = new App_Model_Agent();
            $result = $authathen->fnAgentauth($username,$password);
            if ($result) {
				$this->_redirect( $this->baseUrl . '/agentlogin/index');
            } else {
            	$this->view->alertError = 'Login failed. Either username or password is incorrect';
            }     
        }
		$this->render(); //render the view
    }


    public function logoutAction() {
    	Zend_Session:: namespaceUnset('sis');
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();    	 
		//$this->_redirect($this->view->url(array('controller'=>'index', 'action'=>'login'),'default',true));
		$this->_redirect( $this->baseUrl . '/index/login');
    }
	
}