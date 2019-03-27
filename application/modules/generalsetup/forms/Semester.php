<?php
class GeneralSetup_Form_Semester extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		$lobjdeftype = new App_Model_Definitiontype();
		$lobjsemester = new GeneralSetup_Model_DbTable_Semester();
		$lobjsemestermaster = new GeneralSetup_Model_DbTable_Semestermaster();
		$lobjprogram = new GeneralSetup_Model_DbTable_Program();
		$lobjintake = new GeneralSetup_Model_DbTable_Intake();

		//Get the semester status from maintenance setup
		$semesterstatuslist = $lobjdeftype->fnGetDefinationMs('Semester Status');


		//Fields for Semester Main

		$IdSemesterMaster = new Zend_Form_Element_Hidden('IdSemesterMaster');
		$IdSemesterMaster->removeDecorator("DtDdWrapper");
		$IdSemesterMaster->removeDecorator("Label");
		$IdSemesterMaster->removeDecorator('HtmlTag');

		$SemesterMainName = new Zend_Form_Element_Text('SemesterMainName');
		$SemesterMainName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterMainName->setAttrib('required',"true")
		->setAttrib('maxlength','100')
		->setAttrib('readOnly','true')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$SemesterMainDefaultLanguage = new Zend_Form_Element_Text('SemesterMainDefaultLanguage');
		$SemesterMainDefaultLanguage->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterMainDefaultLanguage->setAttrib('required',"true")
		->setAttrib('maxlength','100')
		->setAttrib('readOnly','true')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		//$checkSemesterCode = new Cms_SemesterCodeCheck();
		$SemesterMainCode = new Zend_Form_Element_Text('SemesterMainCode');
		//$SemesterMainCode->addValidator($checkSemesterCode);
        //$SemesterMainCode->addValidator(new Zend_Validate_Db_NoRecordExists('tbl_semestermaster', 'SemesterMainCode'));
		$SemesterMainCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        //$SemesterMainCode->getValidator('Db_NoRecordExists')->setMessage("Record already exists");
		$SemesterMainCode->setAttrib('required',"true")
		->setAttrib('maxlength','50')
		->setAttrib('readOnly','true')
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
		$SemesterMainStatus->addMultiOptions($semesterstatuslist);

		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$dateformat = "{datePattern:'dd-MM-yyyy'}";

		$SemesterMainStartDate = new Zend_Form_Element_Text('SemesterMainStartDate');
		$SemesterMainStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterMainStartDate->setAttrib('onChange',"dijit.byId('SemesterMainEndDate').constraints.min = arguments[0];");
		$SemesterMainStartDate->setAttrib('required',"true")
		->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
			
		$SemesterMainEndDate = new Zend_Form_Element_Text('SemesterMainEndDate');
		$SemesterMainEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterMainEndDate->setAttrib('onChange',"dijit.byId('SemesterMainStartDate').constraints.max = arguments[0];") ;
		$SemesterMainEndDate->setAttrib('required',"true")
		->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$IsCountable  = new Zend_Form_Element_Checkbox('IsCountable');
		$IsCountable->setAttrib('dojoType',"dijit.form.CheckBox");
		$IsCountable->setvalue('1');
		$IsCountable->removeDecorator("DtDdWrapper");
		$IsCountable->removeDecorator("Label");
		$IsCountable->removeDecorator('HtmlTag');
			
			
		//Fields for Semester Details
		$IdSemester = new Zend_Form_Element_Hidden('IdSemester');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');

		$SemesterCode = new Zend_Form_Element_Text('SemesterCode');
		$SemesterCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		//$SemesterCode->setAttrib('required',"true")
		$SemesterCode->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$StudentIntake = new Zend_Dojo_Form_Element_FilteringSelect('StudentIntake');
		$StudentIntake->removeDecorator("DtDdWrapper");
		$StudentIntake->setAttrib('required',"false") ;
		/*$StudentIntake->addMultiOptions(array('0' => 'No, There will be no intake for this semester.',
        							          '1' => 'Yes, There will be student intake for this semester.'));*/
		$StudentIntake->removeDecorator("Label");
		$StudentIntake->removeDecorator('HtmlTag');
		$StudentIntake->setRegisterInArrayValidator(false);
		$StudentIntake->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$larrintakelist = $lobjintake->fngetallIntake();
		$StudentIntake->addMultiOptions($larrintakelist);

		$Program = new Zend_Dojo_Form_Element_FilteringSelect('Program');
		$Program->removeDecorator("DtDdWrapper");
		$Program->setAttrib('required',"false") ;
		$Program->removeDecorator("Label");
		$Program->removeDecorator('HtmlTag');
		$Program->setRegisterInArrayValidator(false);
		$Program->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$programlists=$lobjprogram->fnGetProgramList();
		$Program->addMultiOptions( $programlists);

		$SemesterStatus = new Zend_Dojo_Form_Element_FilteringSelect('SemesterStatus');
		$SemesterStatus->removeDecorator("DtDdWrapper");
		$SemesterStatus->setAttrib('required',"false") ;
		$SemesterStatus->removeDecorator("Label");
		$SemesterStatus->removeDecorator('HtmlTag');
		$SemesterStatus->setRegisterInArrayValidator(false);
		$SemesterStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterStatus->addMultiOptions($semesterstatuslist);

		$SemesterStartDate = new Zend_Form_Element_Text('SemesterStartDate');
		$SemesterStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		
		//$SemesterStartDate->setAttrib('required',"true")
		//$SemesterStartDate->setAttrib('onChange',"if(arguments[0] != null){dijit.byId('SemesterEndDate').constraints.min = arguments[0];}else{dijit.byId('SemesterEndDate').constraints.min = new Date();}");
		$SemesterStartDate->setAttrib('onChange',"dijit.byId('SemesterEndDate').constraints.min = arguments[0];");
		$SemesterStartDate->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
			
		$SemesterEndDate = new Zend_Form_Element_Text('SemesterEndDate');
		$SemesterEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		//$SemesterEndDate->setAttrib('onChange',"if(arguments[0] != null) { dijit.byId('SemesterStartDate').constraints.max = arguments[0];}") ;
		$SemesterEndDate->setAttrib('onChange',"dijit.byId('SemesterStartDate').constraints.max = arguments[0];") ;
		//$SemesterEndDate->setAttrib('required',"true")
		$SemesterEndDate->setAttrib('constraints', "$dateformat")
			->removeDecorator("DtDdWrapper")
			->removeDecorator("Label")
			->removeDecorator('HtmlTag');

		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addSemesterentry()')
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
		//Copy Button
		$Copy = new Zend_Form_Element_Submit('Copy');
		$Copy->label = $gstrtranslate->_("Copy");
		$Copy->dojotype="dijit.form.Button";
		$Copy->removeDecorator("DtDdWrapper");
		$Copy->removeDecorator('HtmlTag')
		->class = "NormalBtn";

			
		$Back = new Zend_Form_Element_Button('Back');
		$Back->label = $gstrtranslate->_("Back");
		$Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		//Fields for Semester Details Ends Here

		//Fields for Semester Copy

		$BackCopyTo = new Zend_Form_Element_Button('BackCopyTo');
		$BackCopyTo->label = $gstrtranslate->_("Back");
		$BackCopyTo->dojotype="dijit.form.Button";
		$BackCopyTo->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Scheme  = new Zend_Dojo_Form_Element_FilteringSelect('Scheme');
		$Scheme->removeDecorator("DtDdWrapper");
		$Scheme->setAttrib('required',"true") ;
		$Scheme->removeDecorator("Label");
		$Scheme->removeDecorator('HtmlTag');
		$Scheme->setRegisterInArrayValidator(false);
		$Scheme->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$larrscheme = $lobjsemester->fnGetShcemeList();
		$Scheme->addMultiOptions($larrscheme);


		//Adding for semester status
		$SemesterType = new Zend_Dojo_Form_Element_FilteringSelect('SemesterType');
		$SemesterType->removeDecorator("DtDdWrapper");
		$SemesterType->setAttrib('required',"true") ;
		$SemesterType->removeDecorator("Label");
		$SemesterType->removeDecorator('HtmlTag');
		$SemesterType->setRegisterInArrayValidator(false);
		$SemesterType->setAttrib('dojoType',"dijit.form.FilteringSelect");

		//Copy Semester Fields

		//Semester Main Code
		$CopySemesterMainCode = new Zend_Form_Element_Text('CopySemesterMainCode');
		$CopySemesterMainCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CopySemesterMainCode->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$CopySemesterMainName = new Zend_Form_Element_Text('CopySemesterMainName');
		$CopySemesterMainName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CopySemesterMainName->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$FromSemester = new Zend_Dojo_Form_Element_FilteringSelect('FromSemester');
		$FromSemester->removeDecorator("DtDdWrapper");
		$FromSemester->setAttrib('required',"true");
		$FromSemester->removeDecorator("Label");
		$FromSemester->removeDecorator('HtmlTag');
		$FromSemester->setRegisterInArrayValidator(false);
		$FromSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$lobjfromsemster = $lobjsemestermaster->fnGetSemestermasterList();
		$FromSemester->addMultiOptions($lobjfromsemster);

		//Copy Button
		$Copy = new Zend_Form_Element_Submit('Copy');
		$Copy->label = $gstrtranslate->_("Copy");
		$Copy->dojotype="dijit.form.Button";
		$Copy->removeDecorator("DtDdWrapper");
		$Copy->removeDecorator('HtmlTag')
		->class = "NormalBtn";

			
		$Back = new Zend_Form_Element_Button('Back');
		$Back->label = $gstrtranslate->_("Back");
		$Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		
		
		$SemesterCountType = new Zend_Dojo_Form_Element_FilteringSelect('SemesterCountType');
		$SemesterCountType->removeDecorator("DtDdWrapper");
		$SemesterCountType->setAttrib('required',"false") ;
		$SemesterCountType->setAttrib('onChange',"generateNameCode();") ;
		$SemesterCountType->removeDecorator("Label");
		$SemesterCountType->removeDecorator('HtmlTag');
		$SemesterCountType->setRegisterInArrayValidator(false);
		$SemesterCountType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCountType->addMultiOptions(
		    array(
                '1' => 'Gasal',
                '2' => 'Genap'
		    )
		);
		
		$SemesterFunctionType = new Zend_Dojo_Form_Element_FilteringSelect('SemesterFunctionType');
		$SemesterFunctionType->removeDecorator("DtDdWrapper");
		$SemesterFunctionType->setAttrib('required',"false") ;
		$SemesterFunctionType->setAttrib('onChange',"generateNameCode();") ;
		$SemesterFunctionType->removeDecorator("Label");
		$SemesterFunctionType->removeDecorator('HtmlTag');
		$SemesterFunctionType->setRegisterInArrayValidator(false);
		$SemesterFunctionType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterFunctionType->addMultiOptions(
		    array(
		        '0' => 'Regular',
		        '1' => 'Pembaikan',
		        '2' => 'Konversi',
		        '3' => 'Verifikasi',
		        '4' => 'Validasi',
		    )
		);
		
		$acadYear  = new Zend_Dojo_Form_Element_FilteringSelect('idacadyear');
		$acadYear->removeDecorator("DtDdWrapper");
		$acadYear->setAttrib('required',"true") ;
		$acadYear->setAttrib('onChange',"generateNameCode();") ;
		$acadYear->removeDecorator("Label");
		$acadYear->removeDecorator('HtmlTag');
		$acadYear->setRegisterInArrayValidator(false);
		$acadYear->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$acad_year_list = $lobjsemester->fnGetAcademicYearList();
		$acadYear->addMultiOptions($acad_year_list);
		

		//form elements
		$this->addElements(array($IdSemesterMaster,
		$SemesterMainName,
		$SemesterMainCode,
		$IsCountable,
		$SemesterMainStatus,
		$SemesterMainStartDate,
		$SemesterMainEndDate,
		$Scheme,
		$UpdDate,
		$UpdUser,
		$Add,
		$clear,
		$IdSemester,
		$SemesterCode,
		$StudentIntake,
		$Program,
		$SemesterStatus,
		$SemesterStartDate,
		$SemesterEndDate,
		$CopySemesterMainCode,
		$CopySemesterMainName,
		$FromSemester,
		$SemesterMainDefaultLanguage,
		$Copy,
		$Save,
		$SemesterCountType,
		$SemesterFunctionType,
		$acadYear
		    ));

	}
}