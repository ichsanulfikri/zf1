<?php
/**
 * 
 * @author Muhamad Alif
 * @version 
 */


class AjaxUtilityController extends Zend_Controller_Action {
	
	protected $country_tbl = 'tbl_countries ';
	protected $state_tbl = 'tbl_state';
	protected $city_tbl = 'tbl_city';
	protected $family_tbl = 'applicant_family';
	private $_sis_session;
	
	public function init(){
		
		$this->_sis_session = new Zend_Session_Namespace('sis');
	}
	
	public function getStateAction($country_id=0){
    	$country_id = $this->_getParam('country_id', 0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	$select = $db->select()
	                 ->from(array('s'=>$this->state_tbl))
	                 ->where('s.idCountry = ?', $country_id)
	                 ->order('s.StateName ASC');
	    
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
	public function getCityAction($country_id=0){
    	$state_id = $this->_getParam('state_id', 0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	$select = $db->select()
	                 ->from(array('c'=>$this->city_tbl))
	                 ->where('c.idState = ?', $state_id)
	                 ->order('c.CityName ASC');
	    
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    
	public function getFamilyInfoAction(){
		
    	$appl_id = $this->_getParam('appl_id', 0);
    	$relation = $this->_getParam('relation', 0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	$select = $db->select()
	                 ->from(array('f'=>$this->family_tbl))
	                 ->where('f.af_appl_id = ?', $appl_id)
	                 ->where('f.af_relation_type = ?', $relation);	                
	    
        $row = $db->fetchRow($select);
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    
    public function ajaxGetLocationAction($id=null){

	 	$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'index/index' );
		}
			
    	$select_date = $this->_getParam('select_date', 0);
     
     	if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
        
        $applicantPlacementScheduleDB = new App_Model_Application_DbTable_ApplicantPlacementSchedule();
    	$location_list = $applicantPlacementScheduleDB->getLocationByDate($select_date);
    	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($location_list);
		echo $json;
		exit();

    }
    
    

	public function ajaxGetFeesAction($id=null){

	 	$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'index/index' );
		}
			
		$transaction_id = $this->_getParam('transaction_id', 0);
    	$code = $this->_getParam('code', 0);
    	$program = $this->_getParam('program', 0);
    	$location = $this->_getParam('location', 0);
    	$lid = $this->_getParam('lid', 0);//location id    	
    	
    	$program_fee=0;
    	$location_fee=0;
    	
     
     	if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
        
		//get Fees by Program
    	if($program==1){
    		
    		//1st:check how many program apply.    		
    		$ptestDB = new App_Model_Application_DbTable_ApplicantProgram();	
    		$list_program = $ptestDB->getPlacementProgram($transaction_id);
    		$total_program_apply = count($list_program);
    		
    		$feeDB = new App_Model_Application_DbTable_PlacementFeeSetup();
    		$condition = array('type'=>'PROGRAM','value'=>$total_program_apply);
    		$fees_info = $feeDB->getFees($condition);
    		$program_fee = $fees_info["apfs_amt"];
    	}
    	
		//get Fees by location
    	if($location==1){
    		
    		//1st:check where is the location.    		
    		$location_id = $lid;
    		
    		$feeDB = new App_Model_Application_DbTable_PlacementFeeSetup();
    		$condition = array('type'=>'LOCATION','value'=>$location_id);
    		$fees_info = $feeDB->getFees($condition);
    		$location_fee = $fees_info["apfs_amt"];
    	}
    	
    	$total_fees = abs($program_fee)+abs($location_fee);
    	
    	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($total_fees);
		echo $json;
		exit;

    }
    
    
	public function ajaxGetDisciplineAction(){
    	$school_type_id = $this->_getParam('school_type_id', 2);

     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	$select = $db->select()
	                 ->from(array('sd'=>'school_discipline'),array('smd_code','smd_desc'))
	                 ->where('sd.smd_school_type = ?', $school_type_id)
	                 ->order('sd.smd_desc ASC');
	    
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    
public function ajaxGetSchoolAction(){
    	$school_type_id = $this->_getParam('school_type_id', 0);
    	$school_state_id = $this->_getParam('school_state_id', 0);
    	$keyvalue = $this->_getParam('keyvalue', 0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	
	  	if($keyvalue==1){
		  	$select = $db->select()
		                 ->from(array('sm'=>'school_master'),array('sm_id','sm_name'))
		                 ->order('sm.sm_name ASC');
		    
		    if($school_type_id!=0){
		    	$select->where('sm.sm_type = ?', $school_type_id);
		    }
			if($school_state_id!=0){
		    	$select->where('sm.sm_state = ?', $school_state_id);
		    }
	  	}else{
	  		$select = $db->select()
		                 ->from(array('sm'=>'school_master'))
		                 ->order('sm.sm_name ASC');
		    
		    if($school_type_id!=0){
		    	$select->where('sm.sm_type = ?', $school_type_id);
		    }
			if($school_state_id!=0){
		    	$select->where('sm.sm_state = ?', $school_state_id);
		    }
	  	}
	    
	    
	    
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    
public function ajaxGetProgrammePtAction(){
    	$discipline_code = $this->_getParam('discipline_code', 0);
    	
        $this->_helper->layout->disableLayout();
        
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
        //program in placement test with discipline filter

        //transaction data
		$auth = Zend_Auth::getInstance();    	
    	$transaction_id = $auth->getIdentity()->transaction_id;
    	$transDB = new App_Model_Application_DbTable_ApplicantTransaction();
        $transaction_data= $transDB->getTransactionData($transaction_id);
    	
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//get placement test data
		$select = $db->select(array('apt_ptest_code'))
	                 ->from(array('ap'=>'applicant_ptest'))
	                 ->where('ap.apt_at_trans_id = ?', $transaction_id);
	                 
	    $stmt = $db->query($select);
        $placementTestCode = $stmt->fetch();
        
        //get placementest program data filtered with discipline
	  	$select = $db->select()
	                 ->from(array('app'=>'appl_placement_program'))
	                 ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = app.app_program_code', array('ArabicName','ProgramName','ProgramCode','IdProgram') )
	                 ->join(array('apr'=>'appl_program_req'),"apr.apr_program_code = app.app_program_code and apr.apr_decipline_code = '".$discipline_code."' and apr.apr_academic_year = ".$transaction_data['at_academic_year'])
	                 ->where('app.app_placement_code  = ?', $placementTestCode['apt_ptest_code'])
	                 ->order('p.ArabicName ASC');
				
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		
		if($row){
        	$json = Zend_Json::encode($row);
        }else{
        	$json = null;
        }
		
		echo $json;
		exit();
    }
    
public function ajaxGetProgrammeHsAction(){
    	$discipline_code = $this->_getParam('discipline_code', 0);
    	$academic_year = $this->_getParam('academic_year', 0);
    	
        $this->_helper->layout->disableLayout();
        
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
        //program in placement test with discipline filter

        //transaction data
		$auth = Zend_Auth::getInstance();
		$appl_id = $auth->getIdentity()->appl_id;    	
    	$transaction_id = $auth->getIdentity()->transaction_id;
    	$transDB = new App_Model_Application_DbTable_ApplicantTransaction();
        $transaction_data= $transDB->getTransactionData($transaction_id);
    	
		$db = Zend_Db_Table::getDefaultAdapter();
		
	                 
		//get transaction data
		$select = $db->select()
	                 ->from(array('at'=>'applicant_transaction'))
	                 ->where('at.at_trans_id = ?', $transaction_id);
	                 
	    $stmt = $db->query($select);
        $transactionData = $stmt->fetch();
        
        $select_applied = $db->select()
         			 ->from(array('at'=>'applicant_transaction'),array())
	                 ->join(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=at.at_trans_id',array('ap_prog_code'=>'distinct(ap.ap_prog_code)'))
	                 ->where("at.at_appl_id= '".$appl_id."'")
	                 ->where("ap.ap_at_trans_id != '".$transaction_id."'")
	                 ->where("at.at_appl_type=2");	                 
	               

        //get program data based on discipline
	  	$select = $db->select()
	                 ->from(array('apr'=>'appl_program_req'))
	                 ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = apr.apr_program_code' )
	                 ->where("p.ProgramCode NOT IN (?)",$select_applied)
	                 ->order('p.ArabicName ASC');
	                 
	    if($discipline_code!=0){
	    	$select->where('apr.apr_decipline_code  = ?', $discipline_code);
	    }
	    
		if($academic_year!=0){
	    	$select->where('apr.apr_academic_year  = ?', $academic_year);
	    }             
				
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		
		if($row){
        	$json = Zend_Json::encode($row);
        }else{
        	$json = null;
        }
		
		echo $json;
		exit();
    }
    
    
	public function ajaxGetDisciplineSubjectAction(){
    	$discipline_code = $this->_getParam('discipline_code', 0);
    	    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
            
        // $auth = Zend_Auth::getInstance(); 
    	//$appl_id = $auth->getIdentity()->appl_id;
    	$appl_id = $this->_getParam('appl_id', 0);
    	
	  	$db = Zend_Db_Table::getDefaultAdapter();
	  	
	  	//get applicant education head data
	  	$applicationEducationDb = new App_Model_Application_DbTable_ApplicantEducation();
	  	$educationData = $applicationEducationDb->getDataByapplID($appl_id);
	  	
	  	$select = $db->select()
	                 ->from(array('sds'=>'school_decipline_subject'))
	                 ->where('sds.sds_discipline_code  = ?', $discipline_code)
	                 ->join(array('s'=>'school_subject'),'s.ss_id = sds.sds_subject')
	                 //->joinLeft(array('aed'=>'applicant_education_detl'),'aed.')
	                 ->order('s.ss_core_subject DESC')
	                 ->order('s.ss_subject ASC');
	                 
	    if($educationData){
	    	$select->joinLeft(array('aed'=>'applicant_education_detl'),"aed.aed_ae_id = ".$educationData['ae_id']." and  aed.aed_subject_id = sds.sds_subject");
	    }
	    	   
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
	  	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($row);
		
		echo $json;
		exit();
    }
    
    
	public function ajaxGetTestFeesAction($id=null){

	 	$storage = new Zend_Auth_Storage_Session ();
		$data = $storage->read ();
		if (! $data) {
			$this->_redirect ( 'index/index' );
		}
			
		$transaction_id = $this->_getParam('transaction_id', 0);
    	$code = $this->_getParam('code', 0);
    	$program = $this->_getParam('program', 0);
    	$location = $this->_getParam('location', 0);    	
    	$aps_id = $this->_getParam('aps_id', 0);//aps id
    	
    	$program_fee=0;
    	$location_fee=0;   	
     
     	if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
        
        $applicantPlacementScheduleDB = new App_Model_Application_DbTable_ApplicantPlacementSchedule();
    	$shedule_info = $applicantPlacementScheduleDB->getData($aps_id);
    	$lid=$shedule_info["aps_location_id"];
    	
		//get Fees by Program
    	if($program==1){
    		
    		//1st:check how many program apply.    		
    		$ptestDB = new App_Model_Application_DbTable_ApplicantProgram();	
    		$list_program = $ptestDB->getPlacementProgram($transaction_id);
    		$total_program_apply = count($list_program);
    		
    		$feeDB = new App_Model_Application_DbTable_PlacementFeeSetup();
    		$condition = array('type'=>'PROGRAM','value'=>$total_program_apply);
    		$fees_info = $feeDB->getFees($condition);
    		$program_fee = $fees_info["apfs_amt"];
    	}
    	
		//get Fees by location
    	if($location==1){
    		
    		//1st:check where is the location.    		
    		$location_id = $lid;
    		
    		$feeDB = new App_Model_Application_DbTable_PlacementFeeSetup();
    		$condition = array('type'=>'LOCATION','value'=>$location_id);
    		$fees_info = $feeDB->getFees($condition);
    		$location_fee = $fees_info["apfs_amt"];
    	}
    	
    	$total_fees = abs($program_fee)+abs($location_fee);
    	
    	
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

		$json = Zend_Json::encode($total_fees);
		echo $json;
		exit;

    }
    
	public function searchStudentAction(){
		$this->_helper->layout()->disableLayout();
		
		$name = $this->_getParam('name', null);
		$id = $this->_getParam('id', null);
		$findTxn = $this->_getParam('txn', false);
		
		$sis_session = new Zend_Session_Namespace('sis');
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			$ajaxContext = $this->_helper->getHelper('AjaxContext');
	        $ajaxContext->addActionContext('view', 'html');
	        $ajaxContext->initContext();
	            
		  	$db = Zend_Db_Table::getDefaultAdapter();
		  	
		  	if($formData['type']==1){//applicant
			  	$select = $db->select()
			                 ->from(array('ap'=>'applicant_profile'),array('distinct(appl_id)'))
			                 ->join(array('at'=>'applicant_transaction'), 'at.at_appl_id = ap.appl_id', array());
	
			    if($name!=null){
			    	$select->where("concat_ws(' ',ap.appl_fname,ap.appl_mname,ap.appl_lname) like ?", '%'.$name.'%');
			    }
			    
				if($id!=null){
			    	$select->where("at.at_pes_id like '%".$id."%'");
			    }
			    
				//display according to role FACULTY FINANCE(386)
				if($this->_sis_session->IdRole == 386){
					$select->join(array('apr'=>'applicant_program'), 'at.at_trans_id = apr.ap_at_trans_id', array())
							->join(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array())
							->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array())
							->where('c.IdCollege = ?', $sis_session->idCollege)
							->where("at.at_status = 'OFFER'");
							//->where("apr.ap_usm_status = 1");
				}
				
			  
			    $select_clear = $db->select()
			    				->from(array('ap'=>'applicant_profile'),array('appl_id','concat_ws(\' \',ap.appl_fname,ap.appl_mname,ap.appl_lname)name','appl_email'))
			    				->where('ap.appl_id in ('.$select.')');
			    
			    if($findTxn){
			    	$select_clear->joinLeft(array('at'=>'applicant_transaction'), 'at.at_appl_id = ap.appl_id', array('at_trans_id','at_pes_id'));
			    }
			    
			    $row = $db->fetchAll($select_clear);
			    
		  	}else 
	  		if($formData['type']==2){ //student
	  			
	  			$select = $db->select()
	  			->from(array('sp'=>'student_profile'),array('distinct(appl_id)'))
	  			->join(array('sr'=>'tbl_studentregistration'), 'sr.IdApplication = sp.appl_id', array());
	  			
	  			if($name!=null){
	  			    $name = str_replace(" ","%",$name);
	  			    
	  				$select->where("concat_ws(' ',sp.appl_fname,sp.appl_mname,sp.appl_lname) like ?", '%'.$name.'%');
	  			}
	  			 
	  			if($id!=null){
	  				$select->where("sr.registrationId like '%".$id."%'");
	  			}
	  			
	  			//display according to role FACULTY FINANCE(386)
				if($this->_sis_session->IdRole == 386){
					$select->join(array('p'=>'tbl_program'),'p.IdProgram=sr.IdProgram',array())
							->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array())
							->where('c.IdCollege = ?', $sis_session->idCollege);
				}
				
			  
			    $select_clear = $db->select()
	  								->from(array('sp'=>'student_profile'),array('appl_id','concat_ws(\' \',sp.appl_fname,sp.appl_mname,sp.appl_lname)name','appl_email'))
	  								->join(array('sr'=>'tbl_studentregistration'), 'sr.IdApplication = sp.appl_id', array('IdStudentRegistration'=>'IdStudentRegistration','nim'=>'registrationId', 'at_trans_id'=>'transaction_id'))
			    					->where('sp.appl_id in ('.$select.')');
			    
			    $row = $db->fetchAll($select_clear);
	  			
	  		}	
		    				
	        
		  
			$ajaxContext->addActionContext('view', 'html')
	                    ->addActionContext('form', 'html')
	                    ->addActionContext('process', 'json')
	                    ->initContext();
	
			$json = Zend_Json::encode($row);
			
			echo $json;
			exit();
    
		}
	}
	
	public function searchTransactionAction(){
		$this->_helper->layout()->disableLayout();
		
		$name = $this->_getParam('name', null);
		$id = $this->_getParam('id', null);
		
		if ($this->getRequest()->isPost()) {
			
			$ajaxContext = $this->_helper->getHelper('AjaxContext');
	        $ajaxContext->addActionContext('view', 'html');
	        $ajaxContext->initContext();
	            
		  	$db = Zend_Db_Table::getDefaultAdapter();
		  	$select = $db->select()
		                 ->from(array('ap'=>'applicant_profile'),array('concat_ws(\' \',ap.appl_fname,ap.appl_mname,ap.appl_lname)name','appl_email'))
		                 ->join(array('at'=>'applicant_transaction'), 'at.at_appl_id = ap.appl_id and at.at_pes_id is not null', array('at.at_pes_id','at_trans_id','at.at_appl_type'))
		                 ->order('at.at_pes_id');

		    if($name!=null){
		    	$select->where("concat_ws(' ',ap.appl_fname,ap.appl_mname,ap.appl_lname) like ?", '%'.$name.'%');
		    }
		    
			if($id!=null){
		    	$select->where("at.at_pes_id like '%".$id."%'");
		    }
		    
			//display according to role FACULTY FINANCE(386)
			if($this->_sis_session->IdRole == 386){
				$select->join(array('apr'=>'applicant_program'), 'at.at_trans_id = apr.ap_at_trans_id')
							->join(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
							->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
							->where('c.IdCollege = ?', $this->_sis_session->idCollege)
							->where("at.at_status = 'OFFER'");
							//->where("apr.ap_usm_status = 1");
			}
		    		    
		    		    				
	        $row = $db->fetchAll($select);
		  
			$ajaxContext->addActionContext('view', 'html')
	                    ->addActionContext('form', 'html')
	                    ->addActionContext('process', 'json')
	                    ->initContext();
	
			$json = Zend_Json::encode($row);
			
			echo $json;
			exit();
    
		}
	}
	
	public function getAcademicStaffAction(){
	
		$idCollege = $this->_getParam('idCollege',null);
		 
		$this->_helper->layout->disableLayout();
	
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('view', 'html');
		$ajaxContext->initContext();
	
		$staffDb = new GeneralSetup_Model_DbTable_Staffmaster();
		$staff = $staffDb->getAcademicStaff($idCollege);
		 
	
		$ajaxContext->addActionContext('view', 'html')
					->addActionContext('form', 'html')
					->addActionContext('process', 'json')
					->initContext();
	
		$json = Zend_Json::encode($staff);
	
		echo $json;
		exit();
	}
	
	public function getSemesterAction(){
	  
	  $this->_helper->layout()->disableLayout();
	  
	  $academic_year = $this->_getParam('academic_year',null);
	  	
	  
	  $semesterDb = new Records_Model_DbTable_SemesterMaster();
	  
	  $condition = null;
	  if($academic_year){
	    $condition['idacadyear = ?'] = $academic_year;
	  }
	  
	  $semester = $semesterDb->fetchAll($condition,'SemesterMainStartDate DESC');
	  
	  if($semester){
	    $semester = $semester->toArray();
	  }
	  
	  $this->_helper->json($semester);
	  exit;
	  
	  $json = Zend_Json::encode($semester);
	  
	  echo $json;
	  exit();
	}
	
	public function getProgramAction(){
	   
	  $faculty_id = $this->_getParam('faculty_id',null);
	
	  $this->_helper->layout->disableLayout();
	   
	  $ajaxContext = $this->_helper->getHelper('AjaxContext');
	  $ajaxContext->addActionContext('view', 'html');
	  $ajaxContext->initContext();
	   
	  $programDb = new App_Model_Record_DbTable_Program();
	   
	  $condition = array();
	  if($faculty_id){
	    $condition['IdCollege =?'] = $faculty_id;
	  }
	   
	  $program = $programDb->fetchAll($condition,'ProgramCode DESC');
	
	   
	  $ajaxContext->addActionContext('view', 'html')
	  ->addActionContext('form', 'html')
	  ->addActionContext('process', 'json')
	  ->initContext();
	   
	  $json = Zend_Json::encode($program);
	   
	  echo $json;
	  exit();
	   
	}
}

