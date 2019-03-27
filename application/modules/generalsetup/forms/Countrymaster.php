<?php
class GeneralSetup_Form_Countrymaster extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
    	$idCountry = new Zend_Form_Element_Hidden('idCountry');
        $idCountry->removeDecorator("DtDdWrapper");
        $idCountry->removeDecorator("Label");
        $idCountry->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
        $CountryName = new Zend_Form_Element_Text('CountryName');
		$CountryName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CountryName->setAttrib('required',"true")   
        		->setAttrib('propercase','true')    			 
        		->setAttrib('maxlength','80')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag') 
        		->setAttrib('invalidMessage', '');
        		
        
        $CountryCode = new Zend_Form_Element_Text('CountryCode');
		$CountryCode->setAttrib('dojoType',"dijit.form.NumberTextBox");	
        $CountryCode->setAttrib('maxlength','3');
        $CountryCode->setAttrib('required',"true");
        $CountryCode->removeDecorator("DtDdWrapper");
        $CountryCode->removeDecorator("Label");
        $CountryCode->removeDecorator('HtmlTag'); 		
        		
        		//->setAttrib('onBlur', 'fnGetCountryName(this.value)');
        $Alias= new Zend_Form_Element_Text('Alias');
		$Alias->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Alias->setAttrib('required',"true")   
        		->setAttrib('propercase','true')    			 
        		->setAttrib('maxlength','80')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $CountryIso= new Zend_Form_Element_Text('CountryIso');
		$CountryIso->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CountryIso->setAttrib('required',"true")   
        		->setAttrib('propercase','true')    			 
        		->setAttrib('maxlength','2')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $CountryISO3= new Zend_Form_Element_Text('CountryISO3');
		$CountryISO3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CountryISO3->setAttrib('propercase','true')    			 
        			->setAttrib('maxlength','3')       
        			->removeDecorator("DtDdWrapper")
        	   		->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        $CurrencyName= new Zend_Form_Element_Text('CurrencyName');
		$CurrencyName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CurrencyName->setAttrib('propercase','true')    			 	
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');			
        $CurrencyShortName= new Zend_Form_Element_Text('CurrencyShortName');
		$CurrencyShortName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CurrencyShortName->setAttrib('propercase','true')    			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        $CurrencySymbol= new Zend_Form_Element_Text('CurrencySymbol');
		$CurrencySymbol->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $CurrencySymbol->setAttrib('propercase','true')    			 
        			->setAttrib('maxlength','4')       
        			->removeDecorator("DtDdWrapper")
        	   		->removeDecorator("Label")
        			->removeDecorator('HtmlTag');	

        $DefaultLanguage = new Zend_Dojo_Form_Element_FilteringSelect('Default Language');
        $DefaultLanguage->removeDecorator("DtDdWrapper");
        $DefaultLanguage->removeDecorator("Label");
        $DefaultLanguage->removeDecorator('HtmlTag');
        $DefaultLanguage->setAttrib('required',"false");
       // $DefaultLanguage->setAttrib('OnChange', 'fnChangeLabel(this)');
        $DefaultLanguage->setRegisterInArrayValidator(false);
		$DefaultLanguage->setAttrib('dojoType',"dijit.form.FilteringSelect");
        				
   		$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
         		
       $Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
	
		
        //form elements
        $this->addElements(array(
        						 $DefaultLanguage,
        						 $idCountry,
        						 $UpdDate,
        						 $UpdUser,
        						 $CountryName,
        						 $CountryIso,
        						 $CountryISO3,
        						 $Alias,
        						 $CurrencyName,
        						 $CurrencyShortName,
        						 $CurrencySymbol,
        						 $Active,
        						 $Save,
        						 $CountryCode,
        						 $Add	
                                 ));

    }
}