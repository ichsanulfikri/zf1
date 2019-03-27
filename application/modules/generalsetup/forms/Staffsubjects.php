<?php
class GeneralSetup_Form_Staffsubjects extends Zend_Dojo_Form {
	
public function init(){			
	
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
		
		
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
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
        		
      	$IdStaff = new Zend_Form_Element_Hidden('IdStaff');
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        		
        $IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');	
        $IdSemester->setAttrib('required',"true");
        $IdSemester->removeDecorator("DtDdWrapper");
        $IdSemester->removeDecorator("Label");
        $IdSemester->removeDecorator('HtmlTag');
	    $IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");
	    
	    $IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');	
        $IdSubject->setAttrib('required',"true");
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
	    $IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
	    
	    $EffectiveDate = new Zend_Dojo_Form_Element_DateTextBox('EffectiveDate');
       	$EffectiveDate ->setAttrib('dojoType',"dijit.form.DateTextBox")
						->setAttrib('title',"dd-mm-yyyy")
						->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}")
						->setAttrib('required',"true")		
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
        		
	    $Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addStaffsubjectDetails()')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add");
	    
	    //required
     
         		
        $this->addElements(
						array(
							$IdSubject,$IdSemester,
							$UpdDate,$UpdUser,$Save,$Active,$Add,
							$IdStaff,$EffectiveDate
						)
			);
    	}
}