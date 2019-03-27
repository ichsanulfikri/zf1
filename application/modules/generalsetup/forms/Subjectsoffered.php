<?php
class GeneralSetup_Form_Subjectsoffered extends Zend_Dojo_Form {
    public function init() {    	
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		
		$IdCollege = new Zend_Dojo_Form_Element_FilteringSelect('IdCollege');
        $IdCollege->removeDecorator("DtDdWrapper");
        $IdCollege->setAttrib('required',"true") ;
        $IdCollege->removeDecorator("Label");
        $IdCollege->removeDecorator('HtmlTag');
        $IdCollege->setRegisterInArrayValidator(false);
        $IdCollege->setAttrib('OnChange', 'fnGetDeptSubjectsList');
       // $IdCollege->setAttrib('OnChange', 'changeSelect');
		$IdCollege->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		//$IdDepartment = new Zend_Dojo_Form_Element_FilteringSelect('IdDepartment');
		$IdDepartment = new Zend_Form_Element_Hidden('IdDepartment');
        $IdDepartment->removeDecorator("DtDdWrapper");
        //$IdDepartment->setAttrib('required',"true") ;
        $IdDepartment->removeDecorator("Label");
        $IdDepartment->removeDecorator('HtmlTag');
       // $IdDepartment->setRegisterInArrayValidator(false);
       // $IdDepartment->setAttrib('OnChange', 'fnGetDeptSubjectsList');
		//$IdDepartment->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$Branch = new Zend_Dojo_Form_Element_FilteringSelect('Branch');
        $Branch->removeDecorator("DtDdWrapper");
        $Branch->setAttrib('required',"true") ;
        $Branch->removeDecorator("Label");
        $Branch->removeDecorator('HtmlTag');
        $Branch->setRegisterInArrayValidator(false);
		$Branch->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$MinQuota = new Zend_Form_Element_Text('MinQuota',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$MinQuota->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $MinQuota->setAttrib('maxlength','5');  
        $MinQuota->removeDecorator("DtDdWrapper");
        $MinQuota->removeDecorator("Label");
        $MinQuota->removeDecorator('HtmlTag');
        
        $MaxQuota = new Zend_Form_Element_Text('MaxQuota',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$MaxQuota->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
        $MaxQuota->setAttrib('maxlength','5');  
        $MaxQuota->removeDecorator("DtDdWrapper");
        $MaxQuota->removeDecorator("Label");
        $MaxQuota->removeDecorator('HtmlTag');

        $IdSubject = new Zend_Form_Element_Select('IdSubject');
        $IdSubject->removeDecorator("DtDdWrapper")
        			->removeDecorator("Label")
        			->removeDecorator('HtmlTag')
        			->setAttrib('style',"width:166px")
        			->setAttrib('onChange','fnGetSubjectCode(this.value)')
					->setAttrib('dojoType',"dijit.form.FilteringSelect");
        			
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper")
        		->setvalue(date('y-m-d H:i:s'))
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        
        $UpdUser = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        			
      
        $Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
		$Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag');
        
        $Back = new Zend_Form_Element_Button('Back');
        $Back->dojotype="dijit.form.Button";
        $Back->label = $gstrtranslate->_("Back");
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

      	$Add = new Zend_Form_Element_Button('Add');
        $Add->dojotype="dijit.form.Button";
        $Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn')
				->setAttrib('onclick', 'addsubjectsofferedDetails')
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
		
		$applyToAllFaculty  = new Zend_Form_Element_Checkbox('AllFaculty');
		$applyToAllFaculty->setAttrib('dojoType',"dijit.form.CheckBox");
        $applyToAllFaculty	->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
				->setAttrib('onClick','disableCheck()')
        		->removeDecorator('HtmlTag');
				
        $this->addElements(array($Branch,$IdCollege,$IdDepartment,$IdSubject,$MinQuota,$MaxQuota,$UpdUser,$Save,$UpdDate,$Back,$Add,$clear,$applyToAllFaculty));
        			
    }
}