<?php
class GeneralSetup_Form_Collegemaster extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day-1),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
		
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
		$IdCollege = new Zend_Form_Element_Hidden('IdCollege');
        $IdCollege->removeDecorator("DtDdWrapper");
        $IdCollege->removeDecorator("Label");
        $IdCollege->removeDecorator('HtmlTag');
        
        $CollegeName = new Zend_Form_Element_Text('CollegeName');
        $CollegeName->setAttrib('style','width:200px');
		$CollegeName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CollegeName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','100')   
        		->setAttrib('propercase','true')        
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag')
        		->setAttrib('invalidMessage', '');
        		
        $CollegeCode = new Zend_Form_Element_Text('CollegeCode');
        $CollegeCode->setAttrib('style','width:200px');
		$CollegeCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CollegeCode->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','100')         		   
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 		
        		
        $ShortName = new Zend_Form_Element_Text('ShortName');
		$ShortName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ShortName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$ArabicName = new Zend_Form_Element_Text('ArabicName');
       	$ArabicName->setAttrib('style','width:200px');
		$ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")    			 
        				->setAttrib('maxlength','100')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        		
        $Add1 = new Zend_Form_Element_Text('Add1');
		$Add1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Add1->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$Add2 = new Zend_Form_Element_Text('Add2');
		$Add2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Add2 ->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $City = new Zend_Dojo_Form_Element_FilteringSelect('City');
        $City->removeDecorator("DtDdWrapper");
        $City->removeDecorator("Label");
        $City->removeDecorator('HtmlTag');
        $City->setAttrib('required',"false");
        $City->setRegisterInArrayValidator(false);
		$City->setAttrib('dojoType',"dijit.form.FilteringSelect");			
        		
        $State = new Zend_Dojo_Form_Element_FilteringSelect('State');
        $State->removeDecorator("DtDdWrapper");
        $State->setAttrib('required',"true") ;
        $State->removeDecorator("Label");
        $State->removeDecorator('HtmlTag');
        $State->setRegisterInArrayValidator(false);
        $State->setAttrib('OnChange', 'fnGetStateCityList');
		$State->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		        
        $Country = new Zend_Dojo_Form_Element_FilteringSelect('Country');
        $Country->removeDecorator("DtDdWrapper");
        $Country->setAttrib('required',"true") ;
        $Country->removeDecorator("Label");
        $Country->removeDecorator('HtmlTag');
        $Country->setAttrib('OnChange', "fnGetCountryStateList(this,'State')");
        $Country->setRegisterInArrayValidator(false);
		$Country->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Idhead = new Zend_Dojo_Form_Element_FilteringSelect('Idhead');
        $Idhead->removeDecorator("DtDdWrapper");
        $Idhead->removeDecorator("Label");
        $Idhead->removeDecorator('HtmlTag');
        $Idhead->setRegisterInArrayValidator(false);
		$Idhead->setAttrib('dojoType',"dijit.form.FilteringSelect");
			
			
			
		$Zip = new Zend_Form_Element_Text('Zip');
		$Zip->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Zip->setAttrib('maxlength','20');   
        $Zip->removeDecorator("DtDdWrapper");
        $Zip->removeDecorator("Label");
        $Zip->removeDecorator('HtmlTag');
        
        $AffiliatedTo = new Zend_Dojo_Form_Element_FilteringSelect('AffiliatedTo');
        $AffiliatedTo->removeDecorator("DtDdWrapper");
        $AffiliatedTo->setAttrib('required',"true") ;
        $AffiliatedTo->removeDecorator("Label");
        $AffiliatedTo->removeDecorator('HtmlTag');
        $AffiliatedTo->setRegisterInArrayValidator(false);
		$AffiliatedTo->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Email = new Zend_Form_Element_Text('Email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Email->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
		
        $Phone1countrycode = new Zend_Form_Element_Text('Phone1countrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone1countrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone1countrycode->setAttrib('maxlength','3');  
        $Phone1countrycode->setAttrib('style','width:30px');  
        $Phone1countrycode->removeDecorator("DtDdWrapper");
        $Phone1countrycode->removeDecorator("Label");
        $Phone1countrycode->removeDecorator('HtmlTag');
        
        $Phone1statecode = new Zend_Form_Element_Text('Phone1statecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone1statecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone1statecode->setAttrib('maxlength','2');
        $Phone1statecode->setAttrib('style','width:30px');  
        $Phone1statecode->removeDecorator("DtDdWrapper");
        $Phone1statecode->removeDecorator("Label");
        $Phone1statecode->removeDecorator('HtmlTag');
        		
        //$Phone1 = new Zend_Form_Element_Text('Phone1',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone1 = new Zend_Form_Element_Text('Phone1');
        $Phone1->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone1->setAttrib('maxlength','9');
        $Phone1->setAttrib('style','width:93px');    
        $Phone1->removeDecorator("DtDdWrapper");
        $Phone1->removeDecorator("Label");
        $Phone1->removeDecorator('HtmlTag'); 
        
        
        $Phone2countrycode = new Zend_Form_Element_Text('Phone2countrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone2countrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone2countrycode->setAttrib('maxlength','3');  
        $Phone2countrycode->setAttrib('style','width:30px');  
        $Phone2countrycode->removeDecorator("DtDdWrapper");
        $Phone2countrycode->removeDecorator("Label");
        $Phone2countrycode->removeDecorator('HtmlTag');
        
        $Phone2statecode = new Zend_Form_Element_Text('Phone2statecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone2statecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone2statecode->setAttrib('maxlength','2');
        $Phone2statecode->setAttrib('style','width:30px');  
        $Phone2statecode->removeDecorator("DtDdWrapper");
        $Phone2statecode->removeDecorator("Label");
        $Phone2statecode->removeDecorator('HtmlTag');
        
        //$Phone2 = new Zend_Form_Element_Text('Phone2',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phone2 = new Zend_Form_Element_Text('Phone2');
        $Phone2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Phone2->setAttrib('style','width:93px');	
        $Phone2->setAttrib('maxlength','9');
        $Phone2->removeDecorator("DtDdWrapper");
        $Phone2->removeDecorator("Label");
        $Phone2->removeDecorator('HtmlTag'); 

        $Faxcountrycode = new Zend_Form_Element_Text('Faxcountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Faxcountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Faxcountrycode->setAttrib('maxlength','3');  
        $Faxcountrycode->setAttrib('style','width:30px');  
        $Faxcountrycode->removeDecorator("DtDdWrapper");
        $Faxcountrycode->removeDecorator("Label");
        $Faxcountrycode->removeDecorator('HtmlTag');
        
        $Faxstatecode = new Zend_Form_Element_Text('Faxstatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Faxstatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Faxstatecode->setAttrib('maxlength','5');  
        $Faxstatecode->setAttrib('style','width:30px');  
        $Faxstatecode->removeDecorator("DtDdWrapper");
        $Faxstatecode->removeDecorator("Label");
        $Faxstatecode->removeDecorator('HtmlTag');
        
        $Fax = new Zend_Form_Element_Text('Fax',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Fax"));
		$Fax = new Zend_Form_Element_Text('Fax');
        $Fax->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Fax->setAttrib('style','width:93px');
        $Fax->setAttrib('maxlength','20');   
        $Fax->removeDecorator("DtDdWrapper");
        $Fax->removeDecorator("Label");
        $Fax->removeDecorator('HtmlTag');
        
        $CollegeType = new Zend_Dojo_Form_Element_FilteringSelect('CollegeType');
        $CollegeType->removeDecorator("DtDdWrapper");
        $CollegeType->setAttrib('required',"true") ;
        $CollegeType->addMultiOptions(array('0' => 'Admin',
        									'1' => 'School'));
        $CollegeType->removeDecorator("Label");
        $CollegeType->removeDecorator('HtmlTag');
        $CollegeType->setRegisterInArrayValidator(false);
		$CollegeType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
        $IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->setAttrib('OnChange', "fnGetStaffList(this)");
        $IdStaff->setAttrib('required',"true") ;
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        $IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");   
		
      	$IsDepartment  = new Zend_Form_Element_Checkbox('IsDepartment');
        $IsDepartment->setAttrib('dojoType',"dijit.form.CheckBox");
        $IsDepartment->setvalue('0');
        $IsDepartment->removeDecorator("DtDdWrapper");
        $IsDepartment->removeDecorator("Label");
        $IsDepartment->removeDecorator('HtmlTag');
        
		
		
		
///////////////////////////////////////////// Extra fields ////////////////////////////////////////////////////////////////////        
        
        
        
        

        
        $FirstName = new Zend_Form_Element_Text('FirstName',array('regExp'=>"[A-Za-z ]+",'invalidMessage'=>"Alphabets Only"));
		$FirstName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FirstName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $SecondName = new Zend_Form_Element_Text('SecondName');
		$SecondName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $SecondName->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $ThirdName = new Zend_Form_Element_Text('ThirdName');
		$ThirdName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ThirdName->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$FourthName = new Zend_Form_Element_Text('FourthName');
		$FourthName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FourthName->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$FullName = new Zend_Form_Element_Text('FullName');
		$FullName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FullName->setAttrib('maxlength','200')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$Arabic_Name = new Zend_Form_Element_Text('Arabic_Name');
		$Arabic_Name->setAttrib('dojoType',"dijit.form.ValidationTextBox")    			 
        				->setAttrib('maxlength','200')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        		
        $Address1 = new Zend_Form_Element_Text('Address1');
		$Address1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Address1->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$Address2 = new Zend_Form_Element_Text('Address2');
		$Address2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Address2     			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $Citystaff = new Zend_Dojo_Form_Element_FilteringSelect('Citystaff');
        $Citystaff->removeDecorator("DtDdWrapper");
        $Citystaff->removeDecorator("Label");
        $Citystaff->removeDecorator('HtmlTag');
        $Citystaff->setAttrib('required',"false");
        $Citystaff->setRegisterInArrayValidator(false);
		$Citystaff->setAttrib('dojoType',"dijit.form.FilteringSelect");				
        		
        $Statestaff = new Zend_Dojo_Form_Element_FilteringSelect('Statestaff');
        $Statestaff->removeDecorator("DtDdWrapper");
        $Statestaff->setAttrib('required',"true") ;
        $Statestaff->removeDecorator("Label");
        $Statestaff->removeDecorator('HtmlTag');
        $Statestaff->setRegisterInArrayValidator(false);
        $Statestaff->setAttrib('OnChange', 'fnGetStateCityListStaff');
		$Statestaff->setAttrib('dojoType',"dijit.form.FilteringSelect");
		        
        $Countrystaff = new Zend_Dojo_Form_Element_FilteringSelect('Countrystaff');
        $Countrystaff->removeDecorator("DtDdWrapper");
        $Countrystaff->setAttrib('required',"true") ;
        $Countrystaff->removeDecorator("Label");
        $Countrystaff->removeDecorator('HtmlTag');
        $Countrystaff->setAttrib('OnChange', "fnGetCountryStateList(this,'Statestaff')");
        $Countrystaff->setRegisterInArrayValidator(false);
		$Countrystaff->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Zipcode = new Zend_Form_Element_Text('Zipcode');
		$Zipcode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Zipcode->setAttrib('maxlength','20');   
        $Zipcode->removeDecorator("DtDdWrapper");
        $Zipcode->removeDecorator("Label");
        $Zipcode->removeDecorator('HtmlTag');
        
        $Phonecountrycode = new Zend_Form_Element_Text('Phonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonecountrycode->setAttrib('maxlength','3');  
        $Phonecountrycode->setAttrib('style','width:30px');  
        $Phonecountrycode->removeDecorator("DtDdWrapper");
        $Phonecountrycode->removeDecorator("Label");
        $Phonecountrycode->removeDecorator('HtmlTag');
        
        $Phonestatecode = new Zend_Form_Element_Text('Phonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonestatecode->setAttrib('maxlength','5');  
        $Phonestatecode->setAttrib('style','width:30px');  
        $Phonestatecode->removeDecorator("DtDdWrapper");
        $Phonestatecode->removeDecorator("Label");
        $Phonestatecode->removeDecorator('HtmlTag');
        
        $Phone = new Zend_Form_Element_Text('Phone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$Phone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone->setAttrib('maxlength','20');  
        $Phone->setAttrib('style','width:93px');   
        $Phone->removeDecorator("DtDdWrapper");
        $Phone->removeDecorator("Label");
        $Phone->removeDecorator('HtmlTag'); 
                 
        $Mobilecountrycode = new Zend_Form_Element_Text('Mobilecountrycode',array('regExp'=>"[0-9+]+",'invalidMessage'=>"Only digits"));
		$Mobilecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
       	$Mobilecountrycode->setAttrib('maxlength','4');  
        $Mobilecountrycode->setAttrib('style','width:50px');  
        $Mobilecountrycode->removeDecorator("DtDdWrapper");
        $Mobilecountrycode->removeDecorator("Label");
        $Mobilecountrycode->removeDecorator('HtmlTag');
        
        $Mobile = new Zend_Form_Element_Text('Mobile',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$Mobile->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Mobile->setAttrib('style','width:109px'); 	
        $Mobile->setAttrib('maxlength','20');   
        $Mobile->removeDecorator("DtDdWrapper");
        $Mobile->removeDecorator("Label");
        $Mobile->removeDecorator('HtmlTag'); 
        
        $Emailstaff = new Zend_Form_Element_Text('Emailstaff',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Emailstaff->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Emailstaff->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $StaffType = new Zend_Dojo_Form_Element_FilteringSelect('StaffType');
        $StaffType->removeDecorator("DtDdWrapper");
        $StaffType->setAttrib('required',"true") ;
        $StaffType->setAttrib('readonly',"true") ;
        $StaffType->removeDecorator("Label");
        $StaffType->removeDecorator('HtmlTag');
        $StaffType->addMultiOptions(array(1=>'Staff under college',0=>'Staff under university'));
        $StaffType->setRegisterInArrayValidator(false);
		$StaffType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$IdLevel = new Zend_Dojo_Form_Element_FilteringSelect('IdLevel');
        $IdLevel->removeDecorator("DtDdWrapper");
        $IdLevel->setAttrib('required',"true") ;
        $IdLevel->removeDecorator("Label");
        $IdLevel->removeDecorator('HtmlTag');
        $IdLevel->setRegisterInArrayValidator(false);
		$IdLevel->setAttrib('dojoType',"dijit.form.FilteringSelect"); 	
		

		
		$FromDate = new Zend_Form_Element_Text('FromDate');
		$FromDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $FromDate->setAttrib('required',"true")  
			     ->setAttrib('constraints', "$joiningdate")	 		 
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

		$ToDate = new Zend_Form_Element_Hidden('ToDate');
		$ToDate->removeDecorator("DtDdWrapper") 
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');       
        

        $Save = new Zend_Form_Element_Submit('Save');
        $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
    		
         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = "Back";
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($IdCollege,$IsDepartment,
        						 $CollegeName,
        						 $ShortName,
        						 $CollegeCode,
        						 $ArabicName,
        						 $Add1,
                                 $Add2,
                                 $City,
                                 $State,
                                 $Country,
                                 $Zip,
                                 $AffiliatedTo,
                                 $Email,
                                 $Phone1countrycode,
                                 $Phone1statecode,
                                 $Phone1,
                                 $Phone2countrycode,
                                 $Phone2statecode,
                                 $Phone2,
                                 $Faxcountrycode,
                                 $Faxstatecode,
                                 $Fax,
                                 $CollegeType,
                                 $Active,
                                 $IdStaff,
        						 $FirstName,
        						 $SecondName,
        						 $ThirdName,
        						 $FourthName,
                                 $FullName,
                                 $Arabic_Name,
                                 $Address1,
                                 $Address2,
                                 $Citystaff,
                                 $Statestaff,
                                 $Countrystaff,
                                 $Idhead,
                                 $Zipcode,
                                 $Phonecountrycode,
                                 $Phonestatecode,
                                 $Phone,
                                 $Emailstaff,
                                 $Mobilecountrycode,
                                 $Mobile,
                                 $Active,
                                 $StaffType,
                                 $IdCollege,
                                 $IdLevel,
                                 $FromDate,
                                 $ToDate,
                                 $UpdDate,
                                 $UpdUser,
                                 $Save,
                                 $Back));

    }
}