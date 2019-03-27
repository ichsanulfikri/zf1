<?php
class Sis_Plugin_ACL extends Zend_Controller_Plugin_Abstract {
	private $_excludecontroller = array('forgotpassword','agentlogin');

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
 	 	/*
 	 	 *  Commented in order that we enter the resources manually,
 	 	 *  This insertion has to be done after completion of the project to get the list of all the action controller module pairs.
 	 	 *  Use this insertion only once at the end of the project.
 	 	 */

        $objAuth = Zend_Auth::getInstance();
		$clearACL = false;
 	
		// initially treat the user as a guest so we can determine if the current
		// resource is accessible by guest users
		$role = 'guest';
 
		// if its not accessible then we need to check for a user login
		// if the user is logged in then we check if the role of the logged
		// in user has the credentials to access the current resource
 
        try {
        	
	    	if($objAuth->hasIdentity()) {
	    		
	    		$authns = new Zend_Session_Namespace($objAuth->getStorage()->getNamespace());

			    // set an expiration on the Zend_Auth namespace where identity is held
			    $authns->setExpirationSeconds(30*60);  // expire auth storage after 30 min
	    		
		        $arrUser = $objAuth->getIdentity();

		       /* $sess = new Zend_Session_Namespace('My_ACL');
	    	    if($sess->clearACL) {
	        	     $clearACL = true;
	            	  unset($sess->clearACL);
	         	}*/
 
	         	$force_acl_using_db = false;
	         	$objAcl = Sis_ACL_Factory::get($objAuth,$force_acl_using_db);
	         	$str_route_request = $request->getModuleName() .'::' .$request->getControllerName() .'::' .$request->getActionName();
	         	
	         	if(!$objAcl->has($str_route_request)){
	         		//throw resource not found
					$error = new Zend_Controller_Plugin_ErrorHandler();
					$error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE;
					$error->request = clone( $request );
					$error->exception = null; // If you have caught the exception to $e, set it.
		
					$this->getRequest()->setModuleName('default')->setControllerName('error')->setActionName('noresource')->setDispatched(true);
					$request->setParam('error_handler', $error);
					
	         	}else
	         	if(!$objAcl->isAllowed($arrUser->IdRole, $request->getModuleName() .'::' .$request->getControllerName() .'::' .$request->getActionName())) {
	            	
	         		//throw ACL exception
	         		$error = new Zend_Controller_Plugin_ErrorHandler();
	         		$error->type = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
	         		$error->request = clone( $request );
	         		$error->exception = null; // If you have caught the exception to $e, set it.
	         		
	         		$this->getRequest()->setModuleName('default')->setControllerName('error')->setActionName('noauth')->setDispatched(true);
	         		$request->setParam('error_handler', $error);
	         		
	         	}
	         	
	         	
 
            }else{
            	$module =  $request->getModuleName();
            	$controller =  $request->getControllerName();
            	$action =  $request->getActionName();
            	
				if($module == 'default' && $controller=='index' && ($action=='login' || $action=='logout') ){
					//do nothing
				}else
	            if(!in_array($controller,$this->_excludecontroller)){
	            	
	            	//redirect to login
	            	if($request->getMethod() == 'GET'){
		            	$request->setModuleName('default');
		            	$request->setControllerName('index');
		           		$request->setActionName('login');
	            	}else{
	            		$r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
	            		$r->gotoUrl('/')->redirectAndExit();
	            	}
	           		
	            }else{
	            	
	            	if($request->getControllerName()!='error' && $request->getControllerName()!='logout' && $request->getMethod() == 'GET'){
		         		//session path redirect
		            	$view = Zend_Controller_Action_HelperBroker::getStaticHelper('Layout')->getView();
		         		$session = new Zend_Session_Namespace('path_redirect');
		         		$session->previous_url = $view->url(array('module'=>$request->getModuleName(),'controller'=>$request->getControllerName(), 'action'=>$request->getActionName()),'default',true);
		         		$session->setExpirationSeconds(30*60);
	            	}
	            }
		        
		    }
		    
        } catch(Zend_Exception $e) {
        	
        	echo $e->getMessage();
        	echo "<pre>";
        	print_r($e->getTrace());
        	echo "</pre>";
        	exit;
        }
    } 		
}
