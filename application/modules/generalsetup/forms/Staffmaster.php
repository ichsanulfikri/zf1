<?php // FDB
class GeneralSetup_Form_Staffmaster extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day-1),$year));
		$dateofbirth = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
		
		$IdStaff = new Zend_Form_Element_Hidden('IdStaff');
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        
        $FirstName = new Zend_Form_Element_Text('FirstName',array('regExp'=>"[A-Za-z ]+",'invalidMessage'=>"Alphabets Only"));
		$FirstName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FirstName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $SecondName = new Zend_Form_Element_Text('SecondName');
		$SecondName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $SecondName->setAttrib('required',"true")   
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $ThirdName = new Zend_Form_Element_Text('ThirdName');
		$ThirdName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ThirdName->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
 		$JoiningDate = new Zend_Dojo_Form_Element_DateTextBox('JoiningDate');
        $JoiningDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $JoiningDate->setAttrib('constraints', "$dateofbirth");
		$JoiningDate->setAttrib('required',"true");
        $JoiningDate->removeDecorator("DtDdWrapper");
        $JoiningDate->setAttrib('title',"dd-mm-yyyy");
        $JoiningDate->removeDecorator("Label");
        $JoiningDate->removeDecorator('HtmlTag');
        		
       	$FourthName = new Zend_Form_Element_Text('FourthName');
		$FourthName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FourthName->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$FullName = new Zend_Form_Element_Text('FullName');
		$FullName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $FullName->setAttrib('maxlength','200')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$ArabicName = new Zend_Form_Element_Text('ArabicName');
		$ArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox")    			 
        				->setAttrib('maxlength','200')       
        				->removeDecorator("DtDdWrapper")
        	    		->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        $ExtraIdField1 = new Zend_Form_Element_Text('ExtraIdField1');
    	$ExtraIdField1->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField2 = new Zend_Form_Element_Text('ExtraIdField2');
   		$ExtraIdField2->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField3 = new Zend_Form_Element_Text('ExtraIdField3');
    	$ExtraIdField3->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField4 = new Zend_Form_Element_Text('ExtraIdField4');
    	$ExtraIdField4->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField5 = new Zend_Form_Element_Text('ExtraIdField5');
    	$ExtraIdField5->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');
          
       $ExtraIdField6 = new Zend_Form_Element_Text('ExtraIdField6');
    	$ExtraIdField6->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField7 = new Zend_Form_Element_Text('ExtraIdField7');
   		$ExtraIdField7->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField8 = new Zend_Form_Element_Text('ExtraIdField8');
    	$ExtraIdField8->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField9 = new Zend_Form_Element_Text('ExtraIdField9');
    	$ExtraIdField9->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField10 = new Zend_Form_Element_Text('ExtraIdField10');
    	$ExtraIdField10->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');
          
         $ExtraIdField11 = new Zend_Form_Element_Text('ExtraIdField11');
    	$ExtraIdField11->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField12 = new Zend_Form_Element_Text('ExtraIdField12');
   		$ExtraIdField12->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField13 = new Zend_Form_Element_Text('ExtraIdFieldd13');
    	$ExtraIdField13->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField14 = new Zend_Form_Element_Text('ExtraIdField14');
    	$ExtraIdField14->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField15 = new Zend_Form_Element_Text('ExtraIdField15');
    	$ExtraIdField15->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');
          
       $ExtraIdField16 = new Zend_Form_Element_Text('ExtraIdField16');
    	$ExtraIdField16->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField17 = new Zend_Form_Element_Text('ExtraIdField17');
   		$ExtraIdField17->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField18 = new Zend_Form_Element_Text('ExtraIdField18');
    	$ExtraIdField18->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField19 = new Zend_Form_Element_Text('ExtraIdField19');
    	$ExtraIdField19->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    	$ExtraIdField20 = new Zend_Form_Element_Text('ExtraIdField20');
    	$ExtraIdField20->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');
        		
        
        				
        				
        $DOB = new Zend_Dojo_Form_Element_DateTextBox('DOB');
        $DOB->setAttrib('dojoType',"dijit.form.DateTextBox");
        $DOB->setAttrib('constraints', "$dateofbirth");
		$DOB->setAttrib('required',"true");
        $DOB->removeDecorator("DtDdWrapper");
        $DOB->setAttrib('title',"dd-mm-yyyy");
        $DOB->removeDecorator("Label");
        $DOB->removeDecorator('HtmlTag'); 
        
        
        $gender = new Zend_Dojo_Form_Element_FilteringSelect('gender');	
        $gender->addMultiOptions(array('1' => 'Male',
									   '0' => 'Female'));
        $gender->setAttrib('required',"true");
        $gender->removeDecorator("DtDdWrapper");
        $gender->removeDecorator("Label");
        $gender->removeDecorator('HtmlTag');
		$gender->setAttrib('dojoType',"dijit.form.FilteringSelect");		
		
		 //Staff Job Type 		      			
		$StaffJobType = new Zend_Dojo_Form_Element_FilteringSelect('StaffJobType');	
		$StaffJobType->addMultiOptions(array('1' => 'Full time',
									         '0' => 'Part Time'));
        $StaffJobType->setAttrib('required',"true");
        $StaffJobType->removeDecorator("DtDdWrapper");
        $StaffJobType->removeDecorator("Label");
        $StaffJobType->removeDecorator('HtmlTag');
		$StaffJobType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
        $FrontSalutation = new Zend_Dojo_Form_Element_FilteringSelect('FrontSalutation');	
        $FrontSalutation->setAttrib('required',"true");
        $FrontSalutation->removeDecorator("DtDdWrapper");
        $FrontSalutation->removeDecorator("Label");
        $FrontSalutation->removeDecorator('HtmlTag');
		$FrontSalutation->setAttrib('dojoType',"dijit.form.FilteringSelect");

		//Religion
		$Religion = new Zend_Dojo_Form_Element_FilteringSelect('Religion');	
        $Religion->setAttrib('required',"true");
        $Religion->removeDecorator("DtDdWrapper");
        $Religion->removeDecorator("Label");
        $Religion->removeDecorator('HtmlTag');
		$Religion->setAttrib('dojoType',"dijit.form.FilteringSelect");

		//Place of Birth
		$PlaceOfBirth = new Zend_Dojo_Form_Element_FilteringSelect('PlaceOfBirth');	
        $PlaceOfBirth->setAttrib('required',"true");
        $PlaceOfBirth->removeDecorator("DtDdWrapper");
        $PlaceOfBirth->removeDecorator("Label");
        $PlaceOfBirth->removeDecorator('HtmlTag');
		$PlaceOfBirth->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		//Bank Name
		$BankId = new Zend_Dojo_Form_Element_FilteringSelect('BankId');	
        $BankId->setAttrib('required',"false");
        $BankId->removeDecorator("DtDdWrapper");
        $BankId->removeDecorator("Label");
        $BankId->removeDecorator('HtmlTag');
		$BankId->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
        $Add1 = new Zend_Form_Element_Text('Add1');
		$Add1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Add1->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
       	$Add2 = new Zend_Form_Element_Text('Add2');
		$Add2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Add2->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
       //Staffid auto or manual
       	$StaffId = new Zend_Form_Element_Text('StaffId');
		$StaffId->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $StaffId->setAttrib('maxlength','50');      
        $StaffId->setAttrib('required',"true")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 	

        //Back Salutation
               		
        $BackSalutation = new Zend_Dojo_Form_Element_FilteringSelect('BackSalutation');	
        $BackSalutation->setAttrib('required',"true");
        $BackSalutation->removeDecorator("DtDdWrapper");
        $BackSalutation->removeDecorator("Label");
        $BackSalutation->removeDecorator('HtmlTag');
		$BackSalutation->setAttrib('dojoType',"dijit.form.FilteringSelect");
        		
       /* $KTPIC = new Zend_Form_Element_Text('KTPIC');
		$KTPIC->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $KTPIC->setAttrib('maxlength','20');      
        $KTPIC->setAttrib('required',"true")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 		
*/
        //bank account number
        $BankAccountNo = new Zend_Form_Element_Text('BankAccountNo');
		$BankAccountNo->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $BankAccountNo->setAttrib('maxlength','50');      
        $BankAccountNo->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        //Highest Qualification        
        $HighestQualification = new Zend_Form_Element_Text('HighestQualification');
		$HighestQualification->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $HighestQualification->setAttrib('maxlength','50');      
        $HighestQualification->setAttrib('required',"true")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        //Qualification No        
        $QualificationNo = new Zend_Form_Element_Text('QualificationNo');
		$QualificationNo->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $QualificationNo->setAttrib('maxlength','50');      
        $QualificationNo->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        //Home Page
        $HomePage = new Zend_Form_Element_Text('HomePage');
		$HomePage->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $HomePage->setAttrib('maxlength','50');      
        $HomePage->setAttrib('required',"false");
        $HomePage->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	
        	
       //Identification num 
        $DosenCodeEPSBED = new Zend_Form_Element_Text('Dosen_Code_EPSBED');
        $DosenCodeEPSBED->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $DosenCodeEPSBED->setAttrib('maxlength','20');
        $DosenCodeEPSBED->setAttrib('required',"false")
        ->removeDecorator("DtDdWrapper")
        ->removeDecorator("Label")
        ->removeDecorator('HtmlTag');
        
        $IdendityNoOfStateLecturer = new Zend_Form_Element_Text('IdendityNoOfStateLecturer');
		$IdendityNoOfStateLecturer->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $IdendityNoOfStateLecturer->setAttrib('maxlength','50');      
        $IdendityNoOfStateLecturer->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');		
        
        $AuthorizationLetterNumber = new Zend_Form_Element_Text('AuthorizationLetterNumber');
		$AuthorizationLetterNumber->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AuthorizationLetterNumber->setAttrib('maxlength','50');      
        $AuthorizationLetterNumber->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        
        
        $PtiAuthorizationLetterNo = new Zend_Form_Element_Text('PtiAuthorizationLetterNo');
		$PtiAuthorizationLetterNo->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $PtiAuthorizationLetterNo->setAttrib('maxlength','50');      
        $PtiAuthorizationLetterNo->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        		
        $PtiName = new Zend_Form_Element_Text('PtiName');
		$PtiName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $PtiName->setAttrib('maxlength','50');      
        $PtiName->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 		
        		
        
        $PreviousPtiNo = new Zend_Form_Element_Text('PreviousPtiNo');
		$PreviousPtiNo->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $PreviousPtiNo->setAttrib('maxlength','50');      
        $PreviousPtiNo->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        

        $PermitNo = new Zend_Form_Element_Text('PermitNo');
		$PermitNo->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $PermitNo->setAttrib('maxlength','50');      
        $PermitNo->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 		
        		
        $NationalIdendificationNumber1 = new Zend_Form_Element_Text('NationalIdendificationNumber1');
		$NationalIdendificationNumber1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $NationalIdendificationNumber1->setAttrib('maxlength','50');      
        $NationalIdendificationNumber1->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag'); 		
        		
        $NationalIdendificationNumber2 = new Zend_Form_Element_Text('NationalIdendificationNumber2');
		$NationalIdendificationNumber2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $NationalIdendificationNumber2->setAttrib('maxlength','50');      
        $NationalIdendificationNumber2->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $NationalIdendificationNumber3 = new Zend_Form_Element_Text('NationalIdendificationNumber3');
		$NationalIdendificationNumber3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $NationalIdendificationNumber3->setAttrib('maxlength','50');      
        $NationalIdendificationNumber3->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');		

        		
        $NationalIdendificationNumber4 = new Zend_Form_Element_Text('NationalIdendificationNumber4');
		$NationalIdendificationNumber4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $NationalIdendificationNumber4->setAttrib('maxlength','50');      
        $NationalIdendificationNumber4->setAttrib('required',"false")
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');		
        		
        $City = new Zend_Dojo_Form_Element_FilteringSelect('City');
        $City->removeDecorator("DtDdWrapper");
        $City->removeDecorator("Label");
        $City->removeDecorator('HtmlTag');
        $City->setAttrib('required',"false");
        $City->setRegisterInArrayValidator(false);
		$City->setAttrib('dojoType',"dijit.form.FilteringSelect");	 		
        		
        $State = new Zend_Dojo_Form_Element_FilteringSelect('State');
        $State->removeDecorator("DtDdWrapper");
        $State->setAttrib('required',"true") ;
        $State->removeDecorator("Label");
        $State->removeDecorator('HtmlTag');
        $State->setRegisterInArrayValidator(false);
        $State->setAttrib('OnChange', 'fnGetStateCityList');
		$State->setAttrib('dojoType',"dijit.form.FilteringSelect");
		        
        $Country = new Zend_Dojo_Form_Element_FilteringSelect('Country');
        $Country->removeDecorator("DtDdWrapper");
        $Country->setAttrib('required',"true") ;
        $Country->removeDecorator("Label");
        $Country->removeDecorator('HtmlTag');
        $Country->setAttrib('OnChange', 'fnGetCountryStateList');
        $Country->setRegisterInArrayValidator(false);
		$Country->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Zip = new Zend_Form_Element_Text('Zip');
		$Zip->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Zip->setAttrib('maxlength','20');   
        $Zip->removeDecorator("DtDdWrapper");
        $Zip->removeDecorator("Label");
        $Zip->removeDecorator('HtmlTag');
        
        $Phonecountrycode = new Zend_Form_Element_Text('Phonecountrycode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonecountrycode->setAttrib('maxlength','3');  
        $Phonecountrycode->setAttrib('style','width:30px');  
        $Phonecountrycode->removeDecorator("DtDdWrapper");
        $Phonecountrycode->removeDecorator("Label");
        $Phonecountrycode->removeDecorator('HtmlTag');
        
        $Phonestatecode = new Zend_Form_Element_Text('Phonestatecode',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Phonestatecode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phonestatecode->setAttrib('maxlength','2');
        $Phonestatecode->setAttrib('style','width:30px');  
        $Phonestatecode->removeDecorator("DtDdWrapper");
        $Phonestatecode->removeDecorator("Label");
        $Phonestatecode->removeDecorator('HtmlTag');
        
        $Phone = new Zend_Form_Element_Text('Phone',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$Phone->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $Phone->setAttrib('maxlength','9');
        $Phone->setAttrib('style','width:93px');   
        $Phone->removeDecorator("DtDdWrapper");
        $Phone->removeDecorator("Label");
        $Phone->removeDecorator('HtmlTag'); 
        
        
        $Mobilecountrycode = new Zend_Form_Element_Text('Mobilecountrycode',array('regExp'=>"[0-9+]+",'invalidMessage'=>"Only digits"));
		$Mobilecountrycode->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
      	$Mobilecountrycode->setAttrib('maxlength','4');  
        $Mobilecountrycode->setAttrib('style','width:50px');   
        $Mobilecountrycode->removeDecorator("DtDdWrapper");
        $Mobilecountrycode->removeDecorator("Label");
        $Mobilecountrycode->removeDecorator('HtmlTag');
        
        $Mobile = new Zend_Form_Element_Text('Mobile',array('regExp'=>"[0-9()+-]+",'invalidMessage'=>"Not a valid Phone No."));
		$Mobile->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Mobile->setAttrib('style','width:109px'); 	
        $Mobile->setAttrib('maxlength','20');   
        $Mobile->removeDecorator("DtDdWrapper");
        $Mobile->removeDecorator("Label");
        $Mobile->removeDecorator('HtmlTag'); 
        
        $Email = new Zend_Form_Element_Text('Email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>"Not a valid email"));
		$Email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $Email->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
      	$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
       	$StaffType  = new Zend_Form_Element_Radio('StaffType');
		$StaffType->setAttrib('dojoType',"dijit.form.RadioButton");
        $StaffType->addMultiOptions(array('0' => 'University','1' => 'College'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag')
        			->setAttrib('onclick', 'fnToggleCollegeDetails(this.value)');
        			
        			
        			
        $StaffAcademic  = new Zend_Form_Element_Radio('StaffAcademic');
		$StaffAcademic->setAttrib('dojoType',"dijit.form.RadioButton");
        $StaffAcademic->addMultiOptions(array('0' => 'Academic Staff','1' => 'Non-Academic Staff'))
        			->setvalue('0')
        			->setSeparator('&nbsp;')
        			->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag')
        			->setAttrib('onclick', 'fnToggleCollegeDetails(this.value)');			
        			

      
		
		$IdCollege = new Zend_Dojo_Form_Element_FilteringSelect('IdCollege');
        $IdCollege->removeDecorator("DtDdWrapper");
        $IdCollege->setAttrib('required',"true") ;
        $IdCollege->removeDecorator("Label");
        $IdCollege->removeDecorator('HtmlTag');
        $IdCollege->setRegisterInArrayValidator(false);
		$IdCollege->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$IdDepartment = new Zend_Dojo_Form_Element_FilteringSelect('IdDepartment');
        $IdDepartment->removeDecorator("DtDdWrapper");
        $IdDepartment->setAttrib('required',"false") ;
        $IdDepartment->removeDecorator("Label");
        $IdDepartment->removeDecorator('HtmlTag');
        $IdDepartment->setRegisterInArrayValidator(false);
		$IdDepartment->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$IdIdLevel = new Zend_Dojo_Form_Element_FilteringSelect('IdLevel');
        $IdIdLevel->removeDecorator("DtDdWrapper");
        $IdIdLevel->setAttrib('required',"true") ;
        $IdIdLevel->removeDecorator("Label");
        $IdIdLevel->removeDecorator('HtmlTag');
        $IdIdLevel->setRegisterInArrayValidator(false);
		$IdIdLevel->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->setAttrib('required',"false") ;
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
        $IdSubject->setRegisterInArrayValidator(false);
		$IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
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
		$Add->label = $gstrtranslate->_("Add");
        $Add->dojotype="dijit.form.Button";
		$Add->setAttrib('OnClick', 'addSubjectDetails()')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
    		
         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = "Back";
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');


        $NameField1 = new Zend_Form_Element_Text('NameField1');
    $NameField1->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField2 = new Zend_Form_Element_Text('NameField2');
    $NameField2->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField3 = new Zend_Form_Element_Text('NameField3');
    $NameField3->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField4 = new Zend_Form_Element_Text('NameField4');
    $NameField4->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

    $NameField5 = new Zend_Form_Element_Text('NameField5');
    $NameField5->setAttrib('dojoType',"dijit.form.ValidationTextBox")
          ->setAttrib('maxlength','50')
          ->removeDecorator("Label")
          ->removeDecorator("DtDdWrapper")
          ->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($IdStaff,
        						 $FirstName,
        						 $SecondName,
        						 $ThirdName,
        						 $FourthName,
                                 $FullName,
                                 $ArabicName,
                                 $Add1,
                                 $Add2,
                                 $City,
                                 $State,
                                 $Country,
                                 $Zip,
                                 $Phonecountrycode,
                                 $Phonestatecode,
                                 $Phone,
                                 $Mobilecountrycode,
                                 $Mobile,
                                 $NationalIdendificationNumber1,
                                 $NationalIdendificationNumber2,
                                 $NationalIdendificationNumber3,
                                 $NationalIdendificationNumber4,
                                 $Email,
                                 $ExtraIdField1,
                                 $ExtraIdField2,
                                 $ExtraIdField3,
                                 $ExtraIdField4,
                                 $ExtraIdField5,
                                 $ExtraIdField6,
                                 $ExtraIdField7,
                                 $ExtraIdField8,
                                 $ExtraIdField9,
                                 $ExtraIdField10,
                                 $ExtraIdField11,
                                 $ExtraIdField12,
                                 $ExtraIdField13,
                                 $ExtraIdField14,
                                 $ExtraIdField15,
                                 $ExtraIdField16,
                                 $ExtraIdField17,
                                 $ExtraIdField18,
                                 $ExtraIdField19,
                                 $ExtraIdField20,
                                 $Active,
                                 $StaffType,
                                 $IdCollege,
                                 $IdDepartment,
                                 $IdIdLevel,
                                 $IdSubject,
                                 $UpdDate,
                                 $PlaceOfBirth,
                                 $BankId,
                                 $BankAccountNo,
                                 $HighestQualification,
                                 $UpdUser,
                                 $IdendityNoOfStateLecturer,
                                 $AuthorizationLetterNumber,
                                 $HomePage,
                                 $PtiAuthorizationLetterNo,
                                 $Religion,
                                 $PtiName,
                                 $QualificationNo,
                                 $PreviousPtiNo,
                                 $PermitNo,
                                 $Save,$JoiningDate,
                                 $Add,
                                 $Back,$DOB,$gender,$FrontSalutation,$StaffAcademic,$StaffId,$StaffJobType,$BackSalutation,
                                 $NameField1,$NameField2,$NameField3,$NameField4,$NameField5,$DosenCodeEPSBED

            ));

    }
}