<?php
class GeneralSetup_Form_University extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
		$IdUniversity = new Zend_Form_Element_Hidden('IdUniversity');
        $IdUniversity->removeDecorator("DtDdWrapper");
        $IdUniversity->removeDecorator("Label");
        $IdUniversity->removeDecorator('HtmlTag');
        
        $month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
        
        $Univ_Name = new Zend_Form_Element_Text('Univ_Name');
        $Univ_Name->setAttrib('style','width:200px');
		$Univ_Name->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Univ_Name->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50') 
        		->setAttrib('propercase','true')    
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag')
        		->setAttrib('invalidMessage', '');
        		
       	$Univ_ArabicName = new Zend_Form_Element_Text('Univ_ArabicName');
		$Univ_ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")  			 
        				->setAttrib('maxlength','50')       
        				->removeDecorator("DtDdWrapper")
        				->setAttrib('style','width:200px')
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
    	$ShortName = new Zend_Form_Element_Text('ShortName',array('regExp'=>"[A-Za-z|0-9]+",'invalidMessage'=>"Alphabets Only"));
		$ShortName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ShortName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        
        $MoheCode = new Zend_Form_Element_Text('Univ_code_EPSBED');
        $MoheCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $MoheCode->setAttrib('maxlength','10')
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
		$Add2->setAttrib('dojoType',"dijit.form.ValidationTextBox")      			 
        		->setAttrib('maxlength','50')       
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
        $State->setAttrib('OnChange', 'fnGetStateCityListofUniversity');
        $State->setRegisterInArrayValidator(false);
		$State->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		        
        $Country = new Zend_Dojo_Form_Element_FilteringSelect('Country');
        $Country->removeDecorator("DtDdWrapper");
        $Country->setAttrib('required',"true") ;
        $Country->removeDecorator("Label");
        $Country->removeDecorator('HtmlTag');
        $Country->setAttrib('OnChange', "fnGetCountryStateList(this,'State')");
        $Country->setRegisterInArrayValidator(false);
		$Country->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Zip = new Zend_Form_Element_Text('Zip');
		$Zip->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Zip->setAttrib('maxlength','20');   
        $Zip->removeDecorator("DtDdWrapper");
        $Zip->removeDecorator("Label");
        $Zip->removeDecorator('HtmlTag');
        
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
        
        
        $Phone1 = new Zend_Form_Element_Text('Phone1',array('regExp'=>"[0-9]+",'invalidMessage'=>"Not a valid Phone No."));
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
        
        $Phone2 = new Zend_Form_Element_Text('Phone2',array('regExp'=>"[0-9]+",'invalidMessage'=>"Not a valid Work Phone No."));
		$Phone2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Phone2->setAttrib('style','width:93px');	
        $Phone2->setAttrib('maxlength','9');
        $Phone2->removeDecorator("DtDdWrapper");
        $Phone2->removeDecorator("Label");
        $Phone2->removeDecorator('HtmlTag'); 
        
        $faxcountrycode = new Zend_Form_Element_Text('faxcountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxcountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxcountrycode->setAttrib('maxlength','3');  
        $faxcountrycode->setAttrib('style','width:30px');  
        $faxcountrycode->removeDecorator("DtDdWrapper");
        $faxcountrycode->removeDecorator("Label");
        $faxcountrycode->removeDecorator('HtmlTag');
        
        $faxstatecode = new Zend_Form_Element_Text('faxstatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$faxstatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $faxstatecode->setAttrib('maxlength','5');  
        $faxstatecode->setAttrib('style','width:30px');  
        $faxstatecode->removeDecorator("DtDdWrapper");
        $faxstatecode->removeDecorator("Label");
        $faxstatecode->removeDecorator('HtmlTag');
                 
        $Fax = new Zend_Form_Element_Text('Fax',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Fax"));
		$Fax->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Fax->setAttrib('style','width:93px');	
        $Fax->setAttrib('maxlength','20');   
        $Fax->removeDecorator("DtDdWrapper");
        $Fax->removeDecorator("Label");
        $Fax->removeDecorator('HtmlTag');
        
        $Email = new Zend_Form_Element_Text('Email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Email->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$Url = new Zend_Form_Element_Text('Url',array('regExp'=>"^(www)\.([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$"));
		$Url->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Url->setAttrib('required',"true");      
        $Url->setAttrib('title',"http://www.trisakti.com")       			 
        		->setAttrib('maxlength','255')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
		$FromDate = new Zend_Form_Element_Text('FromDate');
		$FromDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $FromDate->setAttrib('required',"true")
			     ->setAttrib('constraints', "$joiningdate")	 
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

		$ToDate = new Zend_Form_Element_Hidden('ToDate');
		$ToDate	->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');        		

        $IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
        $IdStaff->setAttrib('OnChange', "fnGetStaffList(this)");
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->setAttrib('required',"true") ;
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        $IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		
        		
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
        $this->addElements(array($IdUniversity,
        						 $Univ_Name,
        						 $Univ_ArabicName,
        						 $ShortName,
        						 $MoheCode,
        						 $Add1,
                                 $Add2,
                                 $City,
                                 $State,
                                 $Country,
                                 $Zip,
                                 $Phone1countrycode,
                                 $Phone1statecode,
                                 $Phone2countrycode,
                                 $Phone2statecode,
                                 $Phone1,
                                 $Phone2,
                                 $faxcountrycode,
                                 $faxstatecode,
                                 $Fax,
                                 $Email,
                                 $Url,
                                 $Active,
                                 $UpdDate,
                                 $UpdUser,
                                 $FromDate,
                                 $ToDate,
                                 $IdStaff,
                                 $Save,
                                 $Back));

    }
}