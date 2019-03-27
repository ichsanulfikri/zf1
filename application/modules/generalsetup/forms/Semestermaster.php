<?php
class GeneralSetup_Form_Semestermaster extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		 
		$IdSemesterMaster = new Zend_Form_Element_Hidden('IdSemesterMaster');
		$IdSemesterMaster->removeDecorator("DtDdWrapper");
		$IdSemesterMaster->removeDecorator("Label");
		$IdSemesterMaster->removeDecorator('HtmlTag');

		$SemesterMainName = new Zend_Form_Element_Text('SemesterMainName');
		$SemesterMainName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterMainName->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$SemesterMainCode = new Zend_Form_Element_Text('SemesterMainCode');
		$SemesterMainCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterMainCode->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$IsCountable = new Zend_Form_Element_Text('IsCountable');
		$IsCountable->setAttrib('dojoType',"dijit.form.CheckBox")
		->setvalue('1')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$SemesterMainStatus = new Zend_Dojo_Form_Element_FilteringSelect('SemesterMainStatus');
		$SemesterMainStatus->removeDecorator("DtDdWrapper");
		$SemesterMainStatus->setAttrib('required',"true") ;
		$SemesterMainStatus->removeDecorator("Label");
		$SemesterMainStatus->removeDecorator('HtmlTag');
		$SemesterMainStatus->setRegisterInArrayValidator(false);
		$SemesterMainStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$dateformat = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";

		$SemesterMainStartDate = new Zend_Form_Element_Text('SemesterMainStartDate');
		$SemesterMainStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterMainStartDate->setAttrib('required',"true")
		->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		 
		$SemesterMainEndDate = new Zend_Form_Element_Text('SemesterMainEndDate');
		$SemesterMainEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterMainEndDate->setAttrib('required',"true")
		->setAttrib('constraints', "$dateformat")
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
		$this->addElements(array($IdSemesterMaster,
		$SemesterMainName,
		//$ArabicName,
		//$SemCode,
		//$Active,
		$UpdDate,
		$UpdUser,
		$Save,
		$Back
		));

	}
}