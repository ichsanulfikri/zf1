<?php
class GeneralSetup_ChangepasswordController extends Zend_Controller_Action {	

	public function init() { //initialization function
		$lobjforms = new App_Form_Search (); //intialize search lobjuserForm
		$this->view->lobjform = $lobjforms; //send the lobjuserForm object to the view
	}
	
	public function indexAction() {
		// creating form object

	    $lobjChangepasswordform = new GeneralSetup_Form_Changepasswordad(); 

	    // making form available to view
  	    $this->view->form = $lobjChangepasswordform;
	   
  	    // checking for clear button is pressed or not
  	    if ($this->getRequest()->getParam('Clear'))
  	    {
  	    		$this->_redirect( $this->baseUrl . '/generalsetup/changepassword/index');    		
  	    }
  	    
  	    // checking wheather form is pressed or not
	    if($this->getRequest()->ispost())		
		{
			 
			// creating db object			
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
						
			// getting userid form present session 
			$auth = Zend_Auth::getInstance();
			$lintiduser = $auth->getIdentity()->iduser;
			
			// getting current user password from db
			$lstrsql = $lobjdb->select()
						->from('tbl_user','passwd')
						->where("iduser = $lintiduser ");
												
			$stroldpasswordfromdb = App_Model_DefModel::selectrow($lstrsql);

			// getting entered old password data from form & making md5 string of it 
			$strpasswordFromForm = $this->getRequest()->getParam('oldpassword');				
			$strhashmd5 = md5($strpasswordFromForm);
			
			// if oldpassword from db and entered oldpassword in form is not equal then display message

			
			// getting newpassword & retype password data from form  
			$stroldPasswordFromForm = $this->getRequest()->getParam('oldpassword');
			$strnewPasswordFromForm = $this->getRequest()->getParam('newpassword');
			$strretypepasswordFromForm = $this->getRequest()->getParam('retypepassword');
			
						
/*			if($stroldPasswordFromForm == $strnewPasswordFromForm) {
				echo '<script language="javascript">alert("Old password & New password are same")</script>';				
			} */
			if ($stroldpasswordfromdb["passwd"] != $strhashmd5)
			{
				echo '<script language="javascript">alert("Old Password is Invalid")</script>';
			}
			// checking wheather newpassword & retype is equal or not
			elseif($strnewPasswordFromForm != $strretypepasswordFromForm)
			{
				
				echo '<script language="javascript">alert("New password & retype password is not matching")</script>';
			}
			
			// update new password in db if validation is correct
			elseif ($stroldpasswordfromdb["passwd"] == $strhashmd5 && $strnewPasswordFromForm == $strretypepasswordFromForm )
			{
				$strupdatePassword = md5($strretypepasswordFromForm); 
				$lobjdata =  array('passwd'=>$strupdatePassword);
				$lintwhereCondition = "iduser = $lintiduser";
				$lobjresult = App_Model_DefModel::update('tbl_user',$lobjdata,$lintwhereCondition);
                    
				echo '<script language="javascript">alert("Password has been updated")</script>';	
				
				//$this->_redirect( $this->baseUrl . '/generalsetup/changepassword/index');    
						
			}			
		}
	}

}