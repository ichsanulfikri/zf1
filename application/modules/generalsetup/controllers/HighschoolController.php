<?php
/**
 * @author Muhamad Alif
 * @version 1.0
 */

class GeneralSetup_HighschoolController extends Zend_Controller_Action {
	
	private $_DbObj;
	
	public function init(){
		$this->_DbObj = new GeneralSetup_Model_DbTable_SchoolDisciplineProgramme();
	}
	
	public function indexAction() {
		//title
    	$this->view->title= $this->view->translate("High School Set-up");
    	
    	$form = new GeneralSetup_Form_SchoolMaster();
    	$form->removeElement('sm_name_bahasa');
    	$form->removeElement('sm_address1');
    	$form->removeElement('sm_address2');
    	$form->removeElement('sm_email');
    	$form->removeElement('sm_url');
    	$form->removeElement('sm_phone_o');
    	$form->setAction($this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'index', 'search'=>true),'default',true));
    	
    	
    	
    	$schoolMasterDb = new GeneralSetup_Model_DbTable_HighSchool();
    	
    	$search = $this->getRequest()->getParam('search', false);
    	$this->view->search = $search;
    	
    	$ses_search = new Zend_Session_Namespace('search');
    	

    	if ($this->getRequest()->isPost() ) {
			
			$formData = $this->getRequest()->getPost();
			
			//paginator
			$data = $schoolMasterDb->getPaginateData($formData);
			$ses_search->data = $data;
			$ses_search->form = $formData;
			
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($data));
			$paginator->setItemCountPerPage(PAGINATION_SIZE);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
			
			$this->view->paginator = $paginator;
			
			$form->populate($formData);
		
    	}else
    	if($search && $ses_search->data!=null && $ses_search->form!=null){
    		
    		$data = $ses_search->data;
    		
    		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($data));
			$paginator->setItemCountPerPage(PAGINATION_SIZE);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
			
			$this->view->paginator = $paginator;
    			
    		$form->populate($ses_search->form);
    		
    	}else{	
    		$ses_search->data = null;
    		$ses_search->form = null;
    		$ses_search = null;
    		
    		//paginator
			$data = $schoolMasterDb->getPaginateData();
			
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($data));
			$paginator->setItemCountPerPage(PAGINATION_SIZE);
			$paginator->setCurrentPageNumber($this->_getParam('page',1));
			
			$this->view->paginator = $paginator;	
    	}
    	
    	$this->view->searchForm = $form;
    	    	
	}
	
	public function detailAction(){
    	$this->view->title= $this->view->translate("High School Set-up")." - ".$this->view->translate("Detail");
    	
		$id = $this->_getParam('id', null);
		$this->view->id = $id;
		
    	if($id){  		
    		//school data
    		$schoolMasterDb = new GeneralSetup_Model_DbTable_HighSchool();
    		$this->view->school = $schoolMasterDb->getData($id);
    					
		}else{
			$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'index'),'default',true));
		}
    }
	
	public function addAction()
    {
    	//title
    	$this->view->title= $this->view->translate("High School Set-up")." - ".$this->view->translate("Add");
    	    	
    	$form = new GeneralSetup_Form_SchoolMaster();
    	
    	$form->cancel->onClick = "window.location ='".$this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'index'),'default',true)."'; return false;";
    	    	
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				
				$schoolMasterDb = new GeneralSetup_Model_DbTable_HighSchool();
				$id = $schoolMasterDb->addData($formData);
				
				//redirect
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'detail', 'id'=>$id),'default',true));	
			}else{
				$form->populate($formData);
			}
        	
        }
    	
        $this->view->form = $form;
    }
    
	public function editAction(){
		$id = $this->_getParam('id', 0);
		
		//title
    	$this->view->title= $this->view->translate("High School Set-up")." - ".$this->view->translate("Edit");
    	
    	$form = new GeneralSetup_Form_SchoolMaster();
    	$schoolMasterDb = new GeneralSetup_Model_DbTable_HighSchool();
    	
    	if ($this->getRequest()->isPost()) {
    		
    		$formData = $this->getRequest()->getPost();
    		
	    	if ($form->isValid($formData)) {
				
				$schoolMasterDb->updateData($formData,$id);
				
				$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'detail', 'id'=>$id),'default',true)); 
			}else{
				$form->populate($formData);	
			}
    	}else{
    		if($id>0){
    			
    			$form->populate($schoolMasterDb->getData($id));
    		}
    	}
    	
    	$this->view->form = $form;
    }
    
	public function deleteAction($id = null){
    	$id = $this->_getParam('id', 0);
    	
    	if($id>0){
    		$schoolMasterDb = new GeneralSetup_Model_DbTable_HighSchool();
    		$data = $schoolMasterDb->getData($id);
    		
    		$schoolMasterDb->deleteData($id);
    	}
    		
    	$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'highschool', 'action'=>'index'),'default',true));
    	
    }
}

