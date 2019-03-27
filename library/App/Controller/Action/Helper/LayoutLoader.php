<?php
class App_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function preDispatch() 
	{
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$config = $bootstrap->getOptions();
		$module = $this->getRequest()->getModuleName();
		if (isset($config[$module]['resources']['layout']['layout'])) {
			$layoutScript = $config[$module]['resources']['layout']['layout'];
			$this->getActionController()
				 ->getHelper('layout')
				 ->setLayout($layoutScript);
		}
		if (isset($config[$module]['resources']['layout']['layoutPath'])) {
            $layoutPath = $config[$module]['resources']['layout']['layoutPath'];
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath($layoutPath);
        }		
	}
	
}