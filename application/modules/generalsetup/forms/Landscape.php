<?php
class GeneralSetup_Form_Landscape extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
    public function init() {

    	$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$IdLandscape = new Zend_Form_Element_Hidden('IdLandscape');
        $IdLandscape->removeDecorator("DtDdWrapper");
        $IdLandscape->removeDecorator("Label");
        $IdLandscape->removeDecorator('HtmlTag');

        $idlandscapetemp = new Zend_Form_Element_Hidden('idlandscapetemp');
        $idlandscapetemp->removeDecorator("DtDdWrapper");
        $idlandscapetemp->removeDecorator("Label");
        $idlandscapetemp->removeDecorator('HtmlTag');

        $idlandscapeblockid = new Zend_Form_Element_Hidden('idlandscapeblockid');
        $idlandscapeblockid->removeDecorator("DtDdWrapper");
        $idlandscapeblockid->removeDecorator("Label");
        $idlandscapeblockid->removeDecorator('HtmlTag');

        $IdLandscapetempblocksubject = new Zend_Form_Element_Hidden('IdLandscapetempblocksubject');
        $IdLandscapetempblocksubject->removeDecorator("DtDdWrapper");
        $IdLandscapetempblocksubject->removeDecorator("Label");
        $IdLandscapetempblocksubject->removeDecorator('HtmlTag');

        $IdLandscapetempblockyearsemester = new Zend_Form_Element_Hidden('IdLandscapetempblockyearsemester');
        $IdLandscapetempblockyearsemester->removeDecorator("DtDdWrapper");
        $IdLandscapetempblockyearsemester->removeDecorator("Label");
        $IdLandscapetempblockyearsemester->removeDecorator('HtmlTag');

        $session_id = new Zend_Form_Element_Hidden('session_id');
        $session_id->removeDecorator("DtDdWrapper");
        $session_id->removeDecorator("Label");
        $session_id->removeDecorator('HtmlTag');

        $IdProgram = new Zend_Form_Element_Hidden('IdProgram');
        $IdProgram->removeDecorator("DtDdWrapper");
        $IdProgram->setAttrib('required',"true") ;
        $IdProgram->removeDecorator("Label");
        $IdProgram->removeDecorator('HtmlTag');

		$LandscapeType = new Zend_Dojo_Form_Element_FilteringSelect('LandscapeType');
        $LandscapeType->removeDecorator("DtDdWrapper");
        $LandscapeType->setAttrib('required',"true") ;
        $LandscapeType->removeDecorator("Label");
        $LandscapeType->removeDecorator('HtmlTag');
        $LandscapeType->setRegisterInArrayValidator(false);
        $LandscapeType->setAttrib('OnChange', 'fnGetLandscapetype(this)');
		$LandscapeType->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdStartSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdStartSemester');
        $IdStartSemester->removeDecorator("DtDdWrapper");
        $IdStartSemester->setAttrib('required',"false") ;
        $IdStartSemester->removeDecorator("Label");
        $IdStartSemester->removeDecorator('HtmlTag');
        $IdStartSemester->setRegisterInArrayValidator(false);
		$IdStartSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyIdStartSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyIdStartSemester');
        $CopyIdStartSemester->removeDecorator("DtDdWrapper");
        $CopyIdStartSemester->setAttrib('required',"false") ;
        $CopyIdStartSemester->removeDecorator("Label");
        $CopyIdStartSemester->removeDecorator('HtmlTag');
        $CopyIdStartSemester->setRegisterInArrayValidator(false);
		$CopyIdStartSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyFromIdStartSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdStartSemester');
        $CopyFromIdStartSemester->removeDecorator("DtDdWrapper");
        $CopyFromIdStartSemester->setAttrib('required',"false") ;
        $CopyFromIdStartSemester->removeDecorator("Label");
        $CopyFromIdStartSemester->removeDecorator('HtmlTag');
        $CopyFromIdStartSemester->setRegisterInArrayValidator(false);
		$CopyFromIdStartSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");



		$BlockNameList = new Zend_Dojo_Form_Element_FilteringSelect('BlockNameList');
        $BlockNameList->removeDecorator("DtDdWrapper");
        $BlockNameList->setAttrib('required',"false") ;
        $BlockNameList->removeDecorator("Label");
        $BlockNameList->removeDecorator('HtmlTag');
        $BlockNameList->setRegisterInArrayValidator(false);
		$BlockNameList->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$SubjectNameList = new Zend_Dojo_Form_Element_FilteringSelect('SubjectNameList');
		//$SubjectNameList->setAttrib('OnChange', 'fnGetsubjectprereq(this)');
        $SubjectNameList->removeDecorator("DtDdWrapper");
        $SubjectNameList->setAttrib('required',"false") ;
        $SubjectNameList->removeDecorator("Label");
        $SubjectNameList->removeDecorator('HtmlTag');
        $SubjectNameList->setRegisterInArrayValidator(false);
		$SubjectNameList->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SubjectNameList->setAttrib('onchange',"fngetsubjectcredithours(this)");

		$BlockDtlsList = new Zend_Dojo_Form_Element_FilteringSelect('BlockDtlsList');
        $BlockDtlsList->removeDecorator("DtDdWrapper");
        $BlockDtlsList->setAttrib('required',"false") ;
        $BlockDtlsList->removeDecorator("Label");
        $BlockDtlsList->removeDecorator('HtmlTag');
        $BlockDtlsList->setRegisterInArrayValidator(false);
		$BlockDtlsList->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$SemNameList = new Zend_Dojo_Form_Element_FilteringSelect('SemNameList');
        $SemNameList->removeDecorator("DtDdWrapper");
        $SemNameList->setAttrib('required',"false") ;
        $SemNameList->removeDecorator("Label");
        $SemNameList->removeDecorator('HtmlTag');
        $SemNameList->setRegisterInArrayValidator(false);
		$SemNameList->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$SubjectType = new Zend_Dojo_Form_Element_FilteringSelect('SubjectType');
        $SubjectType->removeDecorator("DtDdWrapper");
        $SubjectType->setAttrib('required',"false") ;
        $SubjectType->removeDecorator("Label");
        $SubjectType->removeDecorator('HtmlTag');
        $SubjectType->setRegisterInArrayValidator(false);
		$SubjectType->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$LandscapeSubjectType = new Zend_Dojo_Form_Element_FilteringSelect('LandscapeSubjectType');
        $LandscapeSubjectType->removeDecorator("DtDdWrapper");
        $LandscapeSubjectType->setAttrib('required',"false") ;
        $LandscapeSubjectType->removeDecorator("Label");
        $LandscapeSubjectType->removeDecorator('HtmlTag');
        $LandscapeSubjectType->setRegisterInArrayValidator(false);
		$LandscapeSubjectType->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$LandscapeCreditHours = new Zend_Form_Element_Text('LandscapeCreditHours');
        $LandscapeCreditHours->removeDecorator("DtDdWrapper");
        $LandscapeCreditHours->removeDecorator("Label");
        $LandscapeCreditHours->removeDecorator('HtmlTag');
		$LandscapeCreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox");

		$CreditHours = new Zend_Form_Element_Text('CreditHours');
		$CreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $TotalCreditHours = new Zend_Form_Element_Text('TotalCreditHours');
		$TotalCreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $BlockCreditHours = new Zend_Form_Element_Text('BlockCreditHours');
        $BlockCreditHours->removeDecorator("DtDdWrapper");
        $BlockCreditHours->removeDecorator("Label");
        $BlockCreditHours->removeDecorator('HtmlTag');
		$BlockCreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox");

		$SemsterCount = new Zend_Form_Element_Text('SemsterCount');
		$SemsterCount->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        		->setvalue('8')
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $YearCount = new Zend_Form_Element_Text('YearCount');
		$YearCount->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        		->setvalue('1')
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $blockname = new Zend_Form_Element_Text('blockname');
		$blockname->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $Blockcount = new Zend_Form_Element_Text('Blockcount');
		$Blockcount->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->removeDecorator("DtDdWrapper")
        		->setvalue('4')
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->setAttrib('required',"false") ;
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
        $IdSubject->setRegisterInArrayValidator(false);
		$IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$IdSubject->setAttrib('onchange',"fngetsubjectcredithours(this)");

		$Semester = new Zend_Form_Element_MultiCheckbox('Semester');
        $Semester->setAttrib('required',"true");
        $Semester->removeDecorator("DtDdWrapper");
        $Semester->removeDecorator("Label");
        $Semester->removeDecorator('HtmlTag');
		$Semester->setAttrib('dojoType',"dijit.form.CheckBox");
		$Semester->class = "LearningMode";

		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
        $IdSemester->removeDecorator("DtDdWrapper");
        $IdSemester->setAttrib('required',"false") ;
        $IdSemester->removeDecorator("Label");
        $IdSemester->removeDecorator('HtmlTag');
        $IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$YearSemester = new Zend_Dojo_Form_Element_FilteringSelect('YearSemester');
        $YearSemester->removeDecorator("DtDdWrapper");
        $YearSemester->setAttrib('required',"false") ;
        $YearSemester->removeDecorator("Label");
        $YearSemester->removeDecorator('HtmlTag');
        $YearSemester->setRegisterInArrayValidator(false);
		$YearSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$block = new Zend_Dojo_Form_Element_FilteringSelect('block');
        $block->removeDecorator("DtDdWrapper");
        $block->setAttrib('required',"false") ;
        $block->removeDecorator("Label");
        $block->removeDecorator('HtmlTag');
        $block->setRegisterInArrayValidator(false);
        $block->setAttrib('onchange',"fngetblockname()");
		$block->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$blocksub = new Zend_Dojo_Form_Element_FilteringSelect('blocksub');
        $blocksub->removeDecorator("DtDdWrapper");
        $blocksub->setAttrib('required',"false") ;
        $blocksub->removeDecorator("Label");
        $blocksub->removeDecorator('HtmlTag');
        $blocksub->setRegisterInArrayValidator(false);
		$blocksub->setAttrib('dojoType',"dijit.form.FilteringSelect");		
		
		$subjectblock = new Zend_Dojo_Form_Element_FilteringSelect('subjectblock');
        $subjectblock->removeDecorator("DtDdWrapper");
        $subjectblock->setAttrib('required',"false") ;
        $subjectblock->removeDecorator("Label");
        $subjectblock->removeDecorator('HtmlTag');
        $subjectblock->setRegisterInArrayValidator(false);
		$subjectblock->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$subjectblock->setAttrib('onchange',"fngetcoursetype()");
		
		
		

		$semesterid = new Zend_Dojo_Form_Element_FilteringSelect('semesterid');
        $semesterid->removeDecorator("DtDdWrapper");
        $semesterid->setAttrib('required',"false") ;
        $semesterid->removeDecorator("Label");
        $semesterid->removeDecorator('HtmlTag');
        $semesterid->setRegisterInArrayValidator(false);
		$semesterid->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Year = new Zend_Dojo_Form_Element_FilteringSelect('Year');
        $Year->removeDecorator("DtDdWrapper");
        $Year->setAttrib('required',"false") ;
        $Year->removeDecorator("Label");
        $Year->removeDecorator('HtmlTag');
        $Year->setRegisterInArrayValidator(false);
		$Year->setAttrib('dojoType',"dijit.form.FilteringSelect");


      	$Active  = new Zend_Dojo_Form_Element_FilteringSelect('Active');
        $Active->setRegisterInArrayValidator(false);
		$Active->setAttrib('dojoType',"dijit.form.FilteringSelect");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');

        $CopyActive  = new Zend_Dojo_Form_Element_FilteringSelect('CopyActive');
        $CopyActive->setRegisterInArrayValidator(false);
		$CopyActive->setAttrib('dojoType',"dijit.form.FilteringSelect");
        $CopyActive->setvalue('1');
        $CopyActive->removeDecorator("DtDdWrapper");
        $CopyActive->removeDecorator("Label");
        $CopyActive->removeDecorator('HtmlTag');


        $Compulsory  = new Zend_Form_Element_Checkbox('Compulsory');
        $Compulsory->setAttrib('dojoType',"dijit.form.CheckBox");
        $Compulsory->removeDecorator("DtDdWrapper");
        $Compulsory->removeDecorator("Label");
        $Compulsory->removeDecorator('HtmlTag');

        $Scheme = new Zend_Dojo_Form_Element_FilteringSelect('Scheme');
        $Scheme->removeDecorator("DtDdWrapper");
        $Scheme->setAttrib('required',"false") ;
        $Scheme->removeDecorator("Label");
        $Scheme->removeDecorator('HtmlTag');
        $Scheme->setRegisterInArrayValidator(false);
        $Scheme->setAttrib('onchange',"fngetblockname()");
		$Scheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyScheme');
        $CopyScheme->removeDecorator("DtDdWrapper");
        $CopyScheme->setAttrib('required',"false") ;
        $CopyScheme->removeDecorator("Label");
        $CopyScheme->removeDecorator('HtmlTag');
        $CopyScheme->setRegisterInArrayValidator(false);
        $CopyScheme->setAttrib('onchange',"fngetblockname()");
		$CopyScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyFromScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromScheme');
        $CopyFromScheme->removeDecorator("DtDdWrapper");
        $CopyFromScheme->setAttrib('required',"false") ;
        $CopyFromScheme->removeDecorator("Label");
        $CopyFromScheme->removeDecorator('HtmlTag');
        $CopyFromScheme->setRegisterInArrayValidator(false);
        $CopyFromScheme->setAttrib('onchange',"fngetblockname()");
		$CopyFromScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");



		$IdProgramMajoring = new Zend_Dojo_Form_Element_FilteringSelect('IdProgramMajoring');
        $IdProgramMajoring->removeDecorator("DtDdWrapper");
        $IdProgramMajoring->setAttrib('required',"false") ;
        $IdProgramMajoring->removeDecorator("Label");
        $IdProgramMajoring->removeDecorator('HtmlTag');
        $IdProgramMajoring->setRegisterInArrayValidator(false);
		$IdProgramMajoring->setAttrib('dojoType',"dijit.form.FilteringSelect");

        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');

        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');

        $Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', '');
		$Add->label = $gstrtranslate->_("Add Program Requirement");
        $Add->dojotype="dijit.form.Button";
		$Add->setAttrib('OnClick', 'addLandscapeDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add1 = new Zend_Form_Element_Button('Add1');
		$Add1->setAttrib('class', '');	
		$Add1->label = $gstrtranslate->_("Add Course");
        $Add1->dojotype="dijit.form.Button";
		$Add1->setAttrib('OnClick', 'addLandscapeprogDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add2 = new Zend_Form_Element_Button('Add2');
		$Add2->setAttrib('class', 'NormalBtn');
		$Add2->label = $gstrtranslate->_("Add");
        $Add2->dojotype="dijit.form.Button";
		$Add2->setAttrib('OnClick', 'addBlockDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add3 = new Zend_Form_Element_Button('Add3');
		$Add3->setAttrib('class', 'NormalBtn');		
		$Add3->label = $gstrtranslate->_("Add");
        $Add3->dojotype="dijit.form.Button";
		$Add3->setAttrib('OnClick', 'addBlockDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add4 = new Zend_Form_Element_Button('Add4');
		$Add4->setAttrib('class', 'NormalBtn');
		$Add4->label = $gstrtranslate->_("Add");
        $Add4->dojotype="dijit.form.Button";
		$Add4->setAttrib('OnClick', 'addBlockSubjectDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add5 = new Zend_Form_Element_Button('Add5');
		$Add5->setAttrib('class', 'NormalBtn');
		$Add5->label = $gstrtranslate->_("Add");
        $Add5->dojotype="dijit.form.Button";
		$Add5->setAttrib('OnClick', 'addBlockSemDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add6 = new Zend_Form_Element_Button('Add6');
		$Add6->setAttrib('class', 'NormalBtn');
		$Add6->label = $gstrtranslate->_("Add");
        $Add6->dojotype="dijit.form.Button";
		$Add6->setAttrib('OnClick', 'showtable()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add7 = new Zend_Form_Element_Button('Add7');
		$Add7->setAttrib('class', 'NormalBtn');
		$Add7->label = $gstrtranslate->_("Save");
        $Add7->dojotype="dijit.form.Button";
		$Add7->setAttrib('OnClick', 'showtablesub()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add8 = new Zend_Form_Element_Button('Add8');
		$Add8->setAttrib('class', 'NormalBtn');
		$Add8->label = $gstrtranslate->_("Save");
        $Add8->dojotype="dijit.form.Button";
		$Add8->setAttrib('OnClick', 'showblocksub()')
		//$Add8->setAttrib('OnClick', 'showtablesmester()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add9 = new Zend_Form_Element_Button('Add9');
		$Add9->setAttrib('class', 'NormalBtn');
		$Add9->label = $gstrtranslate->_("Add");
        $Add9->dojotype="dijit.form.Button";
		$Add9->setAttrib('OnClick', 'showprogreq()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add10 = new Zend_Form_Element_Button('Add10');
		$Add10->setAttrib('class', 'NormalBtn');
		$Add10->label = $gstrtranslate->_("Add");
        $Add10->dojotype="dijit.form.Button";
		$Add10->setAttrib('OnClick', 'addBlockSubDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add11 = new Zend_Form_Element_Button('Add11');
		$Add11->setAttrib('class', 'NormalBtn');
		$Add11->label = $gstrtranslate->_("Save");
        $Add11->dojotype="dijit.form.Button";
		$Add11->setAttrib('OnClick', 'showtablesmester()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add12= new Zend_Form_Element_Button('Add12');
		$Add12->setAttrib('class', 'NormalBtn');
		$Add12->label = $gstrtranslate->_("Add");
        $Add12->dojotype="dijit.form.Button";
		$Add12->setAttrib('OnClick', 'addBlockyearDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$Add13 = new Zend_Form_Element_Button('Add13');
		$Add13->setAttrib('class', 'NormalBtn');
		$Add13->label = $gstrtranslate->_("Add");
        $Add13->dojotype="dijit.form.Button";
		$Add13->setAttrib('OnClick', 'showtableyear()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');


        $Balance = new Zend_Form_Element_Text('Balance');
		$Balance->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        		->setAttrib('maxlength','100')
        		->setAttrib ('readonly','true')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $Save = new Zend_Form_Element_Submit('Save');
        $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";



        $Copy = new Zend_Form_Element_Submit('Copy');
        $Copy->label = $gstrtranslate->_("Copy");
        $Copy->setAttrib('OnClick', 'copyfirstlandscape()');
        $Copy->dojotype="dijit.form.Button";
        $Copy->removeDecorator("DtDdWrapper");
        $Copy->removeDecorator('HtmlTag')->class = "NormalBtn";

        $CourseType = new Zend_Dojo_Form_Element_FilteringSelect('CourseType');
        $CourseType->removeDecorator("DtDdWrapper");
        $CourseType->setAttrib('required',"false") ;
        $CourseType->removeDecorator("Label");
        $CourseType->removeDecorator('HtmlTag');
        $CourseType->setRegisterInArrayValidator(false);
		$CourseType->setAttrib('dojoType',"dijit.form.FilteringSelect");

        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$ProgramDescription = new Zend_Form_Element_Textarea('ProgramDescription');
		$ProgramDescription->setAttrib('required',"true");
		$ProgramDescription->setAttrib('size', '20');
		$ProgramDescription->setAttrib('cols', '40');
        $ProgramDescription->setAttrib('rows', '4');
        $ProgramDescription->setAttrib('maxlength','20');
		$ProgramDescription->removeDecorator("DtDdWrapper");
		$ProgramDescription->removeDecorator("Label");
		$ProgramDescription->removeDecorator('HtmlTag');
		$ProgramDescription->setAttrib('dojoType',"dijit.form.Textarea");
		
		$landscapeDefaultLanguage = new Zend_Form_Element_Textarea('landscapeDefaultLanguage');
		$landscapeDefaultLanguage->setAttrib('required',"false");
		$landscapeDefaultLanguage->setAttrib('size', '20');
		$landscapeDefaultLanguage->setAttrib('cols', '40');
                $landscapeDefaultLanguage->setAttrib('rows', '4');
                $landscapeDefaultLanguage->setAttrib('maxlength','150');
		$landscapeDefaultLanguage->removeDecorator("DtDdWrapper");
		$landscapeDefaultLanguage->removeDecorator("Label");
		$landscapeDefaultLanguage->removeDecorator('HtmlTag');
		$landscapeDefaultLanguage->setAttrib('dojoType',"dijit.form.Textarea");
		
		
		$AddDrop  = new Zend_Form_Element_Checkbox('AddDrop');
        $AddDrop->setAttrib('dojoType',"dijit.form.CheckBox");
        $AddDrop->setvalue('0');
        $AddDrop->removeDecorator("DtDdWrapper");
        $AddDrop->removeDecorator("Label");
        $AddDrop->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($IdLandscape,
        						 $ProgramDescription,
        						 $landscapeDefaultLanguage,
        						 $IdProgram,
        						 $CourseType,
        						 $IdLandscapetempblocksubject,
        						 $IdLandscapetempblockyearsemester,
        						 $session_id,
        						 $LandscapeType,
        						 $IdStartSemester,
        						 $CopyIdStartSemester,
        						 $CopyFromIdStartSemester,
        						 $BlockNameList,
        						 $SubjectNameList,
        						 $semesterid,
        						 $YearCount,
        						 $block,
        						 $TotalCreditHours,
        						 $BlockDtlsList,
        						 $SemNameList,
        						 $SubjectType,
        						 $YearSemester,
        						 $Year,
        						 $CreditHours,
        						 $IdSubject,
        						 $IdSemester,
        						 $LandscapeSubjectType,
        						 $LandscapeCreditHours,
        						 $Active,
        						 $CopyActive,
                                 $Add,
                                 $Add1,
                                 $Add2,
                                 $Add3,
                                 $Add4,
                                 $Add5,
                                 $Add6,
                                 $Add7,
                                 $Add8,
                                 $Add9,
                                 $Add10,
                                 $Add11,
                                 $Add12,
                                 $Add13,
                                 $Semester,
                                 $BlockCreditHours,
                                 $idlandscapetemp,
                                 $UpdDate,
                                 $UpdUser,
                                 $Compulsory,
                                 $SemsterCount,
                                 $Blockcount,
                                 $Balance,
                                 $Save,
                                 $Copy,
                                 $blockname,
                                 $idlandscapeblockid,
                                 $Scheme,
                                 $CopyScheme,
                                 $CopyFromScheme,
                                 $IdProgramMajoring,
                                 $blocksub,
                                 $subjectblock,
                                 $Back,$AddDrop));

    }
}
