<?php
class GeneralSetup_Form_Subjectmaster extends Zend_Dojo_Form { //Formclass for the user module
	protected $idCollege;
	
	public function setIdCollege($idCollege){
		$this->idCollege = $idCollege;
	}
	
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate');
        $lobjfaculty = new GeneralSetup_Model_DbTable_Collegemaster();
        
      
        if($this->idCollege!=null){
        	$larrfaculty = $lobjfaculty->fnGetCollegeList($this->idCollege);
        }else{
        	$larrfaculty = $lobjfaculty->fnGetCollegeList();
        }

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

   		$IdFaculty = new Zend_Dojo_Form_Element_FilteringSelect('IdFaculty');
        $IdFaculty->removeDecorator("DtDdWrapper");
        $IdFaculty->setAttrib('required',"true") ;
        $IdFaculty->setAttrib('onChange',"loadDepartment()") ;
        $IdFaculty->removeDecorator("Label");
        $IdFaculty->removeDecorator('HtmlTag');
        $IdFaculty->setRegisterInArrayValidator(false);
		$IdFaculty->setAttrib('dojoType',"dijit.form.FilteringSelect");
        $IdFaculty->addMultiOptions($larrfaculty);

        $IdDepartment = new Zend_Dojo_Form_Element_FilteringSelect('IdDepartment');
        $IdDepartment->removeDecorator("DtDdWrapper");
        $IdDepartment->setAttrib('required',"false") ;
        $IdDepartment->removeDecorator("Label");
        $IdDepartment->removeDecorator('HtmlTag');
        $IdDepartment->setRegisterInArrayValidator(false);
		$IdDepartment->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdReligion = new Zend_Dojo_Form_Element_FilteringSelect('IdReligion');
        $IdReligion->removeDecorator("DtDdWrapper");
        $IdReligion->setAttrib('required',"false");
        $IdReligion->setAttrib('readonly',"true") ;
        $IdReligion->removeDecorator("Label");
        $IdReligion->removeDecorator('HtmlTag');
        $IdReligion->setRegisterInArrayValidator(false);
		$IdReligion->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$SubjectName = new Zend_Form_Element_Text('SubjectName');
		$SubjectName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $SubjectName->setAttrib('required',"true")
        		->setAttrib('propercase','true')
        		->setAttrib('maxlength','255')
        		->removeDecorator("DtDdWrapper")
        	   ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $ShortName = new Zend_Form_Element_Text('ShortName');
		$ShortName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ShortName->setAttrib('required',"true")
        		->setAttrib('maxlength','20')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');


        $courseDescription = new Zend_Form_Element_Textarea('courseDescription');
		$courseDescription->setAttrib('dojoType',"dijit.form.SimpleTextarea");
        $courseDescription->setAttrib('required',"false")
        		//->setAttrib('maxlength','255')
                            ->setAttrib('cols', '30')
                            ->setAttrib('rows','3')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');


        $MinCreditHours = new Zend_Form_Element_Text('MinCreditHours');
		$MinCreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $MinCreditHours->setAttrib('required',"true")
        		->setAttrib('maxlength','20')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $BahasaIndonesia = new Zend_Form_Element_Text('BahasaIndonesia');
		$BahasaIndonesia->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        				->setAttrib('maxlength','255')
        				->removeDecorator("DtDdWrapper")
        				//->setAttrib('style','width:200px')
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');

		$ArabicName = new Zend_Form_Element_Text('ArabicName');
		$ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")
        				->setAttrib('maxlength','255')
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');

        $SubCode = new Zend_Form_Element_Text('SubCode');
		$SubCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $SubCode->setAttrib('required',"true")
        		->setAttrib('maxlength','15')
                 ->setAttrib('onblur',"removespace(this.value)") 
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        $CreditHours = new Zend_Form_Element_Text('CreditHours');
		$CreditHours->setAttrib('dojoType',"dijit.form.NumberTextBox");   //NumberTextBox
        $CreditHours->setAttrib('required',"true")
        		->setAttrib('maxlength','20')
                         ->setAttrib('constraints',"{min:0,max:10000,pattern:'#.##'}")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');



		$AmtPerHour = new Zend_Form_Element_Text('AmtPerHour',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$AmtPerHour->setValue('0')
						->setAttrib('dojoType',"dijit.form.ValidationTextBox")
						->removeDecorator("DtDdWrapper")
						->removeDecorator("Label")
						->removeDecorator('HtmlTag');


		/*$CourseType = new Zend_Form_Element_Radio('CourseType');
        $CourseType->addMultiOptions(array('0' => 'Short Course', '1' => 'Academic Course'))
						->setValue('1')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	*/

		$CourseType = new Zend_Dojo_Form_Element_FilteringSelect('CourseType');
        $CourseType->removeDecorator("DtDdWrapper");
        $CourseType->setAttrib('required',"true") ;
        $CourseType->removeDecorator("Label");
        $CourseType->removeDecorator('HtmlTag');
        $CourseType->setRegisterInArrayValidator(false);
        $CourseType->setAttrib('OnChange', 'fnCourseType');
		$CourseType->setAttrib('dojoType',"dijit.form.FilteringSelect");


       	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');

        $ClassTimeTable  = new Zend_Form_Element_Checkbox('ClassTimeTable');
        $ClassTimeTable->setAttrib('dojoType',"dijit.form.CheckBox");
        $ClassTimeTable->removeDecorator("DtDdWrapper");
        $ClassTimeTable->removeDecorator("Label");
        $ClassTimeTable->removeDecorator('HtmlTag');

        $ExamTimeTable  = new Zend_Form_Element_Checkbox('ExamTimeTable');
        $ExamTimeTable->setAttrib('dojoType',"dijit.form.CheckBox");
        $ExamTimeTable->removeDecorator("DtDdWrapper");
        $ExamTimeTable->removeDecorator("Label");
        $ExamTimeTable->removeDecorator('HtmlTag');

        $ReligiousSubject  = new Zend_Form_Element_Checkbox('ReligiousSubject');
        $ReligiousSubject->setAttrib('dojoType',"dijit.form.CheckBox");
        $ReligiousSubject ->setAttrib('onClick','fnShowReligion(this.value)');
       	$ReligiousSubject->removeDecorator("DtDdWrapper");
        $ReligiousSubject->removeDecorator("Label");
        $ReligiousSubject->removeDecorator('HtmlTag');

        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";

     	$Idcomponents = new Zend_Dojo_Form_Element_FilteringSelect('Idcomponents');
        $Idcomponents->removeDecorator("DtDdWrapper");
        $Idcomponents->setAttrib('required',"true") ;
        $Idcomponents->removeDecorator("Label");
        $Idcomponents->removeDecorator('HtmlTag');
        $Idcomponents->setRegisterInArrayValidator(false);
		$Idcomponents->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CreditHour = new Zend_Form_Element_Text('CreditHour',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		//$CreditHour ->setValue('')
			$CreditHour	->setAttrib('dojoType',"dijit.form.ValidationTextBox")
						->removeDecorator("DtDdWrapper")
						->removeDecorator("Label")
						->removeDecorator('HtmlTag');
						
		$subjectMainDefaultLanguage = new Zend_Form_Element_Text('subjectMainDefaultLanguage');
		$subjectMainDefaultLanguage->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$subjectMainDefaultLanguage->setAttrib('required',"false")
                                            ->setAttrib('maxlength','255')
                                            ->removeDecorator("DtDdWrapper")
                                            ->removeDecorator("Label")
                                            ->removeDecorator('HtmlTag');				

        $Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');


		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addsubjectcreditHours()')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add Component");


        //form elements
        $this->addElements(array($IdSubject,$UpdDate,$IdFaculty,$MinCreditHours,$UpdUser,$IdDepartment,$SubjectName,$ShortName,$courseDescription,$ArabicName,$SubCode,$BahasaIndonesia,
        						 $CreditHours,$AmtPerHour,$ClassTimeTable,$ExamTimeTable,$Idcomponents,$CreditHour,
        						 $Active,$Save,$Clear,$Add,$CourseType,$IdReligion,$ReligiousSubject, $subjectMainDefaultLanguage
                                 ));

    }
}