<?php
class GeneralSetup_ResourceController extends Base_Base {
	
	Protected $auth;
	
	public function init(){
		$this->auth = Zend_Auth::getInstance();
	}
	
	public function addAction(){
	
		if($this->auth->getIdentity()->IdRole == 1 && $this->getRequest()->isPost()){
			
			$formData = $this->getRequest()->getPost();
			
			if(
				$formData['Module']!='' && 
				$formData['Controller']!='' && 
				$formData['Action']!='' && 
				$formData['Name']!='' &&
				$formData['RouteName']!=''
			){
				
				$resourceDb = new App_Model_Resources();
				$data = array(
					'Module' => $formData['Module'],
					'Controller' => $formData['Controller'],
					'Action' => $formData['Action'],
					'Name' => $formData['Name'],
					'RouteName' => $formData['RouteName'],
					'Modified' => date('Y-m-d H:i:s'),
					'Created' => date('Y-m-d H:i:s')
				);
				
				$resourceDb->insert($data);
				
				//remove ACL resource session
				Zend_Session::namespaceUnset('Sis_ACL_Namespace');
				
				$this->redirect($this->view->url(array('module'=>$formData['Module'] ,'controller'=>$formData['Controller'], 'action'=>$formData['Action']),'default',true));
				
			}else{
				//redirect
				$this->redirect('/');
			}
			
		}else{
			//redirect
			$this->redirect('/');
		}
		
	}
	
	public function addAdminRoleResourceAction(){
		if($this->auth->getIdentity()->IdRole == 1 && $this->getRequest()->isPost()){
				
			$formData = $this->getRequest()->getPost();
				
			if(isset($formData['idResources']) && $formData['idResources']!='' && $formData['idResources']!=0 && $formData['assign']==1){
		
				$rolesDb = new GeneralSetup_Model_DbTable_Roles();
				$data = array(
						'idRoles' => 1,
						'idResources' => $formData['idResources'],
						'Modified' => date('Y-m-d H:i:s'),
						'Created' => date('Y-m-d H:i:s')
				);
		
				$rolesDb->insert($data);
				
				//remove ACL resource session
				Zend_Session::namespaceUnset('Sis_ACL_Namespace');
		
				$this->redirect($formData['path']);
		
			}else{
				//redirect
				$this->redirect('/');
			}
				
		}else{
			//redirect
			$this->redirect('/');
		}
	}
	
}
?>