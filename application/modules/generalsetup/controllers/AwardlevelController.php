<?php
class GeneralSetup_AwardlevelController extends Base_Base {
	
	private $locale;
	private $registry;
	private $Awardlevelform;
	private $lobjNewawardform;
	private $Awardlevel;
	private $_gobjlog;
	private $lobjvalidator;
	
	public function init() {
		$this->fnsetObj();
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');		
	}
	
	
	public function fnsetObj(){
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();		
		$this->lobjAwardlevelform = new GeneralSetup_Form_Awardlevel();
		$this->lobjNewawardform	= new GeneralSetup_Form_Newaward();
		$this->Awardlevel = new GeneralSetup_Model_DbTable_Awardlevel();
		$this->lobjdeftype = new App_Model_Definitiontype();
	}
	
	//Index Action
	public function indexAction() {
		
		$this->view->lobjform = $this->lobjform; 
		$this->view->lobjAwardlevelform = $this->lobjAwardlevelform;
		
		/*$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjAwardlevelform->Award->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}*/
		
		$larrresult = $this->Awardlevel->fnGetDefinations('Award');
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->Checklistpaginatorresult);	    	
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if(isset($this->gobjsessionsis->Checklistpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->Checklistpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		//Maintenance Search 
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrFormData = $this->_request->getPost();
			if ($this->lobjform->isValid($larrFormData)) {				
                          $larrResult = $this->Awardlevel->fnSearchAward($this->lobjform->getValues ());
                          $this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
                          $this->gobjsessionsis->Checklistpaginatorresult = $larrResult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/awardlevel/index');
		
		}
	}
	
	
	public function awardlevelAction(){
		
		$this->view->lobjAwardlevelform= $this->lobjAwardlevelform;
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjAwardlevelform->IdLevel->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
			$this->lobjAwardlevelform->IdAllowanceLevel->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$lintAward = ( int ) $this->_getParam ( 'id' );
		$this->view->Idaward = $lintAward;
		$lintAwardDesc = ( int ) $this->_getParam ( 'id' );
		$this->view->lobjAwardlevelform->IdLevel -> setValue($lintAwardDesc);
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjAwardlevelform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjAwardlevelform->UpdUser->setValue( $auth->getIdentity()->iduser);
	
		
				$larrResult = $this->Awardlevel->fnSearchAwardlevels($lintAward);	
				
				if($larrResult){
				$this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->Checklistpaginatorresult = $larrResult;	
				}
		
			if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->lobjAwardlevelform->isValid($larrFormData)) {
			
				unset($larrFormData['Save']);		
				$this->Awardlevel->fnDeleteAwardlevel($larrFormData['IdLevel']);				
				$this->Awardlevel->fnaddAwardlevel($larrFormData);	
				
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Award Level Add id='.$lintAward,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
						
				$this->_redirect( $this->baseUrl . '/generalsetup/awardlevel/');
			}
			
		}	
		
	}
	
	public function awardlevellistAction() {
		
		$this->view->lobjAwardlevelform= $this->lobjAwardlevelform;
		
		$larrdefmsresultset = $this->lobjdeftype->fnGetDefinations('Award');
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjAwardlevelform->IdLevel->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjAwardlevelform->IdAllowanceLevel->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		
		
		$lintIdAllowance = ( int ) $this->_getParam ( 'id' );
		$this->view->IdAllowance = $lintIdAllowance;				
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjAwardlevelform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjAwardlevelform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$larrEditResult = $this->Awardlevel->fnviewAwardListDtls($lintIdAllowance);		
		$this->lobjAwardlevelform->populate($larrEditResult);
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->lobjAwardlevelform->isValid($larrFormData)) {
				unset($larrFormData['Save']);
				$result=$this->Awardlevel->fnupdateAwardlistDtls($lintIdAllowance,$larrFormData);	
						
					
				$this->_redirect( $this->baseUrl . '/generalsetup/awardlevel/awardlevel/id/'.$larrFormData['IdLevel']);
			}
			
		}

		
	}
	
	public function newawardAction(){
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjNewawardform = $this->lobjNewawardform;
		$defResult = $this->lobjdeftype->fnGetDefinationdata('Award');
		$this->view->lobjNewawardform->idDefType->setValue($defResult[0]['idDefType'] );
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			if (!$this->view->lobjNewawardform->isValid($this->getRequest()->getPost())) {
                          $this->view->abcd = 1;
                          return $this->render("newaward");
                        }
			$formData = $this->_request->getPost();
			unset($formData['Save']);
			if($this->lobjdeftype->fnaddAwardLevel($formData)){
        		$this->_redirect( $this->baseUrl . '/generalsetup/awardlevel');
      		}
		}
	}
	
	public function editawardAction(){
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$this->view->lobjNewawardform = $this->lobjNewawardform;
		$awardId = $this->_getParam('id', 0);
    	$ret = $this->lobjdeftype->fetchDetailAward($awardId);    	
    	$this->lobjNewawardform->populate($ret);
    	if ($this->_request->isPost() && $this->_request->getPost('Save')) {
    		$formData = $this->_request->getPost();
    		$defId = $formData['idDefinition'];
			unset($formData['Save']);
			unset($formData['idDefinition']);
			$this->lobjdeftype->fnupdateAwardLevel($defId,$formData);
			$this->_redirect( $this->baseUrl . '/generalsetup/awardlevel');
    	}
	}
	
}




?>