<?php
class GeneralSetup_SubjectcategoryController extends Base_Base { //Controller Class for Bank Details
	
	
	private $lobjsubjectcategoryform;
    //private $lobjMaintenance;
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
		$this->lobjSubjectcategory = new GeneralSetup_Model_DbTable_Subjectcategory();  //local object for Bank Details Model
		$this->lobjsubjectcategoryform = new GeneralSetup_Form_Subjectcategory ();  //local object for Bank Details Form
		//$this->lobjMaintenance = new GeneralSetup_Model_DbTable_Maintenance();  //local object for Maintenance Model
		$this->gobjsessionsis = Zend_Registry::get('sis'); 
	}
public function indexAction() { //Index Action 
	    $this->view->lobjform = $this->lobjform; 
	    // Function to get all the details of Subject Category
	    $larrresult =$this->lobjSubjectcategory->fnGetSubjectcategory(); 
	    
		 if(!$this->_getParam('search'))
   	    	unset($this->gobjsessionstudent->subjectcategorypaginatorresult); 
   	    $lintpagecount = $this->gintPageCount;
	    $lobjPaginator = new App_Model_Common(); // Definitiontype model
	    $lintpage = $this->_getParam('page',1); // Paginator instance
		// Function to get all the Subject Category Names
		$larrSubjectCategoryList=$this->lobjSubjectcategory->fngetSubjectCategoryList();
		$this->lobjform->field5->addMultiOptions($larrSubjectCategoryList);		
		
					if(isset($this->gobjsessionstudent->subjectcategorypaginatorresult)) {
			$this->view->paginator = $lobjPaginator->fnPagination($this->gobjsessionstudent->subjectcategorypaginatorresult,$lintpage,$lintpagecount);
		} else {
			$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
		}
			if ($this->_request->isPost () && $this->_request->getPost ( 'Search' )) {
			$larrformData = $this->_request->getPost ();
			if ($this->lobjform->isValid ( $larrformData )) {
				// Function to Search all the details of Subject Category
				$larrresult = $this->lobjSubjectcategory->fnSearchSubjectCategory( $this->lobjform->getValues () ); 
				
				$this->view->paginator = $lobjPaginator->fnPagination($larrresult,$lintpage,$lintpagecount);
				$this->gobjsessionsis->subjectcategorypaginatorresult = $larrresult;
			}
		}
		if ($this->_request->isPost () && $this->_request->getPost ( 'Clear' )) {
			$this->_redirect( $this->baseUrl . '/generalsetup/subjectcategory/index');
		}
}
public function newsubjectcategoryAction() { //Action to add the new Subject Category	
	    $this->view->lobjsubjectcategoryform =$this->lobjsubjectcategoryform;
		$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsubjectcategoryform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsubjectcategoryform->UpdUser->setValue( $auth->getIdentity()->iduser);
				
		if ($this->_request->isPost () && $this->_request->getPost ( 'Save' )) {
			$larrformData = $this->_request->getPost ();
			
			unset ( $larrformData ['Save'] );//
			if ($this->lobjsubjectcategoryform->isValid ( $larrformData ))
			 {  
			 	
			 	// Function to add Subject Category
			 	$result = $this->lobjSubjectcategory->fnaddsubjectcategory($larrformData);
			 	
			 	// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'New SubjectCategory Add',
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectcategory/index');	
        }     
    }
}   
public function editsubjectcategoryAction(){ //Action to edit the Subject Category
		$this->view->lobjsubjectcategoryform = $this->lobjsubjectcategoryform; 
		 $IdSubjectCategory = $this->_getParam('id');	
		 	 $this->view->lobjsubjectcategoryform->IdSubjectCategory->setValue($IdSubjectCategory);
		// Function to View the Subject Category
    	$larrresult = $this->lobjSubjectcategory->fnViewsubjectcategory($IdSubjectCategory);
    	
    	$ldtsystemDate = date ( 'Y-m-d H:i:s' );
		$this->view->lobjsubjectcategoryform->UpdDate->setValue( $ldtsystemDate );
		$auth = Zend_Auth::getInstance();
		$this->view->lobjsubjectcategoryform->UpdUser->setValue( $auth->getIdentity()->iduser);
		
	 	$this->lobjsubjectcategoryform->populate($larrresult);
    		if ($this->getRequest()->isPost()) {
    		    $formData = $this->getRequest()->getPost();
    		      		 		 
	    	if ($this->lobjsubjectcategoryform->isValid($formData)) {
	   			$lintIdAccount = $formData ['IdSubjectCategory'];
	   				  			
	   			// Function to update Subject Category
				$this->lobjSubjectcategory->fnupdatesubjectcategory($formData,$lintIdAccount);				
				// Write Logs
				$priority=Zend_Log::INFO;
				$larrlog = array ('user_id' => $auth->getIdentity()->iduser,
								  'level' => $priority,
								  'hostname' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				                  'time' => date ( 'Y-m-d H:i:s' ),
				   				  'message' => 'SubjectCategory Edit Id=' . $IdSubjectCategory,
								  'Description' =>  Zend_Log::DEBUG,
								  'ip' => $this->getRequest ()->getServer ( 'REMOTE_ADDR' ) );
				$this->_gobjlog->write ( $larrlog ); //insert to tbl_log
				$this->_redirect( $this->baseUrl . '/generalsetup/subjectcategory/index');
			}
    	}
    } 
}

