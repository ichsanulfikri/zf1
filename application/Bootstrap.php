<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initSession() {
        //Zend_Session::start();
    	$sis = new Zend_Session_Namespace('sis');
	    if (!isset($sis->initialized)) :    
			Zend_Session::regenerateId();
			$sis->initialized = true;
		endif;
		Zend_Registry::set('sis',$sis);

            $applicant = new Zend_Session_Namespace('applicant');
	    if (!isset($sis->initialized)) :
			Zend_Session::regenerateId();
			$sis->initialized = true;
		endif;
		Zend_Registry::set('applicant',$applicant);
	
        Zend_Locale::setDefault('id_ID');
        
    }

    protected function _initAppAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'App',
            'basePath'  => dirname(__FILE__),));
        Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(false);
        
        return $autoloader;
    }
      
   
	protected function _initLog(){
		//Get the config parameters from the config file
	 	$config = new Zend_Config_Ini('../application/configs/application.ini','development');	 		
		//Db object 
		$db = Zend_Db::factory($config->resources->db->adapter,$config->resources->db->params);			
		//Column mapping of the tbl_log 
		$colmap = array('user_id'=>'user_id',
			                'level'=>'level',
			                'hostname'=>'hostname',
			                'time'=>'time',
			                'message'=>'message',
			                'Description'=>'Description',
			                'ip'=>'ip');
		//writer object 
		$writer = new Zend_Log_Writer_Db($db, "tbl_log", $colmap);
			
		//set to registry
	    Zend_Registry::set("log", $writer);
	}
	 
	protected function _initLogp() {
		$options = $this->getOption('resources');

	 	$partitionConfig = $this->getOption('log');
        $logOptions = $options['log'];
		$baseFilename = 'application';
	 	$logFilename = '';
        switch(strtolower($partitionConfig['partitionFrequency'])){
            case 'daily':
                $logFilename = $baseFilename.'_'.date('Y_m_d');
                break;

            case 'weekly':
                $logFilename = $baseFilename.'_'.date('Y_W');
                break;

            case 'monthly':
                $logFilename = $baseFilename.'_'.date('m_Y');
                break;

            case 'yearly':
                $logFilename = $baseFilename.'_'.date('Y');
                break;

            default:
                $logFilename = $baseFilename;
        }

	 	//$log = new Zend_Log_Writer_Stream('../application/logs/'.$logFilename.'log.txt');
	 	//$logger = new Zend_Log($log);
	 	//$logger->setEventItem('random',rand(1,10));
		//Zend_Registry::set("logger", $logger);
	}
	
	 
	protected function _initViewHelpers() {
		
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
	    $view->addHelperPath ( 'Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper' );
		 		
		$view->doctype ('XHTML1_TRANSITIONAL');
		$view->headMeta()->appendHttpEquiv ('Content-Type','text/html;charset=UTF-8');
		$view->headMeta()->appendHttpEquiv ('Cache-control','no-cache');
		$view->headMeta()->appendHttpEquiv ('Pragma','no-cache');
		$view->headTitle()->setSeparator (' - ');
		$view->headTitle(APPLICATION_ENTERPRISE_SHORT ." - ". APPLICATION_TITLE_SHORT);
		
		$view->headLink()->headLink( array( 'rel' => 'shortcut icon',
				'href' => 'images/animated_favicon1.gif',
				'type' => 'image/x-icon' ));

		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);		
	}
	
	/**
      * used for handling top-level navigation
      * @return Zend_Navigation
      */
	protected function _initNavigation(){
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity()){
			
			$layout = $this->getResource('layout');
	        $view = $layout->getView();
	          
			//db config
	        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');		
			$params = array('host'=>$config->resources->db->params->host,
						'username' => $config->resources->db->params->username,
						'password'=>$config->resources->db->params->password,
						'dbname'=>$config->resources->db->params->dbname,
						'unix_socket'    => $config->resources->db->params->unix_socket,
						'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
					);
			$dbAdapter= Zend_Db::factory('Pdo_Mysql', $params);
			
		    $menuArray = $dbAdapter->fetchAll("SELECT * FROM tbl_nav_menu WHERE role_id = ".$auth->getIdentity()->IdRole);
		    
		    //put in nav container
		    $container = new Zend_Navigation();
		
		    foreach ( $menuArray as $value )
		    {
		        $container->addPage(
		            Zend_Navigation_Page::factory(array(
		            	'module' => $value['module'],
		            	'controller' => $value['controller'],
		            	'action' => $value['action'],
		                'label' => $value['label'],
		                'title' => $value['title'],
		            	'order' => $value['seq_order'],
		            	'id' => $value['id'],
		            ))
		        );
		    }
		    
		    //logout nav
		    $container->addPage(
	            Zend_Navigation_Page::factory(array(
	                'module' => 'default',
	            	'controller' => 'index',
	            	'action' => 'logout',
	                'label' => 'Logout',
	                'title' => 'logout',
	            	'order' => 99999999,
	            ))
	        );
	                
	        $view->navigation($container);
		}
		
		
	}
		 
	protected function _initAuth() {
		$auth = Zend_Auth::getInstance();

        $fc = Zend_Controller_Front::getInstance();
        //$fc->registerPlugin(new Sis_Plugin_Auth($auth,null));
        
        //Zend ACL Plugin
		$fc->registerPlugin(new Sis_Plugin_ACL());
       
	}
		
	protected function setconstants($constants){
        foreach ($constants as $key=>$value){
            if(!defined($key)){
                define($key, $value);
            }
        }
	}
	
	protected function _initTranslate(){
		$registry = Zend_Registry::getInstance();	
		
		 // Create Session block and save the locale
        $session = new Zend_Session_Namespace('session'); 

		    	
		$locale = new Zend_Locale('id_ID');		
		$file = APPLICATION_PATH . DIRECTORY_SEPARATOR .'languages'. DIRECTORY_SEPARATOR . "id_ID.php";

		$translate = new Zend_Translate('array',
            $file, $locale,
            array(
            'disableNotices' => true,    // This is a very good idea!
            'logUntranslated' => false,  // Change this if you debug
            )
        );

        $registry->set('Zend_Locale', $locale);
        $registry->set('Zend_Translate', $translate);
              
        
        return $registry;
	}
	
	protected function _initLanguageSelector(){
		$fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Sis_Plugin_LangSelector());
	}
	
	protected function _initLogging(){

		$auth = Zend_Auth::getInstance();
		
		if ($auth->hasIdentity()) {
        	$user_id = $auth->getInstance()->getIdentity()->iduser;
        	$username = $auth->getInstance()->getIdentity()->loginName;
        	
			$fc = Zend_Controller_Front::getInstance();
			
			//log registration
	        $logger = new App_Model_System_DbTable_Log($user_id);
	        Zend_Registry::set('system_logger', $logger);

	        //system log plugin
			$fc->registerPlugin(new Sis_Plugin_SystemLog($user_id));
		}
	}
	
/*	public function __construct($application) {
    parent::__construct($application);
   Sis_Error_Handler::set();
}*/
	

/*	protected function _initZFDebug() 
	 {
	    $autoloader = Zend_Loader_Autoloader::getInstance();
	    $autoloader->registerNamespace('ZFDebug');
	    $options = array(
	   		//'jquery_path' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',
	   		'plugins' => array('Variables',
	   		'Html',
	   		'Database' => array(),
	   		'Memory',
	   		'Time',
	   		'Registry',
	   		'Exception')
	 	);
	    $debug = new ZFDebug_Controller_Plugin_Debug($options);
	    $this->bootstrap('frontController');
	    $frontController = $this->getResource('frontController');
	    $frontController->registerPlugin($debug);
	 }*/

	protected function _initDatabase(){
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');		
		$parameters = array('host'=>$config->resources->db->params->host,
					'username' => $config->resources->db->params->username,
					'password'=>$config->resources->db->params->password,
					'dbname'=>$config->resources->db->params->dbname,
					'unix_socket'    => $config->resources->db->params->unix_socket,
					'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
				);
		
		try {
		    $db = Zend_Db::factory('Pdo_Mysql', $parameters);
		    $db->getConnection();
		} catch (Zend_Db_Adapter_Exception $e) {
		    echo $e->getMessage();
		    die('Could not connect to database.');
		} catch (Zend_Exception $e) {
		    echo $e->getMessage();
		    die('Could not connect to database.');
		}
		
		Zend_Registry::set('dbapp', $db);
		
		 $resource = $this->getPluginResource('multidb');
      	 Zend_Registry::set("multidb", $resource);		
    }
    
	protected function _initDomPdf(){
		//set_include_path(APPLICATION_PATH . "/../../library/dompdf" . PATH_SEPARATOR . get_include_path());
		set_include_path("/var/www/html/sis/library/dompdf/" . PATH_SEPARATOR . get_include_path());
    }
}