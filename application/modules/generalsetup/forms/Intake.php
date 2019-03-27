<?php
class GeneralSetup_Form_Intake extends Zend_Dojo_Form {
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		$lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$lobjsemestermaster = new GeneralSetup_Model_DbTable_Semestermaster();
		$lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster();
		$lobjintake = new GeneralSetup_Model_DbTable_Intake();
		$lobjprogram = new GeneralSetup_Model_DbTable_Program();

		$IdIntake = new Zend_Form_Element_Hidden('IdIntake');
		$IdIntake->removeDecorator("DtDdWrapper");
		$IdIntake->removeDecorator("Label");
		$IdIntake->removeDecorator('HtmlTag');

		//Intake Code
		$IntakeCode = new Zend_Form_Element_Text('IntakeId');
		$IntakeCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$IntakeCode->setAttrib('required',"true")
		->setAttrib('maxlength','50')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		//Intake Description
		$IntakeDescription = new Zend_Form_Element_Text('IntakeDesc');
		$IntakeDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$IntakeDescription->setAttrib('required',"true")
		->setAttrib('maxlength','150')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		//Intake Translation
		$IntakeTranslation = new Zend_Form_Element_Text('IntakeDefaultLanguage');
		$IntakeTranslation->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$IntakeTranslation->setAttrib('required',"false")
		->setAttrib('maxlength','150')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		//Intake Scheme
		$ApplicationStartDate  = new Zend_Form_Element_Text('ApplicationStartDate');
		$ApplicationStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $ApplicationStartDate->setAttrib('onChange',"dijit.byId('ApplicationEndDate').constraints.min = arguments[0];") ;
    $ApplicationStartDate->setAttrib('required',"true");
    $ApplicationStartDate->removeDecorator("DtDdWrapper");
    $ApplicationStartDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $ApplicationStartDate->removeDecorator("Label");
    $ApplicationStartDate->removeDecorator('HtmlTag');

	//Intake PSSB First 3 digits code
		$IntakePSSBcode = new Zend_Form_Element_Text('IntakePSSBcode');
		$IntakePSSBcode->setAttrib('required',"false")
		->setAttrib('maxlength','5')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$ApplicationEndDate  = new Zend_Form_Element_Text('ApplicationEndDate');
		$ApplicationEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $ApplicationEndDate->setAttrib('onChange',"dijit.byId('ApplicationStartDate').constraints.max = arguments[0];") ;
    $ApplicationEndDate->setAttrib('required',"true");
    $ApplicationEndDate->removeDecorator("DtDdWrapper");
    $ApplicationEndDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $ApplicationEndDate->removeDecorator("Label");
    $ApplicationEndDate->removeDecorator('HtmlTag');


		$Faculty  = new Zend_Dojo_Form_Element_FilteringSelect('Faculty');
		$Faculty->removeDecorator("DtDdWrapper");
		$Faculty->setAttrib('required',"false") ;
		$Faculty->removeDecorator("Label");
		$Faculty->removeDecorator('HtmlTag');
		$Faculty->setRegisterInArrayValidator(false);
		$Faculty->setAttrib('OnChange', 'fnloadPrograms');
		$Faculty->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$larrcollege = $lobjcollegemaster->fnGetListofCollege();
		$Faculty->addMultiOptions($larrcollege);

		$Program  = new Zend_Dojo_Form_Element_FilteringSelect('Program');
		$Program->removeDecorator("DtDdWrapper");
		$Program->setAttrib('required',"false") ;
		$Program->removeDecorator("Label");
		$Program->removeDecorator('HtmlTag');
		$Program->setRegisterInArrayValidator(false);
		//$Program->setAttrib('OnChange', 'fnloadPrograms');
		$Program->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$programlists=$lobjprogram->fnGetProgramList();
		$Program->addMultiOptions( $programlists);

		$Branch  = new Zend_Dojo_Form_Element_FilteringSelect('Branch');
		$Branch->removeDecorator("DtDdWrapper");
		$Branch->setAttrib('required',"false") ;
		$Branch->removeDecorator("Label");
		$Branch->removeDecorator('HtmlTag');
		$Branch->setRegisterInArrayValidator(false);
		$Branch->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$larrbranchlist = $lobjintake->fngetBranchList();
		if(count($larrbranchlist) > 0) {
			$larrbranchlist[] = array("key" => "all","value" => "All");
		}
		$Branch->addMultiOptions($larrbranchlist);




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

		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addentry()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearpageAdd()');
		$clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');



		//Form Elements for Intake Copy
		//Semester Main Code
		$CopyIntakeId = new Zend_Form_Element_Text('CopyIntakeId');
		$CopyIntakeId->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CopyIntakeId->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$CopyIntakeDescription = new Zend_Form_Element_Text('CopyIntakeDescription');
		$CopyIntakeDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CopyIntakeDescription->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$CopyApplicationStartDate  = new Zend_Form_Element_Text('CopyApplicationStartDate');
		$CopyApplicationStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $CopyApplicationStartDate->setAttrib('onChange',"dijit.byId('CopyApplicationEndDate').constraints.min = arguments[0];") ;
    $CopyApplicationStartDate->setAttrib('required',"true");
    $CopyApplicationStartDate->removeDecorator("DtDdWrapper");
    $CopyApplicationStartDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $CopyApplicationStartDate->removeDecorator("Label");
    $CopyApplicationStartDate->removeDecorator('HtmlTag');



		$CopyApplicationEndDate  = new Zend_Form_Element_Text('CopyApplicationEndDate');
		$CopyApplicationEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
    $CopyApplicationEndDate->setAttrib('onChange',"dijit.byId('CopyApplicationStartDate').constraints.max = arguments[0];") ;
    $CopyApplicationEndDate->setAttrib('required',"true");
    $CopyApplicationEndDate->removeDecorator("DtDdWrapper");
    $CopyApplicationEndDate->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
    $CopyApplicationEndDate->removeDecorator("Label");
    $CopyApplicationEndDate->removeDecorator('HtmlTag');




		$FromIntake = new Zend_Dojo_Form_Element_FilteringSelect('FromIntake');
		$FromIntake->removeDecorator("DtDdWrapper");
		$FromIntake->setAttrib('required',"true");
		$FromIntake->removeDecorator("Label");
		$FromIntake->removeDecorator('HtmlTag');
		//$FromIntake->setRegisterInArrayValidator(false);
		$FromIntake->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$larrintakelist = $lobjintake->fngetallIntake();
		$FromIntake->addMultiOptions($larrintakelist);


		//Copy Button
		$Copy = new Zend_Form_Element_Submit('Copy');
		$Copy->label = $gstrtranslate->_("Copy");
		$Copy->dojotype="dijit.form.Button";
		$Copy->removeDecorator("DtDdWrapper");
		$Copy->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$this->addElements(array($IntakeCode,
		$IntakeDescription,
		$IntakeTranslation,
		$ApplicationStartDate,
		$ApplicationEndDate,
		$Faculty,
		$Program,
		$Branch,
		$Add,
		$clear,
		$UpdDate,
		$UpdUser,
		$IdIntake,
		$Save,
		$CopyIntakeId,
		$CopyIntakeDescription,
		$CopyApplicationStartDate,
		$CopyApplicationEndDate,
		$FromIntake,
		$Copy,
		$IntakePSSBcode
		));

	}

}