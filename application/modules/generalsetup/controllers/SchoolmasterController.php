<?php
error_reporting (E_ALL ^ E_NOTICE);
class GeneralSetup_SchoolmasterController extends Base_Base { //Controller for the User Module
	private $_gobjlog;
	
    public function init() {
		$this->fnsetObj();
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate); 
   	    $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object   	   
	}
	
	public function fnsetObj(){
			$this->lobjschoolForm = new GeneralSetup_Form_Schoolmaster (); 
			$this->lobjschoolmodel = new GeneralSetup_Model_DbTable_Schoolmaster();	
	}
	
	public function indexAction() { // action for search and view
		$this->view->lobjform = $this->lobjform;
		$larrresult =$this->lobjschoolmodel->fngetSchoolDetails(); //get user details
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->schoolpaginatorresult);
   	    		
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance

		if(isset($this->gobjsessionsis->schoolpaginatorresult)) {
			$this->view->paginator =  $this->lobjCommon->fnPagination($this->gobjsessionsis->schoolpaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator =  $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->lobjschoolmodel->fnSearchSchool( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->schoolpaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/schoolmaster/index');
		}
	}

	public function newschoolmasterAction() { //Action for creating the new user
		$lobjDepartmentmasterForm = new GeneralSetup_Form_Schoolmaster(); //intialize user lobjuserForm
		$this->view->lobjschoolmasterForm = $this->lobjschoolForm; //send the lobjuserForm object to the view
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjschoolmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjschoolmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$lobjcountry = $this->lobjschoolmodel->fnGetCountryList();
		$this->view->lobjschoolmasterForm->country->addMultiOptions($lobjcountry);
		$this->view->lobjschoolmasterForm->country->setValue ($this->view->DefaultCountry);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->lobjschoolForm->isValid ($larrformData)) {
				/*echo "<pre>";
					print_r($larrformData);
					die();*/
				$result = $this->lobjschoolmodel->fnaddschool($larrformData); //instance for adding the lobjuserForm values to DB
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New School Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/schoolmaster/index');
			}
		}
	}

	public function schoolmasterlistAction() { //Action for the updation and view of the user details
		$this->view->lobjschoolmasterForm =  $this->lobjschoolForm;//send the lobjuserForm object to the view
		
		$lintidepartment = ( int ) $this->_getParam ( 'id' );
		$this->view->idSchool = $lintidepartment;
		$lobjcountry = $this->lobjschoolmodel->fnGetCountryList();
		$this->view->lobjschoolmasterForm->country->addMultiOptions($lobjcountry);
		
		    $larrresult = $this->lobjschoolmodel->fnViewSchool($lintidepartment); //getting user details based on userid
		   
		    $PermCity = $larrresult['idCity'];
		    $this->view->lobjschoolmasterForm->idState->addMultiOptions($this->lobjschoolmodel->fnGetStateList($larrresult['country']));
		    $this->view->lobjschoolmasterForm->idCity->addMultiOptions($this->lobjschoolmodel->fnGetcityList($larrresult['idState']));
		   
		    $this->lobjschoolForm->populate($larrresult);
	    if($PermCity == 12) {
	        $this->view->lobjschoolmasterForm->idCity->addMultiOption('12','Others');
		 }
		    $arrworkphone = explode("-",$larrresult ['workPhone']);
			$this->view->lobjschoolmasterForm->workcountrycode->setValue ( $arrworkphone [0] );
			$this->view->lobjschoolmasterForm->workstatecode->setValue ( $arrworkphone [1] );
			$this->view->lobjschoolmasterForm->workPhone->setValue ( $arrworkphone [2] );
    	  					
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjschoolmasterForm->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjschoolmasterForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		//$this->view->lobjschoolmasterForm->idCity->setValue ( $larrresult['idCity'] );
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			$formData = $larrformData;
		     unset( $larrformData ['Save'] );
				if ($this->lobjschoolForm->isValid ( $larrformData )) {	
					
					$this->lobjschoolmodel->fnupdateSchool($larrformData,$lintidepartment);
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'School Edit Id=' . $lintidepartment,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					$this->_redirect( $this->baseUrl . '/generalsetup/schoolmaster/index');				
			}
		}
	}

public function getcountrystateslistAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		//Get Country Id
		$lintIdCountry = $this->_getParam('idCountry');

		$larrCountryStatesDetails = $this->lobjCommon->fnResetArrayFromValuesToNames($this->lobjschoolmodel->fnGetStateCityList($lintIdCountry));
		$larrCountryStatesDetails[]=array('key'=>'253',name=>'Others');
		echo Zend_Json_Encoder::encode($larrCountryStatesDetails);
	}
	
}