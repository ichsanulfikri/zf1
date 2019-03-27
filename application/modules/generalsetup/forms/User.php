<?php
class GeneralSetup_Form_User extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day-1),$year));
		$dateofbirth = "{datePattern:'dd-MM-yyyy'}"; 
	    
		$iduser = new Zend_Form_Element_Hidden('iduser');
        $iduser->removeDecorator("DtDdWrapper");
        $iduser->removeDecorator("Label");
        $iduser->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
   
        $loginName = new Zend_Form_Element_Text('loginName');
        $loginName->addValidator('regex', true, 
                       array(
                           'pattern'=>'/^[(a-zA-Z0-9_)]+$/', 
                           'messages'=>array(
                               	'regexNotMatch'=>'Invalid Username.Only the letters A to Z and numbers are allowed'
                           )
                       )
					);
					
        $loginName->addValidator(new Zend_Validate_Db_NoRecordExists('tbl_user', 'loginName'));	
		$loginName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $loginName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
       
        $IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->setAttrib('required',"true") ;
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        $IdStaff->setAttrib('OnChange', 'fnGetStaffDetails');
        $IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");

        
        $lName = new Zend_Form_Element_Text('lName');
		$lName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $lName//->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
                  
       
        $mName = new Zend_Form_Element_Text('mName');
		$mName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $mName->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
    $NameField1 = new Zend_Form_Element_Text('NameField1');
    $NameField1->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField2 = new Zend_Form_Element_Text('NameField2');
    $NameField2->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField3 = new Zend_Form_Element_Text('NameField3');
    $NameField3->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField4 = new Zend_Form_Element_Text('NameField4');
    $NameField4->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField5 = new Zend_Form_Element_Text('NameField5');
    $NameField5->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');


                  
        		
        $userArabicName = new Zend_Form_Element_Text('userArabicName');
		$userArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $userArabicName->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
       
        $fName = new Zend_Form_Element_Text('fName');
		$fName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $fName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        
        $passwd = new Zend_Form_Element_Password('passwd',array('regExp'=>"[\w]+[@#$%^&*?>)(<!~+-/}{=_]+[\w@#$%^&*!~+-/]*",'invalidMessage'=>"No Spaces Allowed and One special Character required"));
        $passwd->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $passwd->setAttrib('required',"true") ;
        $passwd->removeDecorator("DtDdWrapper");
        $passwd->removeDecorator("Label");
        $passwd->removeDecorator('HtmlTag');
                  
        $DOB = new Zend_Dojo_Form_Element_DateTextBox('DOB');
        $DOB->setAttrib('dojoType',"dijit.form.DateTextBox");
        $DOB->setAttrib('constraints', "$dateofbirth");
		//$DOB->setAttrib('required',"true");
        $DOB->removeDecorator("DtDdWrapper");
        $DOB->setAttrib('title',"dd-mm-yyyy");
        $DOB->removeDecorator("Label");
        $DOB->removeDecorator('HtmlTag'); 
        
        
        $gender = new Zend_Dojo_Form_Element_FilteringSelect('gender');	
        $gender->addMultiOptions(array('1' => 'Male',
									   '0' => 'Female'));
        //$gender->setAttrib('required',"true");
        $gender->removeDecorator("DtDdWrapper");
        $gender->removeDecorator("Label");
        $gender->removeDecorator('HtmlTag');
		$gender->setAttrib('dojoType',"dijit.form.FilteringSelect");
 
              
        
        $addr1 = new Zend_Form_Element_Text('addr1');
		$addr1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $addr1//->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
                   
        $addr2 = new Zend_Form_Element_Text('addr2');
		$addr2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $addr2->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
                  
        $city = new Zend_Dojo_Form_Element_FilteringSelect('city');
        $city->removeDecorator("DtDdWrapper");
        $city->removeDecorator("Label");
        $city->removeDecorator('HtmlTag');
        //$city->setAttrib('required',"false");
        $city->setRegisterInArrayValidator(false);
		$city->setAttrib('dojoType',"dijit.form.FilteringSelect");		

        
        $country = new Zend_Dojo_Form_Element_FilteringSelect('country');
        $country->removeDecorator("DtDdWrapper");
        //$country->setAttrib('required',"true") ;
        $country->removeDecorator("Label");
        $country->removeDecorator('HtmlTag');
        $country->setAttrib('OnChange', 'fnGetCountryStateList');
        $country->setRegisterInArrayValidator(false);
		$country->setAttrib('dojoType',"dijit.form.FilteringSelect");
        
        
        $gender = new Zend_Dojo_Form_Element_FilteringSelect('gender');	
        $gender->addMultiOptions(array('1' => 'Male',
									   '0' => 'Female'));
       // $gender->setAttrib('required',"true");
        $gender->removeDecorator("DtDdWrapper");
        $gender->removeDecorator("Label");
        $gender->removeDecorator('HtmlTag');
        $gender->setRegisterInArrayValidator(false);
		$gender->setAttrib('dojoType',"dijit.form.FilteringSelect");
        
       
        $state = new Zend_Dojo_Form_Element_FilteringSelect('state');
        $state->removeDecorator("DtDdWrapper");
        //$state->setAttrib('required',"true") ;
        $state->removeDecorator("Label");
        $state->removeDecorator('HtmlTag');
        $state->setRegisterInArrayValidator(false);
        $state->setAttrib('OnChange', 'fnGetStateCityList');
		$state->setAttrib('dojoType',"dijit.form.FilteringSelect");
              
        
        $zipCode = new Zend_Form_Element_Text('zipCode');
		$zipCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $zipCode->setAttrib('maxlength','20');   
        $zipCode->removeDecorator("DtDdWrapper");
        $zipCode->removeDecorator("Label");
        $zipCode->removeDecorator('HtmlTag');
                           
        $homecountrycode = new Zend_Form_Element_Text('homecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$homecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $homecountrycode->setAttrib('maxlength','3');  
        $homecountrycode->setAttrib('style','width:30px');  
        $homecountrycode->removeDecorator("DtDdWrapper");
        $homecountrycode->removeDecorator("Label");
        $homecountrycode->removeDecorator('HtmlTag');
        
        $homestatecode = new Zend_Form_Element_Text('homestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$homestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $homestatecode->setAttrib('maxlength','2');
        $homestatecode->setAttrib('style','width:30px');  
        $homestatecode->removeDecorator("DtDdWrapper");
        $homestatecode->removeDecorator("Label");
        $homestatecode->removeDecorator('HtmlTag');

        
        //$homePhone = new Zend_Form_Element_Text('homePhone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Home Phone No."));
        $homePhone = new Zend_Form_Element_Text('homePhone',array('regExp'=>"[0-9]+",'invalidMessage'=>"Not a valid Home Phone No."));
	$homePhone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $homePhone->setAttrib('maxlength','9');
        $homePhone->setAttrib('style','width:93px');
        $homePhone->removeDecorator("DtDdWrapper");
        $homePhone->removeDecorator("Label");
        $homePhone->removeDecorator('HtmlTag'); 
        
        
        $workcountrycode = new Zend_Form_Element_Text('workcountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
	$workcountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $workcountrycode->setAttrib('maxlength','3');  
        $workcountrycode->setAttrib('style','width:30px');  
        $workcountrycode->removeDecorator("DtDdWrapper");
        $workcountrycode->removeDecorator("Label");
        $workcountrycode->removeDecorator('HtmlTag');
        
        $workstatecode = new Zend_Form_Element_Text('workstatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$workstatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $workstatecode->setAttrib('maxlength','2');
        $workstatecode->setAttrib('style','width:30px');  
        $workstatecode->removeDecorator("DtDdWrapper");
        $workstatecode->removeDecorator("Label");
        $workstatecode->removeDecorator('HtmlTag');
        
        
        $workPhone = new Zend_Form_Element_Text('workPhone',array('regExp'=>"[0-9]+",'invalidMessage'=>"Not a valid Work Phone No."));
		$workPhone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $workPhone->setAttrib('maxlength','9');
        $workPhone->setAttrib('style','width:93px');  
        $workPhone->removeDecorator("DtDdWrapper");
        $workPhone->removeDecorator("Label");
        $workPhone->removeDecorator('HtmlTag');
        
        
        $countrycode = new Zend_Form_Element_Text('countrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$countrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $countrycode->setAttrib('maxlength','4');  
        $countrycode->setAttrib('style','width:50px');  
        $countrycode->removeDecorator("DtDdWrapper");
        $countrycode->removeDecorator("Label");
        $countrycode->removeDecorator('HtmlTag');
        
/*        $statecode = new Zend_Form_Element_Text('statecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$statecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $statecode->setAttrib('maxlength','5');  
        $statecode->setAttrib('style','width:30px');  
        $statecode->removeDecorator("DtDdWrapper");
        $statecode->removeDecorator("Label");
        $statecode->removeDecorator('HtmlTag');*/
        
        
        $cellPhone = new Zend_Form_Element_Text('cellPhone',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$cellPhone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $cellPhone->setAttrib('maxlength','20');  
        $cellPhone->setAttrib('style','width:109px');  
        $cellPhone->removeDecorator("DtDdWrapper");
        $cellPhone->removeDecorator("Label");
        $cellPhone->removeDecorator('HtmlTag');
              
       	$faxcountrycode = new Zend_Form_Element_Text('faxcountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxcountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxcountrycode->setAttrib('maxlength','3');  
        $faxcountrycode->setAttrib('style','width:30px');  
        $faxcountrycode->removeDecorator("DtDdWrapper");
        $faxcountrycode->removeDecorator("Label");
        $faxcountrycode->removeDecorator('HtmlTag');
        
        $faxstatecode = new Zend_Form_Element_Text('faxstatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxstatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxstatecode->setAttrib('maxlength','2');
        $faxstatecode->setAttrib('style','width:30px');  
        $faxstatecode->removeDecorator("DtDdWrapper");
        $faxstatecode->removeDecorator("Label");
        $faxstatecode->removeDecorator('HtmlTag');
        
        $Fax = new Zend_Form_Element_Text('fax',array('regExp'=>"[0-9]+",'invalidMessage'=>"Not a valid Fax"));
		$Fax->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Fax->setAttrib('style','width:93px'); 
        $Fax->setAttrib('maxlength','20');
        $Fax->removeDecorator("DtDdWrapper");
        $Fax->removeDecorator("Label");
        $Fax->removeDecorator('HtmlTag');
        
       
        $email = new Zend_Form_Element_Text('email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $email//->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        
        $UserStatus  = new Zend_Form_Element_Checkbox('UserStatus');
        $UserStatus->setAttrib('dojoType',"dijit.form.CheckBox");
        $UserStatus->setvalue('1');
        $UserStatus->removeDecorator("DtDdWrapper");
        $UserStatus->removeDecorator("Label");
        $UserStatus->removeDecorator('HtmlTag');
        
        
        $IdRole = new Zend_Dojo_Form_Element_FilteringSelect('IdRole');
        $IdRole->setAttrib('maxlength','50');
        $IdRole->removeDecorator("DtDdWrapper");
        $IdRole->setAttrib('required',"true") ;
        $IdRole->removeDecorator("Label");
        $IdRole->removeDecorator('HtmlTag');
		$IdRole->setAttrib('dojoType',"dijit.form.FilteringSelect");
        
 
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
         		
         		
         		
        $Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Close = new Zend_Form_Element_Button('Close');
		$Close->dojotype="dijit.form.Button";
       	$Close->label = $gstrtranslate->_("Back");
		$Close->setAttrib('class', 'NormalBtn')				
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
	
		$idRole = new Zend_Dojo_Form_Element_FilteringSelect('idRole');
        $idRole->setAttrib('maxlength','50');
        $idRole->removeDecorator("DtDdWrapper");
        $idRole->setAttrib('required',"true") ;
        $idRole->removeDecorator("Label");
        $idRole->removeDecorator('HtmlTag');
        $idRole->setAttrib('OnChange', 'fnGetCountryStateList');
		$idRole->setAttrib('dojoType',"dijit.form.FilteringSelect")
				->setRegisterInArrayValidator(false);
		
		
		$fromDate = new Zend_Dojo_Form_Element_DateTextBox('fromDate');
        $fromDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $fromDate->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
    	$fromDate->setAttrib('required',"true");
        $fromDate->removeDecorator("DtDdWrapper")
        	->setAttrib('title',"dd-mm-yyyy");
        $fromDate->removeDecorator("Label");
        $fromDate->removeDecorator('HtmlTag');

        
        $toDate = new Zend_Dojo_Form_Element_DateTextBox('toDate');
        $toDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $toDate->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
    	$toDate->setAttrib('required',"true");
        $toDate->removeDecorator("DtDdWrapper")
        	->setAttrib('title',"dd-mm-yyyy");
        $toDate->removeDecorator("Label");
        $toDate->removeDecorator('HtmlTag');
        
        
        $description = new Zend_Form_Element_Text('description');
		$description->setAttrib('dojoType',"dijit.form.ValidationTextBox");			 
        $description->setAttrib('maxlength','200')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');



        //form elements
        $this->addElements(array($iduser,$loginName,$UpdDate,$lName,$UpdUser,
                                 $mName,$fName,
                                 $NameField1,$NameField2,$NameField3,$NameField4,$NameField5,
                                 $passwd,$IdRole,
                                 $DOB,$gender,$addr1,
                                 $addr2,$city,$state,
                                 $country,$zipCode,$UserStatus,
                                 $homePhone,$workPhone,
                                 $cellPhone,$faxcountrycode,$faxstatecode,$Fax,$Save,$email,$idRole,$fromDate,$toDate,$description,
                                 $Clear,$Close,$Add,$countrycode,$workcountrycode,$homecountrycode,$workstatecode,$homestatecode,$userArabicName,$IdStaff
                                 ));

    }
}