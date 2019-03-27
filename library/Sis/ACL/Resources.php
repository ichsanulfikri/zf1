<?php
	//Resources Class of ACL 
	class Sis_ACL_Resources 
	{

		private $arrModules = array();

		private $arrControllers = array();

		private $arrActions = array();

		private $arrIgnore = array('.','..','.svn');

		public function __get($strVar) 
		{
			return ( isset($this->$strVar) ) ? $this->$strVar : null;
		}

		public function __set($strVar, $strValue) 
		{
			$this->$strVar = $strValue;
		}	
		
		//Empty Constructor
		public function __construct() {}

		//Function to Insert to DB modules,controllers,actions
		public function writeToDB() 
		{
			$this->checkForData();
			
			foreach( $this->arrModules as $strModuleName ) 
			{
				if( array_key_exists( $strModuleName, $this->arrControllers ) ) 
				{
					foreach( $this->arrControllers[$strModuleName] as $strControllerName ) 
					{
						if( array_key_exists( $strControllerName, $this->arrActions[$strModuleName] ) ) 
						{
							foreach( $this->arrActions[$strModuleName][$strControllerName] as $strActionName ) 
							{
								//Entities_ResourceTable::addResourceIfNonExistant($strModuleName, $strControllerName, $strActionName);
								$lobjresourcemodel = new App_Model_Resources();
								$where = "Module = '$strModuleName' AND Controller = '$strControllerName' AND Action = '$strActionName'";
								$lobjresultset = $lobjresourcemodel->fetchAll($where);
								$larrresultset = $lobjresultset->toArray();
								//print_r($larrresultset);die();
								if(count($larrresultset) < 1)
								{
									$larradata = array("Module"=>$strModuleName,"Controller"=>$strControllerName,"Action"=>$strActionName,"Modified"=>date('Y-m-d H:i:s'),"Created"=>date('Y-m-d H:i:s'));
									$lobjresourcemodel->insert($larradata);
								}
							}
						}
					}
				}
			}	
			return $this;		
		}

		private function checkForData() 
		{
			if ( count($this->arrModules) < 1 ) { throw new Sis_ACL_Exception('No modules found.'); }
			
			if ( count($this->arrControllers) < 1 ) { throw new Sis_ACL_Exception('No Controllers found.'); }
			
			if ( count($this->arrActions) < 1 ) { throw new Sis_ACL_Exception('No Actions found.'); }
		}

		//Function to build all the arrays
		public function buildAllArrays() 
		{
	    	$this->buildModulesArray();
	    	
	    	$this->buildControllerArrays();
	    	
	    	$this->buildActionArrays();
	    	
	    	return $this;
		}

		//Function to create an array of Modules
		public function buildModulesArray() 
		{
	        $dstApplicationModules = opendir( APPLICATION_PATH . '/modules' );
	        
	        while ( ($dstFile = readdir($dstApplicationModules) ) !== false ) 
	        {
	            if( ! in_array($dstFile, $this->arrIgnore) ) 
	            {
	            	if( is_dir(APPLICATION_PATH . '/modules/' . $dstFile) ) { $this->arrModules[] = $dstFile; }
	            }
	        }
	        
	        closedir($dstApplicationModules);
	        $this->arrModules[] = 'default';
			//print_r($this->arrModules);
		}

		//Function to create an array of Controllers
		public function buildControllerArrays() 
		{
			if( count($this->arrModules) > 0 ) 
			{
				foreach( $this->arrModules as $strModuleName ) 
				{
					if($strModuleName != 'default')
					{
						$datControllerFolder = opendir(APPLICATION_PATH . '/modules/' . $strModuleName . '/controllers' );
						
				        while ( ($dstFile = readdir($datControllerFolder) ) !== false ) {
				        	
				            if( ! in_array($dstFile, $this->arrIgnore)) {
				            
				            	if( preg_match( '/Controller/', $dstFile) ) { $this->arrControllers[$strModuleName][] = strtolower( substr( $dstFile,0,-14 ) ); }
			
				            }
				        }
				        closedir($datControllerFolder);
					}
					else 
					{
						$datControllerFolder = opendir(APPLICATION_PATH . '/controllers' );
						
				        while ( ($dstFile = readdir($datControllerFolder) ) !== false ) 
				        {
				            if( ! in_array($dstFile, $this->arrIgnore)) 
				            {
				            	if( preg_match( '/Controller/', $dstFile) ) { $this->arrControllers[$strModuleName][] = strtolower( substr( $dstFile,0,-14 ) ); }
				            }
				        }
				        closedir($datControllerFolder);
					}
				}
			}
			//print_r($this->arrControllers);die();
		}

		//Function to create an array of Actions	
		public function buildActionArrays() 
		{
			if( count($this->arrControllers) > 0 ) 
			{
				//echo "a";
				foreach( $this->arrControllers as $strModule => $arrController ) 
				{
					if($strModule != 'default')
					{
						//echo "b";
						foreach( $arrController as $strController ) 
						{
							//echo "c";
							/*$larrmodule = explode('-',$strModule);
							$larrmodule[0] = ucfirst($larrmodule[0]);
							$larrmodule[1] = ucfirst($larrmodule[1]);
							$modulename = implode('',$larrmodule);*/
							//$strClassName = ucfirst( $strModule ).'_'.ucfirst( $strController . 'Controller' );
							$strClassName = ucfirst( $strModule ).'_'.ucfirst( $strController . 'Controller' );
	
							if( ! class_exists( $strClassName ) ) {
								//echo (APPLICATION_PATH . '/modules/'.$strModule.'/controllers/'.ucfirst( $strController ).'Controller.php');
								Zend_Loader::loadFile(APPLICATION_PATH . '/modules/'.$strModule.'/controllers/'.ucfirst( $strController ).'Controller.php');
							}
							//echo "d";
							$objReflection = new Zend_Reflection_Class( $strClassName ); 
							$arrMethods = $objReflection->getMethods(); 
							//print_r($arrMethods);die();
							foreach( $arrMethods as $objMethods ) 
							{
								if( preg_match( '/Action/', $objMethods->name ) ) 
								{
									$this->arrActions[$strModule][$strController][] = substr($this->_camelCaseToHyphens($objMethods->name),0,-6 );
								}
							}
						}
					}
					else 
					{
						foreach( $arrController as $strController ) 
						{
							//echo "c";
							/*$larrmodule = explode('-',$strModule);
							$larrmodule[0] = ucfirst($larrmodule[0]);
							$larrmodule[1] = ucfirst($larrmodule[1]);
							$modulename = implode('',$larrmodule);*/
							//$strClassName = ucfirst( $strModule ).'_'.ucfirst( $strController . 'Controller' );
							$strClassName = ucfirst( $strController . 'Controller' );
	
							if( ! class_exists( $strClassName ) ) 
							{
								//echo (APPLICATION_PATH . '/modules/'.$strModule.'/controllers/'.ucfirst( $strController ).'Controller.php');
								Zend_Loader::loadFile(APPLICATION_PATH . '/controllers/'.ucfirst( $strController ).'Controller.php');
							}
							//echo "d";
							$objReflection = new Zend_Reflection_Class( $strClassName ); 
							$arrMethods = $objReflection->getMethods(); 
							//print_r($arrMethods);die();
							foreach( $arrMethods as $objMethods ) 
							{
								if( preg_match( '/Action/', $objMethods->name ) ) 
								{
									$this->arrActions[$strModule][$strController][] = substr($this->_camelCaseToHyphens($objMethods->name),0,-6 );
								}
							}
						}
					}
				}
			}			//print_r($this->arrActions);die();
		}
		
		private function _camelCaseToHyphens($string) 
		{
			if($string == 'currentPermissionsAction') {$found = true;}
			$length = strlen($string);
			$convertedString = '';
			for($i = 0; $i <$length; $i++) 
			{
				if(ord($string[$i]) > ord('A') && ord($string[$i]) < ord('Z')) 
				{
					$convertedString .= '-' .strtolower($string[$i]);
				} 
				else 
				{
					$convertedString .= $string[$i];
				}
			}
			return strtolower($convertedString);
		}
	}

?>
