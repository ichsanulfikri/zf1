<?php
	//Factory Class of ACL
	class Sis_ACL_Factory 
	{
		
		private static $_sessionNameSpace = 'Sis_ACL_Namespace';//Set an ACL Namespace
		private static $_objAuth;
		private static $_objAclSession;
		private static $_objAcl;
		
		//Function to get the ACL from DB or session
		public static function get(Zend_Auth $objAuth,$bolClearACL=false) 
		{
			self::$_objAuth = $objAuth;
			self::$_objAclSession = new Zend_Session_Namespace(self::$_sessionNameSpace);
			//var_dump(self::$_objAclSession->acl);die();
			if($bolClearACL) { self::_clear(); }
			//return (isset(self::$_objAclSession->acl)) ? self::$_objAclSession->acl : self::_loadAclFromDB();
			
			if(isset(self::$_objAclSession->acl)) {
				//echo "Use Session ACL";die();
				return self::$_objAclSession->acl;		
			}else{ 
		    	//echo "Load DB ACL";die();
		        return self::_loadAclFromDB();
			}
		}
		
		//Function to clear the session ACL
		private static function _clear() 
		{
			unset(self::$_objAclSession->acl);
		}
		
		//Function to save ACL to the session
		private static function _saveAclToSession() 
		{
			self::$_objAclSession->acl = self::$_objAcl;
		}
		
		//Function to load the roles, resources, role resource map from the DB and set roles for the resources
		private static function _loadAclFromDB() 
		{
			$lobjdeftype = new App_Model_Definitiontype();
			$lobjmodelresources = new App_Model_Resources();
			$lobjmodelrolesresources = new App_Model_RoleResources();
			

			$arrRoles = $lobjdeftype->fnGetDefinations('Role');
			
			$arrResources = $lobjmodelresources->fngetAllRows();
			
			$arrRoleResources = $lobjmodelrolesresources->fngetAllRows();

			
			self::$_objAcl = new Zend_Acl();


			//add roles
			foreach($arrRoles as $role){
				if($role['DefinitionDesc']=='guest'){
					self::$_objAcl->addRole(new Zend_Acl_Role($role['idDefinition']));
				}else{
	            	self::$_objAcl->addRole(new Zend_Acl_Role($role['idDefinition'],'guest'));
				}
			}	
			
			
	        // add all resources to the acl
	        foreach($arrResources as $resource){
	            self::$_objAcl->addResource(new Zend_Acl_Resource($resource['Module'] .'::' .$resource['Controller'] .'::' .$resource['Action']));
	        }	
	 		
	        // allow roles to resources
	        foreach($arrRoleResources as $roleResource){
	            self::$_objAcl->allow($roleResource['idRoles'],$roleResource['Module'] .'::' .$roleResource['Controller'] .'::' .$roleResource['Action']);
	        }	
	       				
			self::_saveAclToSession();	
			
			return self::$_objAcl;
		}
	}