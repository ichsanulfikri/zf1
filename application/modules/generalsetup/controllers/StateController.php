<?php
class GeneralSetup_StateController extends Base_Base{
	private $lobjstate;
	private $lobjStateForm;
	private $lobjCountry;
	private $_gobjlog;
	public function init() 
	{
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->fnsetObj();
   	    
	}
	public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search (); //searchform
		$this->lobjstate = new GeneralSetup_Model_DbTable_State();
		$this->lobjStateForm = new GeneralSetup_Form_State(); 
		$this->lobjCountry = new GeneralSetup_Model_DbTable_Country();
	}    
	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult = $this->lobjCountry->fnGetCountryListDetails();
		
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->statepaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if(isset($this->gobjsessionsis->statepaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->statepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrformData = $this->_request->getPost();
			if ($this->_request->isPost()) {
				$larrformData = $this->_request->getPost();
				if ($this->lobjform->isValid($larrformData)) {
					
					//checking the data and calling the search fuction
					$larrresult = $this->lobjstate->fnSearchCountries($this->lobjform->getValues());
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->statepaginatorresult = $larrresult;
				}
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/state/index');
		}
	}
        	
  	public function statelistAction() {
  		
  		$this->view->lobjStateForm = $this->lobjStateForm;
  		
  		$this->view->lobjStateForm->StateName->setAttrib('validator','validateUsername');
  		
  		$this->view->lobjStateForm->StateCode->setAttrib('validator','validateStatecode');
  		
  		$idCountry = $this->_getParam("id");
		$this->view->idcountry=$idCountry;
        $lobjName =$this->lobjstate->fnName($idCountry); 
		
        $ldtsystemDate = date ( 'Y-m-d H:i:s' );
        $this->view->lobjStateForm->UpdDate->setValue($ldtsystemDate);
        $auth = Zend_Auth::getInstance();
		$this->view->lobjStateForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$this->view->lobjStateForm->idCountry->setValue($lobjName['CountryName']);
        $this->view->lobjStateForm->idCountry->setAttrib('readonly',true);
        
        $larrresult = $this->lobjstate->fnGetstatedetailslist($idCountry);
        
       
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1);
		$lintpagecount = $this->gintPageCount;;
		
		$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		$this->gobjsessionstudent->statepaginatorresult = $larrresult;

		if($this->_getParam('edit'))
		{
			$arrresult = $this->lobjstate->fnstateinfodtl($idCountry,$this->_getParam('idState'));
		    $idCountry = $arrresult['idCountry'];
            $lobjName =$this->lobjstate->fnName($idCountry); 
			$this->lobjStateForm->populate($arrresult);
			$this->view->lobjStateForm->idCountry->setValue($lobjName['CountryName']);
		}
         
        //on click of save button 
  		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
		$larrformData = $this->_request->getPost();		
			
			if($this->_getParam('edit'))
			{
				if ($this->lobjStateForm->isValid($larrformData)) {
					unset($larrformData['Save']);
					
					$idState = $this->_getParam('idState');
					$larrformData['idCountry']=$idCountry;
				
					$UpdateData =array('idCountry'=>$idCountry,
									   'StateName'=>$this->_getParam('StateName'),
									   'StateCode'=>$this->_getParam('StateCode'),
									   'UpdDate'=>$this->_getparam('UpdDate'),
	                                   'UpdUser'=>$this->_getparam('UpdUser'),
									   'Active'=>$this->_getparam('Active'));
	
					$larrresult = $this->lobjstate->fnUpdateState($UpdateData,$idState);
					
              	$this->_redirect( $this->baseUrl.'/generalsetup/state/statelist/id/'.$idCountry);
				   }
				
			}else {
			//checks for the form validation
			if ($this->lobjStateForm->isValid($larrformData)) {
				
				unset($larrformData['Save']);
				
				$larrformData['idCountry']=$idCountry;
			
				$insertData =array('idCountry'=>$idCountry,
								   'StateName'=>$this->_getParam('StateName'),
								   'StateCode'=>$this->_getParam('StateCode'),
								   'UpdDate'=>$this->_getparam('UpdDate'),
                                   'UpdUser'=>$this->_getparam('UpdUser'),
								   'Active'=>$this->_getparam('Active'));

				$larrresult = $this->lobjstate->fnAddState($insertData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'state edit id=' . $idCountry,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
				
               $this->_redirect( $this->baseUrl.'/generalsetup/state/statelist/id/'.$idCountry);
			
			   }
	       }   
		  
		}

	}
	
	
	public function getstatenameAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();		
		$StatName = $this->_getParam('StateName');	
		$Contry = $this->_getParam('countryId');				
		$larrDetails = $this->lobjstate->fngetstatename($StatName,$Contry);		
		echo $larrDetails['StateName'];		
	}
	
	
	
	public function getstatecodeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();		
		$StatCode = $this->_getParam('StateCode');	
		$Contry = $this->_getParam('countryId');				
		$larrDetails = $this->lobjstate->fngetstatecode($StatCode,$Contry);		
		echo $larrDetails['StateCode'];		
	}
  	
}