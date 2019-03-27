<?php
class GeneralSetup_Form_Subjectdetails extends Zend_Dojo_Form {
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		
		$CourseDetailCode = new Zend_Form_Element_Text('CourseDetailCode');
		$CourseDetailCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CourseDetailCode->setAttrib('required',"false")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		$CourseDetailDescription = new Zend_Form_Element_Text('CourseDetailDescription');
		$CourseDetailDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CourseDetailDescription->setAttrib('required',"false")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		
		$Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
		$Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('OnClick', 'addsubjectdetails()');
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

		$IdSubject = new Zend_Form_Element_Hidden('IdSubject');
		$IdSubject->removeDecorator("DtDdWrapper");
		$IdSubject->removeDecorator("Label");
		$IdSubject->removeDecorator('HtmlTag');
		
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');
		$this->addElements(array($CourseDetailCode,$CourseDetailDescription,$Add,$Save,$clear,$IdSubject,$UpdDate,$UpdUser));
	}
}