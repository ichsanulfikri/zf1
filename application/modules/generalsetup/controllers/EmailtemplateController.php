<?php
class GeneralSetup_EmailtemplateController extends Zend_Controller_Action {
	private $gobjsessionsis; //class session global variable
	private $gintPageCount;
	private $_gobjlog;
	
	public function init() { //initialization function
		$this->gobjsessionsis = Zend_Registry::get('sis'); //initialize session variable
		$lobjinitialconfigModel = new GeneralSetup_Model_DbTable_Initialconfiguration(); //user model object
		$larrInitialSettings = $lobjinitialconfigModel->fnGetInitialConfigDetails($this->gobjsessionsis->idUniversity);
		$this->gintPageCount = isset($larrInitialSettings['noofrowsingrid'])?$larrInitialSettings['noofrowsingrid']:"5";
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object	  
	}

	public function indexAction() {			
 		$lobjsearchform = new App_Form_Search(); //intialize search lobjuserForm
		$this->view->form = $lobjsearchform; //send the lobjsearchform object to the view				

		$lobjemailTemplateModel = new GeneralSetup_Model_DbTable_Emailtemplate();  // email template model object
		$larrresult = $lobjemailTemplateModel->fnGetTemplateDetails(); // get template details	
		
		  if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->emailtemplatepaginatorresult);
					
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model\
		$lintpage = $this->_getParam('page',1); // Paginator instance

		
		if(isset($this->gobjsessionsis->emailtemplatepaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->emailtemplatepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}	
						
		if ($this->_request->isPost() && $this->_request->getPost('Search')) { // search operation
			$larrformData = $this->_request->getPost();						
				if ($lobjsearchform->isValid($larrformData)) {	
					$larrresult = $lobjemailTemplateModel->fnSearchTemplate($lobjsearchform->getValues());										
		    		$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		    		$this->gobjsessionsis->emailtemplatepaginatorresult = $larrresult;						
				}			
		}
		//Clear
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect('/generalsetup/emailtemplate');
			$this->_redirect( $this->baseUrl . '/generalsetup/emailtemplate/index');
		
		}
	}
        	
	/*
	 * Add New Template
	 */
  	public function newtemplateAction() { 	
 		$lobjemailTemplateForm = new GeneralSetup_Form_Emailtemplate();  // email template form
		$this->view->form = $lobjemailTemplateForm;	
		$auth = Zend_Auth::getInstance();
		$this->view->form->UpdUser->setValue ( $auth->getIdentity()->iduser);		
		
    	$lobjDefinitionTypeModel = new App_Model_Definitiontype();	// model object	
		$larrDropdownValues = $lobjDefinitionTypeModel->fnGetDefinationMs('Email Template Module Name');  // get all 'Room Type' Definitions from DMS Table 
				      $this->view->form->idDefinition->addMultiOptions($larrDropdownValues);  		       						 
  		if ($this->_request->isPost() && $this->_request->getPost('Save')) { // save opeartion
			$larrformData = $this->_request->getPost();							
			if ($lobjemailTemplateForm->isValid($larrformData)) {		
				$editorData = $larrformData['content1'];		
																
				$lobjemailTemplateModel = new GeneralSetup_Model_DbTable_Emailtemplate();	// email template row 								   
			    $lintresult = $lobjemailTemplateModel->fnAddEmailTemplate($lobjemailTemplateForm->getValues(),$editorData); // add method			

			    // Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Email Template Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
			    //$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'emailtemplate', 'action'=>'index'),'default',true));
			    $this->_redirect( $this->baseUrl . '/generalsetup/emailtemplate/index');        
			 
			}			
		}
	}

	public function edittemplateAction() {		
 		
 		$idTemplate = $this->_getParam('id');
 		$lobjemailTemplateForm = new GeneralSetup_Form_Emailtemplate(); // email template form
		$this->view->form = $lobjemailTemplateForm;
		
    	$lobjDefinitionTypeModel = new App_Model_Definitiontype();	// model object	
		$larrDropdownValues = $lobjDefinitionTypeModel->fnGetDefinationMs('Email Template Module Name');  // get all 'Room Type' Definitions from DMS Table 
			  $this->view->form->idDefinition->addMultiOptions($larrDropdownValues);  		     
				//send the lobjemailTemplateForm object to the view		
        if ($this->_request->isPost() && $this->_request->getPost('Save')) {  // update operation	
            $larrformData = $this->_request->getPost();   
            unset($larrformData['Save']);            				
            if ($lobjemailTemplateForm->isValid($larrformData)) {
            		$idTemplate = $larrformData['idTemplate'];
                	$where = 'idTemplate = '. $idTemplate;					           
				 	$lobjemailTemplateModel = new GeneralSetup_Model_DbTable_Emailtemplate();	
				 	$editorData = $larrformData['TemplateBody'];							 	
                    $lobjemailTemplateModel->fnUpdateTemplate($where,$larrformData,$editorData); // update email template details		 	
                   
                    // Write Logs
				$priority=Zend_Log::INFO;
				$auth = Zend_Auth::getInstance();
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Email Template Edit Id=' . $idTemplate,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
                    // $this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'emailtemplate', 'action'=>'index'),'default',true));
                   $this->_redirect( $this->baseUrl . '/generalsetup/emailtemplate/index'); 
				} 			
        } else {
        		// 	get external panel row values by id and populate
				$lobjemailTemplateModel = new GeneralSetup_Model_DbTable_Emailtemplate(); //email template model object
		        $larrresult = $lobjemailTemplateModel->fnViewTemplte($idTemplate);
		       	$this->view->TemplateBody = $larrresult['TemplateBody'];

		        
				$lobjemailTemplateForm->populate($larrresult); // populate all the values to the form according to id			
  		}  	
  	}	  	
}