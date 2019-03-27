<?php
class GeneralSetup_Form_Program extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
		$IdProgram = new Zend_Form_Element_Hidden('IdProgram');
        $IdProgram->removeDecorator("DtDdWrapper");
        $IdProgram->removeDecorator("Label");
        $IdProgram->removeDecorator('HtmlTag');
        
        $IdProgramQuota = new Zend_Form_Element_Text('IdProgramQuota');
		$IdProgramQuota->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $IdProgramQuota->removeDecorator("DtDdWrapper");
        $IdProgramQuota->removeDecorator("Label");
        $IdProgramQuota->removeDecorator('HtmlTag');
        
        $ProgramName = new Zend_Form_Element_Text('ProgramName');	
		$ProgramName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ProgramName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','100')       
        		->setAttrib('propercase','true')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
        		
        $ProgramCode = new Zend_Form_Element_Text('ProgramCode');	
		$ProgramCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ProgramCode->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->setAttrib('propercase','true')
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
        		
       	$ArabicName = new Zend_Form_Element_Text('ArabicName');
		$ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")    			 
        				->setAttrib('maxlength','100')       
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
        $FrontSalutation = new Zend_Dojo_Form_Element_FilteringSelect('programSalutation');	
        $FrontSalutation->setAttrib('required',"false");
        $FrontSalutation->removeDecorator("DtDdWrapper");
        $FrontSalutation->removeDecorator("Label");
        $FrontSalutation->removeDecorator('HtmlTag');
		$FrontSalutation->setAttrib('dojoType',"dijit.form.FilteringSelect");				
        				
        //$Duration = new Zend_Form_Element_Text('Duration',array('regExp'=>'[1-9]+[0-9]*[.]?[0-9]?','invalidMessage'=>"Numbers Only"));
        				
        $Duration = new Zend_Form_Element_Text('Duration',array('regExp'=>'[1-9]+[0-9]*','invalidMessage'=>"Numbers Only"));
		$Duration->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Duration->setAttrib('required',"true")    			 
        				->setAttrib('maxlength','3')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        				
        $OptimalDuration = new Zend_Form_Element_Text('OptimalDuration',array('regExp'=>'[1-9]+[0-9]*','invalidMessage'=>"Numbers Only"));
		$OptimalDuration->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$OptimalDuration->setAttrib('required',"true")    			 
        				->setAttrib('maxlength','3')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        $TotalCreditHours = new Zend_Form_Element_Text('TotalCreditHours',array('regExp'=>'[1-9]+[0-9]*[.]?[0-9]*','invalidMessage'=>"Numbers Only"));
		$TotalCreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$TotalCreditHours->setAttrib('required',"true")    			 
        				->setAttrib('maxlength','6')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');	
        				
/*        $IdCourseMaster = new Zend_Dojo_Form_Element_FilteringSelect('IdCourseMaster');
        $IdCourseMaster->removeDecorator("DtDdWrapper");
        $IdCourseMaster->setAttrib('required',"true") ;
        $IdCourseMaster->removeDecorator("Label");
        $IdCourseMaster->removeDecorator('HtmlTag');
        $IdCourseMaster->setRegisterInArrayValidator(false);
		$IdCourseMaster->setAttrib('dojoType',"dijit.form.FilteringSelect");*/
		
		
        $LearningMode = new Zend_Form_Element_MultiCheckbox('LearningMode');
        $LearningMode->removeDecorator("DtDdWrapper");
        $LearningMode->setAttrib('required',"true") ;
        $LearningMode->removeDecorator("Label");
        $LearningMode->removeDecorator('HtmlTag');
        $LearningMode->setSeparator('<br/>');
        $LearningMode->setRegisterInArrayValidator(false);
		$LearningMode->setAttrib('dojoType',"dijit.form.CheckBox");
		
		$Award = new Zend_Dojo_Form_Element_FilteringSelect('Award');
        $Award->removeDecorator("DtDdWrapper");
        $Award->setAttrib('required',"true") ;
        $Award->removeDecorator("Label");
        $Award->removeDecorator('HtmlTag');
        $Award->setRegisterInArrayValidator(false);
        $Award->setAttrib('onChange','fnGetAwardCode(this.value)');
		$Award->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
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
		$Add->dojotype="dijit.form.Button";
		$Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('OnClick', 'QuotaDetails()')
			->setValue('Add')	
			->removeDecorator("Label")
			->removeDecorator("DtDdWrapper")
			->removeDecorator('HtmlTag');
			
		$Add1 = new Zend_Form_Element_Button('Add1');
		$Add1->dojotype="dijit.form.Button";
		$Add1->label = $gstrtranslate->_("Update");
		$Add1->setAttrib('class', 'NormalBtn');
		$Add1->setAttrib('OnClick', 'QuotaDetails()')
			->setValue('Add1')	
			->removeDecorator("Label")
			->removeDecorator("DtDdWrapper")
			->removeDecorator('HtmlTag');	
    		
         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
				
		$AccreditionType = new Zend_Dojo_Form_Element_FilteringSelect('AccreditionType');
        $AccreditionType->setAttrib('required',"false") ;
        $AccreditionType->removeDecorator("Label");
        $AccreditionType->removeDecorator('HtmlTag');
        $AccreditionType->setRegisterInArrayValidator(false);
		$AccreditionType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
        $MoheDate = new Zend_Dojo_Form_Element_DateTextBox('MoheDate');
        $MoheDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $MoheDate->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
		$MoheDate->setAttrib('required',"false");
        $MoheDate->removeDecorator("DtDdWrapper");
        $MoheDate->setAttrib('title',"dd-mm-yyyy");
        $MoheDate->removeDecorator("Label");
        $MoheDate->removeDecorator('HtmlTag'); 
        
        $MoheReferences = new Zend_Form_Element_Text('MoheReferences');
		$MoheReferences->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$MoheReferences->setAttrib('required',"false")    			 
        				->setAttrib('maxlength','50')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');	
        				
        				
        				
        $AccredictionDate = new Zend_Dojo_Form_Element_DateTextBox('AccredictionDate');
        $AccredictionDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $AccredictionDate->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
		$AccredictionDate->setAttrib('required',"false");
        $AccredictionDate->removeDecorator("DtDdWrapper");
        $AccredictionDate->setAttrib('title',"dd-mm-yyyy");
        $AccredictionDate->removeDecorator("Label");
        $AccredictionDate->removeDecorator('HtmlTag'); 
        
        $AccredictionReferences = new Zend_Form_Element_Text('AccredictionReferences');
		$AccredictionReferences->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccredictionReferences->setAttrib('required',"false")    			 
        				->setAttrib('maxlength','50')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');

        $BahasaDescription = new Zend_Form_Element_Text('BahasaDescription');
		$BahasaDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BahasaDescription->setAttrib('required',"false")    			 
        				->setAttrib('maxlength','50')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');

        $EnglishDescription = new Zend_Form_Element_Text('EnglishDescription');
		$EnglishDescription->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$EnglishDescription->setAttrib('required',"false")    			 
        				->setAttrib('maxlength','50')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');							
						
		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearpageAdd()');
        $clear->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Erase = new Zend_Form_Element_Button('Erase');
		$Erase->setAttrib('class', 'NormalBtn');
		$Erase->setAttrib('dojoType',"dijit.form.Button");
		$Erase->label = $gstrtranslate->_("Clear");
		$Erase->setAttrib('OnClick', 'clearpagemajoring()');
        $Erase->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'accredictionInsert()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$Insert= new Zend_Form_Element_Button('Insert');
		$Insert->setAttrib('class', 'NormalBtn');
		$Insert->setAttrib('dojoType',"dijit.form.Button");
		$Insert->label = $gstrtranslate->_("Add");
		$Insert->setAttrib('OnClick', 'majoringAdd()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$PlacementTestType = new Zend_Dojo_Form_Element_FilteringSelect('PlacementTestType');
        $PlacementTestType->setAttrib('required',"true") ;
        $PlacementTestType->removeDecorator("Label");
        $PlacementTestType->removeDecorator('HtmlTag');
        $PlacementTestType->setRegisterInArrayValidator(false);
		$PlacementTestType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$MinimumAge = new Zend_Form_Element_Text('MinimumAge',array('regExp'=>'[1-9]+[0-9]*','invalidMessage'=>"Numbers Only"));
		$MinimumAge->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$MinimumAge->setAttrib('required',"true")    			 
        				->setAttrib('maxlength','2')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				      				
        $AgentVerification  = new Zend_Form_Element_Checkbox('AgentVerification');
        $AgentVerification->setAttrib('dojoType',"dijit.form.CheckBox");
        $AgentVerification->setvalue('1');
        $AgentVerification->removeDecorator("DtDdWrapper");
        $AgentVerification->removeDecorator("Label");
        $AgentVerification->removeDecorator('HtmlTag');
        
        $SelectColgDept = new Zend_Dojo_Form_Element_FilteringSelect('SelectColgDept');
        $SelectColgDept->addMultiOptions(array('0' => 'College',
									   '1' => 'Department'));
        $SelectColgDept->removeDecorator("DtDdWrapper");
        $SelectColgDept->setAttrib('required',"true") ;
        $SelectColgDept->removeDecorator("Label");
        $SelectColgDept->removeDecorator('HtmlTag');
        $SelectColgDept->setRegisterInArrayValidator(false);
       	$SelectColgDept->setAttrib('onChange','fnGetColgDeptid(this.value)');
		$SelectColgDept->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdCollege = new Zend_Dojo_Form_Element_FilteringSelect('IdCollege');
        $IdCollege->removeDecorator("DtDdWrapper");
        $IdCollege->setAttrib('required',"true") ;
        $IdCollege->removeDecorator("Label");
        $IdCollege->removeDecorator('HtmlTag');
        $IdCollege->setRegisterInArrayValidator(false);
       	$IdCollege->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
       	
        //form elements
        $this->addElements(array($IdProgram,$AccredictionDate,$AccredictionReferences,
        						 $IdProgramQuota,$MinimumAge,
        						 $ProgramName,$AgentVerification,$SelectColgDept,$IdCollege,
        						 $ArabicName,
        						 $ShortName,
        						 $ProgramCode,
        						 $LearningMode,
        						 $Duration,
        						 $Award,				 
                                 $Active,
                                 $UpdDate,
                                 $UpdUser,
								 $FrontSalutation,
                                 $Save,
                                 $Back,
                                 $Add,
                                 $Add1,$AccreditionType,$clear,$Add,$TotalCreditHours,$MoheReferences,$MoheDate,$OptimalDuration,$PlacementTestType,$EnglishDescription,$BahasaDescription,$Insert,$Erase));

    }
}