<?php
class GeneralSetup_Form_Subjectprogram extends Zend_Dojo_Form {
	
    public function init(){			
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		
	
		$IdProgram = new Zend_Form_Element_Hidden('IdProgram');
		$IdProgram->setAttrib('dojoType',"dijit.form.TextBox");
        $IdProgram->removeDecorator("DtDdWrapper");
        $IdProgram->removeDecorator("Label");
        $IdProgram->removeDecorator('HtmlTag');
					
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');				 	
				 				 	
				 	
		$Mark = new Zend_Form_Element_Text('Mark');
		$Mark  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					->setAttrib('required',"true")                               
					->setAttrib('maxlength','3')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');	
					
		$Description = new Zend_Form_Element_Textarea('Description');	
        $Description	->setAttrib('cols', '30')
        				->setAttrib('rows','3')
        				->setAttrib('style','width = 10%;')
        				->setAttrib('maxlength','250')
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        			
        			
        			
        $Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');			

        
       	$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Add");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');	
        $IdSubject->setAttrib('required',"true");
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
	    $IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		
     
         		
        $this->addElements(
						array(
							$IdProgram,$Mark,$Description,$IdSubject,
							$UpdDate,$UpdUser,$Save,$Active
							
						)
			);
    	}
	}
?>