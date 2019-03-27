<?php

class Sis_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    private $_auth;
    private $_acl;
    private $no_check_module = array("onnapp","test");

    private $_noauth = array('module' => 'default',
                             'controller' => 'index',
                             'action' => 'login');

    private $_noacl = array('module' => 'default',
                            'controller' => 'error',
                            'action' => 'privileges');
    
    private $_excludecontroller = array('onlineapplication','forgotpassword','agentlogin','result');
  
    public function __construct($auth, $acl)
    { 
        $this->_auth = $auth;
        $this->_acl = $acl;
    }
    
    

	public function preDispatch(Zend_Controller_Request_Abstract $request){

		
		
		$controller = $request->controller;
		$action = $request->action;
		$module = $request->module;
		$resource = $controller;
		
		if ($this->_auth->hasIdentity()) {
			//$role = $this->_auth->getIdentity()->getUser()->role;
		} else 
		if($module!="default" && in_array($module,$this->no_check_module)){
			//do nothing	
		}else{
			if(!in_array($controller,$this->_excludecontroller)){
			$module = $this->_noauth['module'];
			$controller = $this->_noauth['controller'];
			$action = $this->_noauth['action'];
			}
		}
	
		
		$request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
	}
}

?>
