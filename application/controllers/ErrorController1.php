<?php

class ErrorController extends Zend_Controller_Action {

 public function errorAction () {

        $content = null;
        $errors = $this->_getParam ('error_handler') ;
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
                // 404 error -- controller or action not found
                $this->getResponse ()->setRawHeader ( 'HTTP/1.1 404 Not Found' ) ;

                // ... get some output to display...
                $content .= "<h1 align = 'center'>404. That is an error.</h1>" . PHP_EOL;
                $content .= "<p align = 'center'>The requested URL  ". $this->getRequest()->getRequestUri() . "  was not found on this server</p>";
                break ;
                
            default :
                // application error; display error page, but don't change             
                // status code 
                $content .= "<h1>Error!</h1>" . PHP_EOL;
                $content .= "<p>An unexpected error occurred with your request. Please try again later.</p>";

                break ;
        }

        // Clear previous content
        $this->getResponse()->clearBody();
        $this->view->content = $content;
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
    	//$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    	    	
    	echo "Resource not found";
    }
    
    public function noauthAction()
    {
 	
    	$this->view->url = $this->getRequest()->getRequestUri();	
    }
    

}

