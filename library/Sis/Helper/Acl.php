    <?php
				class Sis_Helper_Acl {
					public $acl;
					public function __construct() {
						$this->acl = new Zend_Acl ();
					}
					public function setRoles() {
						$this->acl->addRole ( new Zend_Acl_Role ('guest' ) );
						$this->acl->addRole ( new Zend_Acl_Role ( 'editor' ) );
						$this->acl->addRole ( new Zend_Acl_Role ( 'admin' ) );
					
					}
					
					public function setResources() {
						
						$this->acl->add ( new Zend_Acl_Resource ( 'view' ) );
						$this->acl->add ( new Zend_Acl_Resource ( 'edit' ) );
						$this->acl->add ( new Zend_Acl_Resource ( 'delete' ) );
					
					}
					
					public function setPrivilages() {
						$this->acl->allow ( 'guest', null, 'view' );
						$this->acl->allow ( 'editor', array ('view', 'edit' ) );
						$this->acl->allow ( 'admin' );
					}
					public function setAcl() {
						Zend_Registry::set ( 'acl', $this->acl );
					}
				}
	?>
