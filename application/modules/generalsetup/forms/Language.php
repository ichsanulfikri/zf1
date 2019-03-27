<?php
class GeneralSetup_Form_Language extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
		
    	$Idlang = new Zend_Form_Element_Hidden('id');
        $Idlang->removeDecorator("DtDdWrapper");
        $Idlang->removeDecorator("Label");
        $Idlang->removeDecorator('HtmlTag');
        
        $system = new Zend_Form_Element_Text('system');	
		$system->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $system->setAttrib('required',"true")       			 
        		->setAttrib('size','100')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$english = new Zend_Form_Element_Textarea('english');
		$english->setAttrib('dojoType',"dijit.form.ValidationTextArea")    			 
        				->setAttrib('cols','50')     
        				->setAttrib('rows','10')   
        				->setAttrib('required',"true")  
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				 		
       	$Arabic = new Zend_Form_Element_Textarea('arabic');
		$Arabic->setAttrib('dojoType',"dijit.form.ValidationTextArea")    			 
        				->setAttrib('cols','50')    
        				->setAttrib('rows','10')       
                        ->setAttrib('required',"true")				
		                 ->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        		
        		
 
        
        $UpdDate = new Zend_Form_Element_Hidden('createddt');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('createdby');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        

        
        $Language = new Zend_Form_Element_Hidden('Language');
        $Language->removeDecorator("DtDdWrapper");
        $Language->removeDecorator("Label");
        $Language->removeDecorator('HtmlTag');

        $Save = new Zend_Form_Element_Submit('Save');
        $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
    		
         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($Idlang,
        						 $system,
        						 $english,
                                 $Arabic,
                                 $UpdDate,
                                 $UpdUser,
                                 $Language,
                                 $Save,
                                 $Back));

    }
}