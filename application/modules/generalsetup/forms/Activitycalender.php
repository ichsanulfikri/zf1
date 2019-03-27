<?php
class GeneralSetup_Form_Activitycalender extends Zend_Dojo_Form { 
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		
        
		$lobSemesterCalender = new GeneralSetup_Model_DbTable_Activity();

		
        $month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$dateformat = "{datePattern:'dd-MM-yyyy'}";
        
        $SemesterAddDropStartDate = new Zend_Form_Element_Text('StartDate');
		$SemesterAddDropStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterAddDropStartDate->setAttrib('onChange',"dijit.byId('EndDate').constraints.min = arguments[0];");
		$SemesterAddDropStartDate->setAttrib('required',"false")
		->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
        
		$SemesterAddDropEndDate = new Zend_Form_Element_Text('EndDate');
		$SemesterAddDropEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterAddDropEndDate->setAttrib('onChange',"dijit.byId('StartDate').constraints.max = arguments[0];") ;
		$SemesterAddDropEndDate->setAttrib('required',"false")
		->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

        $id = new Zend_Form_Element_Hidden('id');
		$id->removeDecorator("DtDdWrapper");
		$id->removeDecorator("Label");
		$id->removeDecorator('HtmlTag');
        
		//form elements
		$this->addElements(array(
        $SemesterAddDropStartDate,
        $SemesterAddDropEndDate,
        $id
        ));

	}
}