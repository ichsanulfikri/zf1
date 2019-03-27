<?php
class GeneralSetup_CityController extends Base_Base{
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
		$this->lobjStateForm = new GeneralSetup_Form_City(); 
		$this->lobjCountry = new GeneralSetup_Model_DbTable_City();
	}    
	public function indexAction() {
		$this->view->lobjform = $this->lobjform;
		$larrresult = $this->lobjstate->fnGetStateListDetails();		
		
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->citypaginatorresult);
   	    	
		$lintpagecount = $this->gintPageCount;
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		if(isset($this->gobjsessionsis->citypaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionsis->citypaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		
		if ($this->_request->isPost() && $this->_request->getPost('Search')) {
			$larrformData = $this->_request->getPost();
			if ($this->_request->isPost()) {
				$larrformData = $this->_request->getPost();
				if ($this->lobjform->isValid($larrformData)) {
					
					//checking the data and calling the search fuction
					$larrresult = $this->lobjstate->fnSearchStates($this->lobjform->getValues());
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->citypaginatorresult = $larrresult;
				}
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
			$this->_redirect( $this->baseUrl . '/generalsetup/city/index');
		}
	}
        	
  	public function citylistAction() {
  		
  		$this->view->lobjStateForm = $this->lobjStateForm;
  		
  		$this->view->lobjStateForm->CityName->setAttrib('validator', 'validateCityName');  	
  		  		
     	$this->view->lobjStateForm->CityCode->setAttrib('validator', 'validateCityCode'); 		
  		
  		$idCountry = $this->_getParam("id");
  		$this->view->idcountry = $idCountry;
  				
        $lobjName =$this->lobjCountry->fnStateName($idCountry); 
		
        $ldtsystemDate = date ( 'Y-m-d H:i:s' );
        $this->view->lobjStateForm->UpdDate->setValue($ldtsystemDate);
        $auth = Zend_Auth::getInstance();
		$this->view->lobjStateForm->UpdUser->setValue ( $auth->getIdentity()->iduser);
		$this->view->lobjStateForm->idState->setValue($lobjName['StateName']);
        $this->view->lobjStateForm->idState->setAttrib('readonly',true);
        
        $larrresult = $this->lobjCountry->fnGetcitydetailslist($idCountry);
        
       
		$lobjPaginator = new App_Model_Common(); // Definitiontype model
		$lintpage = $this->_getParam('page',1);
		$lintpagecount = $this->gintPageCount;;
		
		$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		$this->gobjsessionstudent->statepaginatorresult = $larrresult;

		if($this->_getParam('edit'))
		{
			$arrresult = $this->lobjCountry->fncityinfodtl($idCountry,$this->_getParam('idCity'));
		    $idCountry = $arrresult['idState'];
            $lobjName =$this->lobjCountry->fnStateName($idCountry); 
			$this->lobjStateForm->populate($arrresult);
			$this->view->lobjStateForm->idState->setValue($lobjName['StateName']);
		}
         
        //on click of save button 
  		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
		$larrformData = $this->_request->getPost();		
	
			
			if($this->_getParam('edit'))
			{
				if ($this->lobjStateForm->isValid($larrformData)) {
					unset($larrformData['Save']);
					
					$idCity = $this->_getParam('idCity');
					$larrformData['idState']=$idCountry;
				
					$UpdateData =array('idState'=>$idCountry,
					                   'CityCode'=>$this->_getParam('CityCode'),
									   'CityName'=>$this->_getParam('CityName'),
									   'UpdDate'=>$this->_getparam('UpdDate'),
	                                   'UpdUser'=>$this->_getparam('UpdUser'),
									   'Active'=>$this->_getparam('Active'));
					
					$larrresult = $this->lobjCountry->fnUpdateCity($UpdateData,$idCity);
					
              	$this->_redirect( $this->baseUrl.'/generalsetup/city/citylist/id/'.$idCountry);
				   }
				
			}else {
			//checks for the form validation
			if ($this->lobjStateForm->isValid($larrformData)) {
				
				unset($larrformData['Save']);
				
				$larrformData['idState']=$idCountry;
			
				$insertData =array('idState'=>$idCountry,
				                   'CityCode'=>$this->_getParam('CityCode'),
								   'CityName'=>$this->_getParam('CityName'),
								   'UpdDate'=>$this->_getparam('UpdDate'),
                                   'UpdUser'=>$this->_getparam('UpdUser'),
								   'Active'=>$this->_getparam('Active'));
				//print_r($insertData);die();

				$larrresult = $this->lobjCountry->fnAddCity($insertData);
				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'kodya edit id=' . $idCountry,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				
               $this->_redirect( $this->baseUrl.'/generalsetup/city/citylist/id/'.$idCountry);
			
			   }
	       }   
		  
		}

	}
	
	public function getcitynameAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();		
		$cityName = $this->_getParam('cityName');	
		$statid = $this->_getParam('stateId');				
		$larrDetails = $this->lobjCountry->fngetcityname($cityName,$statid);		
		echo $larrDetails['CityName'];	
		
	}
	
	public function getcitycodeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();		
		$cityCode = $this->_getParam('cityCode');	
		$statid = $this->_getParam('stateId');				
		$larrDetails = $this->lobjCountry->fngetcitycode($cityCode,$statid);		
		echo $larrDetails['CityName'];
		
		
	}
  	
}