<?php
class GeneralSetup_Form_Subjectprerequisites extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
    	$IdSubject = new Zend_Form_Element_Hidden('IdSubject');
    	$IdSubject->setAttrib('dojoType',"dijit.form.TextBox");
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
        
    	$IdSubjectPrerequisites = new Zend_Form_Element_Hidden('IdSubjectPrerequisites');
        $IdSubjectPrerequisites->removeDecorator("DtDdWrapper");
        $IdSubjectPrerequisites->removeDecorator("Label");
        $IdSubjectPrerequisites->removeDecorator('HtmlTag');
        

		
		
		$IdRequiredSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdRequiredSubject');
        $IdRequiredSubject->removeDecorator("DtDdWrapper");
        //$IdRequiredSubject->setAttrib('required',"true") ;
        $IdRequiredSubject->removeDecorator("Label");
        $IdRequiredSubject->removeDecorator('HtmlTag');
        $IdRequiredSubject->setRegisterInArrayValidator(false);
        $IdRequiredSubject->setAttrib('OnChange', 'fnGetSubjectCode');
		$IdRequiredSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
        			
		
		$PrerequisiteType = new Zend_Dojo_Form_Element_FilteringSelect('PrerequisiteType');	
        $PrerequisiteType->addMultiOptions(array(//''  => 'Select',
        										  '0' => 'Pass with Grade',
						   			 			  '1' => 'Complete Subject'));
       	$PrerequisiteType->removeDecorator("DtDdWrapper");
       	$PrerequisiteType->setAttrib('OnChange', 'fngrade');
        $PrerequisiteType->removeDecorator("Label");
        $PrerequisiteType->removeDecorator('HtmlTag');
		$PrerequisiteType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
	   	/*$PrerequisiteGrade = new Zend_Form_Element_Text('PrerequisiteGrade');
		$PrerequisiteGrade->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $PrerequisiteGrade //->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','30')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	*/

         $PrerequisiteGrade = new Zend_Dojo_Form_Element_FilteringSelect('PrerequisiteGrade');
        $PrerequisiteGrade->removeDecorator("DtDdWrapper");
        //$PrerequisiteGrade->setAttrib('required',"true") ;
        $PrerequisiteGrade->removeDecorator("Label");
        $PrerequisiteGrade->removeDecorator('HtmlTag');
        $PrerequisiteGrade->setRegisterInArrayValidator(false);
        $PrerequisiteGrade->setAttrib('dojoType',"dijit.form.FilteringSelect");			
														
      
        $Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add").' '.$gstrtranslate->_("Subject");
        $Add->setAttrib('OnClick', 'AddSubjectPrerequisitesDetails()');
		$Add->setAttrib('class', 'NormalBtnauto')
			->removeDecorator("Label")
			->removeDecorator("DtDdWrapper")
			->removeDecorator('HtmlTag');
			
		
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
				
    	$SubCode = new Zend_Form_Element_Hidden('SubjectCode');
    	$SubCode->setAttrib('dojoType',"dijit.form.TextBox");
        $SubCode->removeDecorator("DtDdWrapper");
        $SubCode->removeDecorator("Label");
        $SubCode->removeDecorator('HtmlTag');
        
        $MinCreditHours = new Zend_Form_Element_Text('MinCreditHours',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$MinCreditHours ->setValue('0')
						->setAttrib('dojoType',"dijit.form.ValidationTextBox")				
						->removeDecorator("DtDdWrapper")
						->removeDecorator("Label") 					
						->removeDecorator('HtmlTag');
				
		
        //form elements
        $this->addElements(array($IdSubject,$IdSubjectPrerequisites,$IdRequiredSubject,$SubCode,
        						 $PrerequisiteType,$PrerequisiteGrade,	
        						 $Save,$Clear,$Add,$MinCreditHours	
                                 ));

    }
}