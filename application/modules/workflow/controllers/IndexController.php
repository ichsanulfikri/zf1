<?php 
class Workflow_indexController extends Base_Base{
	
	private $locale;
	private $registry;
	private $lobjJob;
	private $lobjForm;
	private $_gobjlog;
	private $_lobjconfig;
	private $_id;
	private $_Catlist;
	private $_Statuslist;
	
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
		$this->view->title='Inbox';
		//get inbox data
		$larrresult= $this->lobjJob->fnGetInbox(540,"99");
	//	echo var_dump($larrresult);
		$this->view->lobjform= $this->lobjForm;
		$this->_Statuslist=$this->lobjJob->fnGetJobStatus();
		$this->_Catlist = $this->lobjJob->fnGetJobCategory();
		$this->view->lobjform->lsStatus->addMultiOptions(array("00"=>"All"));
		$this->view->lobjform->lsStatus->addMultiOptions($this->_Statuslist);
		$this->view->lobjform->lsCategory->addMultiOptions(array("00"=>"All"));
		$this->view->lobjform->lsCategory->addMultiOptions($this->_Catlist);
		//

		if(!$this->_getParam('search'))
			unset($this->gobjsessionsis->userpaginatorresult);
			
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if(isset($this->gobjsessionsis->userpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->userpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			echo var_dump($larrformData);
			if ($this->lobjForm->isValid ( $larrformData )) {
				$larrresult = $this->lobjJob->fnSearcJob(540,$larrformData ); //searching the values for the user
				//echo var_dump($larrresult);
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->userpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'user', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/workflow/index/index');
		}
		
	}	 
	public function outboxAction(){
		$this->view->title='Outbox';
		//get inbox data
		$larrresult= $this->lobjJob->fnGetOutbox(540,"99");
		//	echo var_dump($larrresult);
		$this->view->lobjform= $this->lobjForm;
		$this->_Statuslist=$this->lobjJob->fnGetJobStatus();
		$this->_Catlist = $this->lobjJob->fnGetJobCategory();
		$this->view->lobjform->lsStatus->addMultiOptions(array("00"=>"All"));
		$this->view->lobjform->lsStatus->addMultiOptions($this->_Statuslist);
		$this->view->lobjform->lsCategory->addMultiOptions(array("00"=>"All"));
		$this->view->lobjform->lsCategory->addMultiOptions($this->_Catlist);
		//
	
		if(!$this->_getParam('search'))
			unset($this->gobjsessionsis->userpaginatorresult);
			
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
	
		if(isset($this->gobjsessionsis->userpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->userpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			echo var_dump($larrformData);
			if ($this->lobjForm->isValid ( $larrformData )) {
				$larrresult = $this->lobjJob->fnSearcJob(540,$larrformData ); //searching the values for the user
				//echo var_dump($larrresult);
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->userpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'user', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/workflow/index/outbox');
		}
	
	}
}