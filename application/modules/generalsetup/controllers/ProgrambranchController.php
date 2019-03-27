<?php
class GeneralSetup_ProgrambranchController extends Base_Base {
	private $lobjprogrambranch;
	private $lobjprogrambranchForm;
	private $lobjprogram;
	private $lobjcollegemaster;
	private $lobjsemester;
	private $_gobjlog;
	
	public function init() {
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->fnsetObj();
		  
	}
	
	public function fnsetObj(){
		$this->lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster();
		$this->lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$this->lobjprogrambranchForm = new GeneralSetup_Form_Programbranch ();		
		$this->lobjprogrambranch = new GeneralSetup_Model_DbTable_Programbranch();
		$this->lobjsemester = new GeneralSetup_Model_DbTable_Semester();
	}
	
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		
		if($this->gobjsessionsis->UserCollegeId == '0') {
			$larrresult = $this->lobjcollegemaster->fngetCollegemasterDetails(); //get user details
		} else {
			$larrresult = $this->lobjcollegemaster->fngetCollegemasterDetailsById($this->gobjsessionsis->UserCollegeId); //get user details
		}
		
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->programbranchpaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->programbranchpaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->programbranchpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
       
	    $larrdefmsresultset = $this->lobjprogrambranch->fnGetUniversityName();
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$this->lobjform->field5->addMultiOption($larrdefmsresult['IdUniversity'],$larrdefmsresult['Univ_Name']);
		}
				
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				
				
			if($this->gobjsessionsis->UserCollegeId == '0') {
				$larrresult = $this->lobjprogrambranch->fnSearchProgrambranch($this->lobjform->getValues()); 
			} else {
				$larrresult = $this->lobjprogrambranch->fnSearchUserProgrambranch($this->lobjform->getValues(),$this->gobjsessionsis->UserCollegeId);
			}
		
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->programbranchpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'programbranch', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/programbranch');
		}
		
	}
	
	public function newprogrambranchAction() { //title
    	$programlist = $this->lobjprogram->fnGetProgramList();
		$this->lobjprogrambranchForm->IdProgram->addMultiOptions($programlist);
		$larrsemesterresult = $this->lobjsemester->fngetlandscapeSemesterDetails();
		foreach($larrsemesterresult as $larrsemesterresult) {
			$this->lobjprogrambranchForm->IdSemester->addMultiOption($larrsemesterresult['IdSemester'],$larrsemesterresult['SemesterMainName']);
		}

		$this->view->lobjprogrambranchForm = $this->lobjprogrambranchForm;
		$IdCollege = $this->_getParam('id');
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjprogrambranchForm->UpdDate->setValue( $ldtsystemDate );
		$this->view->lobjprogrambranchForm->UpdUser->setValue( $auth->getIdentity()->iduser);		
		$this->view->lobjprogrambranchForm->IdCollege->setValue( $IdCollege);
		$larrCollegename = $this->lobjprogrambranch->fnGetCollegeName($IdCollege);
		$this->view->lobjprogrambranchForm->CollegeName->setValue( $larrCollegename['CollegeName']);

		$larrresult = $this->lobjprogrambranch->fnGetBranchLinkDetails($IdCollege);
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		
		$idbranchlink = $this->_getParam('idbranchlink');
		if($idbranchlink) {
			$this->lobjprogrambranchForm->IdProgram->setAttrib('readonly',true);
			//$this->lobjprogrambranchForm->Save->setLabel('Update');
			$larrEditBranhLink = $this->lobjprogrambranch->fnEditBranckLinkDetails($idbranchlink);
			$this->view->lobjprogrambranchForm->populate($larrEditBranhLink);
		}
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			if ($this->lobjprogrambranchForm->isValid ( $larrformData )) {
				
				if($idbranchlink) {
					$this->lobjprogrambranch->fnUpdateProgrambranch($larrformData['IdProgramBranchLink'],$larrformData);
					$this->_redirect( $this->baseUrl . '/generalsetup/programbranch/newprogrambranch/id/'.$larrformData['IdCollege']);
					//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'programbranch', 'action'=>'newprogrambranch' , 'id'=>$larrformData['IdCollege'] ),'default',true));	//redirect
				} else {
					$arrexistresult = $this->lobjprogrambranch->fnCheckExistingLink($larrformData['IdCollege'],$larrformData['IdProgram']);
					if(!empty($arrexistresult)) {
						echo '<script language="javascript">alert("Already Quota has been defined for this Combination")</script>';
					} else {
						$this->lobjprogrambranch->fnaddProgrambranch($larrformData);
						
						// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New quota add Id=' . $IdCollege,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
						//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'programbranch', 'action'=>'newprogrambranch' , 'id'=>$larrformData['IdCollege'] ),'default',true));	//redirect
						$this->_redirect( $this->baseUrl . '/generalsetup/programbranch/newprogrambranch/id/'.$larrformData['IdCollege']);
					}
				}
			}
		}
    }
}