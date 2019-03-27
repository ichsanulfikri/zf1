<?php
class GeneralSetup_Form_Emailtemplate extends Zend_Dojo_Form {
    public function init() {    	
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	$strSystemDate = date('Y-m-d H:i:s');
    	
        $idTemplate = new Zend_Form_Element_Hidden('idTemplate');
        $idTemplate->removeDecorator("DtDdWrapper");
        $idTemplate->removeDecorator("Label");
        $idTemplate->removeDecorator('HtmlTag');

        			
        $TemplateName = new Zend_Form_Element_Text('TemplateName');
		$TemplateName-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			        -> setAttrib('required',"true") 
					->setAttrib('maxlength','25')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
        			

        			 
        			 
        $TemplateFrom = new Zend_Form_Element_Text('TemplateFrom');
		$TemplateFrom-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       // ->setAttrib('class','txt_put')
			        -> setAttrib('required',"true") 
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');


        				
        	$TemplateFromDesc = new Zend_Form_Element_Text('TemplateFromDesc');
			$TemplateFromDesc-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       // ->setAttrib('class','txt_put')
					->setAttrib('maxlength','50')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

        
        				
        $TemplateSubject = new Zend_Form_Element_Text('TemplateSubject');
		$TemplateSubject-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       // ->setAttrib('class','txt_put')
					->setAttrib('maxlength','100')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');				

        				
        				
        $TemplateHeader = new Zend_Form_Element_Text('TemplateHeader');
		$TemplateHeader-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			        //->setAttrib('class','txt_put')
					->setAttrib('maxlength','225')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

        				
        $TemplateFooter = new Zend_Form_Element_Text('TemplateFooter');
		$TemplateFooter-> setAttrib('dojoType',"dijit.form.ValidationTextBox")
			       // ->setAttrib('class','txt_put')
					->setAttrib('maxlength','225')
					->addFilter('StripTags')
					->addFilter('StringTrim')					
					->removeDecorator("Label") 
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
        				
        				
        				

        $Deleteflag = new Zend_Form_Element_Checkbox('Deleteflag');        
        $Deleteflag-> setAttrib('dojoType',"dijit.form.CheckBox")
        			->removeDecorator("DtDdWrapper")
        			->setChecked(true)
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');        
            
    
        
        $idDefinition = new Zend_Form_Element_Select('idDefinition');
        $idDefinition->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag')
        			-> setAttrib('style',"width:166px")
        			 ->setAttrib('dojoType',"dijit.form.FilteringSelect")
        			//->addMultiOptions($larrDropdownValues)        			        			       		        					
        			->setAttrib('required',"true") ;

        			
        $idDefination = new Zend_Form_Element_Select('idDefination');      	
		$idDefination ->  setAttrib('dojoType',"dijit.form.FilteringSelect")
			 	-> setAttrib('required',"true")
			 	-> setAttrib('style',"width:150px") 
				->setRegisterInArrayValidator(false)				
				->removeDecorator("DtDdWrapper")
				->removeDecorator("Label") 				
				->removeDecorator('HtmlTag');
				

        			
        			
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper")
        		->setvalue($strSystemDate)
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        
        $UpdUser = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        			
      
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag');
        
        $Back = new Zend_Form_Element_Button('Back');
        $Back->dojotype="dijit.form.Button";
        $Back->label = $gstrtranslate->_("Close");
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

        $this->addElements(array($idTemplate,$TemplateName,$TemplateFrom,
                                 $TemplateFromDesc,$TemplateSubject,
                                 $TemplateHeader,$TemplateFooter,
                                 $Deleteflag,$UpdDate,$UpdUser,$idDefinition,$idDefination,$Save,$Back));

    }
}