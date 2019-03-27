<?php
class GeneralSetup_RolesController extends Base_Base {
	private $lobjprogrammaster;
	private $lobjcoursemasterForm;
	private $lobjRoles;
	private $lobjdeftype;
	private $_gobjlog;
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	       
	}
	
	public function fnsetObj(){
		$this->lobjdeftype = new App_Model_Definitiontype();
		$this->lobjcoursemaster = new GeneralSetup_Model_DbTable_Coursemaster();
		$this->lobjcoursemasterForm = new GeneralSetup_Form_Coursemaster (); 
		$this->lobjRoles = new GeneralSetup_Model_DbTable_Roles ();
	}
	
	public function indexAction() {
        $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
    	$this->view->title="Roles Setup";
		$this->view->lobjform = $this->lobjform; //send the lobjuniversityForm object to the view
		$larrresult = $this->lobjdeftype->fnGetDefinations('Role');

		if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->rolepaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->rolepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->rolepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjRoles->fnSearchRoles ( $this->lobjform->getValues (),'Role' ); //searching the values for the user
				//$this->view->paginator = $this->Paginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->rolepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'general-setup' ,'controller'=>'coursemaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/roles/index');
		}
		
	}
 
	public function editroleAction(){
    	$this->view->title="Edit Role";  //title
    	$resources = new App_Model_Resources();
    	$this->view->resultset = $resources->fngetAllRows();
    	$this->view->controller = $resources->fngetControllers();
    	
	    $role = new GeneralSetup_Model_DbTable_Roles();
	    $roleid = $this->_getparam('id');
    	$this->view->roleid = $roleid;
    	$larrroleset = $role->fngetRoles($roleid);
    	
    	$larridresource = array();
    	foreach($larrroleset as $larrrole)
    	{
    		$larridresource[] = $larrrole['idResources'];
    	}
    	
    	$this->view->larridresource = $larridresource;
	
    	$lobjform = new GeneralSetup_Form_Roles();
    	$this->view->lobjform = $lobjform;
    	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjform->UpdUser->setValue( $auth->getIdentity()->iduser);
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($this->view->lobjform->isValid($formData)) {
				$role->delete("idRoles = $roleid");
				//print_r($formData);die();
	    		$role->fnaddRoles($formData);//update university
				//$this->_redirect($this->view->url(array('module'=>'general-setup','controller'=>'coursemaster', 'action'=>'index'),'default',true));
				
	    		// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Roles Edit id=' . $roleid,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/roles/index');
			}
    	}
    }
}