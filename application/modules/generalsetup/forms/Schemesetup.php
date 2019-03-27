<?php
class GeneralSetup_Form_Schemesetup extends Zend_Dojo_Form { //Formclass for the schemesetup module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
	
       	$SchemeCode = new Zend_Form_Element_Text('SchemeCode');	
		$SchemeCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $SchemeCode->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$EnglishDescription = new Zend_Form_Element_Text('EnglishDescription');
		$EnglishDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$EnglishDescription->setAttrib('required',"true")     			 
        				->setAttrib('maxlength','255')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        $ArabicDescription = new Zend_Form_Element_Text('ArabicDescription');
		$ArabicDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox"); 
		$ArabicDescription->setAttrib('required',"true")
        				->setAttrib('maxlength','255')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        $Mode = new Zend_Dojo_Form_Element_FilteringSelect('Mode');
        $Mode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Mode->setAttrib('required',"true");
        $Mode->removeDecorator("DtDdWrapper");
         $Mode->removeDecorator("Label");
        $Mode->removeDecorator('HtmlTag');
        $Mode->setRegisterInArrayValidator(false);
		$Mode->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
         		
         $Clear = new Zend_Form_Element_Submit('Clear');
        $Clear->label = $gstrtranslate->_("Clear");
        $Clear->dojotype="dijit.form.Button";
        $Clear->removeDecorator("DtDdWrapper");
        $Clear->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
    		
        $Back = new Zend_Form_Element_Submit('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
        $IdScheme  = new Zend_Form_Element_Hidden('IdScheme');
        $IdScheme->removeDecorator("DtDdWrapper");
        $IdScheme->removeDecorator("Label");
        $IdScheme->removeDecorator('HtmlTag');
 
        //form elements
        $this->addElements(array($SchemeCode,$EnglishDescription,$ArabicDescription,$Clear,$Active,$Save,$Back,$UpdDate,$UpdUser,$Mode,$IdScheme
        ));

    }
}