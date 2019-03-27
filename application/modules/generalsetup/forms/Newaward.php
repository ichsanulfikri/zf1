<?php
class GeneralSetup_Form_Newaward extends Zend_Dojo_Form {	
	public function init(){		
		
		$gstrtranslate = Zend_Registry::get('Zend_Translate');		
		
		$idDefType = new Zend_Form_Element_Hidden('idDefType');
		$idDefType	->setAttrib('id','idDefType')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
		
		$idDefinition = new Zend_Form_Element_Hidden('idDefinition');
		$idDefinition->setAttrib('id','idDefType')
					->removeDecorator("Label")
					->removeDecorator("DtDdWrapper")
					->removeDecorator('HtmlTag');
					
		//$DefinitionCode = new Zend_Form_Element_Text('DefinitionCode',array('regExp'=>"^[a-zA-Z0-9]+$",'invalidMessage'=>"Code should be in numeric or alphabetic"));
		$DefinitionCode = new Zend_Form_Element_Text('DefinitionCode');
		$DefinitionCode->addValidator(new Zend_Validate_Db_NoRecordExists('tbl_definationms', 'DefinitionCode'));	
		$DefinitionCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DefinitionCode->setAttrib('required',"true")       			 
        		->setAttrib('maxlength','20')       
        		->removeDecorator("DtDdWrapper")
                        ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
                $DefinitionCode->setAttrib('DefinitionCode',array('invalidMessage' => 'First name must be filled out'));
        		
        $DefinitionDesc = new Zend_Form_Element_Text('DefinitionDesc');
		$DefinitionDesc->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$DefinitionDesc->setAttrib('required',"true")				
        		->setAttrib('maxlength','1000')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        		
        $BahasaIndonesia= new Zend_Form_Element_Text('BahasaIndonesia');
		$BahasaIndonesia->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$BahasaIndonesia->setAttrib('maxlength','100')       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag');
        
        $Description = new Zend_Form_Element_Textarea('Description');	
        $Description	->setAttrib('cols', '30')
        				->setAttrib('rows','3')
        				->setAttrib('style','width = 10%;')
        				->setAttrib('maxlength','250')
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        
        $Status = new Zend_Form_Element_Checkbox('Status');
        $Status	->setAttrib('dojoType',"dijit.form.CheckBox")
       			 	->setAttrib("checked","checked")
        			->removeDecorator("DtDdWrapper")
      				->removeDecorator("Label")
        			->removeDecorator('HtmlTag');
        			
        $Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
       	$Save->label = $gstrtranslate->_("Save");
		$Save	->setAttrib('id', 'save')
				->removeDecorator("Label")
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
        			
        $this->addElements(
					array(
						$idDefType, $DefinitionCode,$DefinitionDesc,$Status,$BahasaIndonesia,$Description,$Save,$idDefinition
					)
		);
					
	}
	
}