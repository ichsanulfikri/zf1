<?php
class ForgotpasswordController extends Zend_Controller_Action {
	
 	private $gstrsessionSIS;//Global Session Name
	public function init() {
  	}
	
  	
	public function indexAction() {
		$this->_helper->layout->disableLayout ();
		$this->gstrsessionSIS = new Zend_Session_Namespace('sis');
		$lintiduser = "";
		//creating form object
	    //$lobjForgotPassword = new GeneralSetup_Form_User;
	    $lobjLoginForm = new App_Form_Login;
	    $reterivepassword = new App_Form_Forgotpassword;

  	    //$this->view->lobjForgotPassword = $lobjForgotPassword;
  	    $this->view->lobjLoginForm = $lobjLoginForm;
  	    $this->view->reterivepassword = $reterivepassword;

  	    // checking wheather form is pressed or not
	    if($this->getRequest()->ispost()) {	 
			//creating db object			
			$lobjdb = Zend_Db_Table::getDefaultAdapter();
						
			$Email = $this->getRequest()->getParam('email');
		
			//getting current user password from db
			$lstrsql = $lobjdb->select()
						->from('tbl_user',array('loginName','iduser'))
						->where("email = '$Email' ");
			$larrResult = $lobjdb->fetchRow($lstrsql);
		
			if(empty($larrResult["iduser"])){
			 	echo '<script language="javascript">alert("User Not Found")</script>';
			} else {
				$lintiduser =  $larrResult['iduser'];
				$lstrsql2 = $lobjdb	->select()
									->from(array('usr'=>'tbl_user'),array("usr.IdStaff","usr.iduser","usr.email","usr.loginName","CONCAT_WS(' ',IFNULL(usr.fName,''),IFNULL(usr.mName,''),IFNULL(usr.lName,''))	 AS UserName"))
									->where("email = '$Email' ");													 			
				$larrResult2 = $lobjdb->fetchRow($lstrsql2);
				$lstrloginName = $larrResult['loginName'];
            	$lstrUserName = $larrResult2["UserName"];
            	$strEmailAddress = $larrResult2['email'];
            	
            	
            	
				/*
				 * Get the Template Details
				 */	  	
            	
            	$lobjcommonCommonModel = new App_Model_Common();
				$larrEmailTempResult = $lobjcommonCommonModel->fnGetRegistrationEmailTemplate();
				//print_r($larrEmailTempResult);die();
				$stremailTemplateFrom =  $larrEmailTempResult['TemplateFrom'];
				$stremailTemplateFromDesc =  $larrEmailTempResult['TemplateFromDesc'];
	      		$stremailTemplateSubject =  $larrEmailTempResult['TemplateSubject'];
	      		$stremailTemplateBody =  $larrEmailTempResult['TemplateBody'];      

				//generate random password
				$lstrpasswd = self::fnCreateRandPassword(6);
				$bind = array(	'passwd'=>md5($lstrpasswd),
								'LastLogAttemptOn'=>NULL);
				$where = 'iduser = '.$lintiduser;
		
		  
				$stremailTemplateBody = str_replace("[<b><i><u>Candidate</u></i></b>]", $lstrUserName , $stremailTemplateBody);
				$stremailTemplateBody = str_replace("[Password]", $lstrpasswd , $stremailTemplateBody);
		  
	      		/*
	       		 * Get the Email Settings from Initial Config
		       	 */
				$lobjcommonCommonModel = new App_Model_Common();
				$staffType = $lobjcommonCommonModel->fnGetStaffDetails($larrResult2["IdStaff"]);
				if($staffType['StaffType'] == 0) {
					$iduniversity = $staffType['IdCollege'];
				} else if($staffType['StaffType'] == 1) {
					$collegee = $lobjcommonCommonModel->fnGetCollegeDetails($staffType["IdCollege"]);
					$iduniversity = $collegee['AffiliatedTo'];
				}

		  		$larrinitConfigFetchAllData = $lobjcommonCommonModel->fnGetInitialConfigDetails($iduniversity);
				if(count($larrinitConfigFetchAllData)!=0) {
					$strsmtpServer = $larrinitConfigFetchAllData['SMTPServer'];
		    		$strusername = $larrinitConfigFetchAllData['SMTPUsername'];
		    		$strpassword = $larrinitConfigFetchAllData['SMTPPassword'];
		        	$strsslValue = $larrinitConfigFetchAllData['SSL'];
				    $strfromEmail = $stremailTemplateFrom;
		        	$strsmtpPort = $larrinitConfigFetchAllData['SMTPPort'];
			     	 /*
				      * Check if any of the parameters are empty
				      */
					 if(!isset($strsmtpServer)) {
	  			 		echo '<script language="javascript">alert("Unable to send mail \n Check  STMP Settings")</script>';
	  			 		echo "<script>parent.refreshpage();</script>";
			       	 } else {
			       	 	$lobjTransport = new Zend_Mail_Transport_Smtp();
						$lobjProtocol = new Zend_Mail_Protocol_Smtp($strsmtpServer);
		  				try{
							$lobjProtocol->connect();
		   					$lobjProtocol->helo($strusername);
							$lobjTransport->setConnection($lobjProtocol);
			 
							//Intialize Zend Mailing Object
							$lobjMail = new Zend_Mail();
							$lobjMail->setFrom($strfromEmail,$stremailTemplateFromDesc);
							$lobjMail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
							$lobjMail->addHeader('MIME-Version', '1.0');
							$lobjMail->setSubject($stremailTemplateSubject);
			       	 		$lobjMail->addTo($strEmailAddress,$lstrUserName);
			       	 		$lobjMail->setBodyHtml($stremailTemplateBody);
			       	 		
			       	 		try {
								$lobjMail->send($lobjTransport);
								App_Model_DefModel::update('tbl_user',$bind,$where);
							} catch (Exception $e) {
								echo '<script language="javascript">alert("Unable to send mail \n check Internet Connection ")</script>';	
							}
			    	  }catch(Exception $e){
			    	  	echo '<script language="javascript">alert("Unable to send mail \n check Internet Connection ")</script>';
			    	  }
			    	  echo "<script>parent.refreshpage();</script>";
					}
				}  
			}		
		}
	}
	
	//Action To Generate Passowrd Randomly
    public function fnCreateRandPassword($length) 
    {
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$password = "";
		while ($i <= $length) 
		{
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
    
	}
	
	public function getemailAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$email = $this->_getParam('email');
		$lobjdb = Zend_Db_Table::getDefaultAdapter();
		$lstrsql = $lobjdb->select()
						  ->from('tbl_user',array('email','iduser'))
						  ->where("email = '$email' ");
		$larrResult = $lobjdb->fetchRow($lstrsql);
		echo $larrResult['email'];
		
	}

}