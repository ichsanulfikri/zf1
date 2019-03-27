<?php
class GeneralSetup_Form_Coursetype extends Zend_Dojo_Form {
    public function init(){			
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		
		$IdCourseType = new Zend_Form_Element_Hidden('IdCourseType');
		$IdCourseType->setAttrib('id','IdCourseType')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');
				 	
				 	
		$CourseType = new Zend_Form_Element_Text('CourseType');
		$CourseType  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")  
					-> setAttrib('required',"true")                               
					->setAttrib('maxlength','100')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');	
					
		$Bahasaindonesia = new Zend_Form_Element_Text('Bahasaindonesia');
		$Bahasaindonesia  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")                             
					->setAttrib('maxlength','100')
					->addFilter('StripTags')
					->addFilter('StringTrim')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")					
					->removeDecorator('HtmlTag');
					
		$MandatoryCreditHrs  = new Zend_Form_Element_Radio('MandatoryCreditHrs');
		$MandatoryCreditHrs->setAttrib('dojoType',"dijit.form.RadioButton");
        $MandatoryCreditHrs->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        			
        $ExamTimeTable  = new Zend_Form_Element_Radio('ExamTimeTable');
		$ExamTimeTable->setAttrib('dojoType',"dijit.form.RadioButton");
        $ExamTimeTable->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        			
        $Practical  = new Zend_Form_Element_Radio('Practical ');
		$Practical->setAttrib('dojoType',"dijit.form.RadioButton");
        $Practical->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        			
        $MandatoryAmount  = new Zend_Form_Element_Radio('MandatoryAmount');
		$MandatoryAmount->setAttrib('dojoType',"dijit.form.RadioButton");
        $MandatoryAmount->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');	
        			
      	
        $CourseExam  = new Zend_Form_Element_Radio('CourseExam');
		$CourseExam->setAttrib('dojoType',"dijit.form.RadioButton");
        $CourseExam->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');	
        
        $PassFailGrade  = new Zend_Form_Element_Checkbox('PassFailGrade');
        $PassFailGrade->setAttrib('dojoType',"dijit.form.CheckBox");
        $PassFailGrade->setvalue('1');
        $PassFailGrade->removeDecorator("DtDdWrapper");
        $PassFailGrade->removeDecorator("Label");
        $PassFailGrade->removeDecorator('HtmlTag');			
        			
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
        		
        $Audit  = new Zend_Form_Element_Radio('Audit');
		$Audit->setAttrib('dojoType',"dijit.form.RadioButton");
        $Audit->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');	
        			
        $Project  = new Zend_Form_Element_Radio('Project');
		$Project->setAttrib('dojoType',"dijit.form.RadioButton");
        $Project->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        			
        $classtimetable  = new Zend_Form_Element_Radio('ClassTimetable');
		$classtimetable->setAttrib('dojoType',"dijit.form.RadioButton");
        $classtimetable->addMultiOptions(array('0' => 'No','1' => 'Yes'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
         		
        $this->addElements(
						array(
							$IdCourseType,
							$UpdDate,
							$UpdUser,
							$ExamTimeTable,
							$Practical,
							$PassFailGrade,
							$CourseType,
							$MandatoryCreditHrs,
							$MandatoryAmount,
							$Save,
							$Active,
							$Bahasaindonesia,
							$CourseExam,$Audit,$Project,$classtimetable
						)
			);
    	}
	}
?>