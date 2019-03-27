<?php
class GeneralSetup_Form_Sponsor extends Zend_Dojo_Form {
    public function init(){			
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		$strSystemDate = date('Y-m-d H:i:s');
    	
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day-1),$year));
		$dateofbirth = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
		
		$Update = new Zend_Form_Element_Hidden('UpdDate');
        $Update->removeDecorator("DtDdWrapper")
        			->setvalue($strSystemDate)
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');

		$idsponsor = new Zend_Form_Element_Hidden('idsponsor');
		$idsponsor->setAttrib('id','idsponsor')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$typeSponsor = new Zend_Form_Element_Select('typeSponsor');
        $typeSponsor ->  setAttrib('dojoType',"dijit.form.FilteringSelect");
		$typeSponsor->setAttrib('required',"true")
					->setAttrib('OnChange', "fnSetCompany")
				->removeDecorator("DtDdWrapper")
				->addMultiOption("0","Individual")
				->addMultiOption("1","Organisation")				
				->removeDecorator("Label") 				
				->removeDecorator('HtmlTag');			
					
		$Organisation = new Zend_Form_Element_Text('Organisation');
		$Organisation  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					//-> setAttrib('required',"true")                               
					->setAttrib('maxlength','250')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
								
		$lName = new Zend_Form_Element_Text('lName',array('regExp'=>"[A-Za-z ]+",'invalidMessage'=>"Alphabets Only"));
		$lName  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")                                 
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');

		$mName = new Zend_Form_Element_Text('mName',array('regExp'=>"[A-Za-z ]+",'invalidMessage'=>"Alphabets Only"));
		$mName -> setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$fName = new Zend_Form_Element_Text('fName',array('regExp'=>"[A-Za-z ]+",'invalidMessage'=>"Alphabets Only"));
		$fName-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			      -> setAttrib('required',"true") 						
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$dob  = new Zend_Form_Element_Text('DOB');
		$dob ->  setAttrib('dojoType',"dijit.form.DateTextBox")	
			->setAttrib('constraints', "$dateofbirth")
			//-> setAttrib('required',"true") 		 				
							->removeDecorator("Label")							
							->setAttrib('title',"dd-mm-yyyy")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
														
		$gender = new Zend_Form_Element_Radio('gender');       
        $gender->  setAttrib('dojoType',"dijit.form.RadioButton")
        	        ->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag')
        			->addMultiOptions(array('0' => 'Male','1' => 'Female'))
					->setValue('0')				
					->setSeparator('')
					->setAttrib('required',"true");
																			
		$Add1 = new Zend_Form_Element_Text('Add1');
		$Add1-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					-> setAttrib('required',"true") 
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$Add2 = new Zend_Form_Element_Text('Add2');
		$Add2-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 				
					->removeDecorator('HtmlTag');

		
		$City = new Zend_Dojo_Form_Element_FilteringSelect('City');
        $City->removeDecorator("DtDdWrapper");
        $City->removeDecorator("Label");
        $City->removeDecorator('HtmlTag');
        $City->setAttrib('required',"true");
        $City->setRegisterInArrayValidator(false);
		$City->setAttrib('dojoType',"dijit.form.FilteringSelect");				
					
		$ZipCode = new Zend_Form_Element_Text('zipCode');
		$ZipCode-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       //->setAttrib('required',"true")
					->setAttrib('maxlength','20')
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');					

		$Country = new Zend_Form_Element_Select('Country');
        $Country ->  setAttrib('dojoType',"dijit.form.FilteringSelect");
		$Country->setAttrib('required',"true")
					->setAttrib('OnChange', "fnGetCountryStateList")
				->removeDecorator("DtDdWrapper")
				->removeDecorator("Label") 				
				->removeDecorator('HtmlTag');
				
		$State = new Zend_Form_Element_Select('State');
		$State ->  setAttrib('dojoType',"dijit.form.FilteringSelect")
				->setAttrib('required',"true")			
				->setRegisterInArrayValidator(false)
				->setAttrib('OnChange', 'fnGetStateCityList')
				->removeDecorator("DtDdWrapper")
				->removeDecorator("Label") 				
				->removeDecorator('HtmlTag');	

		$HomePhonecountrycode = new Zend_Form_Element_Text('HomePhonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$HomePhonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $HomePhonecountrycode->setAttrib('maxlength','3');  
        $HomePhonecountrycode->setAttrib('style','width:30px');  
        $HomePhonecountrycode->removeDecorator("DtDdWrapper");
        $HomePhonecountrycode->removeDecorator("Label");
        $HomePhonecountrycode->removeDecorator('HtmlTag');
        
        $HomePhonestatecode = new Zend_Form_Element_Text('HomePhonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$HomePhonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $HomePhonestatecode->setAttrib('maxlength','2');
        $HomePhonestatecode->setAttrib('style','width:30px');  
        $HomePhonestatecode->removeDecorator("DtDdWrapper");
        $HomePhonestatecode->removeDecorator("Label");
        $HomePhonestatecode->removeDecorator('HtmlTag');		
				
		$HomePhone = new Zend_Form_Element_Text('HomePhone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Home Phone No."));
		$HomePhone-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('style','width:93px') 
			         ->setAttrib('maxlength','9')
					->addFilter('StripTags')
					->addFilter('StringTrim')				
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');
					
		$WorkPhonecountrycode = new Zend_Form_Element_Text('WorkPhonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$WorkPhonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $WorkPhonecountrycode->setAttrib('maxlength','3');  
        $WorkPhonecountrycode->setAttrib('style','width:30px');  
        $WorkPhonecountrycode->removeDecorator("DtDdWrapper");
        $WorkPhonecountrycode->removeDecorator("Label");
        $WorkPhonecountrycode->removeDecorator('HtmlTag');
        
        $WorkPhonestatecode = new Zend_Form_Element_Text('WorkPhonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$WorkPhonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $WorkPhonestatecode->setAttrib('maxlength','2');
        $WorkPhonestatecode->setAttrib('style','width:30px');  
        $WorkPhonestatecode->removeDecorator("DtDdWrapper");
        $WorkPhonestatecode->removeDecorator("Label");
        $WorkPhonestatecode->removeDecorator('HtmlTag');						
					
		$WorkPhone = new Zend_Form_Element_Text('WorkPhone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Home Phone No."));
		$WorkPhone-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('style','width:93px') 
			        ->setAttrib('maxlength','3')
					->addFilter('StripTags')
					->addFilter('StringTrim')				
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');
		$CellPhonecountrycode = new Zend_Form_Element_Text('CellPhonecountrycode',array('regExp'=>"[0-9+]+",'invalidMessage'=>"Only digits"));
		$CellPhonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $CellPhonecountrycode->setAttrib('maxlength','4');  
        $CellPhonecountrycode->setAttrib('style','width:50px');  
        $CellPhonecountrycode->removeDecorator("DtDdWrapper");
        $CellPhonecountrycode->removeDecorator("Label");
        $CellPhonecountrycode->removeDecorator('HtmlTag');
        
       				
		$CellPhone = new Zend_Form_Element_Text('CellPhone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Home Phone No."));
		$CellPhone-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('style','width:109px')
			         ->setAttrib('maxlength','20')
					->addFilter('StripTags')
					->addFilter('StringTrim')				
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');
					
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
        
        
		$Fax = new Zend_Form_Element_Text('Fax',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Home Phone No."));
		$Fax-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
				->setAttrib('style','width:93px')
			    ->setAttrib('maxlength','20')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
				
		$Email = new Zend_Form_Element_Text('Email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Email-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       -> setAttrib('required',"true")				
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$url =  new Zend_Form_Element_Text('url',array('regExp'=>"(www).[A-Za-z0-9-_]+\.[A-Za-z0-9-_%&\?\/\.=]+",'invalidMessage'=>"Not a valid Url"));
		$url-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');			
					
		$Active = new Zend_Form_Element_Checkbox('Active');
		$Active->  setAttrib('dojoType',"dijit.form.CheckBox") 
			        ->setChecked(true)
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');
		
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
         		
         $Close = new Zend_Form_Element_Button('Close');
         $Close->dojotype="dijit.form.Button";
         $Close->label = $gstrtranslate->_("Close");
		 $Close->setAttrib('class', 'NormalBtn')
				->setAttrib('onclick', 'fnCloseLyteBox()')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				 	 
			$this->addElements(
						array(
							$Update, $UpdUser,$idsponsor,$Organisation,$typeSponsor,
							$lName,$mName,$fName,$dob, $gender,
							$Add1,$Add2,$City,$ZipCode,$State,$Country,$url,
							$HomePhonecountrycode,$HomePhonestatecode,$HomePhone,$WorkPhonecountrycode,$WorkPhonestatecode,$WorkPhone, $CellPhonecountrycode,$CellPhone,$Faxcountrycode,$Faxstatecode,$Fax,
							$Email,$Active,$Save,$Close
							//,$limitofsponsorship,$idstudent														
						)
			);
    	}
	}
?>