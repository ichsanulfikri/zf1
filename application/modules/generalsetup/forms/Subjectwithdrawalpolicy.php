<?php
class GeneralSetup_Form_Subjectwithdrawalpolicy extends Zend_Dojo_Form {
	
public function init(){			
	
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		
	
		$IdSubject = new Zend_Form_Element_Hidden('IdSubject');
		$IdSubject->setAttrib('dojoType',"dijit.form.TextBox");
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
        
        $SubjectName = new Zend_Form_Element_Hidden('SubjectName');
		$SubjectName->setAttrib('dojoType',"dijit.form.TextBox");
        $SubjectName->removeDecorator("DtDdWrapper");
        $SubjectName->removeDecorator("Label");
        $SubjectName->removeDecorator('HtmlTag')
        			->setAttrib('readonly','true');
        			
        			
        $SubjectCode = new Zend_Form_Element_Hidden('SubjectCode');
		$SubjectCode->setAttrib('dojoType',"dijit.form.TextBox");
        $SubjectCode->removeDecorator("DtDdWrapper");
        $SubjectCode->removeDecorator("Label");
        $SubjectCode->removeDecorator('HtmlTag')
        			->setAttrib('readonly','true');
					
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');	

		$IdSubjectWithdrawalPolicy = new Zend_Form_Element_Hidden('IdSubjectWithdrawalPolicy');
		$IdSubjectWithdrawalPolicy->setAttrib('id','IdSubjectWithdrawalPolicy')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');	
				 				 	
				 	
		$Days = new Zend_Form_Element_Text('Days');
		$Days  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					->setAttrib('required',"true")                               
					->setAttrib('maxlength','2')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');	
					
		$Percentage = new Zend_Form_Element_Text('Percentage');	
        $Percentage	->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					->setAttrib('required',"true")                               
					->setAttrib('maxlength','3')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
        			
        			
        			
        $Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');			

        
       	$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');	
        $IdSemester->setAttrib('required',"true");
        $IdSemester->removeDecorator("DtDdWrapper");
        $IdSemester->removeDecorator("Label");
        $IdSemester->removeDecorator('HtmlTag');
	    $IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		
	    $Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addSubjectwithdrawalpolicyDetails()')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add");
	    
	    //required
     
         		
        $this->addElements(
						array(
							$IdSubject,$Days,$Percentage,$IdSemester,
							$UpdDate,$UpdUser,$Save,$Active,$IdSubjectWithdrawalPolicy,$Add,$SubjectName,$SubjectCode
							
						)
			);
    	}
}