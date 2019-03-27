<?php
class GeneralSetup_SponsortemplateController extends Base_Base {
	private $locale;
	private $registry;
	private $sponsortemplateform;
	private $Sponsortemplate;
	private $_gobjlog;
	
	public function init() {
		$this->fnsetObj();
		$this->registry = Zend_Registry::getInstance();
		$this->locale = $this->registry->get('Zend_Locale');
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		
	}
	
	public function fnsetObj(){
		$this->sponsortemplateform = new GeneralSetup_Form_Sponsortemplate();
		$this->Sponsortemplate = new GeneralSetup_Model_DbTable_Sponsortemplate();		
	}
	
	public function indexAction() {
		$this->view->lobjform = $this->lobjform; 
		
		$larrresult = $this->Sponsortemplate->fngetSponsorTemplateDetails();
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionsis->sponsortemplatepaginatorresult);
   	    	
   	    $larrCharges =$this->Sponsortemplate->fnGetCharges();
   	  	$this->view->lobjform->field5->addMultiOptions($larrCharges);	
   	    	
		$lintpagecount = $this->gintPageCount;// Definitiontype model
		$lintpage = $this->_getParam('page',1); // Paginator instance
		if(isset($this->gobjsessionsis->sponsortemplatepaginatorresult)) {
			$this->view->paginator = $this->lobjCommon->fnPagination($this->gobjsessionsis->sponsortemplatepaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				$larrresult = $this->Sponsortemplate->fnSearchSponsorTemplate( $this->lobjform->getValues () ); //searching the values for the user
				$this->view->paginator = $this->lobjCommon->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->sponsortemplatepaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/sponsortemplate/index');
		}
		
	}
	
	public function newsponsortemplateAction() { //title
		$this->view->lobjsponsortemplateform= $this->sponsortemplateform;
		
		$larrCharges =$this->Sponsortemplate->fnGetCharges();
		$this->view->lobjsponsortemplateform->idAccount->addMultiOptions($larrCharges);
		
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsponsortemplateform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsponsortemplateform->UpdUser->setValue( $auth->getIdentity()->iduser);
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost (); //getting the values of lobjuserFormdata from post
			unset ( $larrformData ['Save'] );
			if ($this->sponsortemplateform->isValid ( $larrformData )) {
				$result = $this->Sponsortemplate->fnaddSponsorTemplate($larrformData);
				$this->Sponsortemplate->fnaddSponsorTemplateDetails($larrformData,$result);

				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New Sponsor Template Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/sponsortemplate/index');
			}
		}

	}
    
	public function sponsortemplatelistAction() { //Action for the updation and view of the  details
		
		$this->view->lobjsponsortemplateform= $this->sponsortemplateform;
		 
		$lintidSponsortemplate = ( int ) $this->_getParam ( 'id' );
		$this->view->idSponsortemplate = $lintidSponsortemplate;
		

		$larrCharges =$this->Sponsortemplate->fnGetCharges();
		$this->view->lobjsponsortemplateform->idAccount->addMultiOptions($larrCharges);
		
		 $SponsorTemplateDetails = $this->Sponsortemplate->fnviewSponsorTemplateDetails($lintidSponsortemplate);
		foreach($SponsorTemplateDetails as $larrresult)
		$this->sponsortemplateform->populate($larrresult);
		
		$larrsponsortemplatedetails =$this->Sponsortemplate->fnsponsortemplatedetails($lintidSponsortemplate);
		foreach($larrsponsortemplatedetails as $larrsponsortemplatedtls){
			$larridaccounts[]=$larrsponsortemplatedtls['idAccount'];
		}
		$this->view->lobjsponsortemplateform->idAccount->setValue($larridaccounts);
		

		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsponsortemplateform->UpdDate->setValue ( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsponsortemplateform->UpdUser->setValue ( $auth->getIdentity()->iduser);
		
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->_request->isPost ()) {
				$larrformData = $this->_request->getPost ();
				unset ( $larrformData ['Save'] );
				if ($this->sponsortemplateform->isValid ( $larrformData )) {
					$lintiidSponsortemplate = $larrformData ['idSponsortemplate'];
					$result=$this->Sponsortemplate->fnupdateSponsorTemplate($lintiidSponsortemplate, $larrformData );
					
					$this->Sponsortemplate->fnDeleteSponsorTemplateDetails($lintiidSponsortemplate);
					$this->Sponsortemplate->fnaddSponsorTemplateDetails($larrformData,$lintiidSponsortemplate); 
					
					// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'Sponsot Template Edit Id=' . $lintidSponsortemplate,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
					//$lintidSponsortemplatedetails = $larrformData ['idSponsortemplatedetails'];
					//$this->Sponsortemplate->fnupdateSponsorTemplateDtls($idSponsortemplatedetails, $larrformData );
					//$this->_redirect($this->view->url(array('module'=>'generalsetup' ,'controller'=>'subjectmaster', 'action'=>'index'),'default',true));
					$this->_redirect( $this->baseUrl . '/generalsetup/sponsortemplate/index');
				}
			}
		}
		$this->view->lobjsponsortemplateform= $this->sponsortemplateform;
	}
	
}