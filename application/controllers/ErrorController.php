<?php

class ErrorController extends Zend_Controller_Action {

public function errorAction()
    {

    	//$this->_helper->layout->setLayout('error/error');
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
    }
	
    
    
  public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    public function noresourceAction()
    {
    	
    	$errors = $this->_getParam('error_handler');
    	
    	if ($this->getInvokeArg('displayExceptions') == true) {
    		$this->view->exception = $errors->exception;
    	}
    	
    	$this->_helper->layout->setLayout('error/error');
    	//$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender();
		    	    	
		$session = new Zend_Session_Namespace('path_redirect');
    	$this->view->url = $session->previous_url;
		    	
    	$auth = Zend_Auth::getInstance();
    	if ($auth->hasIdentity()) {
    		$role = $auth->getIdentity()->IdRole;
    			
    			
    		if($role == 1){
    			$this->view->admin = true;
    		}
    	}
    }
    
    public function noauthAction()
    {
    	//$this->_helper->layout->setLayout('error/error');
    	
    	$errors = $this->_getParam('error_handler');
    	 
    	if ($this->getInvokeArg('displayExceptions') == true) {
    		$this->view->exception = $errors->exception;
    	}
    	
    	$this->view->path = $this->view->url(array('module'=>$errors->request->getModuleName(),'controller'=>$errors->request->getControllerName(), 'action'=>$errors->request->getActionName()),'default',true);
    	
    	$session = new Zend_Session_Namespace('path_redirect');
    	$this->view->url = $session->previous_url;
    	
    	 $auth = Zend_Auth::getInstance();
    	if ($auth->hasIdentity()) {
			$role = $auth->getIdentity()->IdRole;
			
			
			if($role == 1){
				$this->view->admin = true;
				
				$objAcl = Sis_ACL_Factory::get( Zend_Auth::getInstance(),true);
				
				
				//get resource id
				$resourceDb = new App_Model_Resources();
				$where = array(
					'Module=?'=>$errors->request->getModuleName(),
					'Controller=?'=>$errors->request->getControllerName(),
					'Action=?'=>$errors->request->getActionName()
				);
				$resource = $resourceDb->fetchRow($where)->toArray();
				
				$this->view->resource = $resource;
			}
		}
    }
    

}

