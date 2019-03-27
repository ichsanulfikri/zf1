<?php
class GeneralSetup_Form_Activity extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate');
    	
    	$ActivityName = new Zend_Form_Element_Text('ActivityName');
		$ActivityName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ActivityName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       $ActivityColorCode = new Zend_Form_Element_Text('ActivityColorCode');
	   $ActivityColorCode ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
       $ActivityColorCode ->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
       $UpdDate->removeDecorator("DtDdWrapper");
       $UpdDate->removeDecorator("Label");
       $UpdDate->removeDecorator('HtmlTag');
        
       $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
       $UpdUser->removeDecorator("DtDdWrapper");
       $UpdUser->removeDecorator("Label");
       $UpdUser->removeDecorator('HtmlTag');
       
       $actvity  = new Zend_Form_Element_Hidden('IdActivity');
       $actvity->removeDecorator("DtDdWrapper");
       $actvity->removeDecorator("Label");
       $actvity->removeDecorator('HtmlTag');
       
       $Save = new Zend_Form_Element_Submit('Save');
       $Save->dojotype="dijit.form.Button";
       $Save->label = $gstrtranslate->_("Save");
       $Save->removeDecorator("DtDdWrapper");
       $Save->removeDecorator("Label");
       $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
       // adding elements to cuttent object
        $this->addElements(array($actvity,       											
        						$ActivityName,
        						$ActivityColorCode,
        						$Save,
        						$UpdDate,
        						$UpdUser        						
        	));
    }
}