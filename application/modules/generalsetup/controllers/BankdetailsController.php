<?php
class GeneralSetup_BankdetailsController extends Base_Base { //Controller Class for Bank Details
	
	private $lobjBank;
	private $lobjBankDetailsform;
    private $lobjMaintenance;
    private $_gobjlog;

public function init() { //initialization function
		$this->locale = Zend_Registry::get('Zend_Locale');
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
   	    $this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
   	    $this->fnsetObj();
	}
public function fnsetObj()
	{
		$this->lobjform = new App_Form_Search ();  //local object for Search Form
		$this->lobjBank = new GeneralSetup_Model_DbTable_Bankdetails();  //local object for Bank Details Model
		$this->lobjBankDetailsform = new GeneralSetup_Form_Bankdetails();  //local object for Bank Details Form
		$this->lobjMaintenance = new GeneralSetup_Model_DbTable_Maintenance();  //local object for Maintenance Model
		$this->gobjsessionsis = Zend_Registry::get('sis'); 
	}
public function indexAction() { //Index Action 
	    $this->view->lobjform = $this->lobjform; 
	    // Function to get all the details of Bank Details
	    $larrresult =$this->lobjBank->fnGetBankDetails(); 
	    /*echo "<pre>";
	    print_r($larrresult);die();*/
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionstudent->bankdtlspaginatorresult); 
   	    $lintpagecount = $this->gintPageCount;
	    $lobjPaginator = new App_Model_Common(); // Definitiontype model
	    $lintpage = $this->_getParam('page',1); // Paginator instance
		// Function to get all the Bank Names
		$larrBankNameList=$this->lobjBank->fngetBankNameList();
		$this->lobjform->field5->addMultiOptions($larrBankNameList);
		// Function to get all the Account Types
		$larrBankAccountTypeList =$this->lobjBank->fngetBankAccountTypeList();
		$this->lobjform->field8->addMultiOptions($larrBankAccountTypeList);
					if(isset($this->gobjsessionstudent->bankdtlspaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->bankdtlspaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
			if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				// Function to Search all the details of Bank Details
				$larrresult = $this->lobjBank->fnSearchBankDetails( $this->lobjform->getValues () ); 
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->bankdtlspaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/bankdetails/index');
		}
}
public function newbankdetailsAction() { //Action to add the new Bank Details	
	    $this->view->lobjBankDetailsform =$this->lobjBankDetailsform;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBankDetailsform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBankDetailsform->UpdUser->setValue( $auth->getIdentity()->iduser);
		// Function to get all the Bank Names
		$larrBankNameList=$this->lobjBank->fngetBankNameList();
		$this->lobjBankDetailsform->IdBank->addMultiOptions($larrBankNameList);
		// Function to get all the Account Types
		$larrBankAccountTypeList =$this->lobjBank->fngetBankAccountTypeList();
		$this->lobjBankDetailsform->AccountType->addMultiOptions($larrBankAccountTypeList);
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			
			unset ( $larrformData ['Save'] );//
			if ($this->lobjBankDetailsform->isValid ( $larrformData ))
			 {  
			 	// Function to add Bank Details
			 	$result = $this->lobjBank->fnaddBankDetails($larrformData);
			 	//print_r($result);die();
			 	// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New BankDetails Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/bankdetails/index');	
        }     
    }
}   
public function editbankdetailsAction(){ //Action to edit the Bank Details
		$this->view->lobjBankDetailsform = $this->lobjBankDetailsform; 
		 $IdAccount = $this->_getParam('id');	
		 	 $this->view->lobjBankDetailsform->IdAccount->setValue( $IdAccount);
		// Function to View the Bank Details
    	$larrresult = $this->lobjBank->fnViewBankDetails($IdAccount);
    	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjBankDetailsform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjBankDetailsform->UpdUser->setValue( $auth->getIdentity()->iduser);
		// Function to get all the Bank Names
    	$larrBankNameList=$this->lobjBank->fngetBankNameList();
		$this->lobjBankDetailsform->IdBank->addMultiOptions($larrBankNameList);
		// Function to get all the Account Types
		$larrBankAccountTypeList =$this->lobjBank->fngetBankAccountTypeList();
		$this->lobjBankDetailsform->AccountType->addMultiOptions($larrBankAccountTypeList);
	 	$this->lobjBankDetailsform->populate($larrresult);
    		if ($this->getRequest()->isPost()) {
    		    $formData = $this->getRequest()->getPost();
    		      		 		 
	    	if ($this->lobjBankDetailsform->isValid($formData)) {
	   			$lintIdAccount = $formData ['IdAccount'];
	   				  			
	   			// Function to update Bank Details
				$this->lobjBank->fnupdateBankDetails($formData,$lintIdAccount);				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'BankDetails Edit Id=' . $IdAccount,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/bankdetails/index');
			}
    	}
    } 
}

