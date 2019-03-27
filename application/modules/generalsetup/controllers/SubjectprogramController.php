<?php
class GeneralSetup_SubjectprogramController extends Base_Base {
	private $locale;
	private $registry;
	private $Subjectprogramform;
	private $Subjectprogram;
	
	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');		
	}
	
	public function fnsetObj(){
		$this->lobjUser = new GeneralSetup_Model_DbTable_User();
		$this->Subjectprogramform = new GeneralSetup_Form_Subjectprogram();
		$this->Subjectprogram = new GeneralSetup_Model_DbTable_Subjectprogram();		
	}
  	
  	//Index Action
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		
		$larrresult = $this->Subjectprogram->fnGetProgramDetails();
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->Checklistpaginatorresult);
   	    	
   	    $lobjSubjectList = $this->lobjUser->fnGetSubjectList();
		$this->view->lobjform->field21->addMultiOptions($lobjSubjectList);
		
		
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
				
				$larrResult = $this->Subjectprogram->fnSearchProgram($this->lobjform->getValues ());
		    	$this->view->paginator = $this->lobjCommon->fnPagination($larrResult,$lintpage,$lintpagecount);
		    	$this->gobjsessionsis->Checklistpaginatorresult = $larrResult;	
			
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'maintenance', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectprogram/index');
		
		}
	}
	
	public function subjectprogramAction() { //Action for the updation and view of the  details
		
		$this->view->lobjSubjectprogramform= $this->Subjectprogramform;

		$lintIdProgram = ( int ) $this->_getParam ( 'id' );
		$this->view->Idprogram = $lintIdProgram;
		$this->view->lobjSubjectprogramform->IdProgram->setValue($lintIdProgram);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSubjectprogramform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjSubjectprogramform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		  $lobjSubjectList = $this->lobjUser->fnGetSubjectList();
		  $this->Subjectprogramform->IdSubject->addMultiOptions($lobjSubjectList);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrresult = $this->Subjectprogram->fnViewSubjectlist($lintIdProgram);
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Subjectprogramform->isValid($larrFormData)) {
				unset($larrFormData['Save']);				
				$lastinsertId=$this->Subjectprogram->fnaddSubjectprogramname($larrFormData);						
				//$this->_redirect( $this->baseUrl . '/generalsetup/subjectprogram/subjectprogramlist/id/'.$larrFormData['IdProgram'].'/IdSubjectprogramList/'.$lastinsertId);
			   $this->_redirect( $this->baseUrl . '/generalsetup/subjectprogram/subjectprogramlist/id/'.$larrFormData['IdProgram']);
			}
			
		}

	}
	
	public function subjectprogramlistAction() { //Action for the updation and view of the  details
		
		$this->view->lobjSubjectprogramform = $this->Subjectprogramform;
				$lintIdProgram = ( int ) $this->_getParam ( 'id' );
		$this->view->Idprogram = $lintIdProgram;
		$this->view->lobjSubjectprogramform->IdProgram->setValue($lintIdProgram);
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjSubjectprogramform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjSubjectprogramform->UpdUser->setValue( $auth->getIdentity()->iduser);

		$this->view->Idprogram = $lintIdProgram;
		if ($this->_getParam ('IdSubjectList')){
			$lintIdCheckList = ( int ) $this->_getParam ('IdSubjectList');
			$this->view->IdCheckList = $lintIdCheckList;
			$larrEditResult = $this->Subjectprogram->fnviewSubjectprogramDtls($lintIdCheckList);
			$this->Subjectprogramform->populate($larrEditResult);
		}
				
		  $lobjSubjectList = $this->lobjUser->fnGetSubjectList();
		  $this->Subjectprogramform->IdSubject->addMultiOptions($lobjSubjectList);
	
		
		//$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		//$this->view->lobjProgramchecklistform->UpdDate->setValue( $ldtsystemDate );
		//$auth = Zend_Auth::getInstance();
		//$this->view->lobjProgramchecklistform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrresult = $this->Subjectprogram->fnViewSubjectlist($lintIdProgram);
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Subjectprogramform->isValid($larrFormData)) {
				unset($larrFormData['Save']);
				if ($this->_getParam ('IdSubjectList')){
					$result=$this->Subjectprogram->fnupdateChecklistDtls($lintIdCheckList,$larrFormData );		
				}
				else{
					$lastinsertId=$this->Subjectprogram->fnaddSubjectprogramname($larrFormData);
				}
				
						
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectprogram/subjectprogramlist/id/'.$larrFormData['IdProgram']);
			}
			
		}

	}
	
	
      public function subjectprogramlisteditAction() { //Action for the updation and view of the  details
		
		$this->view->lobjSubjectprogramform = $this->Subjectprogramform;

		$lintIdProgram = ( int ) $this->_getParam ( 'id' );
		$this->view->Idprogram = $lintIdProgram;
		
		$lintIdSubjectList = ( int ) $this->_getParam ( 'IdSubjectList' );
		$this->view->IdSubjectList = $lintIdSubjectList;

		$larrEditResult = $this->Subjectprogram->fnviewSubjectprogramDtls($lintIdSubjectList);
		$this->Subjectprogramform->populate($larrEditResult);
		
		//$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		//$this->view->lobjProgramchecklistform->UpdDate->setValue( $ldtsystemDate );
		//$auth = Zend_Auth::getInstance();
		//$this->view->lobjProgramchecklistform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$larrresult = $this->Subjectprogram->fnViewSubjectlist($lintIdProgram);
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			$larrFormData = $this->_request->getPost();
			if ($this->Subjectprogramform->isValid($larrFormData)) {
				unset($larrFormData['Save']);
				$result=$this->Programchecklist->fnupdateChecklistDtls($lintIdCheckList,$larrFormData );				
				$this->_redirect( $this->baseUrl . '/generalsetup/programchecklist/programchecklist/id/'.$larrFormData['IdProgram']);
			}
			
		}

	}
	
}