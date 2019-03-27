<?php
class GeneralSetup_Form_Programbranch extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate');
    	 
		$IdProgramBranchLink  = new Zend_Form_Element_Hidden('IdProgramBranchLink');
        $IdProgramBranchLink->removeDecorator("DtDdWrapper");
        $IdProgramBranchLink->setAttrib('dojoType',"dijit.form.TextBox");
        $IdProgramBranchLink->removeDecorator("Label");
        $IdProgramBranchLink->removeDecorator('HtmlTag');
        
		$IdCollege  = new Zend_Form_Element_Hidden('IdCollege');
        $IdCollege->removeDecorator("DtDdWrapper");
        $IdCollege->setAttrib('dojoType',"dijit.form.TextBox");
        $IdCollege->removeDecorator("Label");
        $IdCollege->removeDecorator('HtmlTag');
        
		
		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
        $IdProgram->removeDecorator("DtDdWrapper");
        $IdProgram->setAttrib('required',"true") ;
        $IdProgram->removeDecorator("Label");
        $IdProgram->removeDecorator('HtmlTag');
        $IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
        $IdSemester->removeDecorator("DtDdWrapper");
        $IdSemester->setAttrib('required',"true") ;
        $IdSemester->removeDecorator("Label");
        $IdSemester->removeDecorator('HtmlTag');
        $IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
        $MaxQuota = new Zend_Form_Element_Text('MaxQuota',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$MaxQuota->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$MaxQuota->setAttrib('required',"true") ;
        $MaxQuota->setAttrib('maxlength','10');
        $MaxQuota->removeDecorator("DtDdWrapper");
        $MaxQuota->removeDecorator("Label");
        $MaxQuota->removeDecorator('HtmlTag');
        
        $Quota = new Zend_Form_Element_Text('Quota',array('regExp'=>"[0-9]+",'invalidMessage'=>"Only digits"));
		$Quota->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$Quota->setAttrib('required',"true") ;
        $Quota->setAttrib('maxlength','10');
        $Quota->removeDecorator("DtDdWrapper");
        $Quota->removeDecorator("Label");
        $Quota->removeDecorator('HtmlTag');
        
        $CollegeName = new Zend_Form_Element_Text('CollegeName');
		$CollegeName->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
		$CollegeName->setAttrib('readonly',"true") ;
        $CollegeName->removeDecorator("DtDdWrapper");
        $CollegeName->removeDecorator("Label");
        $CollegeName->removeDecorator('HtmlTag');
        		
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
       // $Save->label = $gstrtranslate->_("Save");
        $Save->dojotype="dijit.form.Button";
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";

         		
        $Back = new Zend_Form_Element_Button('Back');
        $Back->label = $gstrtranslate->_("Back");
        $Back->dojotype="dijit.form.Button";
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');

        //form elements
        $this->addElements(array($IdProgramBranchLink,
        						 $IdCollege,
        						 $IdProgram,
                                 $Active,
                                 $UpdDate,
                                 $UpdUser,
                                 $Quota,
                                 $IdSemester,
                                 $CollegeName,
                                 $MaxQuota,
                                 $Save,
                                 $Back));

    }
}