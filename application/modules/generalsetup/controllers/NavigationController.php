<?php

class GeneralSetup_NavigationController extends Base_Base {
	
	public function init(){
		$this->_gobjlog = Zend_Registry::get ( 'log' ); //instantiate log object
		
		$this->view->translate =Zend_Registry::get('Zend_Translate'); 
   	    Zend_Form::setDefaultTranslator($this->view->translate);
	}
	
	public function indexAction(){
		
		$this->view->title = $this->view->translate("Navigation Setup - Role Selection");
		
		$definitionDb = new App_Model_Definitiontype();
		
		$lintpagecount = $this->gintPageCount;
		$lintpage = $this->_getParam('page',1); // Paginator instance
		
		$roleList = $definitionDb->fnGetDefinations('Role');
		$this->view->paginator = $this->lobjCommon->fnPagination($roleList,$lintpage,$lintpagecount);	
	}
	
	public function menuAction(){
		
		$this->view->title = $this->view->translate("Navigation Setup - Top Menu Navigation");
		
		$role_id = $this->_getParam('role_id',0);
		$this->view->role_id = $role_id;
		
		if($role_id==0){
			$this->_redirect( $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'index'),'default',true) );
		}else{
			
			$defDb = new App_Model_DefModel();
			$role = $defDb->selectrow("SELECT * FROM tbl_definationms WHERE idDefinition = ".$role_id);
			$this->view->role = $role;
			
			$navigationMenuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
			$this->view->menuList = $navigationMenuDb->getRoleMenuList($role_id);	
		}
		
	}
	
	public function addMenuAction(){
		
		$this->view->title = $this->view->translate("Navigation Setup - Top Menu Navigation : ADD");
		
		$role_id = $this->_getParam('role_id',0);
		$this->view->role_id = $role_id;
		
		$this->view->backURL = $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'menu', 'role_id'=> $role_id),'default',true);
		
		$form = new GeneralSetup_Form_NavigationMenu();
		
		$form->cancel->onClick = "window.location ='".$this->view->backURL."'; return false; ";
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$menuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
				
				$data = array(
							'role_id' => $role_id,
							'label' => $formData['label'],
							'title' => $formData['title'],
							'module' => $formData['module'],
							'controller' => $formData['controller'],
							'action' => $formData['action']
						);
				
				$menuDb->insert($data);
				
				//redirect
				$this->_redirect($this->view->backURL);	
			}else{
				$form->populate($formData);
			}
			
		}
		
		$this->view->form = $form;
	}
	
	public function menuAjaxAction(){
		$role_id = $this->_getParam('role_id',0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
        	
	    
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

        $navigationMenuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
		$menuList = $navigationMenuDb->getRoleMenuList($role_id);
			
		$json = Zend_Json::encode($menuList);
		
		echo $json;
		exit();
	}
	
	public function editMenuAction(){
		
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('id',null);
		
		$menuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
		
		$data = $menuDb->getData($menu_id);
		
		$form = new GeneralSetup_Form_NavigationMenu();
		
		$this->view->title = $this->view->translate("Navigation Setup - Edit Menu Navigation");
		$this->view->backURL = $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'menu','role_id'=>$role_id),'default',true);
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
		
				$data = array(
							'label' => $formData['label'],
							'title' => $formData['title'],
							'module' => $formData['module'],
							'controller' => $formData['controller'],
							'action' => $formData['action']
						);
				
				$menuDb->update($data,'id = '.$menu_id);
				
				//redirect
				$this->_redirect($this->view->backURL);	
			}else{
				$form->populate($formData);
			}
			
		}else{
			$form->populate($data);
		}
		
		$this->view->form = $form;
	}
	
	public function deleteMenuAction(){
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('id',null);
		
		$menuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
		$menuDb->deleteData($menu_id);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'menu','role_id'=>$role_id),'default',true));
	}
	
	public function moveMenuAction(){
		$role_id = $this->_getParam('role_id',null);
		$direction = $this->_getParam('direction',null);
		
		$menu_id = $this->_getParam('menu_id',null);
		$menu_order = $this->_getParam('current_order',null);
		$above_order = $this->_getParam('above_order',null);
		
		$navigationMenuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
		
		if($direction=='up'){
			
			//update order +1
			$data = array(
			    'seq_order' => new Zend_Db_Expr('seq_order + 1')
			);

			$where = "role_id = ".$role_id." and seq_order > ".$above_order." and seq_order < ".$menu_order;
			$navigationMenuDb->update($data, $where);
			
			//update selected sidebar
			$data = array(
			    'seq_order' => $above_order+1
			);
			
			$where = "role_id = ".$role_id." and id = ".$menu_id;
			$navigationMenuDb->update($data, $where);
		}else
		if($direction=='down'){
			
			//update order -1
			$data = array(
			    'seq_order' => new Zend_Db_Expr('seq_order - 1')
			);
			

			$where = "role_id = ".$role_id." and seq_order <= ".$above_order." and seq_order > ".$menu_order;
			$navigationMenuDb->update($data, $where);
			
			
			//update selected sidebar
			$data = array(
			    'seq_order' => $above_order
			);
			
			$where = "role_id = ".$role_id." and id = ".$menu_order;
			$navigationMenuDb->update($data, $where);
			
		}
		
		echo 1;
		exit;
	}
	
	
	public function sidebarAction(){
		$this->view->title = $this->view->translate("Navigation Setup - Sidebar Menu Navigation");
		
		$role_id = $this->_getParam('role_id',0);
		$this->view->role_id = $role_id;
		
		$menu_id = $this->_getParam('menu_id',0);
		$this->view->menu_id = $menu_id;
		
		if($role_id==0 || $menu_id==0){
			$this->_redirect( $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'index'),'default',true) );
		}else{
			
			$defDb = new App_Model_DefModel();
			$role = $defDb->selectrow("SELECT * FROM tbl_definationms WHERE idDefinition = ".$role_id);
			$this->view->role = $role;
			
			$navigationMenuDb = new GeneralSetup_Model_DbTable_NavigationMenu();
			$this->view->menu = $navigationMenuDb->getData($menu_id);

			$navigationSidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
			$this->view->sidebarList = $navigationSidebarDb->getSidebarList($menu_id);
		}
	}
	
	public function sidebarAjaxAction(){
		$menu_id = $this->_getParam('menu_id',0);
    	
     	//if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        //}
        
     	$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html');
        $ajaxContext->initContext();
        	
	    
		$ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('form', 'html')
                    ->addActionContext('process', 'json')
                    ->initContext();

        $navigationSidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
		$sidebarList = $navigationSidebarDb->getSidebarList($menu_id);
			
		$json = Zend_Json::encode($sidebarList);
		
		echo $json;
		exit();
	}
	
	public function addSidebarAction(){
		
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('menu_id',null);
		
		$this->view->title = $this->view->translate("Navigation Setup - Add Sidebar Navigation");
		
		$this->view->backURL = $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'sidebar','role_id'=>$role_id, 'menu_id'=>$menu_id),'default',true);
		
		$form = new GeneralSetup_Form_Sidebar(array('roleid'=>$role_id, 'menuid'=>$menu_id));
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$sidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
				
				$data = array(
							'nav_menu_id' => $menu_id,
							'name' => $formData['name'],
							'module' => $formData['module'],
							'controller' => $formData['controller'],
							'action' => $formData['action'],
							'title_head' => $formData['title_head'],
							'visible' => $formData['visible'],
						);
				
				$sidebarDb->insert($data);
				
				//redirect
				$this->_redirect($this->view->backURL);	
			}else{
				$form->populate($formData);
			}
			
		}
		
		$this->view->form = $form;
		
	}
	
	public function editSidebarAction(){
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('menu_id',null);
		$sidebar_id = $this->_getParam('id',null);
		
		$sidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
		
		$data = $sidebarDb->getData($sidebar_id);
		
		$form = new GeneralSetup_Form_Sidebar(array('roleid'=>$role_id,'menuid'=>$data['nav_menu_id']));
		
		$this->view->title = $this->view->translate("Navigation Setup - Edit Sidebar Navigation");
		$this->view->backURL = $this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'sidebar','role_id'=>$role_id, 'menu_id'=>$menu_id),'default',true);
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
		
				$sidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
				
				$data = array(
							'name' => $formData['name'],
							'module' => $formData['module'],
							'controller' => $formData['controller'],
							'action' => $formData['action'],
							'title_head' => $formData['title_head'],
							'visible' => $formData['visible'],
						);
				
				$sidebarDb->update($data,'id = '.$sidebar_id);
				
				//redirect
				$this->_redirect($this->view->backURL);	
			}else{
				$form->populate($formData);
			}
			
		}else{
			$form->populate($data);
		}
		
		$this->view->form = $form;
		
	}
	
	public function deleteSidebarAction(){
		
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('menu_id',null);
		$sidebar_id = $this->_getParam('id',null);
		
		$sidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
		$sidebarDb->deleteData($sidebar_id);
		
		$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'sidebar','role_id'=>$role_id, 'menu_id'=>$menu_id),'default',true));
	}
	
	public function moveSidebarAction(){
		
		$role_id = $this->_getParam('role_id',null);
		$menu_id = $this->_getParam('menu_id',null);
		$direction = $this->_getParam('direction',null);
		
		$sidebar_id = $this->_getParam('current_id',null);
		$sidebar_order = $this->_getParam('current_order',null);
		$above_order = $this->_getParam('above_order',null);
		
		$navigationSidebarDb = new GeneralSetup_Model_DbTable_NavigationSidebar();
		
		if($direction=='up'){
			
			//update order +1
			$data = array(
			    'seq_order' => new Zend_Db_Expr('seq_order + 1')
			);

			$where = "nav_menu_id = ".$menu_id." and seq_order > ".$above_order." and seq_order < ".$sidebar_order;
			$navigationSidebarDb->update($data, $where);
			
			//update selected sidebar
			$data = array(
			    'seq_order' => $above_order+1
			);
			
			$where = "nav_menu_id = ".$menu_id." and id = ".$sidebar_id;
			$navigationSidebarDb->update($data, $where);
		}else
		if($direction=='down'){
			
			//update order -1
			$data = array(
			    'seq_order' => new Zend_Db_Expr('seq_order - 1')
			);
			

			$where = "nav_menu_id = ".$menu_id." and seq_order <= ".$above_order." and seq_order > ".$sidebar_order;
			$navigationSidebarDb->update($data, $where);
			
			
			//update selected sidebar
			$data = array(
			    'seq_order' => $above_order
			);
			
			$where = "nav_menu_id = ".$menu_id." and id = ".$sidebar_id;
			$navigationSidebarDb->update($data, $where);
			
		}
		
		echo 1;
		exit;
		//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'navigation', 'action'=>'sidebar'),'default',true));
	}
}
?>