<?php
class GeneralSetup_SubjectequivalentController extends Base_Base {//Controller class for Subjectequivalent
	private $_gobjlog;
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object

  	}
	
  	public function indexAction() { // action for the diaplay list
                $this->view->defaultlanguage = $this->gobjsessionsis->UniversityLanguage;
		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		$lobjSubjectmaster = new GeneralSetup_Model_DbTable_Subjectmaster(); //user model object
		
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $lobjSubjectmaster->fngetSubjectDetails();
		} else {
			$larrresult = $lobjSubjectmaster->fngetUserSubjectDetails($this->gobjsessionsis->UserCollegeId); //get user details
		}
		
		$lobjUniversityMasterList = $lobjSubjectmaster->fnGetUniversityMasterList();
		$lobjform->field1->addMultiOptions($lobjUniversityMasterList);
		
		$lobjCollegeList = $lobjSubjectmaster->fnGetCollegeList();
		$lobjform->field5->addMultiOptions($lobjCollegeList);
		$lobjform->field5->setAttrib('OnChange', 'fnGetColgDeptList');
		
		
		$lobjBranchList = $lobjSubjectmaster->fnGetBranchList();
		$lobjform->field8->addMultiOptions($lobjBranchList);
		
		
		
		$lobjDepartmentList = $lobjSubjectmaster->fnGetDepartmentList();
		$lobjform->field20->addMultiOptions($lobjDepartmentList);

		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionstudent->subjectmasterpaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->subjectmasterpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($lobjform->isValid ( $larrformData )) {
				 
				
			if($this->gobjsessionsis->UserCollegeId == '0') {
				$larrresult = $lobjSubjectmaster->fnSearchSubject($lobjform->getValues());
			} else {
				$larrresult = $lobjSubjectmaster->fnSearchUserSubject($lobjform->getValues(),$this->gobjsessionsis->UserCollegeId); //get user details
			}
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionstudent->subjectmasterpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectequivalent/index');
		}
  		
}
	
	public function newsubjectequivalentAction() { //Action for new subject equivalent
		$lobjform = new GeneralSetup_Form_Subjectequivalent (); //object of Subjectequivalent Form class
		$this->view->lobjform = $lobjform;//send object of Subjectequivalent Form class to view
		$lobjSubjectequivalent = new GeneralSetup_Model_DbTable_Subjectequivalent(); //object of Subjectequivalent model
		$editsubid = ( int ) $this->_getParam ( 'id' );
		$this->view->lobjform->IdOriginalSubject->setValue ( $editsubid ); 
		$resultsubjectequivalent = $lobjSubjectequivalent->fnViewSubjectEquivalentDetails($editsubid);//function to view subject equivalent details
		$this->view->resultTempsubjectequivalent=$resultsubjectequivalent;
		$subname=$this->_getParam ( 'name' );
		$this->view->subname = $subname;
		$subcode=$this->_getParam ( 'code' );
		$this->view->subcode = $subcode;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
        $auth = Zend_Auth::getInstance();
		$user=$auth->getIdentity()->iduser;
		
        $lobjSubjectMasterList = $lobjSubjectequivalent->fnGetSubjectMasterList($editsubid);//Function to get the subject master list
		$lobjform->EquivalentSubject->addMultiOptions($lobjSubjectMasterList);
		
		$lobjSubjectMasterList = $lobjSubjectequivalent->fnGetSubjectCodeList($editsubid);//function to get equivalent subjectcode list
		$lobjform->EquivalentSubjectCode->addMultiOptions($lobjSubjectMasterList);
	    
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$formData = $this->_request->getPost (); 
			if ($lobjform->isValid ( $formData )) {
				
			    $result=$lobjSubjectequivalent->fnDeleteFromDb($formData);//function to delete from  database
			    $lobjSubjectequivalent->fnInsertToDb($formData,$ldtsystemDate,$user);//function to insert to database
			    
			    // Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Subject Equivalent Edit Id=' . $formData['IdOriginalSubject'],
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectequivalent/index');	
				}	
		 } 
	}
	public function getsubjectcodeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$lobjSubjectequivalent = new GeneralSetup_Model_DbTable_Subjectequivalent(); //subject equivalent object
	    $equivalentsubcode = $this->_getParam('$equivalentsubcode');
	   	$credithours=$lobjSubjectequivalent->fnGetCreditHours($equivalentsubcode);//function to get credit hour of selected  equivalent subject
	    $crhour=$credithours['CreditHours'];
	    echo $credithours['CreditHours'];   
	}
	
	public function addeditemdeleteAction(){
	    $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();	
		$lobjSubjectequivalent = new GeneralSetup_Model_DbTable_Subjectequivalent();
		$equivalentsub =$this->_getParam('itemid');
		echo $equivalentsub;
	}
}