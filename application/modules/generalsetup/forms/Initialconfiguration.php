<?php
class GeneralSetup_Form_Initialconfiguration extends Zend_Dojo_Form
{
	public function init()
	{
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
	
		$idConfig = new Zend_Form_Element_Hidden('idConfig');
		$idConfig	->setAttrib('id','idConfig')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate ->removeDecorator("DtDdWrapper")
        		 ->removeDecorator("Label")
        		 ->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser ->removeDecorator("DtDdWrapper")
        		 ->removeDecorator("Label")
        		 ->removeDecorator('HtmlTag');
        		 
        $idUniversity  = new Zend_Form_Element_Hidden('idUniversity');
        $idUniversity ->removeDecorator("DtDdWrapper")
        		 ->removeDecorator("Label")
        		 ->removeDecorator('HtmlTag');
					
        $noofrowsingrid = new Zend_Form_Element_Text('noofrowsingrid',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only Digits"));
		$noofrowsingrid->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $noofrowsingrid->setAttrib('maxlength','7');   
        $noofrowsingrid->removeDecorator("DtDdWrapper");
        $noofrowsingrid->removeDecorator("Label");
        $noofrowsingrid->removeDecorator('HtmlTag');
        
		$CollegeAliasName = new Zend_Form_Element_Text('CollegeAliasName');
		$CollegeAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$CollegeAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$DepartmentAliasName = new Zend_Form_Element_Text('DepartmentAliasName');
		$DepartmentAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$DepartmentAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$DeanAliasName = new Zend_Form_Element_Text('DeanAliasName');
		$DeanAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$DeanAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$RegisterAliasName = new Zend_Form_Element_Text('RegisterAliasName');
		$RegisterAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$RegisterAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$SubjectAliasName = new Zend_Form_Element_Text('SubjectAliasName');
		$SubjectAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$SubjectAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');


		$PPField1 = new Zend_Form_Element_Text('PPField1');
		$PPField1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$PPField1->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$PPField2 = new Zend_Form_Element_Text('PPField2');
		$PPField2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$PPField2->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$PPField3 = new Zend_Form_Element_Text('PPField3');
		$PPField3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$PPField3->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$PPField4 = new Zend_Form_Element_Text('PPField4');
		$PPField4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$PPField4->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
					
		$PPField5 = new Zend_Form_Element_Text('PPField5');
		$PPField5->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$PPField5	->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$VisaDetailField1 = new Zend_Form_Element_Text('VisaDetailField1');
		$VisaDetailField1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$VisaDetailField1->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$VisaDetailField2 = new Zend_Form_Element_Text('VisaDetailField2');
		$VisaDetailField2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$VisaDetailField2->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');			

		$VisaDetailField3 = new Zend_Form_Element_Text('VisaDetailField3');
		$VisaDetailField3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$VisaDetailField3->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$VisaDetailField4 = new Zend_Form_Element_Text('VisaDetailField4');
		$VisaDetailField4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$VisaDetailField4->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');		

		$VisaDetailField5 = new Zend_Form_Element_Text('VisaDetailField5');
		$VisaDetailField5->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$VisaDetailField5->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					

					
		$ApplicantCodeType = new Zend_Form_Element_Radio('ApplicantCodeType');
                $ApplicantCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
              $ApplicantIdFormat = new Zend_Form_Element_Text('ApplicantIdFormat');
			$ApplicantIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$ApplicantIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

              $ApplicantPrefix = new Zend_Form_Element_Text('ApplicantPrefix');
			$ApplicantPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$ApplicantPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

			$ResetApplicantSeq  = new Zend_Form_Element_Checkbox('ResetApplicantSeq');
			$ResetApplicantSeq->setAttrib('dojoType',"dijit.form.CheckBox");
        	$ResetApplicantSeq->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

					
				

						
			//new Addictionnnn
		
		$RegistrationCodeType = new Zend_Form_Element_Radio('RegistrationCodeType');
        $RegistrationCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,7)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');			
					
		$RegistrationIdFormat = new Zend_Form_Element_Text('RegistrationIdFormat');
			$RegistrationIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$RegistrationIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
			
			$RegistrationPrefix = new Zend_Form_Element_Text('RegistrationPrefix');
			$RegistrationPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$RegistrationPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
            
			$ResetRegistrationSeq = new Zend_Form_Element_Select('ResetRegistrationSeq');
				$ResetRegistrationSeq->setAttrib('dojoType',"dijit.form.FilteringSelect");
				$ResetRegistrationSeq->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->setAttrib('required',"true")
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
                         $larrresetoptions  = array(''=>'Select','year'=>'Year','program'=>'Program','intake'=>'Intake','progintake'=>'Program&Intake');
                         $ResetRegistrationSeq->addMultiOptions($larrresetoptions);

                        
		$RegistrationCodeText1 = new Zend_Form_Element_Text('RegistrationCodeText1');
		$RegistrationCodeText1 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RegistrationCodeText1 ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
								
		$RegistrationCodeText2 = new Zend_Form_Element_Text('RegistrationCodeText2');
		$RegistrationCodeText2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RegistrationCodeText2 ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$RegistrationCodeText3 = new Zend_Form_Element_Text('RegistrationCodeText3');
		$RegistrationCodeText3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RegistrationCodeText3 ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$RegistrationCodeText4 = new Zend_Form_Element_Text('RegistrationCodeText4');
		$RegistrationCodeText4 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RegistrationCodeText4 ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	
							
							
		
						
		$SemesterCodeType = new Zend_Form_Element_Radio('SemesterCodeType');
        $SemesterCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,2)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	
						
						
						
		$SemesterSeparator = new Zend_Form_Element_Text('SemesterSeparator');
		$SemesterSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterSeparator  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$SemesterCodeField1 = new Zend_Form_Element_Select('SemesterCodeField1');
		$SemesterCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCodeField1 ->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->setAttrib('onChange','showSemTextfield(this.value,1)')
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$SemesterCodeField2 = new Zend_Form_Element_Select('SemesterCodeField2');
		$SemesterCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCodeField2 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->setAttrib('onChange','showSemTextfield(this.value,2)')
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$SemesterCodeField3 = new Zend_Form_Element_Select('SemesterCodeField3');
		$SemesterCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCodeField3 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->setAttrib('onChange','showSemTextfield(this.value,3)')
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$SemesterCodeField4 = new Zend_Form_Element_Select('SemesterCodeField4');
		$SemesterCodeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCodeField4 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->setAttrib('onChange','showSemTextfield(this.value,4)')
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');			

		$SemesterCodeText1 = new Zend_Form_Element_Text('SemesterCodeText1');
		$SemesterCodeText1 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterCodeText1  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
								
		$SemesterCodeText2 = new Zend_Form_Element_Text('SemesterCodeText2');
		$SemesterCodeText2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterCodeText2  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$SemesterCodeText3 = new Zend_Form_Element_Text('SemesterCodeText3');
		$SemesterCodeText3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterCodeText3  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$SemesterCodeText4 = new Zend_Form_Element_Text('SemesterCodeText4');
		$SemesterCodeText4 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterCodeText4  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
						
		$SubjectCodeType = new Zend_Form_Element_Radio('SubjectCodeType');
        $SubjectCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');				
		$SubjectIdFormat = new Zend_Form_Element_Text('SubjectIdFormat');
			$SubjectIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$SubjectIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
			
			$SubjectPrefix = new Zend_Form_Element_Text('SubjectPrefix');
			$SubjectPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$SubjectPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
            
			$ResetSubjectSeq  = new Zend_Form_Element_Checkbox('ResetSubjectSeq');
			$ResetSubjectSeq->setAttrib('dojoType',"dijit.form.CheckBox");
        	$ResetSubjectSeq	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
/////////////////////item code 
        $ItemCode= new Zend_Form_Element_Radio('ItemCode');
        $ItemCode->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','itembase(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	

		$Itembase= new Zend_Form_Element_Radio('Itembase');
        $Itembase->addMultiOptions(array('0' => 'Level', '1' => 'General'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','itemlevelType(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	

		$generalbaseitemField1 = new Zend_Form_Element_Select('generalbaseitemField1');
		$generalbaseitemField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseitemField1 ->setAttrib('class','txt_put')		                   
					       ->setAttrib('onChange','showgroupTextitemfield(this.value,1)')
						   ->removeDecorator("Label")
						   ->removeDecorator("DtDdWrapper")
						   ->removeDecorator('HtmlTag');
						
						
		$generalbaseitemField2 = new Zend_Form_Element_Select('generalbaseitemField2');
		$generalbaseitemField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseitemField2 ->setAttrib('class','txt_put')		                
						->setAttrib('onChange','showgroupTextitemfield(this.value,2)')						
						->removeDecorator("Label")		
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$generalbaseitemField3 = new Zend_Form_Element_Select('generalbaseitemField3');
		$generalbaseitemField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseitemField3 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showgroupTextitemfield(this.value,3)')
						->removeDecorator("Label")						
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$generalbaseitemField4 = new Zend_Form_Element_Select('generalbaseitemField4');
		$generalbaseitemField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseitemField4 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showgroupTextitemfield(this.value,4)')						
						->removeDecorator("Label")						
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
										
        $generalbaseitemSeparator = new Zend_Form_Element_Text('GeneralbaseitemSeparator');
		$generalbaseitemSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseitemSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
		$generalbaseitemText1 = new Zend_Form_Element_Text('generalbaseitemText1');
		$generalbaseitemText1 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseitemText1  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
								
		$generalbaseitemText2 = new Zend_Form_Element_Text('generalbaseitemText2');
		$generalbaseitemText2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseitemText2  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$generalbaseitemText3 = new Zend_Form_Element_Text('generalbaseitemText3');
		$generalbaseitemText3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseitemText3  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$generalbaseitemText4 = new Zend_Form_Element_Text('generalbaseitemText4');
		$generalbaseitemText4 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseitemText4  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');					
						
		$itemlevelbase= new Zend_Form_Element_Radio('itemlevelbase');
        $itemlevelbase->addMultiOptions(array('0' => 'Fixed', '1' => 'Firstletter'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','itemlevel(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');

	    $FirstletteritemSeparator = new Zend_Form_Element_Text('FirstletteritemSeparator');
		$FirstletteritemSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FirstletteritemSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	
		$FixeditemSeparator= new Zend_Form_Element_Text('FixeditemSeparator');
		$FixeditemSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FixeditemSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');					

		$FixeditemText = new Zend_Form_Element_Text('FixeditemText');
		$FixeditemText ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FixeditemText  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');					
							
		$AccountCode= new Zend_Form_Element_Radio('AccountCode');
        $AccountCode->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('1')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','base(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');					
						
/////////////////////						
		
 ///////////////account code
		$base= new Zend_Form_Element_Radio('base');
        $base->addMultiOptions(array('0' => 'Level', '1' => 'General'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','levelType(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	
						
       
						
		$generalbaseField1 = new Zend_Form_Element_Select('generalbaseField1');
		$generalbaseField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseField1 ->setAttrib('class','txt_put')		                   
					       ->setAttrib('onChange','showgroupTextfield(this.value,1)')
						   ->removeDecorator("Label")
						   ->removeDecorator("DtDdWrapper")
						   ->removeDecorator('HtmlTag');
						
						
		$generalbaseField2 = new Zend_Form_Element_Select('generalbaseField2');
		$generalbaseField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseField2 ->setAttrib('class','txt_put')		                
						->setAttrib('onChange','showgroupTextfield(this.value,2)')						
						->removeDecorator("Label")		
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$generalbaseField3 = new Zend_Form_Element_Select('generalbaseField3');
		$generalbaseField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseField3 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showgroupTextfield(this.value,3)')
						->removeDecorator("Label")						
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$generalbaseField4 = new Zend_Form_Element_Select('generalbaseField4');
		$generalbaseField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$generalbaseField4 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showgroupTextfield(this.value,4)')						
						->removeDecorator("Label")						
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');					
						
		$levelbase= new Zend_Form_Element_Radio('levelbase');
        $levelbase->addMultiOptions(array('0' => 'Fixed', '1' => 'Firstletter'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','level(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	
							
		$generalbaseSeparator = new Zend_Form_Element_Text('GeneralbaseSeparator');
		$generalbaseSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$FirstletterSeparator = new Zend_Form_Element_Text('FirstletterSeparator');
		$FirstletterSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FirstletterSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	
		$FixedSeparator= new Zend_Form_Element_Text('FixedSeparator');
		$FixedSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FixedSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');					

		$FixedText = new Zend_Form_Element_Text('FixedText');
		$FixedText ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$FixedText  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');					
							
		$AccountCode= new Zend_Form_Element_Radio('AccountCode');
        $AccountCode->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','base(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
						
		
						
		$Failedsubjects= new Zend_Form_Element_Radio('Failedsubjects');
        $Failedsubjects->addMultiOptions(array('0' => 'To Push Failed Courses', '1' => 'Ignore Failed Courses'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						//->setAttrib('onClick','base(this.value)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	

		 

		$generalbaseText1 = new Zend_Form_Element_Text('generalbaseText1');
		$generalbaseText1 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseText1  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
								
		$generalbaseText2 = new Zend_Form_Element_Text('generalbaseText2');
		$generalbaseText2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseText2  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$generalbaseText3 = new Zend_Form_Element_Text('generalbaseText3');
		$generalbaseText3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseText3  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$generalbaseText4 = new Zend_Form_Element_Text('generalbaseText4');
		$generalbaseText4 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$generalbaseText4  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');				
						
///////////////////////						
		$AccountCodeType = new Zend_Form_Element_Radio('AccountCodeType');
        $AccountCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setValue('0')
						->setAttrib('onClick','ledgerCodeType(this.value,8)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');			
					
		$AccountSeparator = new Zend_Form_Element_Text('AccountSeparator');
		$AccountSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccountSeparator  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$AccountCodeField1 = new Zend_Form_Element_Select('AccountCodeField1');
		$AccountCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AccountCodeField1 ->setAttrib('class','txt_put')
						->setAttrib('onChange','showAccTextfield(this.value,1)')						
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$AccountCodeField2 = new Zend_Form_Element_Select('AccountCodeField2');
		$AccountCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AccountCodeField2 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showAccTextfield(this.value,2)')		
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$AccountCodeField3 = new Zend_Form_Element_Select('AccountCodeField3');
		$AccountCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AccountCodeField3 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showAccTextfield(this.value,3)')		
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');

		$AccountCodeField4 = new Zend_Form_Element_Select('AccountCodeField4');
		$AccountCodeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AccountCodeField4 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showAccTextfield(this.value,4)')		
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				

		$AccountCodeText1 = new Zend_Form_Element_Text('AccountCodeText1');
		$AccountCodeText1 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccountCodeText1  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
								
		$AccountCodeText2 = new Zend_Form_Element_Text('AccountCodeText2');
		$AccountCodeText2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccountCodeText2  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$AccountCodeText3 = new Zend_Form_Element_Text('AccountCodeText3');
		$AccountCodeText3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccountCodeText3  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	

		$AccountCodeText4 = new Zend_Form_Element_Text('AccountCodeText4');
		$AccountCodeText4 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AccountCodeText4  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');				
						
		$InvoiceSeparator = new Zend_Form_Element_Text('InvoiceSeparator');
		$InvoiceSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$InvoiceSeparator  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$InvoiceCodeField1 = new Zend_Form_Element_Select('InvoiceCodeField1');
		$InvoiceCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$InvoiceCodeField1 ->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$InvoiceCodeField2 = new Zend_Form_Element_Select('InvoiceCodeField2');
		$InvoiceCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$InvoiceCodeField2 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$InvoiceCodeField3 = new Zend_Form_Element_Select('InvoiceCodeField3');
		$InvoiceCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$InvoiceCodeField3 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$InvoiceCodeField4 = new Zend_Form_Element_Select('InvoiceCodeField4');
		$InvoiceCodeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$InvoiceCodeField4 	->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');		

		//----------------------------------------------------------------------------------------------
		
		$AppealCodeType = new Zend_Form_Element_Radio('AppealCodeType');
        $AppealCodeType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,9)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');			
						
		$AppealCodeSeparator = new Zend_Form_Element_Text('AppealCodeSeparator');
		$AppealCodeSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$AppealCodeSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
							
							
						
							
							
							
							
							

		$AppealCodeField1 = new Zend_Form_Element_Select('AppealCodeField1');
		$AppealCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AppealCodeField1 ->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$AppealCodeField2 = new Zend_Form_Element_Select('AppealCodeField2');
		$AppealCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AppealCodeField2->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$AppealCodeField3 = new Zend_Form_Element_Select('AppealCodeField3');
		$AppealCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AppealCodeField3->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$AppealCodeField4 = new Zend_Form_Element_Select('AppealCodeField4');
		$AppealCodeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AppealCodeField4->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');		
						
		//----------------------------------------------------------------------------------------------				
						
						
						
		$StaffIdType = new Zend_Form_Element_Radio('StaffIdType');
                $StaffIdType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,6)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');

                $StaffIdFormat = new Zend_Form_Element_Text('StaffIdFormat');
			$StaffIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$StaffIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

			$StaffPrefix = new Zend_Form_Element_Text('StaffPrefix');
			$StaffPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$StaffPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

			$ResetStaffSeq  = new Zend_Form_Element_Checkbox('ResetStaffSeq');
			$ResetStaffSeq->setAttrib('dojoType',"dijit.form.CheckBox");
        	$ResetStaffSeq	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
		
					
		$NoRefCode = new Zend_Dojo_Form_Element_FilteringSelect('NoRefCode');
        $NoRefCode->removeDecorator("DtDdWrapper");
        $NoRefCode->setAttrib('required',"true") ;
        $NoRefCode->removeDecorator("Label");
        $NoRefCode->removeDecorator('HtmlTag');
        $NoRefCode->setRegisterInArrayValidator(false);
		$NoRefCode->setAttrib('dojoType',"dijit.form.FilteringSelect");	
		$NoRefCode->addMultiOptions(array('1'=>'1','2'=>'2','3'=>'3','4'=>'4'));
		$NoRefCode->setAttrib('onchange','enablerefcodefields(this.value)');	

		$RefCodeField1 = new Zend_Form_Element_Text('RefCodeField1');
		$RefCodeField1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RefCodeField1->setAttrib('maxlength','20')
					->setAttrib('disabled','true')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$RefCodeField2 = new Zend_Form_Element_Text('RefCodeField2');
		$RefCodeField2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RefCodeField2->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->setAttrib('disabled','true')
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$RefCodeField3 = new Zend_Form_Element_Text('RefCodeField3');
		$RefCodeField3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RefCodeField3->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->setAttrib('disabled','true')
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$RefCodeField4 = new Zend_Form_Element_Text('RefCodeField4');
		$RefCodeField4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$RefCodeField4->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->setAttrib('disabled','true')
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');									

						
		$DepartmentCodeType = new Zend_Form_Element_Radio('DepartmentCodeType');
        $DepartmentCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,4)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	
									
		$DepartmentSeparator = new Zend_Form_Element_Text('DepartmentSeparator');
		$DepartmentSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DepartmentSeparator  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$DepartmentCodeField1 = new Zend_Form_Element_Select('DepartmentCodeField1');
		$DepartmentCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$DepartmentCodeField1 ->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$DepartmentCodeField2 = new Zend_Form_Element_Select('DepartmentCodeField2');
		$DepartmentCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$DepartmentCodeField2 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$DepartmentCodeField3 = new Zend_Form_Element_Select('DepartmentCodeField3');
		$DepartmentCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$DepartmentCodeField3 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	

		$DepartmentCodeField4 = new Zend_Form_Element_Select('DepartmentCodeField4');
		$DepartmentCodeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$DepartmentCodeField4	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
						
						
						

		$CollegeCodeType = new Zend_Form_Element_Radio('CollegeCodeType');
        $CollegeCodeType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,5)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');	
									
		$CollegeSeparator = new Zend_Form_Element_Text('CollegeSeparator');
		$CollegeSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$CollegeSeparator  ->setAttrib('class','txt_put')
							->setValue('-')
							->setAttrib('maxlength','1')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$CollegeCodeField1 = new Zend_Form_Element_Select('CollegeCodeField1');
		$CollegeCodeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$CollegeCodeField1 ->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$CollegeCodeField2 = new Zend_Form_Element_Select('CollegeCodeField2');
		$CollegeCodeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$CollegeCodeField2 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$CollegeCodeField3 = new Zend_Form_Element_Select('CollegeCodeField3');
		$CollegeCodeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$CollegeCodeField3 	->setAttrib('class','txt_put')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
						
		$SMTPServer = new Zend_Form_Element_Text('SMTPServer');
		$SMTPServer->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SMTPServer  ->setAttrib('maxlength','50')
					 ->removeDecorator("Label")
					 ->removeDecorator("DtDdWrapper")
					 ->removeDecorator('HtmlTag');
					 
		$SMTPUsername = new Zend_Form_Element_Text('SMTPUsername');
		$SMTPUsername->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SMTPUsername->setAttrib('maxlength','50')
					 ->removeDecorator("Label")
					 ->removeDecorator("DtDdWrapper")
					 ->removeDecorator('HtmlTag');
					 
		$SMTPPassword = new Zend_Form_Element_Text('SMTPPassword',array('regExp'=>"[\w]+",'invalidMessage'=>"No Spaces Allowed"));
		$SMTPPassword->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SMTPPassword  	->setAttrib('maxlength','150')
					 	->removeDecorator("Label")
					 	->removeDecorator("DtDdWrapper")
					 	->removeDecorator('HtmlTag');
					 
		$SMTPPort = new Zend_Form_Element_Text('SMTPPort',array('regExp'=>"[0-9]*",'invalidMessage'=>"Only Numbers"));
		$SMTPPort->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SMTPPort 	 ->setAttrib('maxlength','5')
					 ->setValue('25')
					 ->removeDecorator("Label")
					 ->removeDecorator("DtDdWrapper")
					 ->removeDecorator('HtmlTag');
					 
		$SSL  = new Zend_Form_Element_Checkbox('SSL');
		$SSL->setAttrib('dojoType',"dijit.form.CheckBox");
        $SSL	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $AddDrop  = new Zend_Form_Element_Checkbox('AddDrop');
		$AddDrop->setAttrib('dojoType',"dijit.form.CheckBox");
        $AddDrop	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $DefaultEmail = new Zend_Form_Element_Text('DefaultEmail',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
        $DefaultEmail->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DefaultEmail  	->setAttrib('maxlength','50')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save	->setAttrib('id', 'save')
				->removeDecorator("Label")
					->setAttrib('class', 'NormalBtn')
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$ProgramAliasName = new Zend_Form_Element_Text('ProgramAliasName');
		$ProgramAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ProgramAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$BranchAliasName = new Zend_Form_Element_Text('BranchAliasName');
		$BranchAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$BranchAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$LandscapeAliasName = new Zend_Form_Element_Text('LandscapeAliasName');
		$LandscapeAliasName	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$LandscapeAliasName  	->setAttrib('maxlength','20')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$Language  = new Zend_Form_Element_Radio('Language');
		$Language->setAttrib('dojoType',"dijit.form.RadioButton");
        $Language->addMultiOptions(array('1' => 'Arabic','2' => 'Indonesia'))
        			->setvalue('2')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag');	

        			
        $LocalStudent  = new Zend_Form_Element_Checkbox('LocalStudent');
		$LocalStudent->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalStudent->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');			
		
        		
        $BlockType = new Zend_Form_Element_Radio('BlockType');
        $BlockType->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")						
						->removeDecorator("Label")
						->setAttrib('onClick','BlockShow(this.value)')
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');

        $BlockName = new Zend_Form_Element_Text('BlockName');
		$BlockName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BlockName->setAttrib('maxlength','20')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
        		
        
		$BlockSeparator = new Zend_Form_Element_Text('BlockSeparator');
		$BlockSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BlockSeparator->setAttrib('maxlength','1')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
						
		/*
 		 * Accredition Fields
 		 */        			
        $AccDtlCount = new Zend_Form_Element_Select('AccDtlCount');
		$AccDtlCount->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$AccDtlCount 	->setAttrib('class','txt_put')
						->addMultiOptions(array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10',
												'11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'))
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
								
		$AccDtl1 = new Zend_Form_Element_Text('AccDtl1');
		$AccDtl1	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl1  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$AccDtl2 = new Zend_Form_Element_Text('AccDtl2');
		$AccDtl2	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl2  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl3 = new Zend_Form_Element_Text('AccDtl3');
		$AccDtl3	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl3  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl4 = new Zend_Form_Element_Text('AccDtl4');
		$AccDtl4	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl4  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl5 = new Zend_Form_Element_Text('AccDtl5');
		$AccDtl5	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl5  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl6 = new Zend_Form_Element_Text('AccDtl6');
		$AccDtl6	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl6  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl7 = new Zend_Form_Element_Text('AccDtl7');
		$AccDtl7	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl7  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl8 = new Zend_Form_Element_Text('AccDtl8');
		$AccDtl8	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl8  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl9 = new Zend_Form_Element_Text('AccDtl9');
		$AccDtl9	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl9  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl10 = new Zend_Form_Element_Text('AccDtl10');
		$AccDtl10	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl10  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl11 = new Zend_Form_Element_Text('AccDtl11');
		$AccDtl11	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl11  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl12 = new Zend_Form_Element_Text('AccDtl12');
		$AccDtl12	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl12  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl13 = new Zend_Form_Element_Text('AccDtl13');
		$AccDtl13	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl13  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl14 = new Zend_Form_Element_Text('AccDtl14');
		$AccDtl14	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl14  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl15 = new Zend_Form_Element_Text('AccDtl15');
		$AccDtl15	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl15 	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl16 = new Zend_Form_Element_Text('AccDtl16');
		$AccDtl16	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl16  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl17 = new Zend_Form_Element_Text('AccDtl17');
		$AccDtl17	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl17  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl18 = new Zend_Form_Element_Text('AccDtl18');
		$AccDtl18	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl18  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl19 = new Zend_Form_Element_Text('AccDtl19');
		$AccDtl19	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl19  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$AccDtl20 = new Zend_Form_Element_Text('AccDtl20');
		$AccDtl20	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$AccDtl20  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
							
							
							
		/*
 		 * MOHE Fields
 		 */        			
        $MoheDtlCount = new Zend_Form_Element_Select('MoheDtlCount');
		$MoheDtlCount->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$MoheDtlCount 	->setAttrib('class','txt_put')
						->addMultiOptions(array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10',
												'11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20'))
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');		

		$MoheDtl1 = new Zend_Form_Element_Text('MoheDtl1');
		$MoheDtl1	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl1  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$MoheDtl2 = new Zend_Form_Element_Text('MoheDtl2');
		$MoheDtl2	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl2  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl3 = new Zend_Form_Element_Text('MoheDtl3');
		$MoheDtl3	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl3  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl4 = new Zend_Form_Element_Text('MoheDtl4');
		$MoheDtl4	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl4  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl5 = new Zend_Form_Element_Text('MoheDtl5');
		$MoheDtl5	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl5  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl6 = new Zend_Form_Element_Text('MoheDtl6');
		$MoheDtl6	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl6  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl7 = new Zend_Form_Element_Text('MoheDtl7');
		$MoheDtl7	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl7  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl8 = new Zend_Form_Element_Text('MoheDtl8');
		$MoheDtl8	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl8  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl9 = new Zend_Form_Element_Text('MoheDtl9');
		$MoheDtl9	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl9  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl10 = new Zend_Form_Element_Text('MoheDtl10');
		$MoheDtl10	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl10  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl11 = new Zend_Form_Element_Text('MoheDtl11');
		$MoheDtl11	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl11  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl12 = new Zend_Form_Element_Text('MoheDtl12');
		$MoheDtl12	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl12  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl13 = new Zend_Form_Element_Text('MoheDtl13');
		$MoheDtl13	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl13  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl14 = new Zend_Form_Element_Text('MoheDtl14');
		$MoheDtl14	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl14  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl15 = new Zend_Form_Element_Text('MoheDtl15');
		$MoheDtl15	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl15 	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$MoheDtl16 = new Zend_Form_Element_Text('MoheDtl16');
		$MoheDtl16	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl16  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');      
							  			
		$MoheDtl17 = new Zend_Form_Element_Text('MoheDtl17');
		$MoheDtl17	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl17  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');  
							      			
		$MoheDtl18 = new Zend_Form_Element_Text('MoheDtl18');
		$MoheDtl18	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl18  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');    
							    			
		$MoheDtl19 = new Zend_Form_Element_Text('MoheDtl19');
		$MoheDtl19	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl19  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');  
							      			
		$MoheDtl20 = new Zend_Form_Element_Text('MoheDtl20');
		$MoheDtl20	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MoheDtl20  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		
							
		$InternationalPlacementTest  = new Zend_Form_Element_Checkbox('InternationalPlacementTest');
		$InternationalPlacementTest->setAttrib('dojoType',"dijit.form.CheckBox");
        $InternationalPlacementTest	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->setAttrib('onClick','disableCheck()')
        		->removeDecorator('HtmlTag');
        		
        $InternationalCertification  = new Zend_Form_Element_Checkbox('InternationalCertification');
		$InternationalCertification->setAttrib('dojoType',"dijit.form.CheckBox");
        $InternationalCertification	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->setAttrib('onClick','disableCheck()')
        		->removeDecorator('HtmlTag');      		
       

        $InternationalAndOr  = new Zend_Form_Element_Checkbox('InternationalAndOr');
		$InternationalAndOr->setAttrib('dojoType',"dijit.form.CheckBox");
        $InternationalAndOr	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $LocalPlacementTest  = new Zend_Form_Element_Checkbox('LocalPlacementTest');
		$LocalPlacementTest->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalPlacementTest	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->setAttrib('onClick','disableCheck()')
        		->removeDecorator('HtmlTag');		

        $LocalCertification  = new Zend_Form_Element_Checkbox('LocalCertification');
		$LocalCertification->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalCertification	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->setAttrib('onClick','disableCheck()')
        		->removeDecorator('HtmlTag');

        $LocalAndOr  = new Zend_Form_Element_Checkbox('LocalAndOr');
		$LocalAndOr->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalAndOr	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');		
        		
        		
        		
        $InternationalPlcamenetTestProcessingFee  = new Zend_Form_Element_Checkbox('InternationalPlcamenetTestProcessingFee');
		$InternationalPlcamenetTestProcessingFee->setAttrib('dojoType',"dijit.form.CheckBox");
        $InternationalPlcamenetTestProcessingFee->removeDecorator("DtDdWrapper")
        										->removeDecorator("Label")
        										->removeDecorator('HtmlTag');	

        $InternationalCertificationProcessingFee  = new Zend_Form_Element_Checkbox('InternationalCertificationProcessingFee');
		$InternationalCertificationProcessingFee->setAttrib('dojoType',"dijit.form.CheckBox");
        $InternationalCertificationProcessingFee->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
        		
        		
        		
        $LocalPlcamenetTestProcessingFee  = new Zend_Form_Element_Checkbox('LocalPlcamenetTestProcessingFee');
		$LocalPlcamenetTestProcessingFee->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalPlcamenetTestProcessingFee->removeDecorator("DtDdWrapper")
        										->removeDecorator("Label")
        										->removeDecorator('HtmlTag');	

        $LocalCertificationProcessingFee  = new Zend_Form_Element_Checkbox('LocalCertificationProcessingFee');
		$LocalCertificationProcessingFee->setAttrib('dojoType',"dijit.form.CheckBox");
        $LocalCertificationProcessingFee->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
        		
		$Completionofyears = new Zend_Form_Element_Text('Completionofyears');
		$Completionofyears->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Completionofyears->setAttrib('maxlength','40')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
		$NoofWarnings = new Zend_Form_Element_Text('NoofWarnings',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$NoofWarnings->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $NoofWarnings->setAttrib('maxlength','3');  
        $NoofWarnings->setAttrib('style','width:50px');  
       	$NoofWarnings->removeDecorator("DtDdWrapper");
        $NoofWarnings->removeDecorator("Label");
        $NoofWarnings->removeDecorator('HtmlTag');
        
		$DefaultCountry = new Zend_Dojo_Form_Element_FilteringSelect('DefaultCountry');
        $DefaultCountry->removeDecorator("DtDdWrapper");
        $DefaultCountry->setAttrib('required',"true") ;
        $DefaultCountry->removeDecorator("Label");
        $DefaultCountry->removeDecorator('HtmlTag');
        $DefaultCountry->setRegisterInArrayValidator(false);
		$DefaultCountry->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$DefaultDropDownLanguage = new Zend_Dojo_Form_Element_FilteringSelect('DefaultDropDownLanguage');
		$DefaultDropDownLanguage->addMultiOptions(array( '0' => 'English',
														 '1' => 'Bahasa Indonesia'));
        $DefaultDropDownLanguage->removeDecorator("DtDdWrapper");
        $DefaultDropDownLanguage->setAttrib('required',"true") ;
        $DefaultDropDownLanguage->removeDecorator("Label");
        $DefaultDropDownLanguage->removeDecorator('HtmlTag');
        $DefaultDropDownLanguage->setRegisterInArrayValidator(false);
		$DefaultDropDownLanguage->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$MarksAppeal  = new Zend_Form_Element_Radio('MarksAppeal');
		$MarksAppeal->addMultiOptions(array('0' => 'Take Latest', '1' => 'Take Highest'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton");
        $MarksAppeal->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        		
        $TakeMarks  = new Zend_Form_Element_Radio('TakeMarks');
		$TakeMarks->addMultiOptions(array('0' => 'Marks By Average', '1' => 'Marks by Rank'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton");
        $TakeMarks->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
		
		$marksdistributiontype  = new Zend_Form_Element_Radio('marksdistributiontype');
		$marksdistributiontype->addMultiOptions(array('0' => 'By Course', '1' => 'By Lecturer'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton");
        $marksdistributiontype->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
     //---------------------------------------------------//billcode----------------------------------------
        $BillNoType = new Zend_Form_Element_Radio('BillNoType');
        $BillNoType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,10)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');			
        		
        $BillNoTypeSeparator = new Zend_Form_Element_Text('BillNoTypeSeparator');
		$BillNoTypeSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BillNoTypeSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('-')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	
							
							
							
							
							
							
							
		$BillNoTypeField1 = new Zend_Form_Element_Select('BillNoTypeField1');
		$BillNoTypeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$BillNoTypeField1 ->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
                $BillNoTypeField2 = new Zend_Form_Element_Select('BillNoTypeField2');
		$BillNoTypeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$BillNoTypeField2->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$BillNoTypeField3 = new Zend_Form_Element_Select('BillNoTypeField3');
		$BillNoTypeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$BillNoTypeField3->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$BillNoTypeField4 = new Zend_Form_Element_Select('BillNoTypeField4');
		$BillNoTypeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$BillNoTypeField4->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,4)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
						
		//---------------------------------------------------------------------------------------------

                //-----------------Code start here for Student Id generation Form -------------------------------//
                $StudentIdType = new Zend_Form_Element_Radio('StudentIdType');
                $StudentIdType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,12)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
						
				$StudentIdFormat = new Zend_Form_Element_Text('StudentIdFormat');
				$StudentIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
				$StudentIdFormat->setAttrib('class','txt_put')					
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
			
				$StudentIdPrefix = new Zend_Form_Element_Text('StudentIdPrefix');
				$StudentIdPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
				$StudentIdPrefix->setAttrib('class','txt_put')
					->setAttrib('maxlength','4') 			
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
				
				$ResetStudentIdSeq = new Zend_Form_Element_Select('ResetStudentIdSeq');
				$ResetStudentIdSeq->setAttrib('dojoType',"dijit.form.FilteringSelect");
				$ResetStudentIdSeq->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->setAttrib('required',"true")
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
					
				

                //-----------------Code end here for Student Id generation Form -------------------------------//
                
			//-----------------Code start here for Application Id generation Form -------------------------------//
			$CourseIdType = new Zend_Form_Element_Radio('CourseIdType');
            $CourseIdType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,13)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
						
			$CourseIdFormat = new Zend_Form_Element_Text('CourseIdFormat');
			$CourseIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$CourseIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
			
			$CoursePrefix = new Zend_Form_Element_Text('CoursePrefix');
			$CoursePrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$CoursePrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
            
			$ResetCourseSeq  = new Zend_Form_Element_Checkbox('ResetCourseSeq');
			$ResetCourseSeq->setAttrib('dojoType',"dijit.form.CheckBox");
        	$ResetCourseSeq	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
		
			
			//-----------------Code start here for Application Id generation Form -------------------------------//

						
						
						
		$HijriDate = new Zend_Form_Element_Text('HijriDate',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$HijriDate->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$HijriDate  ->setAttrib('class','txt_put')
							->setAttrib('maxlength','5')
							->setAttrib('style','width:100px;')
							//->setAttrib('size', '10')
							//->setValue('+')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
							
							
							
	    $HijriDateOptions = new Zend_Form_Element_Select('HijriDateOptions');
		$HijriDateOptions->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$HijriDateOptions ->setAttrib('class','txt_put')
		                  ->addMultiOptions(array( '+' => '+',
												   '-' => '-'))
						->setAttrib('style','width:50px;')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
		//---------------------------------------------------------------------------------------------	
		
						
		$ReceiptType = new Zend_Form_Element_Radio('ReceiptType');
        $ReceiptType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,11)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');			
        		
        $ReceiptTypeSeparator = new Zend_Form_Element_Text('ReceiptTypeSeparator');
		$ReceiptTypeSeparator->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$ReceiptTypeSeparator->setAttrib('class','txt_put')
							->setAttrib('maxlength','1')
							->setValue('*')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');	
							
							
							
							
							
							
							
		$ReceiptTypeField1 = new Zend_Form_Element_Select('ReceiptTypeField1');
		$ReceiptTypeField1 ->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$ReceiptTypeField1 ->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,1)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
						
	    $ReceiptTypeField2 = new Zend_Form_Element_Select('ReceiptTypeField2');
		$ReceiptTypeField2->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$ReceiptTypeField2->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,2)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$ReceiptTypeField3 = new Zend_Form_Element_Select('ReceiptTypeField3');
		$ReceiptTypeField3->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$ReceiptTypeField3->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,3)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');				
						
						
		$ReceiptTypeField4 = new Zend_Form_Element_Select('ReceiptTypeField4');
		$ReceiptTypeField4->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$ReceiptTypeField4->setAttrib('class','txt_put')
						->setAttrib('onChange','showTextfield(this.value,4)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	

						
						
						
						//--------------------------------------
						
	    $Branch  = new Zend_Form_Element_Checkbox('Branch');
		$Branch->setAttrib('dojoType',"dijit.form.CheckBox");
        $Branch	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $Office  = new Zend_Form_Element_Checkbox('Office');
		$Office->setAttrib('dojoType',"dijit.form.CheckBox");
        $Office	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $Venue  = new Zend_Form_Element_Checkbox('Venue');
		$Venue->setAttrib('dojoType',"dijit.form.CheckBox");
        $Venue	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        		
        $Scheme  = new Zend_Form_Element_Checkbox('Scheme');
		$Scheme->setAttrib('dojoType',"dijit.form.CheckBox");
        $Scheme	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $Landscape  = new Zend_Form_Element_Checkbox('Landscape');
		$Landscape->setAttrib('dojoType',"dijit.form.CheckBox");
        $Landscape	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

        		
        $NameDtlCount = new Zend_Form_Element_Select('NameDtlCount');
		$NameDtlCount->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$NameDtlCount 	->setAttrib('class','txt_put')
						->addMultiOptions(array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'))
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');	
								
		$NameDtl1 = new Zend_Form_Element_Text('NameDtl1');
		$NameDtl1	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$NameDtl1  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');

		$NameDtl2 = new Zend_Form_Element_Text('NameDtl2');
		$NameDtl2	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$NameDtl2  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag'); 
							       			
		$NameDtl3 = new Zend_Form_Element_Text('NameDtl3');
		$NameDtl3	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$NameDtl3  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$NameDtl4 = new Zend_Form_Element_Text('NameDtl4');
		$NameDtl4	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$NameDtl4  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');        			
		$NameDtl5 = new Zend_Form_Element_Text('NameDtl5');
		$NameDtl5	->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$NameDtl5  	->setAttrib('maxlength','100')
							->removeDecorator("Label")
							->removeDecorator("DtDdWrapper")
							->removeDecorator('HtmlTag');
							
							
		$ExtarIdDtlCount = new Zend_Form_Element_Select('ExtarIdDtlCount');
		$ExtarIdDtlCount->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$ExtarIdDtlCount 	->setAttrib('class','txt_put')
						->addMultiOptions(array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11',
									'12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19', '20'=>'20'))
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
						
		$ExtarIdDtl1 = new Zend_Form_Element_Text('ExtarIdDtl1');
		$ExtarIdDtl1->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl1->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl2 = new Zend_Form_Element_Text('ExtarIdDtl2');
		$ExtarIdDtl2->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl2->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
							
		$ExtarIdDtl3 = new Zend_Form_Element_Text('ExtarIdDtl3');
		$ExtarIdDtl3->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl3->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl4 = new Zend_Form_Element_Text('ExtarIdDtl4');
		$ExtarIdDtl4->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl4->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl5 = new Zend_Form_Element_Text('ExtarIdDtl5');
		$ExtarIdDtl5->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl5->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl6 = new Zend_Form_Element_Text('ExtarIdDtl6');
		$ExtarIdDtl6->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl6->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl7 = new Zend_Form_Element_Text('ExtarIdDtl7');
		$ExtarIdDtl7->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl7->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
							
		$ExtarIdDtl8 = new Zend_Form_Element_Text('ExtarIdDtl8');
		$ExtarIdDtl8->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl8->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
							
		$ExtarIdDtl9 = new Zend_Form_Element_Text('ExtarIdDtl9');
		$ExtarIdDtl9->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl9->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$ExtarIdDtl10 = new Zend_Form_Element_Text('ExtarIdDtl10');
		$ExtarIdDtl10->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl10->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl11 = new Zend_Form_Element_Text('ExtarIdDtl11');
		$ExtarIdDtl11->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl11->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl12 = new Zend_Form_Element_Text('ExtarIdDtl12');
		$ExtarIdDtl12->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl12->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
							
		$ExtarIdDtl13 = new Zend_Form_Element_Text('ExtarIdDtl13');
		$ExtarIdDtl13->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl13->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');

		$ExtarIdDtl14 = new Zend_Form_Element_Text('ExtarIdDtl14');
		$ExtarIdDtl14->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl14->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl15 = new Zend_Form_Element_Text('ExtarIdDtl15');
		$ExtarIdDtl15->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl15->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl16 = new Zend_Form_Element_Text('ExtarIdDtl16');
		$ExtarIdDtl16->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl16->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl17 = new Zend_Form_Element_Text('ExtarIdDtl17');
		$ExtarIdDtl17->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl17->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		$ExtarIdDtl18 = new Zend_Form_Element_Text('ExtarIdDtl18');
		$ExtarIdDtl18->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl18->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl19 = new Zend_Form_Element_Text('ExtarIdDtl19');
		$ExtarIdDtl19->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl19->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$ExtarIdDtl20 = new Zend_Form_Element_Text('ExtarIdDtl20');
		$ExtarIdDtl20->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$ExtarIdDtl20->setAttrib('maxlength','100')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
					
		$AgentIdType = new Zend_Form_Element_Radio('AgentIdType');
            $AgentIdType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,14)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
						
			$AgentIdFormat = new Zend_Form_Element_Text('AgentIdFormat');
			$AgentIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$AgentIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
			
			$AgentPrefix = new Zend_Form_Element_Text('AgentPrefix');
			$AgentPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$AgentPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
            
			$ResetAgentSeq  = new Zend_Form_Element_Checkbox('ResetAgentSeq');
			$ResetCourseSeq->setAttrib('dojoType',"dijit.form.CheckBox");
        	$ResetCourseSeq	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
						
						
						
			// INVENTORY CODE GENARATION FORM FIELDS STARTS				
		
        		$inventoryIdType = new Zend_Form_Element_Radio('inventoryIdType');
                        $inventoryIdType ->addMultiOptions(array('0' => 'Manual', '1' => 'Auto'))
						->setValue('0')
						->setAttrib('dojoType',"dijit.form.RadioButton")
						->setAttrib('onClick','ledgerCodeType(this.value,15)')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag')
						->setseparator('&nbsp;');
                        $inventoryPrefix = new Zend_Form_Element_Text('inventoryPrefix');
			$inventoryPrefix->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$inventoryPrefix->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
                        
                        $inventoryIdFormat = new Zend_Form_Element_Text('inventoryIdFormat');
			$inventoryIdFormat->setAttrib('dojoType',"dijit.form.ValidationTextBox");
			$inventoryIdFormat->setAttrib('class','txt_put')
					->removeDecorator("Label")
					->setAttrib('required',"true")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
                       // INVENTORY CODE GENARATION FORM FIELDS ENDS	     
        		
        $this->addElements(array(
        					$idConfig,$UpdDate,$UpdUser,$InternationalPlcamenetTestProcessingFee,$InternationalCertificationProcessingFee,$LocalPlcamenetTestProcessingFee,
        					$noofrowsingrid,$CollegeAliasName,$LocalCertificationProcessingFee,$Completionofyears,$DefaultCountry,$DefaultDropDownLanguage,
        					$PPField1,$PPField2,$PPField3,$PPField4,$PPField5,
        					$VisaDetailField1,$VisaDetailField2,$VisaDetailField3,$VisaDetailField4,
        					$VisaDetailField5,$NoofWarnings,
        					$ApplicantCodeType,$ApplicantIdFormat,$ApplicantPrefix,$ResetApplicantSeq,
        					$SemesterSeparator,$SemesterCodeType,$SemesterCodeField1,$SemesterCodeField2,$SemesterCodeField3,$SemesterCodeField4,
        					$SemesterCodeText1,$SemesterCodeText2,$SemesterCodeText3,$SemesterCodeText4,
        					$SubjectCodeType,$SubjectIdFormat,$SubjectPrefix,$ResetSubjectSeq,
        					$AccountSeparator,$AccountCodeType,$AccountCodeField1,$AccountCodeField2,$AccountCodeField3,$AccountCodeField4,
        					$AccountCodeText1,$AccountCodeText2,$AccountCodeText3,$AccountCodeText4,
        					$InvoiceSeparator,$InvoiceCodeField1,$InvoiceCodeField2,$InvoiceCodeField3,$InvoiceCodeField4,        					
        					$StaffIdType,$ResetStaffSeq,$StaffIdFormat,$StaffPrefix,
						$CollegeSeparator,$CollegeCodeType,$CollegeCodeField1,$CollegeCodeField2,$CollegeCodeField3,//$CollegeCodeField4,
        					$DepartmentSeparator,$DepartmentCodeType,$DepartmentCodeField1,$DepartmentCodeField2,$DepartmentCodeField3,$DepartmentCodeField4,
        					$SMTPServer,$SMTPUsername,$SMTPPassword,$SMTPPort,$SSL,
        					$DefaultEmail,$Save,$idUniversity,$SubjectAliasName,$DepartmentAliasName,$DeanAliasName,$RegisterAliasName,
        					$ProgramAliasName,$BranchAliasName,$LandscapeAliasName,$Language,$LocalStudent,$BlockType,$BlockName,$BlockSeparator,
        					$NoRefCode,$RefCodeField1,$RefCodeField2,$RefCodeField3,$RefCodeField4,
        					$AccDtlCount,$AccDtl1,$AccDtl2,$AccDtl3,$AccDtl4,$AccDtl5,$AccDtl6,$AccDtl7,$AccDtl8,$AccDtl9,$AccDtl10,$AccDtl11,$AccDtl12,$AccDtl13,$AccDtl14,$AccDtl15,$AccDtl16,$AccDtl17,$AccDtl18,$AccDtl19,$AccDtl20,
        					$MoheDtlCount,$MoheDtl1,$MoheDtl2,$MoheDtl3,$MoheDtl4,$MoheDtl5,$MoheDtl6,$MoheDtl7,$MoheDtl8,$MoheDtl9,$MoheDtl10,$MoheDtl11,$MoheDtl12,$MoheDtl13,$MoheDtl14,$MoheDtl15,$MoheDtl16,$MoheDtl17,$MoheDtl18,$MoheDtl19,$MoheDtl20,
        					$InternationalPlacementTest,$InternationalCertification,$InternationalAndOr,$LocalPlacementTest,$LocalCertification,$LocalAndOr,
        					$RegistrationCodeType,$RegistrationIdFormat,$RegistrationPrefix,$ResetRegistrationSeq,
        					$RegistrationCodeText1,$RegistrationCodeText2,$RegistrationCodeText3,$RegistrationCodeText4,$AddDrop,
        					$AppealCodeSeparator,$AppealCodeField1,$AppealCodeField2,$AppealCodeField3,$AppealCodeField4,$AppealCodeType,
        					$MarksAppeal,$TakeMarks,$marksdistributiontype,$AccountCode,$base,$generalbaseField1,$generalbaseField2,$generalbaseField3,$generalbaseField4,$levelbase,$generalbaseText1,$generalbaseText2,$generalbaseText3,$generalbaseText4,$generalbaseSeparator,$FirstletteritemSeparator,$FixeditemSeparator,$FixeditemText,
        					$FixedText,$FirstletterSeparator,$FixedSeparator,$Failedsubjects,$ItemCode,$Itembase,$generalbaseitemField1,$itemlevelbase,$generalbaseitemField2,$generalbaseitemField3,$generalbaseitemField4,$generalbaseitemSeparator,$generalbaseitemText1,$generalbaseitemText2,$generalbaseitemText3,$generalbaseitemText4,
        					$BillNoType,$BillNoTypeSeparator,$BillNoTypeField1,$BillNoTypeField2,$BillNoTypeField3,$BillNoTypeField4,
        					$StudentIdType,$StudentIdPrefix,$StudentIdFormat,$ResetStudentIdSeq,
                                                $CourseIdType,$CourseIdFormat,$CoursePrefix,$ResetCourseSeq,
                                                $AgentIdType,$AgentIdFormat,$AgentPrefix,$ResetAgentSeq,
        					$HijriDate,$HijriDateOptions,
        					$NameDtlCount,$NameDtl1,$NameDtl2,$NameDtl3,$NameDtl4,$NameDtl5,
        					$ExtarIdDtlCount,$ExtarIdDtl1, $ExtarIdDtl2,$ExtarIdDtl3,$ExtarIdDtl4,$ExtarIdDtl5,$ExtarIdDtl6,$ExtarIdDtl7,$ExtarIdDtl8,$ExtarIdDtl9,$ExtarIdDtl10,$ExtarIdDtl11,$ExtarIdDtl12,$ExtarIdDtl13,$ExtarIdDtl14,$ExtarIdDtl15,$ExtarIdDtl16,$ExtarIdDtl17,$ExtarIdDtl18,$ExtarIdDtl19,$ExtarIdDtl20,
        					$ReceiptType,$ReceiptTypeSeparator,$ReceiptTypeField1,$ReceiptTypeField2,$ReceiptTypeField3,$ReceiptTypeField4,
        					$Branch,$Office,$Venue,$Scheme,$Landscape,
                                                $inventoryIdType,$inventoryPrefix,$inventoryIdFormat
        					));
	}	
}