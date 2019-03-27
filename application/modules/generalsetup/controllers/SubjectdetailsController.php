<?php
class GeneralSetup_SubjectdetailsController extends Base_Base {
	private $_gobjlog;
	public function init() { //initialization function
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$lobjform = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjform; //send the lobjuserForm object to the view
		$this->locale = Zend_Registry::get('Zend_Locale');
		if(!$this->_getParam('search'))
		unset($this->gobjsessionstudent->subjectmasterpaginatorresult);
	}

	public function indexAction() { // action for search and view
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
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectmaster/index');
		}
	}

	public function newsubjectdetailsAction() {
		$lobjform = new GeneralSetup_Form_Subjectdetails();
		$lobjsubjectdetail = new GeneralSetup_Model_DbTable_Subjectdetails();
		$this->view->lobjform = $lobjform;
		$editsubid = ( int ) $this->_getParam ( 'id' );
		$this->view->lobjform->IdSubject->setValue ( $editsubid );
		$subname=$this->_getParam ( 'name' );
		$this->view->subname = $subname;
		$subcode=$this->_getParam ( 'code' );
		$this->view->subcode = $subcode;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjform->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjform->UpdUser->setValue ( $auth->getIdentity()->iduser);

		$resultsubjectdetails = $lobjsubjectdetail->fngetsubjectdetailsbysub($editsubid);
		$this->view->subjectdetailslist = $resultsubjectdetails;
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$formData = $this->_request->getPost ();
			if ($lobjform->isValid ( $formData )) {
				$lobjsubjectdetail->fndeletesubjectdetails($editsubid);
				for($i=0; $i< count($formData['SubjectDetailId']);$i++) {
					$subjectdetail = array();
					$subjectdetail['IdSubject'] = $editsubid;
					$subjectdetail['SubjectDetailId'] = $formData['SubjectDetailId'][$i];
					$subjectdetail['SubjectDetailDesc'] = $formData['SubjectDetailDesc'][$i];
					$subjectdetail['UpdDate'] = $ldtsystemDate;
					$subjectdetail['UpdUser'] = $auth->getIdentity()->iduser;
					$lobjsubjectdetail->fnaddsubjectdetail($subjectdetail);
					// Write Logs
					$priority=Zend_Log::INFO;
					$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Subject Detail Add Id=' . $formData['IdSubject'],
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
					$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				}
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectdetails/index');
			}
		}
	}

}