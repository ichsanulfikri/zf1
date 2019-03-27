<?php
class GeneralSetup_Form_Formulir extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    
		$Year = new Zend_Dojo_Form_Element_FilteringSelect('Year');
        $Year->setAttrib('dojoType',"dijit.form.FilteringSelect")
        	 ->setAttrib('required',"true");
       	$Year->removeDecorator("DtDdWrapper");
        $Year->removeDecorator("Label");
        $Year->removeDecorator('HtmlTag');
        	
        $Apptype = new Zend_Dojo_Form_Element_FilteringSelect('Apptype');
		$Apptype->setAttrib('required',"true");
		$Apptype->removeDecorator("DtDdWrapper");
		$Apptype->removeDecorator("Label");
		$Apptype->removeDecorator('HtmlTag');
		$Apptype->setRegisterInArrayValidator(false);
		$Apptype->setAttrib('dojoType',"dijit.form.FilteringSelect");
			
		$EnterNo = new Zend_Form_Element_Text('EnterNo');
		$EnterNo->setAttrib('dojoType',"dijit.form.ValidationTextBox")
				->setAttrib('required',"true") ;              			        
        $EnterNo->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		   
        $Generate = new Zend_Form_Element_Submit('Generate');
		$Generate->dojotype="dijit.form.Button";
        $Generate->setAttrib('class', 'NormalBtn')
        		  ->removeDecorator("DtDdWrapper")
	        	  ->removeDecorator("Label")
    	    	  ->removeDecorator('HtmlTag');
		$Generate->label = $gstrtranslate->_("Generate");
        		
        $this->addElements(array($Year,$Apptype,$EnterNo,$Generate));
	    
    }
}