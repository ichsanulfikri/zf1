<?php
class GeneralSetup_Form_Semestercalender extends Zend_Dojo_Form {
  public function init() {
    $gstrtranslate =Zend_Registry::get('Zend_Translate');

    $lobjsemester = new GeneralSetup_Model_DbTable_Semester();

    $id = new Zend_Form_Element_Hidden('id');
    $id->removeDecorator("DtDdWrapper");
    $id->removeDecorator("Label");
    $id->removeDecorator('HtmlTag');

    $semesterList = $lobjsemester->getAllsemesterList();    
// Create the Semester Field
    $IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
    $IdSemester->removeDecorator("DtDdWrapper");
    $IdSemester->setAttrib('required',"true") ;
    $IdSemester->removeDecorator("Label");
    $IdSemester->removeDecorator('HtmlTag');
    $IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");
    $IdSemester->addMultiOptions($semesterList);

    $lobjactivity = new GeneralSetup_Model_DbTable_Activity();
    $activityList = $lobjactivity->fngetActivityList();

    $IdActivity = new Zend_Dojo_Form_Element_FilteringSelect('IdActivity');
    $IdActivity->removeDecorator("DtDdWrapper");
    $IdActivity->setAttrib('required',"true") ;
    $IdActivity->removeDecorator("Label");
    $IdActivity->removeDecorator('HtmlTag');
    $IdActivity->setAttrib('dojoType',"dijit.form.FilteringSelect");
    $IdActivity->addMultiOptions($activityList);


    $SemCalenderStartdate = new Cms_SemCalenderStartdateCheck();
    $StartDate = new Zend_Form_Element_Text('StartDate');
    $StartDate->addValidator($SemCalenderStartdate);
    $StartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $StartDate->setAttrib('required',"true") ;
    $StartDate->setAttrib('onChange',"dijit.byId('EndDate').constraints.min = arguments[0];");
    $StartDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $StartDate->removeDecorator("DtDdWrapper");
    $StartDate->removeDecorator("Label");
    $StartDate->removeDecorator('HtmlTag');


    $SemCalenderEnddate = new Cms_SemCalenderEnddateCheck();
    $EndDate = new Zend_Form_Element_Text('EndDate');
    $EndDate->addValidator($SemCalenderEnddate);
    $EndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $EndDate->setAttrib('onChange',"dijit.byId('StartDate').constraints.max = arguments[0];") ;
    $EndDate->setAttrib('required',"true");
    $EndDate->removeDecorator("DtDdWrapper");
    $EndDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $EndDate->removeDecorator("Label");
    $EndDate->removeDecorator('HtmlTag');

    $Save = new Zend_Form_Element_Submit('Save');
    $Save->label = $gstrtranslate->_("Save");
    $Save->dojotype="dijit.form.Button";
    $Save->removeDecorator("DtDdWrapper");
    $Save->removeDecorator('HtmlTag')->class = "NormalBtn";


    $Back = new Zend_Form_Element_Button('Back');
    $Back->label = $gstrtranslate->_("Back");
    $Back->dojotype="dijit.form.Button";
    $Back->setAttrib('class', 'NormalBtn');
    $Back->removeDecorator("Label");
    $Back->removeDecorator("DtDdWrapper");
    $Back->removeDecorator('HtmlTag');

    $this->addElements(array($id,$IdSemester, $IdActivity, $StartDate, $EndDate, $Save, $Back));

  }

}

?>
