<?php
class GeneralSetup_Form_Departmentmaster extends Zend_Dojo_Form { //Formclass for the user module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	
    	$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}"; 
		
		
    	$IdDepartment = new Zend_Form_Element_Hidden('IdDepartment');
        $IdDepartment->removeDecorator("DtDdWrapper");
        $IdDepartment->removeDecorator("Label");
        $IdDepartment->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
   		
        $DepartmentName = new Zend_Form_Element_Text('DepartmentName');
		$DepartmentName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $DepartmentName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')     
        		->setAttrib('propercase','true')    
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
      	$ShortName = new Zend_Form_Element_Text('ShortName');
		$ShortName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $ShortName->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $ArabicName = new Zend_Form_Element_Text('ArabicName');
        $ArabicName->setAttrib('dojoType',"dijit.form.TextBox");
        $ArabicName//->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','50')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');		
        		
        $DeptCode = new Zend_Form_Element_Text('DeptCode',array('regExp'=>"^[a-zA-Z0-9]+$",'invalidMessage'=>""));
		$DeptCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $DeptCode->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','25')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');	

        $DepartmentType  = new Zend_Form_Element_Radio('DepartmentType');
		$DepartmentType->setAttrib('dojoType',"dijit.form.RadioButton");
        $DepartmentType->addMultiOptions(array('0' => 'Admin','1' => 'School'))
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
        //$IdCollege->setAttrib('OnChange', 'fnGetBrancheList');
        $IdCollege->setRegisterInArrayValidator(false);
		$IdCollege->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
		$FromDate = new Zend_Form_Element_Text('FromDate');
		$FromDate->setAttrib('dojoType',"dijit.form.DateTextBox");
        $FromDate->setAttrib('required',"true")
			     ->setAttrib('constraints', "$joiningdate")	 
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');

		$ToDate = new Zend_Form_Element_Hidden('ToDate');
		$ToDate	->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');        		

        $IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
        $IdStaff->setAttrib('OnChange', "fnGetStaffList(this)");
        $IdStaff->removeDecorator("DtDdWrapper");
        $IdStaff->setAttrib('required',"true") ;
        $IdStaff->removeDecorator("Label");
        $IdStaff->removeDecorator('HtmlTag');
        $IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
		$Active  = new Zend_Form_Element_Checkbox('Active');
        $Active->setAttrib('dojoType',"dijit.form.CheckBox");
        $Active->setvalue('1');
        $Active->removeDecorator("DtDdWrapper");
        $Active->removeDecorator("Label");
        $Active->removeDecorator('HtmlTag');
        
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
         		
        $Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
	
		$Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
	
		
        //form elements
        $this->addElements(array($IdDepartment,$UpdDate,$UpdUser,$DepartmentName,$ArabicName,$DeptCode,$DepartmentType,
        						$IdCollege,$Active,$ShortName,
        						 $Save,$Clear,$Add,$FromDate,$ToDate,$IdStaff
                                 ));

    }
}