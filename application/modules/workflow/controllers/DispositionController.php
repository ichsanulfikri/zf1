<?php 
class Workflow_DispositionController extends Base_Base {
	private $locale;
	private $registry;
	private $lobjJob;
	private $lobjForm;
	private $_gobjlog;
	private $_lobjconfig;
	
	public function init() { //initialization function
		$this->view->translate =Zend_Registry::get('Zend_Translate');
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		Zend_Form::setDefaultTranslator($this->view->translate);
		$this->fnsetObj();
	}
	public function fnsetObj() {
		$this->lobjJob = new Workflow_Model_DbTable_Myjob(); //user model object
		$this->lobjCommon = new App_Model_Common();
		$this->lobjForm = new Workflow_Form_Workflow(); //intialize user lobjuserForm
		$this->registry = Zend_Registry::getInstance();
		$this->view->locale = $this->locale = $this->registry->get('Zend_Locale');
	}
	public function indexAction(){
		$ivent = $this->_getParam('ivent');
		$this->view->param = $ivent;
		$formid = $this->_getParam('formid');
		$url = $this->lobjJob->fnGetFormURL($formid);
		$workflow = $this->lobjJob->fnGetWorkflow($ivent);
		//echo var_dump($url);
		$this->view->lobjform = $this->lobjForm;
		$this->view->lobjform->lsStatus->addMultiOptions($this->lobjJob->fnGetJobStatus());
		$this->view->url = $url;
		$this->view->headtitle = 'Promotion';
		$this->view->title = 'Disposition';
		$this->view->workflowtitle = 'History of Disposition';
		$this->view->workflow = $workflow;
		
	}
}

?>