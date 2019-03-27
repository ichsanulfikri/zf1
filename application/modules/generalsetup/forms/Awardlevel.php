<?php
class GeneralSetup_Form_Awardlevel extends Zend_Dojo_Form {
	
public function init(){			
	
		$gstrtranslate =Zend_Registry::get('Zend_Translate'); 			
	
        
        $IdLevel = new Zend_Dojo_Form_Element_FilteringSelect('IdLevel');
        $IdLevel->removeDecorator("DtDdWrapper");
        $IdLevel->setAttrib('required',"true") ;
        $IdLevel->setAttrib('readonly',"true") ;
        $IdLevel->removeDecorator("Label");
        $IdLevel->removeDecorator('HtmlTag');
        $IdLevel->setRegisterInArrayValidator(false);       
		$IdLevel->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		
		$IdAllowanceLevel = new Zend_Dojo_Form_Element_FilteringSelect('IdAllowanceLevel');
        $IdAllowanceLevel->removeDecorator("DtDdWrapper");
        $IdAllowanceLevel->setAttrib('required',"true") ;
        $IdAllowanceLevel->removeDecorator("Label");
        $IdAllowanceLevel->removeDecorator('HtmlTag');
        $IdAllowanceLevel->setRegisterInArrayValidator(false);       
		$IdAllowanceLevel->setAttrib('dojoType',"dijit.form.FilteringSelect");
					
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        		 	 
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');	
				 	
		$Idaward = new Zend_Form_Element_Hidden('Idaward');
		$Idaward->setAttrib('id','UpdUser')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
				 	->removeDecorator('HtmlTag');
        		
        
       	$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn');
        $Save->removeDecorator("DtDdWrapper")
        		->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
     
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addAwardlevelDetails()')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add");
	    
	    //required
     
         		
        $this->addElements(
						array(
							$IdLevel,$IdAllowanceLevel,$Idaward,
							$UpdDate,$UpdUser,$Save,$Add
							
						)
			);
    	}
}