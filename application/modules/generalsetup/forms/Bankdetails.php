<?php
class GeneralSetup_Form_Bankdetails extends Zend_Dojo_Form { //Form Class for Bank Details
    public function init() {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	$strSystemDate = date('Y-m-d');
    	
    	$IdAccount = new Zend_Form_Element_Hidden('IdAccount');
        $IdAccount->removeDecorator("DtDdWrapper");
        $IdAccount->removeDecorator("Label");
        $IdAccount->removeDecorator('HtmlTag');
        
        $IdBank = new Zend_Dojo_Form_Element_FilteringSelect('IdBank');
        $IdBank->setAttrib('dojoType',"dijit.form.FilteringSelect");
        $IdBank->removeDecorator("DtDdWrapper");
        $IdBank->setAttrib('required',"true") ;
        $IdBank->removeDecorator("Label");
        $IdBank->removeDecorator('HtmlTag');
		
		$AccountType = new Zend_Dojo_Form_Element_FilteringSelect('AccountType');
		$AccountType->setAttrib('dojoType',"dijit.form.FilteringSelect");
        $AccountType->removeDecorator("DtDdWrapper");
        $AccountType->setAttrib('required',"true") ;
        $AccountType->removeDecorator("Label");
        $AccountType->removeDecorator('HtmlTag');
        $AccountType->setRegisterInArrayValidator(false);
		
		$AccountNumber = new Zend_Form_Element_Text('AccountNumber');
		$AccountNumber->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AccountNumber->setAttrib('required',"true")       			        
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $AccountingHeadName = new Zend_Form_Element_Text('AccountingHeadName');
		$AccountingHeadName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $AccountingHeadName->setAttrib('required',"true"); 
        $AccountingHeadName->setAttrib('maxlength','250')	        
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        		
        $Description = new Zend_Form_Element_Textarea('Description');	
        $Description	->setAttrib('cols', '30')
        				->setAttrib('rows','1')
        				->setAttrib('style','width = 5%;')
        				->setAttrib('maxlength','250')
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
         $Active = new Zend_Form_Element_Checkbox('Active');
         $Active	->setAttrib('dojoType',"dijit.form.CheckBox")
       			 	->setAttrib("checked","checked")
        			->removeDecorator("DtDdWrapper")
      				->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        		
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->setvalue($strSystemDate);
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
          
        $Clear = new Zend_Form_Element_Button('Clear');
        $Clear->dojotype="dijit.form.Button";
        $Clear->label = $gstrtranslate->_("Clear");
		$Clear->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Search = new Zend_Form_Element_Button('Search');
		$Search->dojotype="dijit.form.Button";
       	$Search->label = $gstrtranslate->_("Search");
		$Search->setAttrib('class', 'NormalBtn')				
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
				
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Back = new Zend_Form_Element_Button('Back');
		$Back->dojotype="dijit.form.Button";
        $Back->label = $gstrtranslate->_("Back");
		$Back->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
        
        $this->addElements(
                    array(
                        $IdAccount,$IdBank,$AccountType,$AccountNumber,$Description,$Active,$UpdUser,$UpdDate,
                        $Clear,$Search,$Add,$Save,$Back,$AccountingHeadName
                        )
                        );
		}
}			
					
        		
        		
        		
        		