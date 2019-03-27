<?php
class Workflow_Form_Workflow extends Zend_Dojo_Form { //Formclass for the user module
    
	
	public function init() {
    	
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	//$this->lobjEmp = new Records_Model_DbTable_Employee();
    	//$_countrylist = $this->lobjEmp->fnGetCountryList();
		$month= date("m"); /// Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day-1),$year));
		$dateofbirth = "{datePattern:'dd-MM-yyyy'}"; 
	    		   
        $scr = new Zend_Form_Element_Text('txtSearch');
        $scr->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $scr->setAttrib('maxlength','20')
        ->removeDecorator("DtDdWrapper")
        ->removeDecorator("Label")
        ->removeDecorator('HtmlTag');
        
        $no = new Zend_Form_Element_Text('letter_no');
        $no->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $no->setAttrib('maxlength','50')
        ->removeDecorator("DtDdWrapper")
        ->removeDecorator("Label")
        ->removeDecorator('HtmlTag');
        
        $rmk = new Zend_Form_Element_Textarea('remark');
        $rmk->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $rmk->setAttrib('width','200');
        $rmk->setAttrib('maxlength','300')
        ->removeDecorator("DtDdWrapper")
        ->removeDecorator("Label")
        ->removeDecorator('HtmlTag');
       
        $lsCat = new Zend_Dojo_Form_Element_FilteringSelect('lsCategory');	
        //$religion->addMultiOptions($this->);
        //$gender->setAttrib('required',"true");
        $lsCat->removeDecorator("DtDdWrapper");
        $lsCat->removeDecorator("Label");
        $lsCat->removeDecorator('HtmlTag');
        $lsCat->setAttrib('OnChange', 'fnGetCategoryList');
        $lsCat->setRegisterInArrayValidator(false);
		$lsCat->setAttrib('dojoType',"dijit.form.FilteringSelect");
 
		$lsMaker = new Zend_Dojo_Form_Element_FilteringSelect('lsMaker');
		//$religion->addMultiOptions($this->);
		//$gender->setAttrib('required',"true");
		$lsMaker->removeDecorator("DtDdWrapper");
		$lsMaker->removeDecorator("Label");
		$lsMaker->removeDecorator('HtmlTag');
		$lsMaker->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$lsSts = new Zend_Dojo_Form_Element_FilteringSelect('lsStatus');
		//$religion->addMultiOptions($this->);
		//$gender->setAttrib('required',"true");
		$lsSts->removeDecorator("DtDdWrapper");
		$lsSts->removeDecorator("Label");
		$lsSts->removeDecorator('HtmlTag');
		$lsSts->setAttrib('OnChange', 'fnGetStatusList');
		$lsSts->setRegisterInArrayValidator(false);
		$lsSts->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
	
		$Search = new Zend_Form_Element_Submit('Search');
		$Search->dojotype="dijit.form.Button";
		$Search->label = $gstrtranslate->_("Search");
		$Search->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		
		$Cancel = new Zend_Form_Element_Submit('Cancel');
		$Cancel->dojotype="dijit.form.Button";
		$Cancel->label = $gstrtranslate->_("Cancel");
		$Cancel->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
	
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		
		
		//form elements
        $this->addElements(array($scr,$lsCat,$lsSts,$rmk,$Cancel,$Save,$Search,$no,$lsMaker));

    }
}