<?php
class GeneralSetup_Form_Subjectequivalent extends Zend_Dojo_Form { //Form class for the Subjectequivalent module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
		$EquivalentSubject = new Zend_Dojo_Form_Element_FilteringSelect('EquivalentSubject');
        $EquivalentSubject->removeDecorator("DtDdWrapper");
        $EquivalentSubject->setAttrib('required',"false") ;
        $EquivalentSubject->removeDecorator("Label");
        $EquivalentSubject->removeDecorator('HtmlTag');
        $EquivalentSubject->setRegisterInArrayValidator(false);
        $EquivalentSubject->setAttrib('OnChange', 'fnGetSubjectEquivalentCode(this,"sub")');
		$EquivalentSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$EquivalentSubjectCode = new Zend_Dojo_Form_Element_FilteringSelect('EquivalentSubjectCode');
		$EquivalentSubjectCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$EquivalentSubjectCode->removeDecorator("DtDdWrapper");
        $EquivalentSubjectCode->setAttrib('required',"false");
        $EquivalentSubjectCode->removeDecorator("Label"); 
        $EquivalentSubjectCode->removeDecorator('HtmlTag'); 
        $EquivalentSubjectCode->setRegisterInArrayValidator(false);
        $EquivalentSubjectCode->setAttrib('OnChange', 'fnGetSubjectEquivalentCode(this,"code")');
        $EquivalentSubjectCode->setAttrib('dojoType',"dijit.form.FilteringSelect");
 
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
        $Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');

        $Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
        $Add->setAttrib('OnClick', 'addsubjectcreditHours()');
		$Add->setAttrib('class', 'NormalBtn')
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
	
       	$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'ClearDetails()');
        $clear->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');			

		$IdOriginalSubject = new Zend_Form_Element_Hidden('IdOriginalSubject');
        $IdOriginalSubject->removeDecorator("DtDdWrapper");
        $IdOriginalSubject->removeDecorator("Label");
        $IdOriginalSubject->removeDecorator('HtmlTag');
        
        $Iditem = new Zend_Form_Element_Hidden('Iditem');
        $Iditem->removeDecorator("DtDdWrapper");
        $Iditem->removeDecorator("Label");
        $Iditem->removeDecorator('HtmlTag');
				

        //form elements
        $this->addElements(array($Iditem,$EquivalentSubject,$Save,$EquivalentSubjectCode,$Add,$clear,$Active,$IdOriginalSubject));

    }
}