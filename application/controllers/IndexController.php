<?php
class IndexController extends Zend_Controller_Action {

	private $gstrsessionSIS;//Global Session Name
	public function init() { //instantiate log object

	}

	public function indexAction() {
		//landing page
		
		/*
		 * Get the roles depending on user role
		 */
		$auth = Zend_Auth :: getInstance();
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$menuArray = $dbAdapter->fetchAll("SELECT * FROM tbl_nav_menu WHERE role_id = ".$auth->getIdentity()->IdRole." ORDER BY seq_order ");
		
		$this->view->moduleList = $menuArray;
		
	}

	function loginAction() {
		$this->_helper->layout->disableLayout (); //
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis');
		$lobjform = new App_Form_Login(); //intialize login form
		// echo $this->view->url(array('module'=>'application' ,'controller'=>'manualapplication', 'action'=>'index'),'default',true);die();
		$this->view->lobjform = $lobjform; //send the form object to the view
		$lobjuniversitymodel = new GeneralSetup_Model_DbTable_University();
		$larruniversityresult = $lobjuniversitymodel->fnGetUniversityList();
		if(count($larruniversityresult)>0)
		$lobjform->IdUniversity->addMultiOptions($larruniversityresult);
		else
		$lobjform->IdUniversity->addMultiOptions(array("0"=>"No University"));

		if ($this->_request->isPost()) {
			Zend_Loader::loadClass('Zend_Filter_StripTags');
			$filter = new Zend_Filter_StripTags();
			$username = $filter->filter($this->_request->getPost('username'));
			$password = $filter->filter($this->_request->getPost('password'));
			$IdUniversity = $filter->filter($this->_request->getPost('IdUniversity'));
			$larrdefaultlanguage = $lobjuniversitymodel->fngetUniversityLanguage($IdUniversity);

			if(isset($larrdefaultlanguage[0]['UnivLang'])){
			$lstrdefaultlanguage = $larrdefaultlanguage[0]['UnivLang'];
			}
			else{
				$larrdefaultlanguage[0]['UnivLang'] = "English";
				$lstrdefaultlanguage = $larrdefaultlanguage[0]['UnivLang'];
			}
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

			$authAdapter->setTableName('tbl_user')
			->setIdentityColumn('loginName')
			->setCredentialColumn('passwd');

			$authAdapter->setIdentity($username);
			$authAdapter->setCredential(md5($password));

			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($authAdapter);

			if ($result->isValid()) {
				$data = $authAdapter->getResultRowObject(null, 'passwd');
				
				$data->id = $data->iduser;
				
				$auth->getStorage()->write($data);
				$auth->getIdentity()->iduser;

				$larrCommonModel = new App_Model_Common();
				$Rolename = $larrCommonModel->fnGetRoleName($auth->getIdentity()->IdRole);

				$staffdetails = $larrCommonModel->fnGetStaff($auth->getIdentity()->IdStaff);
				
				$this->gstrsessionSIS->__set('idUniversity',$IdUniversity);
				$this->gstrsessionSIS->__set('idCollege',isset($staffdetails['IdCollege']) ? $staffdetails['IdCollege'] : 0);
				$this->gstrsessionSIS->__set('userType',isset($staffdetails['StaffType']) ? $staffdetails['StaffType'] : 0);  // user type 0:college  1: branch
				$this->gstrsessionSIS->__set('rolename',$Rolename['DefinitionDesc']);
				$this->gstrsessionSIS->__set('UniversityLanguage',$lstrdefaultlanguage);
				$this->gstrsessionSIS->__set('EmployeeId',$auth->getIdentity()->IdStaff);
				//
				//Zend_Registry::set('EmployeeId',2134);
				//ini yatie tambah nak tahu Defination Code 13/12/2012
				$this->gstrsessionSIS->__set('IdRole',$auth->getIdentity()->IdRole);
				echo "id: ".$auth->getIdentity()->IdStaff;

				if($staffdetails['StaffType']==0) {
					$this->gstrsessionSIS->__set('UserCollegeId','0');
				} else if($staffdetails['StaffType']==1) {
					$this->gstrsessionSIS->__set('UserCollegeId',$staffdetails['IdCollege']);
				}

				if($IdUniversity == '0' || $IdUniversity == '') {
					$this->gstrsessionSIS->__set('universityname','No University');
				} else {
					$lobjuniversitymodel = new GeneralSetup_Model_DbTable_University();
					$universityname = $lobjuniversitymodel->fnGetUniversityName($IdUniversity);
					$IdUniversityname= $universityname['Univ_Name'];
					$this->gstrsessionSIS->__set('universityname',$IdUniversityname);
				}

				//$this->_redirect($this->view->url(array('module'=>'generalsetup','controller'=>'user', 'action'=>'index'),'default',false));
				//$this->_redirect( $this->baseUrl . '/generalsetup/user/index');
				
				//clear session ACL
				Zend_Session::namespaceUnset('Sis_ACL_Namespace');
				
				$session = new Zend_Session_Namespace('path_redirect');

				if(isset($session->previous_url)){
					$this->_redirect( $this->baseUrl . $session->previous_url);
				}else{
					$this->_redirect($this->view->url(array('module'=>'default','controller'=>'index', 'action'=>'index'),'default',false));
				}
				
			} else {
				$this->view->alertError = 'Login failed. Either username or password is incorrect';
			}
		}
		$this->render(); //render the view
	}


	public function logoutAction() {
		
		Zend_Session:: namespaceUnset('sis');
		Zend_Session::namespaceUnset('Sis_ACL_Namespace');
		Zend_Session::namespaceUnset('path_redirect');
		
		
		
		$storage = new Zend_Auth_Storage_Session();
		$storage->clear();
		
		
		Zend_Session::destroy();
		
		$this->_redirect($this->view->url(array('module'=>'default','controller'=>'index'),'default',true));
		//$this->_redirect( $this->baseUrl . '/index/login');
	}
}




